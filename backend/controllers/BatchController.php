<?php
require_once __DIR__ . '/../config/database.php';

class BatchController {
    private PDO $db;

    public function __construct(?PDO $db = null) {
        global $db;
        $this->db = $db ?? $GLOBALS['db'];
    }

    /**
     * Fetch all active teachers for batch assignment.
     */
    public function getActiveTeachers(): array {
        return $this->db->query(
            'SELECT teachers.id, users.name
             FROM teachers INNER JOIN users ON users.id = teachers.user_id
             WHERE users.status = \'active\'
             ORDER BY users.name'
        )->fetchAll();
    }

    /**
     * Fetch all batches with assigned teachers, student counts, and schedule timings.
     */
    public function getAllBatches(): array {
        return $this->db->query(
            'SELECT batches.id, batches.name, batches.start_date, batches.status,
                    COALESCE(batches.schedule_time, \'Not scheduled\') AS schedule_time,
                    COALESCE(batches.room_number, \'Room 101\') AS room_number,
                COALESCE((
                    SELECT GROUP_CONCAT(users.name SEPARATOR \', \')
                    FROM batch_teachers
                    INNER JOIN teachers ON teachers.id = batch_teachers.teacher_id
                    INNER JOIN users ON users.id = teachers.user_id
                    WHERE batch_teachers.batch_id = batches.id
                ), \'Not assigned\') AS teacher_names,
                (
                    SELECT COUNT(*)
                    FROM enrollments
                    WHERE enrollments.batch_id = batches.id AND enrollments.status = \'active\'
                ) AS student_count
             FROM batches
             ORDER BY batches.id DESC'
        )->fetchAll();
    }

    /**
     * Fetch all active subjects.
     */
    public function getActiveSubjects(): array {
        return $this->db->query("SELECT id, name FROM subjects WHERE status = 'active' ORDER BY name")->fetchAll();
    }

    /**
     * Fetch all weekly class schedules with batch, subject, teacher details.
     */
    public function getAllClassSchedules(): array {
        return $this->db->query(
            "SELECT cs.id, cs.day_of_week, cs.start_time, cs.end_time, cs.room_number,
                    b.name AS batch_name, s.name AS subject_name, u.name AS teacher_name
             FROM class_schedules cs
             INNER JOIN batches b ON b.id = cs.batch_id
             INNER JOIN subjects s ON s.id = cs.subject_id
             INNER JOIN teachers t ON t.id = cs.teacher_id
             INNER JOIN users u ON u.id = t.user_id
             ORDER BY FIELD(cs.day_of_week, 'Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'), cs.start_time ASC"
        )->fetchAll();
    }

    /**
     * Process add batch form submission with validation.
     */
    public function handleAddBatch(): array {
        $result = ['message' => '', 'messageType' => 'success'];

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || isset($_POST['action']) && $_POST['action'] !== 'add_batch') {
            return $result;
        }

        $name = trim($_POST['name'] ?? '');
        $startDate = $_POST['start_date'] ?? '';
        $scheduleTime = trim($_POST['schedule_time'] ?? '');
        $roomNumber = trim($_POST['room_number'] ?? 'Room 101');
        $status = $_POST['status'] ?? '';
        $teacherId = (int) ($_POST['teacher_id'] ?? 0);
        $errors = [];

        if ($name === '' || strlen($name) < 2 || strlen($name) > 100) {
            $errors[] = 'Batch Name must be between 2 and 100 characters.';
        } else {
            $chkName = $this->db->prepare('SELECT id FROM batches WHERE name = ? LIMIT 1');
            $chkName->execute([$name]);
            if ($chkName->fetch()) {
                $errors[] = 'A batch with this name already exists.';
            }
        }

        if ($startDate === '' || !strtotime($startDate)) {
            $errors[] = 'Please provide a valid start date.';
        }

        if (!in_array($status, ['active', 'inactive', 'completed'], true)) {
            $errors[] = 'Please select a valid batch status.';
        }

        if ($teacherId > 0) {
            $chkTeacher = $this->db->prepare('SELECT id FROM teachers WHERE id = ? LIMIT 1');
            $chkTeacher->execute([$teacherId]);
            if (!$chkTeacher->fetch()) {
                $errors[] = 'The selected teacher does not exist.';
            }
        }

        if (!empty($errors)) {
            $result['message'] = implode('<br>', $errors);
            $result['messageType'] = 'error';
            return $result;
        }

        try {
            $this->db->beginTransaction();

            $batchStatement = $this->db->prepare(
                'INSERT INTO batches (name, start_date, schedule_time, room_number, status) VALUES (?, ?, ?, ?, ?)'
            );
            $batchStatement->execute([
                $name,
                $startDate,
                $scheduleTime !== '' ? $scheduleTime : 'Sat, Mon, Wed (10:00 AM - 11:30 AM)',
                $roomNumber !== '' ? $roomNumber : 'Room 101',
                $status
            ]);
            $batchId = (int) $this->db->lastInsertId();

            if ($teacherId > 0) {
                $teacherStatement = $this->db->prepare(
                    'INSERT INTO batch_teachers (batch_id, teacher_id) VALUES (?, ?)'
                );
                $teacherStatement->execute([$batchId, $teacherId]);
            }

            $this->db->commit();
            $result['message'] = 'Batch created successfully.';
            $result['messageType'] = 'success';
            $_POST = [];
        } catch (PDOException $error) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            $result['message'] = 'Unable to create batch: ' . $error->getMessage();
            $result['messageType'] = 'error';
        }

        return $result;
    }

    /**
     * Process add class schedule / routine slot.
     */
    public function handleAddClassSchedule(): array {
        $result = ['message' => '', 'messageType' => 'success'];

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || ($_POST['action'] ?? '') !== 'add_class_schedule') {
            return $result;
        }

        $batchId = (int) ($_POST['batch_id'] ?? 0);
        $subjectId = (int) ($_POST['subject_id'] ?? 0);
        $teacherId = (int) ($_POST['teacher_id'] ?? 0);
        $dayOfWeek = trim($_POST['day_of_week'] ?? '');
        $startTime = trim($_POST['start_time'] ?? '');
        $endTime = trim($_POST['end_time'] ?? '');
        $roomNumber = trim($_POST['room_number'] ?? 'Room 101');
        $validDays = ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        $errors = [];

        if ($batchId <= 0) {
            $errors[] = 'Please select a valid batch.';
        }
        if ($subjectId <= 0) {
            $errors[] = 'Please select a valid subject.';
        }
        if ($teacherId <= 0) {
            $errors[] = 'Please select a valid teacher.';
        }
        if (!in_array($dayOfWeek, $validDays, true)) {
            $errors[] = 'Please select a valid day of the week.';
        }
        if ($startTime === '' || $endTime === '') {
            $errors[] = 'Start time and end time are required.';
        } elseif (strtotime($startTime) >= strtotime($endTime)) {
            $errors[] = 'End time must be after start time.';
        }

        if (!empty($errors)) {
            $result['message'] = implode('<br>', $errors);
            $result['messageType'] = 'error';
            return $result;
        }

        try {
            $stmt = $this->db->prepare(
                'INSERT INTO class_schedules (batch_id, subject_id, teacher_id, day_of_week, start_time, end_time, room_number)
                 VALUES (?, ?, ?, ?, ?, ?, ?)'
            );
            $stmt->execute([$batchId, $subjectId, $teacherId, $dayOfWeek, $startTime, $endTime, $roomNumber]);
            $result['message'] = 'Class schedule added successfully.';
            $result['messageType'] = 'success';
        } catch (PDOException $e) {
            $result['message'] = 'Failed to add class schedule: ' . $e->getMessage();
            $result['messageType'] = 'error';
        }

        return $result;
    }

    /**
     * Process delete class schedule slot.
     */
    public function handleDeleteClassSchedule(): array {
        $result = ['message' => '', 'messageType' => 'success'];

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || ($_POST['action'] ?? '') !== 'delete_class_schedule') {
            return $result;
        }

        $scheduleId = (int) ($_POST['schedule_id'] ?? 0);
        if ($scheduleId <= 0) {
            return $result;
        }

        try {
            $stmt = $this->db->prepare('DELETE FROM class_schedules WHERE id = ?');
            $stmt->execute([$scheduleId]);
            $result['message'] = 'Class schedule entry deleted.';
            $result['messageType'] = 'success';
        } catch (PDOException $e) {
            $result['message'] = 'Unable to delete schedule: ' . $e->getMessage();
            $result['messageType'] = 'error';
        }

        return $result;
    }

