<?php
require_once __DIR__ . '/../config/database.php';

class ProfileController {
    private PDO $db;

    public function __construct(?PDO $db = null) {
        global $db;
        $this->db = $db ?? $GLOBALS['db'];
    }

    // --- ADMIN PROFILE ---
    public function getAdminDetails(int $adminId): ?array {
        $stmt = $this->db->prepare('SELECT id, name, email, role, status FROM users WHERE id = ?');
        $stmt->execute([$adminId]);
        return $stmt->fetch() ?: null;
    }

    public function handleAdminProfileUpdate(int $adminId): array {
        $result = ['message' => '', 'messageType' => 'success'];
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $result;
        }

        $action = $_POST['action'] ?? '';

        if ($action === 'update_profile') {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $errors = [];

            if ($name === '' || strlen($name) < 2 || strlen($name) > 100) {
                $errors[] = 'Admin Name must be between 2 and 100 characters.';
            } elseif (!preg_match("/^[a-zA-Z\s\.\'-]+$/u", $name)) {
                $errors[] = 'Admin Name can only contain letters, spaces, dots, hyphens, and apostrophes.';
            }

            if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 150) {
                $errors[] = 'Please enter a valid email address (max 150 characters).';
            } else {
                $chkEmail = $this->db->prepare('SELECT id FROM users WHERE email = ? AND id != ? LIMIT 1');
                $chkEmail->execute([$email, $adminId]);
                if ($chkEmail->fetch()) {
                    $errors[] = 'This email address is already in use by another account.';
                }
            }

            if (!empty($errors)) {
                return ['message' => implode('<br>', $errors), 'messageType' => 'error'];
            }

