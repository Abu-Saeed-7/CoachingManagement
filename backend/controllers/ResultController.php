<?php
require_once __DIR__ . '/../config/database.php';

class ResultController {
    private PDO $db;

    public function __construct(?PDO $db = null) {
        global $db;
        $this->db = $db ?? $GLOBALS['db'];
    }

    public function getExamsDropdown(): array {
        return $this->db->query(
            'SELECT exams.id, exams.name, exams.total_marks, batches.name AS batch_name
             FROM exams
             INNER JOIN batches ON batches.id = exams.batch_id
             ORDER BY exams.id DESC'
        )->fetchAll();
    }

    public function getStudentsDropdown(): array {
        return $this->db->query(
            'SELECT students.id, users.name, students.roll
             FROM students
             INNER JOIN users ON users.id = students.user_id
             ORDER BY users.name'
        )->fetchAll();
    }

    public function getAllResults(): array {
        return $this->db->query(
            'SELECT results.id, students.id AS student_id, students.roll, users.name AS student_name,
                    exams.name AS exam_name, exams.total_marks, batches.name AS batch_name,
                    results.marks, results.grade,
                    CASE WHEN results.marks >= (exams.total_marks * 0.4) THEN \'Passed\' ELSE \'Failed\' END AS status
             FROM results
             INNER JOIN students ON students.id = results.student_id
             INNER JOIN users ON users.id = students.user_id
             INNER JOIN exams ON exams.id = results.exam_id
             INNER JOIN batches ON batches.id = exams.batch_id
             ORDER BY results.id DESC'
        )->fetchAll();
    }

    public function handleAddResult(): array {
        $result = ['message' => '', 'messageType' => 'success'];

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $result;
        }

        $examId = (int) ($_POST['exam_id'] ?? 0);
        $studentId = (int) ($_POST['student_id'] ?? 0);
        $marks = isset($_POST['marks']) && $_POST['marks'] !== '' ? (float) $_POST['marks'] : null;
        $errors = [];

        if ($examId < 1) {
            $errors[] = 'Please select a valid exam.';
        } else {
            $examStmt = $this->db->prepare('SELECT id, name, batch_id, total_marks FROM exams WHERE id = ? LIMIT 1');
            $examStmt->execute([$examId]);
            $examRow = $examStmt->fetch();
            if (!$examRow) {
                $errors[] = 'The selected exam does not exist.';
            }
        }

        if ($studentId < 1) {
            $errors[] = 'Please select a valid student.';
        } else {
            $stuStmt = $this->db->prepare('SELECT id FROM students WHERE id = ? LIMIT 1');
            $stuStmt->execute([$studentId]);
            if (!$stuStmt->fetch()) {
                $errors[] = 'The selected student does not exist.';
            }
        }

        if ($marks === null) {
            $errors[] = 'Marks obtained is required.';
        } elseif ($marks < 0) {
            $errors[] = 'Marks obtained cannot be negative.';
        } elseif (!empty($examRow) && $marks > (float) $examRow['total_marks']) {
            $errors[] = 'Marks obtained (' . $marks . ') cannot exceed the exam total marks (' . (float)$examRow['total_marks'] . ').';
        }

        if (!empty($errors)) {
            $result['message'] = implode('<br>', $errors);
            $result['messageType'] = 'error';
            return $result;
        }

        try {
            $totalMarks = (float) $examRow['total_marks'];

            $percentage = ($marks / $totalMarks) * 100;
            if ($percentage >= 80) $grade = 'A+';
            elseif ($percentage >= 70) $grade = 'A';
            elseif ($percentage >= 60) $grade = 'A-';
            elseif ($percentage >= 50) $grade = 'B';
            elseif ($percentage >= 40) $grade = 'C';
            elseif ($percentage >= 33) $grade = 'D';
            else $grade = 'F';

            $stmt = $this->db->prepare(
                'INSERT INTO results (student_id, exam_id, marks, grade) 
                 VALUES (?, ?, ?, ?)
                 ON DUPLICATE KEY UPDATE marks = VALUES(marks), grade = VALUES(grade)'
            );
            $stmt->execute([$studentId, $examId, $marks, $grade]);
            $result['message'] = 'Result saved successfully.';
            $result['messageType'] = 'success';
            $_POST = [];
        } catch (PDOException $e) {
            $result['message'] = 'Unable to save result: ' . $e->getMessage();
            $result['messageType'] = 'error';
        }