    /**
     * Get batches assigned to a specific teacher.
     */
    public function getTeacherBatches(int $teacherId): array {
        $stmt = $this->db->prepare(
            'SELECT batches.id, batches.name, batches.start_date, batches.status,
                    COALESCE(batches.schedule_time, \'Not scheduled\') AS schedule_time,
                    COALESCE(batches.room_number, \'Room 101\') AS room_number,
                (SELECT COUNT(*) FROM enrollments WHERE enrollments.batch_id = batches.id AND enrollments.status = \'active\') AS student_count
             FROM batches
             INNER JOIN batch_teachers ON batch_teachers.batch_id = batches.id
             WHERE batch_teachers.teacher_id = ?
             ORDER BY batches.id DESC'
        );
        $stmt->execute([$teacherId]);
        return $stmt->fetchAll();
    }

    /**
     * Get class schedule routines assigned to a specific teacher.
     */
    public function getTeacherClassSchedules(int $teacherId): array {
        $stmt = $this->db->prepare(
            "SELECT cs.id, cs.day_of_week, cs.start_time, cs.end_time, cs.room_number,
                    b.name AS batch_name, s.name AS subject_name
             FROM class_schedules cs
             INNER JOIN batches b ON b.id = cs.batch_id
             INNER JOIN subjects s ON s.id = cs.subject_id
             WHERE cs.teacher_id = ?
             ORDER BY FIELD(cs.day_of_week, 'Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'), cs.start_time ASC"
        );
        $stmt->execute([$teacherId]);
        return $stmt->fetchAll();
    }
}
