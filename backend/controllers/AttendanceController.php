<?php
require_once __DIR__ . '/../config/database.php';

class AttendanceController {
    private PDO $db;

    public function __construct(?PDO $db = null) {
        global $db;
        $this->db = $db ?? $GLOBALS['db'];
    }

    public function handleSaveTeacherAttendance(int $teacherId): array {
        $result = ['message' => '', 'messageType' => 'success'];

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['save_attendance'])) {
            return $result;
        }

        $batchId = (int) ($_POST['batch_id'] ?? 0);
        $date = trim($_POST['date'] ?? date('Y-m-d'));
        $attendanceData = $_POST['status'] ?? [];
        $errors = [];

        if ($batchId < 1) {
            $errors[] = 'Please select a valid batch.';
        } else {
            $bStmt = $this->db->prepare('SELECT id FROM batch_teachers WHERE batch_id = ? AND teacher_id = ?');
            $bStmt->execute([$batchId, $teacherId]);
            if (!$bStmt->fetch()) {
                $errors[] = 'You are not assigned to manage attendance for this batch.';
            }
        }

        if ($date === '' || !strtotime($date)) {
            $errors[] = 'Please provide a valid attendance date.';
        }

        if (empty($attendanceData)) {
            $errors[] = 'Please select attendance status for at least one student.';
        }

        if (!empty($errors)) {
            $result['message'] = implode('<br>', $errors);
            $result['messageType'] = 'error';
            return $result;
        }

        try {
            $stmt = $this->db->prepare(
                'INSERT INTO attendance (student_id, batch_id, date, status) VALUES (?, ?, ?, ?)
                 ON DUPLICATE KEY UPDATE status = VALUES(status)'
            );
            $savedCount = 0;
            foreach ($attendanceData as $sId => $status) {
                if (in_array($status, ['present', 'absent', 'late'], true)) {
                    $stmt->execute([(int)$sId, $batchId, $date, $status]);
                    $savedCount++;
                }
            }
            if ($savedCount > 0) {
                $result['message'] = "Attendance recorded successfully for $savedCount students!";
            } else {
                $result['message'] = 'No valid attendance records were submitted.';
                $result['messageType'] = 'error';
            }
        } catch (PDOException $e) {
            $result['message'] = 'Error recording attendance: ' . $e->getMessage();
            $result['messageType'] = 'error';
        }

        return $result;
    }

    public function getTeacherAttendanceData(int $teacherId, int $selectedBatchId, string $selectedDate): array {
        $stmt = $this->db->prepare(
            'SELECT batches.id, batches.name 
             FROM batches 
             INNER JOIN batch_teachers ON batch_teachers.batch_id = batches.id 
             WHERE batch_teachers.teacher_id = ?'
        );
        $stmt->execute([$teacherId]);
        $batches = $stmt->fetchAll();

        if ($selectedBatchId === 0 && !empty($batches)) {
            $selectedBatchId = (int) $batches[0]['id'];
        }

        $studentsToAttend = [];
        if ($selectedBatchId > 0) {
            $stmt = $this->db->prepare(
                'SELECT students.id, students.roll, users.name, attendance.status
                 FROM students
                 INNER JOIN users ON users.id = students.user_id
                 INNER JOIN enrollments ON enrollments.student_id = students.id
                 LEFT JOIN attendance ON attendance.student_id = students.id 
                                     AND attendance.batch_id = enrollments.batch_id 
                                     AND attendance.date = ?
                 WHERE enrollments.batch_id = ? AND enrollments.status = \'active\'
                 ORDER BY students.roll'
            );
            $stmt->execute([$selectedDate, $selectedBatchId]);
            $studentsToAttend = $stmt->fetchAll();
        }

        return [
            'batches' => $batches,
            'selectedBatchId' => $selectedBatchId,
            'studentsToAttend' => $studentsToAttend,
        ];
    }

    public function getStudentAttendanceHistory(int $studentId): array {
        $stmt = $this->db->prepare(
            'SELECT 
                COUNT(*) AS total_classes,
                SUM(CASE WHEN status = \'present\' THEN 1 ELSE 0 END) AS attended_classes,
                SUM(CASE WHEN status = \'absent\' THEN 1 ELSE 0 END) AS absent_classes,
                SUM(CASE WHEN status = \'late\' THEN 1 ELSE 0 END) AS late_classes
             FROM attendance 
             WHERE student_id = ?'
        );
        $stmt->execute([$studentId]);
        $attSummary = $stmt->fetch();

        $totalClasses = (int) ($attSummary['total_classes'] ?? 0);
        $attendedClasses = (int) ($attSummary['attended_classes'] ?? 0);
        $absentClasses = (int) ($attSummary['absent_classes'] ?? 0);
        $lateClasses = (int) ($attSummary['late_classes'] ?? 0);
        $overallPercentage = $totalClasses > 0 ? round(($attendedClasses / $totalClasses) * 100) : 100;

        $stmt = $this->db->prepare(
            'SELECT attendance.date, attendance.status, batches.name AS batch_name
             FROM attendance
             INNER JOIN batches ON batches.id = attendance.batch_id
             WHERE attendance.student_id = ?
             ORDER BY attendance.date DESC'
        );
        $stmt->execute([$studentId]);
        $attendanceRecords = $stmt->fetchAll();

        return [
            'totalClasses' => $totalClasses,
            'attendedClasses' => $attendedClasses,
            'absentClasses' => $absentClasses,
            'lateClasses' => $lateClasses,
            'overallPercentage' => $overallPercentage,
            'attendanceRecords' => $attendanceRecords,
        ];
    }
}