        return $result;
    }

    public function handleSaveTeacherMarks(int $teacherId): array {
        $result = ['message' => '', 'messageType' => 'success'];

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['save_marks'])) {
            return $result;
        }

        $examId = (int) ($_POST['exam_id'] ?? 0);
        $marksData = $_POST['marks'] ?? [];
        $errors = [];

        if ($examId < 1 || empty($marksData)) {
            return ['message' => 'Please select an exam and input marks.', 'messageType' => 'error'];
        }

        try {
            $examStmt = $this->db->prepare(
                'SELECT exams.total_marks, exams.name 
                 FROM exams 
                 INNER JOIN batch_teachers ON batch_teachers.batch_id = exams.batch_id
                 WHERE exams.id = ? AND batch_teachers.teacher_id = ?'
            );
            $examStmt->execute([$examId, $teacherId]);
            $examInfo = $examStmt->fetch();

            if (!$examInfo) {
                return ['message' => 'You are not authorized to grade this exam, or the exam does not exist.', 'messageType' => 'error'];
            }

            $totalMarks = (float) $examInfo['total_marks'];
            $validMarksToSave = [];

            foreach ($marksData as $sId => $mark) {
                $sId = (int) $sId;
                if ($mark !== '' && $mark !== null) {
                    if (!is_numeric($mark)) {
                        $errors[] = "Marks for student #$sId must be a valid number.";
                    } else {
                        $m = (float) $mark;
                        if ($m < 0 || $m > $totalMarks) {
                            $errors[] = "Marks for student #$sId ($m) must be between 0 and $totalMarks.";
                        } else {
                            $validMarksToSave[$sId] = $m;
                        }
                    }
                }
            }

            if (!empty($errors)) {
                return ['message' => implode('<br>', $errors), 'messageType' => 'error'];
            }

            if (empty($validMarksToSave)) {
                return ['message' => 'Please enter marks for at least one student before submitting.', 'messageType' => 'error'];
            }

            $stmt = $this->db->prepare(
                'INSERT INTO results (student_id, exam_id, marks, grade) VALUES (?, ?, ?, ?)
                 ON DUPLICATE KEY UPDATE marks = VALUES(marks), grade = VALUES(grade)'
            );

            foreach ($validMarksToSave as $sId => $m) {
                $pct = ($m / $totalMarks) * 100;
                if ($pct >= 80) $grade = 'A+';
                elseif ($pct >= 70) $grade = 'A';
                elseif ($pct >= 60) $grade = 'A-';
                elseif ($pct >= 50) $grade = 'B';
                elseif ($pct >= 40) $grade = 'C';
                elseif ($pct >= 33) $grade = 'D';
                else $grade = 'F';

                $stmt->execute([$sId, $examId, $m, $grade]);
            }

            $result['message'] = 'Results recorded successfully!';
        } catch (PDOException $e) {
            $result['message'] = 'Error saving results: ' . $e->getMessage();
            $result['messageType'] = 'error';
        }

        return $result;
    }

    public function getTeacherGradingView(int $teacherId, int $selectedExamId): array {
        $stmt = $this->db->prepare(
            'SELECT batches.id, batches.name 
             FROM batches 
             INNER JOIN batch_teachers ON batch_teachers.batch_id = batches.id 
             WHERE batch_teachers.teacher_id = ?'
        );
        $stmt->execute([$teacherId]);
        $batches = $stmt->fetchAll();

        $stmt = $this->db->prepare(
            'SELECT exams.id, exams.name, exams.total_marks, batches.name AS batch_name, exams.batch_id
             FROM exams
             INNER JOIN batches ON batches.id = exams.batch_id
             INNER JOIN batch_teachers ON batch_teachers.batch_id = batches.id
             WHERE batch_teachers.teacher_id = ?
             ORDER BY exams.exam_date DESC'
        );
        $stmt->execute([$teacherId]);
        $exams = $stmt->fetchAll();

        $studentsToGrade = [];
        if ($selectedExamId > 0) {
            $stmt = $this->db->prepare(
                'SELECT students.id, students.roll, users.name, results.marks, results.grade, exams.total_marks
                 FROM students
                 INNER JOIN users ON users.id = students.user_id
                 INNER JOIN enrollments ON enrollments.student_id = students.id
                 INNER JOIN exams ON exams.batch_id = enrollments.batch_id
                 LEFT JOIN results ON results.student_id = students.id AND results.exam_id = exams.id
                 WHERE exams.id = ? AND enrollments.status = \'active\'
                 ORDER BY students.roll'
            );
            $stmt->execute([$selectedExamId]);
            $studentsToGrade = $stmt->fetchAll();
        }

        return [
            'batches' => $batches,
            'exams' => $exams,
            'studentsToGrade' => $studentsToGrade,
        ];
    }

    public function getStudentResults(int $studentId): array {
        $stmt = $this->db->prepare(
            'SELECT results.id, exams.name AS exam_name, exams.exam_date, exams.total_marks,
                    subjects.name AS subject_name, batches.name AS batch_name,
                    results.marks, results.grade,
                    CASE WHEN results.marks >= (exams.total_marks * 0.4) THEN \'Passed\' ELSE \'Failed\' END AS status
             FROM results
             INNER JOIN exams ON exams.id = results.exam_id
             INNER JOIN subjects ON subjects.id = exams.subject_id
             INNER JOIN batches ON batches.id = exams.batch_id
             WHERE results.student_id = ?
             ORDER BY exams.exam_date DESC'
        );
        $stmt->execute([$studentId]);
        return $stmt->fetchAll();
    }
}
