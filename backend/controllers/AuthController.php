<?php
require_once __DIR__ . '/../config/database.php';

class AuthController {
    private PDO $db;

    public function __construct(?PDO $db = null) {
        global $db;
        $this->db = $db ?? $GLOBALS['db'];
    }

    /**
     * Process user login request.
     * Returns error string if login fails, or redirects on success.
     */
    public function handleLogin(): string {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return '';
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $selectedRole = $_POST['role'] ?? '';

        if ($email === '' || $password === '' || $selectedRole === '') {
            return 'Please enter both email and password, and select your role.';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return 'Please enter a valid email address.';
        }

        if (!in_array($selectedRole, ['admin', 'teacher', 'student'], true)) {
            return 'Please select a valid role.';
        }

        try {
            $stmt = $this->db->prepare('SELECT id, name, email, password, role, status FROM users WHERE email = ? LIMIT 1');
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if (!$user) {
                return 'No account found with this email.';
            }

            if ($user['status'] !== 'active') {
                return 'This account has been deactivated. Please contact administrator.';
            }

            if ($user['role'] !== $selectedRole) {
                return "This account does not have $selectedRole privileges.";
            }

            $passwordValid = password_verify($password, $user['password']) || ($password === $user['password']);

            if (!$passwordValid) {
                return 'Incorrect password. Please try again.';
            }

            // Set session variables
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $_SESSION['user_id'] = (int) $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'student') {
                $sStmt = $this->db->prepare('SELECT id, roll FROM students WHERE user_id = ?');
                $sStmt->execute([$user['id']]);
                $studentInfo = $sStmt->fetch();
                $_SESSION['student_id'] = $studentInfo ? (int) $studentInfo['id'] : null;
                $_SESSION['student_roll'] = $studentInfo ? $studentInfo['roll'] : null;

                header('Location: ../Student DashBoard/student_dashboard.php');
                exit;
            }

            if ($user['role'] === 'teacher') {
                $tStmt = $this->db->prepare('SELECT id, subject_specialist FROM teachers WHERE user_id = ?');
                $tStmt->execute([$user['id']]);
                $teacherInfo = $tStmt->fetch();
                $_SESSION['teacher_id'] = $teacherInfo ? (int) $teacherInfo['id'] : null;
                $_SESSION['teacher_subject'] = $teacherInfo ? $teacherInfo['subject_specialist'] : null;

                header('Location: ../Teacher DashBoard/teacher_dashboard.php');
                exit;
            }

            if ($user['role'] === 'admin') {
                header('Location: ../Admin DashBoard/admin_dashboard.php');
                exit;
            }

            return 'Unknown role detected.';
        } catch (PDOException $e) {
            return 'Login error: ' . $e->getMessage();
        }
    }

    /**
     * Process logout and redirect to login page.
     */
    public function handleLogout(string $redirect = '../login_page/loginPage.php'): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']
            );
        }
        session_destroy();
        header("Location: $redirect");
        exit;
    }
}
