<?php
// Session and Role Authentication Helper

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if the user is authenticated and has the required role.
 * If not authenticated, redirects to the login page.
 * 
 * @param array $allowedRoles Array of allowed roles (e.g. ['admin'], ['teacher', 'admin'])
 * @param string $loginRedirect Relative path to login page
 */
function checkAuth(array $allowedRoles = [], string $loginRedirect = '../login_page/loginPage.php'): void {
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
        header("Location: $loginRedirect");
        exit;
    }

    if (!empty($allowedRoles) && !in_array($_SESSION['role'], $allowedRoles, true)) {
        // Unauthorized role access
        header("Location: $loginRedirect");
        exit;
    }
}

/**
 * Get current authenticated user session data.
 */
function getAuthUser(): ?array {
    if (!isset($_SESSION['user_id'])) {
        return null;
    }

    return [
        'id' => (int) $_SESSION['user_id'],
        'name' => $_SESSION['user_name'] ?? 'User',
        'email' => $_SESSION['user_email'] ?? '',
        'role' => $_SESSION['role'] ?? '',
        'student_id' => $_SESSION['student_id'] ?? null,
        'student_roll' => $_SESSION['student_roll'] ?? null,
        'teacher_id' => $_SESSION['teacher_id'] ?? null,
        'teacher_subject' => $_SESSION['teacher_subject'] ?? null,
    ];
}

require_once __DIR__ . '/../config/database.php';

/**
 * Resolves current teacher info or provides fallback for development preview.
 */
function getCurrentTeacherSession(): array {
    global $db;
    $teacherId = $_SESSION['teacher_id'] ?? null;
    $teacherName = $_SESSION['user_name'] ?? 'Teacher';

    if (!$teacherId && isset($db)) {
        $firstTeacher = $db->query('SELECT teachers.id, users.name FROM teachers INNER JOIN users ON users.id = teachers.user_id LIMIT 1')->fetch();
        if ($firstTeacher) {
            $teacherId = (int) $firstTeacher['id'];
            $teacherName = $firstTeacher['name'];
        }
    }

    return [
        'id' => (int) ($teacherId ?? 0),
        'name' => $teacherName,
    ];
}

/**
 * Resolves current student info or provides fallback for development preview.
 */
function getCurrentStudentSession(): array {
    global $db;
    $studentId = $_SESSION['student_id'] ?? null;
    $studentName = $_SESSION['user_name'] ?? 'Student';
    $studentRoll = $_SESSION['student_roll'] ?? '';

    if (!$studentId && isset($db)) {
        $firstStudent = $db->query('SELECT students.id, users.name, students.roll FROM students INNER JOIN users ON users.id = students.user_id LIMIT 1')->fetch();
        if ($firstStudent) {
            $studentId = (int) $firstStudent['id'];
            $studentName = $firstStudent['name'];
            $studentRoll = $firstStudent['roll'];
        }
    }

    return [
        'id' => (int) ($studentId ?? 0),
        'name' => $studentName,
        'roll' => $studentRoll,
    ];
}

