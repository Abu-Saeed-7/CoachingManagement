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
     * Fetch all batches with assigned teachers and student counts.
     */
    public function getAllBatches(): array {
        return $this->db->query(
            'SELECT batches.id, batches.name, batches.start_date, batches.status,
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
     * Process add batch form submission with validation.
     */
    public function handleAddBatch(): array {
        $result = ['message' => '', 'messageType' => 'success'];

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $result;
        }

        $name = trim($_POST['name'] ?? '');
        $startDate = $_POST['start_date'] ?? '';
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
                'INSERT INTO batches (name, start_date, status) VALUES (?, ?, ?)'
            );
            $batchStatement->execute([$name, $startDate, $status]);
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
     * Get batches assigned to a specific teacher.
     */
    public function getTeacherBatches(int $teacherId): array {
        $stmt = $this->db->prepare(
            'SELECT batches.id, batches.name, batches.start_date, batches.status,
                (SELECT COUNT(*) FROM enrollments WHERE enrollments.batch_id = batches.id AND enrollments.status = \'active\') AS student_count
             FROM batches
             INNER JOIN batch_teachers ON batch_teachers.batch_id = batches.id
             WHERE batch_teachers.teacher_id = ?
             ORDER BY batches.id DESC'
        );
        $stmt->execute([$teacherId]);
        return $stmt->fetchAll();
    }
}
