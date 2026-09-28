<?php
require_once __DIR__ . '/../../../backend/middleware/auth.php';
require_once __DIR__ . '/../../../backend/controllers/DashboardController.php';

checkAuth(['student', 'admin']);

$student = getCurrentStudentSession();
$studentId = $student['id'];
$studentName = $student['name'];
$studentRoll = $student['roll'];

$dashboardController = new DashboardController();
$data = $dashboardController->getStudentDashboardData((int) $studentId);

$enrolledBatches = $data['enrolledBatches'];
$attendanceRate = $data['attendanceRate'];
$latestResult = $data['latestResult'];
$upcomingExamTitle = $data['upcomingExamTitle'];
$feeStatus = $data['feeStatus'];
$upcomingExamsList = $data['upcomingExamsList'];
$recentAttendance = $data['recentAttendance'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Coaching Management System</title>
    <link rel="stylesheet" href="student_dashboard.css">
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
                <li class="active"><a href="student_dashboard.php">🏠 <span>Dashboard</span></a></li>
                <li><a href="student_profile.php">👤 <span>My Profile</span></a></li>
                <li><a href="student_classes_exams.php">📅 <span>Classes & Exams</span></a></li>
                <li><a href="student_attendance.php">📋 <span>My Attendance</span></a></li>
                <li><a href="student_results.php">📊 <span>My Results</span></a></li>
                <li><a href="student_fees.php">💰 <span>My Fees</span></a></li>
                <li class="logout"><a href="../login_page/loginPage.php">🚪 <span>Logout</span></a></li>
            </ul>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="main_content">
            <!-- WELCOME BANNER -->
            <div class="student_banner">
                <div class="banner_info">
                    <h1>Hello, <?= htmlspecialchars($studentName, ENT_QUOTES, 'UTF-8'); ?> 👋</h1>
                    <p>Roll: <?= htmlspecialchars($studentRoll ?: 'N/A', ENT_QUOTES, 'UTF-8'); ?> | Student Portal Overview</p>
                </div>
                <!-- BATCH SELECTOR DROPDOWN -->
                <div style="display:flex; align-items:center; gap:8px;">
                    <label style="font-size:13px; font-weight:600; color:#fff;">Enrolled Batches:</label>
                    <select class="batch_tag" style="border:none; outline:none; background:rgba(255,255,255,0.25); color:#fff; cursor:pointer; padding:6px 12px; border-radius:20px; font-weight:600;">
                        <?php if (empty($enrolledBatches)): ?>
                            <option value="none" style="color:#000;">No Batches Assigned</option>
                        <?php else: ?>
                            <?php foreach ($enrolledBatches as $b): ?>
                                <option value="<?= (int)$b['id']; ?>" style="color:#000;"><?= htmlspecialchars($b['name'], ENT_QUOTES, 'UTF-8'); ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>

            <!-- STATISTICS CARDS -->
            <div class="stats_grid">
                <a href="student_attendance.php" class="stat_card">
                    <div class="card_icon">📋</div>
                    <div class="card_info">
                        <h3>Overall Attendance</h3>
                        <p class="card_value" style="color:#10b981;"><?= $attendanceRate; ?></p>
                    </div>
                </a>
                <a href="student_results.php" class="stat_card">
                    <div class="card_icon">📊</div>
                    <div class="card_info">
                        <h3>Latest Result</h3>
                        <p class="card_value" style="color:#6366f1;"><?= htmlspecialchars($latestResult, ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                </a>
                <a href="student_classes_exams.php" class="stat_card">
                    <div class="card_icon">📅</div>
                    <div class="card_info">
                        <h3>Next Exam</h3>
                        <p class="card_value" style="color:#f59e0b;"><?= htmlspecialchars($upcomingExamTitle, ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                </a>
                <a href="student_fees.php" class="stat_card">
                    <div class="card_icon">💰</div>
                    <div class="card_info">
                        <h3>Fee Status</h3>
                        <p class="card_value" style="color:<?= $feeStatus === 'Paid' ? '#10b981' : '#ef4444'; ?>;"><?= htmlspecialchars($feeStatus, ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                </a>
            </div>

            <!-- QUICK INFORMATION GRIDS -->
            <div class="info_grid">
                <!-- Upcoming Exams & Schedule -->
                <div class="info_card">
                    <div class="info_card_header">
                        <h3>UPCOMING EXAMS</h3>
                        <a href="student_classes_exams.php" class="view_link">View All ➜</a>
                    </div>
                    <ul class="info_list">
                        <?php if (empty($upcomingExamsList)): ?>
                            <li>
                                <div class="list_item">
                                    <span style="color:#6b7280;">No upcoming exams scheduled for your batches.</span>
                                </div>
                            </li>
                        <?php else: ?>
                            <?php foreach ($upcomingExamsList as $ex): ?>
                                <li>
                                    <div class="list_item" style="display:flex; justify-content:space-between; align-items:center; width:100%;">
                                        <div>
                                            <strong><?= htmlspecialchars($ex['name'], ENT_QUOTES, 'UTF-8'); ?></strong>
                                            <span style="display:block; font-size:12px; color:#6b7280;"><?= htmlspecialchars($ex['subject_name'] . ' • ' . $ex['batch_name'], ENT_QUOTES, 'UTF-8'); ?></span>
                                        </div>
                                        <span style="font-size:13px; font-weight:600; color:#4f46e5;"><?= htmlspecialchars(date('d M, Y', strtotime($ex['exam_date'])), ENT_QUOTES, 'UTF-8'); ?></span>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>

                <!-- Recent Attendance Activity -->
                <div class="info_card">
                    <div class="info_card_header">
                        <h3>RECENT ATTENDANCE</h3>
                        <a href="student_attendance.php" class="view_link">Full Report</a>
                    </div>
                    <ul class="info_list">
                        <?php if (empty($recentAttendance)): ?>
                            <li>
                                <div class="list_item">
                                    <span style="color:#6b7280;">No recent attendance records found.</span>
                                </div>
                            </li>
                        <?php else: ?>
                            <?php foreach ($recentAttendance as $att): ?>
                                <li>
                                    <div class="list_item" style="display:flex; justify-content:space-between; align-items:center; width:100%;">
                                        <span><?= htmlspecialchars(date('d F, Y', strtotime($att['date'])), ENT_QUOTES, 'UTF-8'); ?></span>
                                        <span class="status_active" style="background:<?= $att['status'] === 'present' ? '#dcfce7' : '#fee2e2'; ?>; color:<?= $att['status'] === 'present' ? '#15803d' : '#b91c1c'; ?>; font-size:12px; font-weight:600; padding:2px 8px; border-radius:10px;">
                                            <?= htmlspecialchars(ucfirst($att['status']), ENT_QUOTES, 'UTF-8'); ?>
                                        </span>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
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
