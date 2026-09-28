<?php
require_once __DIR__ . '/../config/database.php';

class ExamController {
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

    public function getAllExams(): array {
        return $this->db->query(
            'SELECT exams.id, exams.name, subjects.name AS subject_name, batches.name AS batch_name,
                    exams.exam_date, exams.total_marks,
                    CASE WHEN exams.exam_date >= CURDATE() THEN \'Upcoming\' ELSE \'Completed\' END AS status
             FROM exams
             INNER JOIN batches ON batches.id = exams.batch_id
             INNER JOIN subjects ON subjects.id = exams.subject_id
             ORDER BY exams.exam_date DESC, exams.id DESC'
        )->fetchAll();
    }

    public function handleAddExam(): array {
        $result = ['message' => '', 'messageType' => 'success'];

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $result;
        }

        $name = trim($_POST['name'] ?? '');
        $batchId = (int) ($_POST['batch_id'] ?? 0);
        $subjectId = (int) ($_POST['subject_id'] ?? 0);
        $examDate = trim($_POST['exam_date'] ?? '');
        $totalMarks = isset($_POST['total_marks']) ? (float) $_POST['total_marks'] : 0;
        $errors = [];

        if ($name === '' || strlen($name) < 2 || strlen($name) > 100) {
            $errors[] = 'Exam Title must be between 2 and 100 characters.';
        }

        if ($subjectId < 1) {
            $errors[] = 'Please select a valid subject.';
        } else {
            $chkSub = $this->db->prepare('SELECT id FROM subjects WHERE id = ? LIMIT 1');
            $chkSub->execute([$subjectId]);
            if (!$chkSub->fetch()) {
                $errors[] = 'The selected subject does not exist.';
            }
        }

        if ($batchId < 1) {
            $errors[] = 'Please select a target batch.';
        } else {
            $chkBatch = $this->db->prepare('SELECT id FROM batches WHERE id = ? LIMIT 1');
            $chkBatch->execute([$batchId]);
            if (!$chkBatch->fetch()) {
                $errors[] = 'The selected batch does not exist.';
            }
        }

        if ($examDate === '' || !strtotime($examDate)) {
            $errors[] = 'Please provide a valid exam date.';
        }

        if ($totalMarks <= 0 || $totalMarks > 1000) {
            $errors[] = 'Total marks must be a positive number up to 1000.';
        }

        if (empty($errors)) {
            $chkDup = $this->db->prepare('SELECT id FROM exams WHERE batch_id = ? AND name = ? AND exam_date = ? LIMIT 1');
            $chkDup->execute([$batchId, $name, $examDate]);
            if ($chkDup->fetch()) {
                $errors[] = 'An exam with this title is already scheduled for this batch on the chosen date.';
            }
        }

        if (!empty($errors)) {
            $result['message'] = implode('<br>', $errors);
            $result['messageType'] = 'error';
            return $result;
        }

        try {
            $stmt = $this->db->prepare(
                'INSERT INTO exams (batch_id, subject_id, name, exam_date, total_marks) VALUES (?, ?, ?, ?, ?)'
            );
            $stmt->execute([$batchId, $subjectId, $name, $examDate, $totalMarks]);
            $result['message'] = 'Exam scheduled successfully.';
            $result['messageType'] = 'success';
            $_POST = [];
        } catch (PDOException $e) {
            $result['message'] = 'Unable to schedule exam: ' . $e->getMessage();
            $result['messageType'] = 'error';
        }

        return $result;
    }

    public function getStudentExamsAndClasses(int $studentId): array {
        $stmt = $this->db->prepare(
            'SELECT batches.id, batches.name, batches.start_date,
                COALESCE((SELECT GROUP_CONCAT(users.name SEPARATOR \', \') 
                 FROM batch_teachers 
                 INNER JOIN teachers ON teachers.id = batch_teachers.teacher_id 
                 INNER JOIN users ON users.id = teachers.user_id 
                 WHERE batch_teachers.batch_id = batches.id), \'Not assigned\') AS teachers,
                COALESCE((SELECT GROUP_CONCAT(users.name SEPARATOR \', \') 
                 FROM batch_teachers 
                 INNER JOIN teachers ON teachers.id = batch_teachers.teacher_id 
                 INNER JOIN users ON users.id = teachers.user_id 
                 WHERE batch_teachers.batch_id = batches.id), \'Not assigned\') AS teacher_names
             FROM enrollments
             INNER JOIN batches ON batches.id = enrollments.batch_id
             WHERE enrollments.student_id = ? AND enrollments.status = \'active\''
        );
        $stmt->execute([$studentId]);
        $enrolledBatches = $stmt->fetchAll();

        $stmt = $this->db->prepare(
            'SELECT exams.id, exams.name, exams.exam_date, exams.total_marks,
                    batches.name AS batch_name, subjects.name AS subject_name,
                    CASE WHEN exams.exam_date >= CURDATE() THEN \'Upcoming\' ELSE \'Completed\' END AS status
             FROM exams
             INNER JOIN batches ON batches.id = exams.batch_id
             INNER JOIN subjects ON subjects.id = exams.subject_id
             INNER JOIN enrollments ON enrollments.batch_id = batches.id
             WHERE enrollments.student_id = ? AND exams.exam_date >= CURDATE()
             ORDER BY exams.exam_date ASC'
        );
        $stmt->execute([$studentId]);
        $upcomingExams = $stmt->fetchAll();

        return [
            'enrolledBatches' => $enrolledBatches,
            'upcomingExams' => $upcomingExams,
            'exams' => $upcomingExams,
        ];
    }
}
