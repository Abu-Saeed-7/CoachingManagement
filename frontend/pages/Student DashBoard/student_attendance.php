<?php
require_once __DIR__ . '/../../../backend/middleware/auth.php';
require_once __DIR__ . '/../../../backend/controllers/AttendanceController.php';

checkAuth(['student', 'admin']);

$student = getCurrentStudentSession();
$studentId = $student['id'];
$studentName = $student['name'];

$attendanceController = new AttendanceController();
$history = $attendanceController->getStudentAttendanceHistory((int) $studentId);

$totalClasses = $history['totalClasses'];
$attended = $history['attendedClasses'] + $history['lateClasses'];
$absences = $history['absentClasses'];
$percentage = $history['overallPercentage'];
$logs = $history['attendanceRecords'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Attendance - Student Panel</title>
    <link rel="stylesheet" href="student_dashboard.css">
    <link rel="stylesheet" href="student_attendance.css">
</head>

<body>
    <!-- ১. TOP NAVBAR -->
    <header class="top_navbar">
        <div class="nav_left">
            <button id="sidebarToggle" class="toggle_btn">☰</button>
            <h2 class="system_title">COACHING MANAGEMENT SYSTEM</h2>
        </div>
        <div class="nav_right">
            <span class="nav_icon" title="Notifications">🔔</span>
            <a href="student_profile.php" class="student_profile_btn">
                <span>🎓</span>
                <span class="student_name"><?= htmlspecialchars($studentName, ENT_QUOTES, 'UTF-8'); ?> ▾</span>
            </a>
        </div>
    </header>

    <!-- ২. মূল লেআউট -->
    <div class="dashboard_container">
        <!-- SIDEBAR -->
        <aside class="sidebar" id="sidebar">
            <ul class="sidebar_menu">
                <li><a href="student_dashboard.php">🏠 <span>Dashboard</span></a></li>
                <li><a href="student_profile.php">👤 <span>My Profile</span></a></li>
                <li><a href="student_classes_exams.php">📅 <span>Classes & Exams</span></a></li>
                <li class="active"><a href="student_attendance.php">📋 <span>My Attendance</span></a></li>
                <li><a href="student_results.php">📊 <span>My Results</span></a></li>
                <li><a href="student_fees.php">💰 <span>My Fees</span></a></li>
                <li class="logout"><a href="../login_page/loginPage.php">🚪 <span>Logout</span></a></li>
            </ul>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="main_content">
            <div class="page_header">
                <h1>My Attendance History</h1>
                <p>Track your daily class attendance and overall attendance percentage.</p>
            </div>

            <!-- SUMMARY STATS -->
            <div class="att_summary_row">
                <div class="att_stat_box">
                    <h4>Total Classes</h4>
                    <p class="number"><?= $totalClasses; ?></p>
                </div>
                <div class="att_stat_box">
                    <h4>Classes Attended</h4>
                    <p class="number present"><?= $attended; ?> Days</p>
                </div>
                <div class="att_stat_box">
                    <h4>Absences</h4>
                    <p class="number absent"><?= $absences; ?> Days</p>
                </div>
                <div class="att_stat_box">
                    <h4>Attendance Percentage</h4>
                    <p class="number" style="color:<?= $percentage >= 75 ? '#10b981' : '#f59e0b'; ?>;"><?= $percentage; ?>%</p>
                </div>
            </div>

            <!-- ATTENDANCE LOG TABLE -->
            <div class="attendance_table_card">
                <div class="card_header">
                    <h3>Detailed Attendance Log</h3>
                </div>

                <div class="table_container">
                    <table class="custom_table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Day</th>
                                <th>Enrolled Batch</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($logs)): ?>
                                <tr>
                                    <td colspan="4" style="text-align: center; color: #6b7280; padding: 24px;">No attendance records found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($logs as $l): ?>
                                    <tr>
                                        <td><?= htmlspecialchars(date('d M, Y', strtotime($l['date'])), ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?= htmlspecialchars(date('l', strtotime($l['date'])), ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?= htmlspecialchars($l['batch_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td>
                                            <span class="<?= $l['status'] === 'present' ? 'status_active' : 'status_inactive'; ?>">
                                                <?= htmlspecialchars(ucfirst($l['status']), ENT_QUOTES, 'UTF-8'); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- JavaScript -->
    <script>
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        sidebarToggle.addEventListener('click', function () {
            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('mobile_open');
            } else {
                sidebar.classList.toggle('collapsed');
            }
        });

        document.addEventListener('click', function (e) {
            if (window.innerWidth <= 768 && sidebar.classList.contains('mobile_open')) {
                if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                    sidebar.classList.remove('mobile_open');
                }
            }
        });
    </script>
</body>

</html>
