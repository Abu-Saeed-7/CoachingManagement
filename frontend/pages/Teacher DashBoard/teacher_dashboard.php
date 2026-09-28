<?php
require_once __DIR__ . '/../../../backend/middleware/auth.php';
require_once __DIR__ . '/../../../backend/controllers/DashboardController.php';

checkAuth(['teacher', 'admin']);

$teacher = getCurrentTeacherSession();
$teacherId = $teacher['id'];
$teacherName = $teacher['name'];

$dashboardController = new DashboardController();
$data = $dashboardController->getTeacherDashboardData((int) $teacherId);

$assignedBatchesCount = $data['activeBatchesCount'];
$totalStudentsCount = $data['totalStudents'];
$todayBatches = $data['assignedBatches'];
$classesToday = count($todayBatches);
$pendingExams = $data['upcomingExams'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard - Coaching Management System</title>
    <link rel="stylesheet" href="teacher_dashboard.css">
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
            <span class="nav_icon" title="Search">🔍</span>
            <a href="teacher_profile.php" class="teacher_profile_btn">
                <span>👨‍🏫</span>
                <span class="teacher_name"><?= htmlspecialchars($teacherName, ENT_QUOTES, 'UTF-8'); ?> ▾</span>
            </a>
        </div>
    </header>

    <!-- ২. মূল লেআউট -->
    <div class="dashboard_container">
        <!-- SIDEBAR -->
        <aside class="sidebar" id="sidebar">
            <ul class="sidebar_menu">
                <li class="active"><a href="teacher_dashboard.php">🏠 <span>Dashboard</span></a></li>
                <li><a href="teacher_batches.php">📚 <span>My Batches</span></a></li>
                <li><a href="teacher_students.php">🎓 <span>My Students</span></a></li>
                <li><a href="teacher_attendance.php">📋 <span>Attendance</span></a></li>
                <li><a href="teacher_results.php">📊 <span>Enter Results</span></a></li>
                <li><a href="teacher_profile.php">👤 <span>My Profile</span></a></li>
                <li class="logout"><a href="../login_page/loginPage.php">🚪 <span>Logout</span></a></li>
            </ul>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="main_content">
            <!-- WELCOME SECTION -->
            <div class="welcome_section">
                <h1>Welcome Back, <?= htmlspecialchars($teacherName, ENT_QUOTES, 'UTF-8'); ?> 👋</h1>
                <p>Here is your teaching schedule and student overview today.</p>
            </div>

            <!-- STATISTICS CARDS -->
            <div class="stats_grid">
                <div class="stat_card" onclick="window.location.href='teacher_batches.php'" style="cursor: pointer;">
                    <div class="card_icon">📚</div>
                    <div class="card_info">
                        <h3>Assigned Batches</h3>
                        <p class="card_value"><?= $assignedBatchesCount; ?> Batches</p>
                    </div>
                </div>
                <div class="stat_card" onclick="window.location.href='teacher_students.php'" style="cursor: pointer;">
                    <div class="card_icon">👥</div>
                    <div class="card_info">
                        <h3>Total Students</h3>
                        <p class="card_value"><?= $totalStudentsCount; ?> Students</p>
                    </div>
                </div>
                <div class="stat_card">
                    <div class="card_icon">📅</div>
                    <div class="card_info">
                        <h3>Active Batches</h3>
                        <p class="card_value"><?= $classesToday; ?> Batches</p>
                    </div>
                </div>
            </div>

            <!-- QUICK ACTIONS -->
            <div class="quick_actions_section">
                <h2>QUICK ACTIONS</h2>
                <div class="action_buttons">
                    <a href="teacher_attendance.php" class="action_btn"><span>📋</span> Take Attendance</a>
                    <a href="teacher_results.php" class="action_btn"><span>📊</span> Input Exam Marks</a>
                    <a href="teacher_batches.php" class="action_btn"><span>👥</span> View My Batches</a>
                </div>
            </div>

            <!-- TODAY'S SCHEDULE & RECENT ACTIVITIES -->
            <div class="info_grid">
                <!-- Schedule Card -->
                <div class="info_card">
                    <div class="info_card_header">
                        <h3>MY ACTIVE BATCHES</h3>
                        <a href="teacher_batches.php" class="view_all_link" style="color: #4f46e5; text-decoration: none; font-size: 13px; font-weight: 600;">View All</a>
                    </div>
                    <ul class="info_list">
                        <?php if (empty($todayBatches)): ?>
                            <li>
                                <div class="schedule_item">
                                    <span style="color:#6b7280;">No active batches assigned.</span>
                                </div>
                            </li>
                        <?php else: ?>
                            <?php foreach ($todayBatches as $b): ?>
                                <li>
                                    <div class="schedule_item" style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                                        <strong><?= htmlspecialchars($b['name'], ENT_QUOTES, 'UTF-8'); ?></strong>
                                        <span style="color: #6b7280; font-size: 13px;">Started: <?= htmlspecialchars($b['start_date'], ENT_QUOTES, 'UTF-8'); ?></span>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>

                <!-- Upcoming Exams to Grade -->
                <div class="info_card">
                    <div class="info_card_header">
                        <h3>BATCH EXAMS & ASSESSMENTS</h3>
                        <a href="teacher_results.php" class="view_all_link" style="color: #4f46e5; text-decoration: none; font-size: 13px; font-weight: 600;">Enter Marks</a>
                    </div>
                    <ul class="info_list">
                        <?php if (empty($pendingExams)): ?>
                            <li>
                                <div class="schedule_item">
                                    <span style="color:#6b7280;">No exams scheduled for your batches.</span>
                                </div>
                            </li>
                        <?php else: ?>
                            <?php foreach ($pendingExams as $ex): ?>
                                <li>
                                    <div class="schedule_item" style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                                        <div>
                                            <strong><?= htmlspecialchars($ex['name'], ENT_QUOTES, 'UTF-8'); ?></strong>
                                            <span style="display: block; font-size: 12px; color: #6b7280;"><?= htmlspecialchars($ex['subject_name'] . ' • ' . $ex['batch_name'], ENT_QUOTES, 'UTF-8'); ?></span>
                                        </div>
                                        <span style="color: #6b7280; font-size: 13px;"><?= htmlspecialchars(date('d M', strtotime($ex['exam_date'])), ENT_QUOTES, 'UTF-8'); ?></span>
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
