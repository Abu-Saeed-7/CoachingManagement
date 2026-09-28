<?php
require_once __DIR__ . '/../config/database.php';

class DashboardController {
    private PDO $db;

    public function __construct(?PDO $db = null) {
        global $db;
        $this->db = $db ?? $GLOBALS['db'];
    }

    /**
     * Get statistics and feeds for Admin Dashboard.
     */
    public function getAdminDashboardData(): array {
        $totalStudents = (int) $this->db->query("SELECT COUNT(*) FROM students")->fetchColumn();
        $totalTeachers = (int) $this->db->query("SELECT COUNT(*) FROM teachers")->fetchColumn();
        $activeBatches = (int) $this->db->query("SELECT COUNT(*) FROM batches WHERE status = 'active'")->fetchColumn();
        $totalExams = (int) $this->db->query("SELECT COUNT(*) FROM exams")->fetchColumn();
        $unpaidFees = (float) $this->db->query("SELECT COALESCE(SUM(amount), 0) FROM fees WHERE status = 'unpaid'")->fetchColumn();

        $recentStudents = $this->db->query(
            'SELECT students.id, users.name, students.roll,
                COALESCE((
                    SELECT batches.name FROM enrollments INNER JOIN batches ON batches.id = enrollments.batch_id WHERE enrollments.student_id = students.id LIMIT 1
                ), \'Not assigned\') AS batch_name
             FROM students
             INNER JOIN users ON users.id = students.user_id
             ORDER BY students.id DESC
             LIMIT 5'
        )->fetchAll();

        $upcomingExams = $this->db->query(
            'SELECT exams.id, exams.name, exams.exam_date, batches.name AS batch_name, subjects.name AS subject_name
             FROM exams
             INNER JOIN batches ON batches.id = exams.batch_id
             INNER JOIN subjects ON subjects.id = exams.subject_id
             WHERE exams.exam_date >= CURDATE()
             ORDER BY exams.exam_date ASC
             LIMIT 5'
        )->fetchAll();

        return [
            'totalStudents' => $totalStudents,
            'totalTeachers' => $totalTeachers,
            'activeBatches' => $activeBatches,
            'totalExams' => $totalExams,
            'unpaidFees' => $unpaidFees,
            'recentStudents' => $recentStudents,
            'upcomingExams' => $upcomingExams,
        ];
    }

    /**
     * Get statistics and feeds for Teacher Dashboard.
     */
    public function getTeacherDashboardData(int $teacherId): array {
        $stmt = $this->db->prepare(
            'SELECT COUNT(DISTINCT batch_teachers.batch_id) 
             FROM batch_teachers 
             INNER JOIN batches ON batches.id = batch_teachers.batch_id 
             WHERE batch_teachers.teacher_id = ? AND batches.status = \'active\''
        );
        $stmt->execute([$teacherId]);
        $activeBatchesCount = (int) $stmt->fetchColumn();

        $stmt = $this->db->prepare(
            'SELECT COUNT(DISTINCT enrollments.student_id) 
             FROM enrollments 
             INNER JOIN batch_teachers ON batch_teachers.batch_id = enrollments.batch_id 
             WHERE batch_teachers.teacher_id = ? AND enrollments.status = \'active\''
        );
        $stmt->execute([$teacherId]);
        $totalStudents = (int) $stmt->fetchColumn();

        $stmt = $this->db->prepare(
            'SELECT COUNT(exams.id) 
             FROM exams 
             INNER JOIN batch_teachers ON batch_teachers.batch_id = exams.batch_id 
             WHERE batch_teachers.teacher_id = ?'
        );
        $stmt->execute([$teacherId]);
        $totalExams = (int) $stmt->fetchColumn();

        $stmt = $this->db->prepare(
            'SELECT batches.id, batches.name, batches.start_date, batches.status,
                (SELECT COUNT(*) FROM enrollments WHERE enrollments.batch_id = batches.id AND enrollments.status = \'active\') AS student_count
             FROM batches
             INNER JOIN batch_teachers ON batch_teachers.batch_id = batches.id
             WHERE batch_teachers.teacher_id = ?
             ORDER BY batches.id DESC'
        );
        $stmt->execute([$teacherId]);
        $assignedBatches = $stmt->fetchAll();

        $stmt = $this->db->prepare(
            'SELECT exams.id, exams.name, exams.exam_date, exams.total_marks, batches.name AS batch_name, subjects.name AS subject_name
             FROM exams
             INNER JOIN batches ON batches.id = exams.batch_id
             INNER JOIN subjects ON subjects.id = exams.subject_id
             INNER JOIN batch_teachers ON batch_teachers.batch_id = batches.id
             WHERE batch_teachers.teacher_id = ? AND exams.exam_date >= CURDATE()
             ORDER BY exams.exam_date ASC
             LIMIT 5'
        );
        $stmt->execute([$teacherId]);
        $upcomingExams = $stmt->fetchAll();

        return [
            'activeBatchesCount' => $activeBatchesCount,
            'totalStudents' => $totalStudents,
            'totalExams' => $totalExams,
            'assignedBatches' => $assignedBatches,
            'upcomingExams' => $upcomingExams,
        ];
    }

    /**
     * Get statistics and feeds for Student Dashboard.
     */
    public function getStudentDashboardData(int $studentId): array {
        $stmt = $this->db->prepare(
            'SELECT batches.id, batches.name 
             FROM batches 
             INNER JOIN enrollments ON enrollments.batch_id = batches.id 
             WHERE enrollments.student_id = ? AND enrollments.status = \'active\''
        );
        $stmt->execute([$studentId]);
        $enrolledBatches = $stmt->fetchAll();

        // Attendance rate
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM attendance WHERE student_id = ?');
        $stmt->execute([$studentId]);
        $totalAttendance = (int) $stmt->fetchColumn();
        $attendanceRate = '--';

        if ($totalAttendance > 0) {
            $stmt = $this->db->prepare('SELECT COUNT(*) FROM attendance WHERE student_id = ? AND status = \'present\'');
            $stmt->execute([$studentId]);
            $presents = (int) $stmt->fetchColumn();
            $attendanceRate = round(($presents / $totalAttendance) * 100) . '%';
        }

        // Latest Result
        $latestResult = '--';
        $stmt = $this->db->prepare(
            'SELECT results.grade, results.marks, exams.total_marks, exams.name AS exam_name
             FROM results
             INNER JOIN exams ON exams.id = results.exam_id
             WHERE results.student_id = ?
             ORDER BY results.id DESC
             LIMIT 1'
        );
        $stmt->execute([$studentId]);
        $res = $stmt->fetch();
        if ($res) {
            $latestResult = $res['grade'] . ' (' . (float)$res['marks'] . '/' . (float)$res['total_marks'] . ')';
        }

        // Upcoming Exams
        $upcomingExamTitle = '--';
        $stmt = $this->db->prepare(
            'SELECT exams.id, exams.name, exams.exam_date, batches.name AS batch_name, subjects.name AS subject_name
             FROM exams
             INNER JOIN enrollments ON enrollments.batch_id = exams.batch_id
             INNER JOIN batches ON batches.id = exams.batch_id
             INNER JOIN subjects ON subjects.id = exams.subject_id
             WHERE enrollments.student_id = ? AND exams.exam_date >= CURDATE()
             ORDER BY exams.exam_date ASC
             LIMIT 3'
        );
        $stmt->execute([$studentId]);
        $upcomingExamsList = $stmt->fetchAll();
        if (!empty($upcomingExamsList)) {
            $upcomingExamTitle = date('d M', strtotime($upcomingExamsList[0]['exam_date']));
        }

        // Fee Status
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM fees WHERE student_id = ? AND status = \'unpaid\'');
        $stmt->execute([$studentId]);
        $unpaidCount = (int) $stmt->fetchColumn();
        $feeStatus = $unpaidCount > 0 ? "Due ($unpaidCount)" : 'Paid';

        // Recent Attendance
        $stmt = $this->db->prepare(
            'SELECT date, status FROM attendance WHERE student_id = ? ORDER BY date DESC LIMIT 4'
        );
        $stmt->execute([$studentId]);
        $recentAttendance = $stmt->fetchAll();

        return [
            'enrolledBatches' => $enrolledBatches,
            'attendanceRate' => $attendanceRate,
            'latestResult' => $latestResult,
            'upcomingExamTitle' => $upcomingExamTitle,
            'feeStatus' => $feeStatus,
            'upcomingExamsList' => $upcomingExamsList,
            'recentAttendance' => $recentAttendance,
        ];
    }
}