            try {
                $stmt = $this->db->prepare('UPDATE users SET name = ?, email = ? WHERE id = ?');
                $stmt->execute([$name, $email, $adminId]);
                $_SESSION['user_name'] = $name;
                $_SESSION['user_email'] = $email;
                return ['message' => 'Administrator profile updated successfully.', 'messageType' => 'success'];
            } catch (PDOException $e) {
                return ['message' => 'Error updating profile: ' . $e->getMessage(), 'messageType' => 'error'];
            }
        }

        if ($action === 'change_password') {
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $errors = [];

            if ($currentPassword === '') {
                $errors[] = 'Please enter your current password.';
            }
            if (strlen($newPassword) < 8) {
                $errors[] = 'New Password must be at least 8 characters long.';
            }
            if ($newPassword !== $confirmPassword) {
                $errors[] = 'New Password and Confirm Password do not match.';
            }

            if (empty($errors)) {
                $stmt = $this->db->prepare('SELECT password FROM users WHERE id = ?');
                $stmt->execute([$adminId]);
                $currentHash = $stmt->fetchColumn();

                $pwdMatch = password_verify($currentPassword, $currentHash) || ($currentPassword === $currentHash);
                if (!$pwdMatch) {
                    $errors[] = 'Incorrect current password.';
                }
            }

            if (!empty($errors)) {
                return ['message' => implode('<br>', $errors), 'messageType' => 'error'];
            }

            try {
                $stmt = $this->db->prepare('UPDATE users SET password = ? WHERE id = ?');
                $stmt->execute([password_hash($newPassword, PASSWORD_DEFAULT), $adminId]);
                return ['message' => 'Password updated successfully.', 'messageType' => 'success'];
            } catch (PDOException $e) {
                return ['message' => 'Error changing password: ' . $e->getMessage(), 'messageType' => 'error'];
            }
        }

        return $result;
    }

    // --- TEACHER PROFILE ---
    public function getTeacherDetails(int $teacherId): ?array {
        $stmt = $this->db->prepare(
            'SELECT teachers.id, users.name, users.email, teachers.subject_specialist, teachers.phone, teachers.gender, teachers.joining_date,
                COALESCE((
                    SELECT GROUP_CONCAT(batches.name SEPARATOR \', \')
                    FROM batch_teachers
                    INNER JOIN batches ON batches.id = batch_teachers.batch_id
                    WHERE batch_teachers.teacher_id = teachers.id
                ), \'None\') AS assigned_batches
             FROM teachers
             INNER JOIN users ON users.id = teachers.user_id
             WHERE teachers.id = ?'
        );
        $stmt->execute([$teacherId]);
        return $stmt->fetch() ?: null;
    }

    public function handleTeacherProfileUpdate(int $teacherId): array {
        $result = ['message' => '', 'messageType' => 'success'];
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $result;
        }

        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';
        $errors = [];

        if ($name === '' || strlen($name) < 2 || strlen($name) > 100) {
            $errors[] = 'Full Name must be between 2 and 100 characters.';
        } elseif (!preg_match("/^[a-zA-Z\s\.\'-]+$/u", $name)) {
            $errors[] = 'Full Name can only contain letters, spaces, dots, hyphens, and apostrophes.';
        }

        if ($phone === '' || !preg_match('/^\+?[0-9]{10,15}$/', $phone)) {
            $errors[] = 'Phone number must be between 10 and 15 digits (e.g. 01712345678).';
        }

        if (!empty($password) && strlen($password) < 8) {
            $errors[] = 'New Password must be at least 8 characters long.';
        }

        if (!empty($errors)) {
            return ['message' => implode('<br>', $errors), 'messageType' => 'error'];
        }

        try {
            $stmt = $this->db->prepare('SELECT user_id FROM teachers WHERE id = ?');
            $stmt->execute([$teacherId]);
            $userId = (int) $stmt->fetchColumn();

            if (!empty($password)) {
                $uStmt = $this->db->prepare('UPDATE users SET name = ?, password = ? WHERE id = ?');
                $uStmt->execute([$name, password_hash($password, PASSWORD_DEFAULT), $userId]);
            } else {
                $uStmt = $this->db->prepare('UPDATE users SET name = ? WHERE id = ?');
                $uStmt->execute([$name, $userId]);
            }

            $tStmt = $this->db->prepare('UPDATE teachers SET phone = ? WHERE id = ?');
            $tStmt->execute([$phone, $teacherId]);
            $_SESSION['user_name'] = $name;

            return ['message' => 'Profile updated successfully!', 'messageType' => 'success'];
        } catch (PDOException $e) {
            return ['message' => 'Error updating profile: ' . $e->getMessage(), 'messageType' => 'error'];
        }
    }

    // --- STUDENT PROFILE ---
    public function getStudentDetails(int $studentId): ?array {
        $stmt = $this->db->prepare(
            'SELECT students.id, students.roll, users.name, users.email, students.phone, students.gender,
                    students.address, students.guardian_phone,
                    COALESCE((
                        SELECT GROUP_CONCAT(batches.name SEPARATOR \', \')
                        FROM enrollments
                        INNER JOIN batches ON batches.id = enrollments.batch_id
                        WHERE enrollments.student_id = students.id AND enrollments.status = \'active\'
                    ), \'None\') AS batch_name
             FROM students
             INNER JOIN users ON users.id = students.user_id
             WHERE students.id = ?'
        );
        $stmt->execute([$studentId]);
        return $stmt->fetch() ?: null;
    }

    public function handleStudentProfileUpdate(int $studentId): array {
        $result = ['message' => '', 'messageType' => 'success'];
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $result;
        }

        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $guardianPhone = trim($_POST['guardian_phone'] ?? '');
        $password = $_POST['password'] ?? '';
        $errors = [];

        if ($phone === '' || !preg_match('/^\+?[0-9]{10,15}$/', $phone)) {
            $errors[] = 'Student phone number must be between 10 and 15 digits (e.g. 01712345678).';
        }

        if ($guardianPhone === '' || !preg_match('/^\+?[0-9]{10,15}$/', $guardianPhone)) {
            $errors[] = 'Guardian phone number must be between 10 and 15 digits.';
        }

        if (strlen($address) > 255) {
            $errors[] = 'Address cannot exceed 255 characters.';
        }

        if (!empty($password) && strlen($password) < 8) {
            $errors[] = 'New Password must be at least 8 characters long.';
        }

        if (!empty($errors)) {
            return ['message' => implode('<br>', $errors), 'messageType' => 'error'];
        }

        try {
            $stmt = $this->db->prepare('SELECT user_id FROM students WHERE id = ?');
            $stmt->execute([$studentId]);
            $userId = (int) $stmt->fetchColumn();

            if (!empty($password)) {
                $uStmt = $this->db->prepare('UPDATE users SET password = ? WHERE id = ?');
                $uStmt->execute([password_hash($password, PASSWORD_DEFAULT), $userId]);
            }

            $sStmt = $this->db->prepare('UPDATE students SET phone = ?, address = ?, guardian_phone = ? WHERE id = ?');
            $sStmt->execute([$phone, $address ?: null, $guardianPhone, $studentId]);

            return ['message' => 'Profile updated successfully!', 'messageType' => 'success'];
        } catch (PDOException $e) {
            return ['message' => 'Error updating profile: ' . $e->getMessage(), 'messageType' => 'error'];
        }
    }
}
