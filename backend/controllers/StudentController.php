<?php
require_once __DIR__ . '/../config/database.php';

class StudentController {
    private PDO $db;

    public function __construct(?PDO $db = null) {
        global $db;
        $this->db = $db ?? $GLOBALS['db'];
    }

    /**
     * Fetch all active batches for student assignment dropdowns.
     */
    public function getActiveBatches(): array {
        return $this->db->query("SELECT id, name FROM batches WHERE status = 'active' ORDER BY name")->fetchAll();
    }

    /**
     * Fetch all students with their assigned batch and contact information.
     */
    public function getAllStudents(): array {
        return $this->db->query(
            'SELECT students.id, users.name, users.email, students.roll, students.phone,
                    students.gender, students.guardian_phone, students.address,
                    COALESCE(users.status, \'active\') AS status,
                COALESCE((
                    SELECT batches.name
                    FROM enrollments
                    INNER JOIN batches ON batches.id = enrollments.batch_id
                    WHERE enrollments.student_id = students.id
                    LIMIT 1
                ), \'Not assigned\') AS batch_name
             FROM students
             INNER JOIN users ON users.id = students.user_id
             ORDER BY students.id DESC'
        )->fetchAll();
    }

    /**
     * Process add student form submission with full validation and transaction.
     */
    public function handleAddStudent(): array {
        $result = ['message' => '', 'messageType' => 'success'];

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $result;
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $roll = trim($_POST['roll'] ?? '');
        $gender = $_POST['gender'] ?? '';
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $guardianPhone = trim($_POST['guardian_phone'] ?? '');
        $batchId = (int) ($_POST['batch_id'] ?? 0);
        $errors = [];

        // Validation
        if ($name === '' || strlen($name) < 2 || strlen($name) > 100) {
            $errors[] = 'Full Name must be between 2 and 100 characters.';
        } elseif (!preg_match("/^[a-zA-Z\s\.\'-]+$/u", $name)) {
            $errors[] = 'Full Name can only contain letters, spaces, dots, hyphens, and apostrophes.';
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

        if ($roll === '' || strlen($roll) > 30) {
            $errors[] = 'Roll number is required and cannot exceed 30 characters.';
        } else {
            $chkRoll = $this->db->prepare('SELECT id FROM students WHERE roll = ? LIMIT 1');
            $chkRoll->execute([$roll]);
            if ($chkRoll->fetch()) {
                $errors[] = 'This roll number is already assigned to another student.';
            }
        }

        if (!in_array($gender, ['male', 'female', 'other'], true)) {
            $errors[] = 'Please select a valid gender option.';
        }

        if ($phone === '' || !preg_match('/^\+?[0-9]{10,15}$/', $phone)) {
            $errors[] = 'Phone number must be between 10 and 15 digits (e.g. 01712345678).';
        }

        if ($guardianPhone === '' || !preg_match('/^\+?[0-9]{10,15}$/', $guardianPhone)) {
            $errors[] = 'Guardian phone number must be between 10 and 15 digits.';
        }

        if (strlen($address) > 255) {
            $errors[] = 'Address cannot exceed 255 characters.';
        }

        if ($batchId > 0) {
            $chkBatch = $this->db->prepare('SELECT id FROM batches WHERE id = ?');
            $chkBatch->execute([$batchId]);
            if (!$chkBatch->fetch()) {
                $errors[] = 'The selected batch does not exist.';
            }
        }

        if (!empty($errors)) {
            $result['message'] = implode('<br>', $errors);
            $result['messageType'] = 'error';
            return $result;
        }

        try {
            $this->db->beginTransaction();

            $userStatement = $this->db->prepare(
                'INSERT INTO users (name, email, password, role, status) VALUES (?, ?, ?, \'student\', \'active\')'
            );
            $userStatement->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT)]);
            $userId = (int) $this->db->lastInsertId();

            $studentStatement = $this->db->prepare(
                'INSERT INTO students (user_id, roll, gender, phone, address, guardian_phone) VALUES (?, ?, ?, ?, ?, ?)'
            );
            $studentStatement->execute([$userId, $roll, $gender, $phone, $address ?: null, $guardianPhone]);
            $studentId = (int) $this->db->lastInsertId();

            if ($batchId > 0) {
                $enrollmentStatement = $this->db->prepare(
                    'INSERT INTO enrollments (student_id, batch_id, status) VALUES (?, ?, \'active\')'
                );
                $enrollmentStatement->execute([$studentId, $batchId]);
            }

            $this->db->commit();
            $result['message'] = 'Student added successfully.';
            $result['messageType'] = 'success';
            $_POST = []; // Reset sticky form
        } catch (PDOException $error) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            $result['message'] = (isset($error->errorInfo[1]) && $error->errorInfo[1] === 1062) 
                ? 'A student with this email or roll already exists.' 
                : 'Unable to add student: ' . $error->getMessage();
            $result['messageType'] = 'error';
        }

        return $result;
    }

    /**
     * Get students enrolled in a teacher's batches.
     */
    public function getTeacherStudents(int $teacherId, int $batchFilterId = 0): array {
        $sql = 'SELECT DISTINCT students.id, students.roll, users.name, users.email, students.phone,
                        students.guardian_phone, batches.name AS batch_name, batches.id AS batch_id
                FROM students
                INNER JOIN users ON users.id = students.user_id
                INNER JOIN enrollments ON enrollments.student_id = students.id
                INNER JOIN batches ON batches.id = enrollments.batch_id
                INNER JOIN batch_teachers ON batch_teachers.batch_id = batches.id
                WHERE batch_teachers.teacher_id = ? AND enrollments.status = \'active\'';
        $params = [$teacherId];

        if ($batchFilterId > 0) {
            $sql .= ' AND batches.id = ?';
            $params[] = $batchFilterId;
        }

        $sql .= ' ORDER BY students.roll ASC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
