<?php
require_once __DIR__ . '/../../../backend/middleware/auth.php';
require_once __DIR__ . '/../../../backend/controllers/ResultController.php';

checkAuth(['student', 'admin']);

$student = getCurrentStudentSession();
$studentId = $student['id'];
$studentName = $student['name'];

$resultController = new ResultController();
$results = $resultController->getStudentResults((int) $studentId);

$totalExamsTaken = count($results);
$avgScore = 0;
$highestGrade = '--';

if ($totalExamsTaken > 0) {
    $totalPercentage = 0;
    foreach ($results as $r) {
        $totalPercentage += ($r['marks'] / $r['total_marks']) * 100;
    }
    $avgScore = round($totalPercentage / $totalExamsTaken, 1);
    $highestGrade = $results[0]['grade'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Results - Student Panel</title>
    <link rel="stylesheet" href="student_dashboard.css">
    <link rel="stylesheet" href="student_results.css">
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
                <li><a href="student_attendance.php">📋 <span>My Attendance</span></a></li>
                <li class="active"><a href="student_results.php">📊 <span>My Results</span></a></li>
                <li><a href="student_fees.php">💰 <span>My Fees</span></a></li>
                <li class="logout"><a href="../login_page/loginPage.php">🚪 <span>Logout</span></a></li>
            </ul>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="main_content">
            <div class="page_header">
                <h1>My Academic Results & Grades</h1>
                <p>View your marks, grades, and exam performance records.</p>
            </div>

            <!-- SUMMARY STATS -->
            <div class="results_stat_row">
                <div class="res_stat_box">
                    <h4>Total Exams Taken</h4>
                    <p class="number"><?= $totalExamsTaken; ?> Exams</p>
                </div>
                <div class="res_stat_box">
                    <h4>Average Score</h4>
                    <p class="number" style="color:#4f46e5;"><?= $avgScore; ?>%</p>
                </div>
                <div class="res_stat_box">
                    <h4>Highest Grade</h4>
                    <p class="number" style="color:#10b981;"><?= htmlspecialchars($highestGrade, ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
            </div>

            <!-- RESULTS TABLE -->
            <div class="results_table_card">
                <div class="card_header">
                    <h3>Exam Performance Marksheet</h3>
                </div>

                <div class="table_container">
                    <table class="custom_table">
                        <thead>
                            <tr>
                                <th>Exam Title</th>
                                <th>Subject</th>
                                <th>Exam Date</th>
                                <th>Marks Obtained</th>
                                <th>Grade</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($results)): ?>
                                <tr>
                                    <td colspan="6" style="text-align: center; color: #6b7280; padding: 24px;">No exam results recorded yet.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($results as $res): ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars($res['exam_name'], ENT_QUOTES, 'UTF-8'); ?></strong></td>
                                        <td><?= htmlspecialchars($res['subject_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?= htmlspecialchars(date('d M, Y', strtotime($res['exam_date'])), ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?= htmlspecialchars(number_format((float)$res['marks'], 1), ENT_QUOTES, 'UTF-8'); ?> / <?= htmlspecialchars(number_format((float)$res['total_marks'], 0), ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><strong><?= htmlspecialchars($res['grade'], ENT_QUOTES, 'UTF-8'); ?></strong></td>
                                        <td>
                                            <span class="<?= ((float)$res['marks'] >= (float)$res['total_marks'] * 0.4) ? 'status_active' : 'status_inactive'; ?>">
                                                <?= ((float)$res['marks'] >= (float)$res['total_marks'] * 0.4) ? 'Passed' : 'Failed'; ?>
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
