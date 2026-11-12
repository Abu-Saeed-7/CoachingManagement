<?php
require_once __DIR__ . '/../config/database.php';

class TeacherController {
    private PDO $db;

    public function __construct(?PDO $db = null) {
        if ($db !== null) {
            $this->db = $db;
        } else {
            global $db;
            $this->db = $db ?? $GLOBALS['db'];
        }
    }

    /**
     * Fetch all teachers with assigned batches.
     */
    public function getAllTeachers(): array {
        return $this->db->query(
            'SELECT teachers.id, users.name, users.email, teachers.subject_specialist, teachers.phone, teachers.gender, teachers.joining_date,
                COALESCE((
                    SELECT GROUP_CONCAT(batches.name SEPARATOR \', \')
                    FROM batch_teachers
                    INNER JOIN batches ON batches.id = batch_teachers.batch_id
                    WHERE batch_teachers.teacher_id = teachers.id
                ), \'Not assigned\') AS assigned_batches
             FROM teachers
             INNER JOIN users ON users.id = teachers.user_id
             ORDER BY teachers.id DESC'
        )->fetchAll();
    }

    /**
     * Process add teacher form submission with validation.
     */
    public function handleAddTeacher(): array {
        $result = ['message' => '', 'messageType' => 'success', 'errors' => []];

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || (isset($_POST['action']) && $_POST['action'] !== 'add_teacher')) {
            return $result;
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $subjectSpecialist = trim($_POST['subject_specialist'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $gender = $_POST['gender'] ?? '';
        $address = trim($_POST['address'] ?? '');
        $joiningDate = $_POST['joining_date'] ?? '';
        $errors = [];

        // Field-level validation
        if ($name === '' || strlen($name) < 2 || strlen($name) > 100) {
            $errors[] = 'Teacher Name must be between 2 and 100 characters.';
        } elseif (!preg_match("/^[a-zA-Z0-9\s\.\'-]+$/u", $name)) {
            $errors[] = 'Teacher Name can only contain letters, numbers, spaces, dots, hyphens, and apostrophes.';
        }

        if ($subjectSpecialist !== '' && (strlen($subjectSpecialist) < 2 || strlen($subjectSpecialist) > 100)) {
            $errors[] = 'Subject Specialization must be between 2 and 100 characters if provided.';
        }

        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 150) {
            $errors[] = 'Please enter a valid email address (max 150 characters).';
        } else {
            $chkEmail = $this->db->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
            $chkEmail->execute([$email]);
            if ($chkEmail->fetch()) {
                $errors[] = 'This email address is already registered.';
            }
        }

        if (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters long.';
        }

        if (!in_array($gender, ['male', 'female', 'other'], true)) {
            $errors[] = 'Please select a valid gender option.';
        }

        if ($phone === '' || !preg_match('/^\+?[0-9]{10,15}$/', $phone)) {
            $errors[] = 'Phone number must be between 10 and 15 digits (e.g. 01712345678).';
        }

        if ($joiningDate === '' || !strtotime($joiningDate)) {
            $errors[] = 'Please provide a valid joining date.';
        }

        if (strlen($address) > 255) {
            $errors[] = 'Address cannot exceed 255 characters.';
        }

        if (!empty($errors)) {
            $result['errors'] = $errors;
            $result['message'] = implode(' ', $errors);
            $result['messageType'] = 'error';
            return $result;
        }

        try {
            $this->db->beginTransaction();

            $userStatement = $this->db->prepare(
                'INSERT INTO users (name, email, password, role, status) VALUES (?, ?, ?, \'teacher\', \'active\')'
            );
            $userStatement->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT)]);
            $userId = (int) $this->db->lastInsertId();

            $teacherStatement = $this->db->prepare(
                'INSERT INTO teachers (user_id, subject_specialist, phone, gender, address, joining_date) VALUES (?, ?, ?, ?, ?, ?)'
            );
            $teacherStatement->execute([$userId, $subjectSpecialist ?: null, $phone, $gender, $address ?: null, $joiningDate]);

            $this->db->commit();
            $result['message'] = 'Teacher added successfully.';
            $result['messageType'] = 'success';
            $_POST = [];
        } catch (PDOException $error) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            $errMsg = (isset($error->errorInfo[1]) && (int) $error->errorInfo[1] === 1062)
                ? 'This email is already registered.'
                : 'Unable to add teacher: ' . $error->getMessage();
            $result['message'] = $errMsg;
            $result['errors'] = [$errMsg];
            $result['messageType'] = 'error';
        }

        return $result;
    }

    /**
     * Delete a teacher and dependent assignments atomically inside a transaction.
     */
    public function handleDeleteTeacher(): array {
        $result = ['message' => '', 'messageType' => 'success'];

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || ($_POST['action'] ?? '') !== 'delete_teacher') {
            return $result;
        }

        $teacherId = (int) ($_POST['teacher_id'] ?? 0);
        if ($teacherId <= 0) {
            $result['message'] = 'Invalid teacher ID.';
            $result['messageType'] = 'error';
            return $result;
        }

        try {
            $stmt = $this->db->prepare(
                'SELECT teachers.user_id, users.name
                 FROM teachers
                 INNER JOIN users ON users.id = teachers.user_id
                 WHERE teachers.id = ? AND users.role = \'teacher\'
                 LIMIT 1'
            );
            $stmt->execute([$teacherId]);
            $teacher = $stmt->fetch();

            if (!$teacher) {
                $result['message'] = 'Teacher record not found.';
                $result['messageType'] = 'error';
                return $result;
            }

            $this->db->beginTransaction();

            // Clean up dependent child rows respecting FK order
            $this->db->prepare('DELETE FROM class_schedules WHERE teacher_id = ?')->execute([$teacherId]);
            $this->db->prepare('DELETE FROM batch_teachers WHERE teacher_id = ?')->execute([$teacherId]);
            $this->db->prepare('DELETE FROM teachers WHERE id = ?')->execute([$teacherId]);
            $this->db->prepare('DELETE FROM users WHERE id = ?')->execute([(int) $teacher['user_id']]);

            $this->db->commit();
            $result['message'] = 'Teacher "' . $teacher['name'] . '" deleted successfully.';
            $result['messageType'] = 'success';
        } catch (PDOException $error) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            $result['message'] = 'Unable to delete teacher: ' . $error->getMessage();
            $result['messageType'] = 'error';
        }

        return $result;
    }
}
