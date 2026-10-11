<?php
require_once __DIR__ . '/../config/database.php';

class ClassController {
    private PDO $db;

    public function __construct(?PDO $db = null) {
        global $db;
        $this->db = $db ?? $GLOBALS['db'];
    }

    public function getActiveBatches(): array {
        return $this->db->query("SELECT id, name FROM batches ORDER BY name")->fetchAll();
    }

    public function getActiveSubjects(): array {
        return $this->db->query("SELECT id, name FROM subjects WHERE status = 'active' ORDER BY name")->fetchAll();
    }

    public function getActiveTeachers(): array {
        return $this->db->query(
            "SELECT teachers.id, users.name 
             FROM teachers 
             INNER JOIN users ON users.id = teachers.user_id 
             WHERE users.status = 'active' 
             ORDER BY users.name"
        )->fetchAll();
    }

    public function getAllClasses(): array {
        return $this->db->query(
            "SELECT cs.id, cs.day_of_week, cs.start_time, cs.end_time, cs.room_number,
                    b.name AS batch_name, s.name AS subject_name, u.name AS teacher_name,
                    b.status AS batch_status
             FROM class_schedules cs
             INNER JOIN batches b ON b.id = cs.batch_id
             INNER JOIN subjects s ON s.id = cs.subject_id
             INNER JOIN teachers t ON t.id = cs.teacher_id
             INNER JOIN users u ON u.id = t.user_id
             ORDER BY FIELD(cs.day_of_week, 'Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'), cs.start_time ASC"
        )->fetchAll();
    }

    public function handleAddClass(): array {
        $result = ['message' => '', 'messageType' => 'success'];

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || (isset($_POST['action']) && $_POST['action'] !== 'add_class')) {
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
        } else {
            $chk = $this->db->prepare('SELECT id FROM batches WHERE id = ? LIMIT 1');
            $chk->execute([$batchId]);
            if (!$chk->fetch()) {
                $errors[] = 'The selected batch does not exist.';
            }
        }

        if ($subjectId <= 0) {
            $errors[] = 'Please select a valid subject.';
        } else {
            $chk = $this->db->prepare('SELECT id FROM subjects WHERE id = ? LIMIT 1');
            $chk->execute([$subjectId]);
            if (!$chk->fetch()) {
                $errors[] = 'The selected subject does not exist.';
            }
        }

        if ($teacherId <= 0) {
            $errors[] = 'Please select a valid teacher.';
        } else {
            $chk = $this->db->prepare('SELECT id FROM teachers WHERE id = ? LIMIT 1');
            $chk->execute([$teacherId]);
            if (!$chk->fetch()) {
                $errors[] = 'The selected teacher does not exist.';
            }
        }

        if (!in_array($dayOfWeek, $validDays, true)) {
            $errors[] = 'Please select a valid day of the week.';
        }

        if ($startTime === '' || $endTime === '') {
            $errors[] = 'Start time and end time are required.';
        } elseif (strtotime($startTime) >= strtotime($endTime)) {
            $errors[] = 'End time must be after start time.';
        }

        if ($roomNumber === '') {
            $roomNumber = 'Room 101';
        }

        if (empty($errors)) {
            $chkDup = $this->db->prepare(
                'SELECT id FROM class_schedules WHERE batch_id = ? AND day_of_week = ? AND start_time = ? LIMIT 1'
            );
            $chkDup->execute([$batchId, $dayOfWeek, $startTime]);
            if ($chkDup->fetch()) {
                $errors[] = 'A class is already scheduled for this batch on ' . $dayOfWeek . ' at ' . date('h:i A', strtotime($startTime)) . '.';
            }
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
            $result['message'] = 'Class routine scheduled successfully.';
            $result['messageType'] = 'success';
            $_POST = [];
        } catch (PDOException $e) {
            $result['message'] = 'Unable to schedule class: ' . $e->getMessage();
            $result['messageType'] = 'error';
        }

        return $result;
    }

    public function handleDeleteClass(): array {
        $result = ['message' => '', 'messageType' => 'success'];

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || ($_POST['action'] ?? '') !== 'delete_class') {
            return $result;
        }

        $id = (int) ($_POST['class_id'] ?? 0);
        if ($id <= 0) {
            return $result;
        }

        try {
            $stmt = $this->db->prepare('DELETE FROM class_schedules WHERE id = ?');
            $stmt->execute([$id]);
            $result['message'] = 'Class schedule entry removed successfully.';
            $result['messageType'] = 'success';
        } catch (PDOException $e) {
            $result['message'] = 'Unable to delete class: ' . $e->getMessage();
            $result['messageType'] = 'error';
        }

        return $result;
    }
}
