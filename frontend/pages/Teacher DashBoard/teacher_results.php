<?php
require_once __DIR__ . '/../../../backend/middleware/auth.php';
require_once __DIR__ . '/../../../backend/controllers/ResultController.php';

checkAuth(['teacher', 'admin']);

$teacher = getCurrentTeacherSession();
$teacherId = $teacher['id'];
$teacherName = $teacher['name'];

$resultController = new ResultController();
$postResult = $resultController->handleSaveTeacherMarks((int) $teacherId);
$message = $postResult['message'];
$messageType = $postResult['messageType'];

$selectedExamId = (int) ($_GET['exam_id'] ?? 0);
$gradingView = $resultController->getTeacherGradingView((int) $teacherId, $selectedExamId);

$batches = $gradingView['batches'];
$exams = $gradingView['exams'];
$studentsToGrade = $gradingView['studentsToGrade'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Results - Teacher Panel</title>
    <link rel="stylesheet" href="teacher_dashboard.css">
    <link rel="stylesheet" href="teacher_results.css">
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
                <li><a href="teacher_dashboard.php">🏠 <span>Dashboard</span></a></li>
                <li><a href="teacher_batches.php">📚 <span>My Batches</span></a></li>
                <li><a href="teacher_students.php">🎓 <span>My Students</span></a></li>
                <li><a href="teacher_attendance.php">📋 <span>Attendance</span></a></li>
                <li class="active"><a href="teacher_results.php">📊 <span>Enter Results</span></a></li>
                <li><a href="teacher_profile.php">👤 <span>My Profile</span></a></li>
                <li class="logout"><a href="../login_page/loginPage.php">🚪 <span>Logout</span></a></li>
            </ul>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="main_content">
            <!-- PAGE HEADER -->
            <div class="page_header">
                <div>
                    <h1>Enter Exam Results</h1>
                    <p>Select an exam to input or update students marks directly into the database.</p>
                </div>
            </div>

            <?php if ($message !== ''): ?>
                <p style="padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; background: <?= $messageType === 'success' ? '#dcfce7' : '#fee2e2'; ?>; color: <?= $messageType === 'success' ? '#166534' : '#991b1b'; ?>;">
                    <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
                </p>
            <?php endif; ?>

            <!-- CONTROLS BAR -->
            <form method="get" class="results_controls">
                <div class="control_group">
                    <label>Select Exam to Grade</label>
                    <select name="exam_id" onchange="this.form.submit()">
                        <option value="">-- Choose Exam --</option>
                        <?php foreach ($exams as $ex): ?>
                            <option value="<?= (int)$ex['id']; ?>" <?= $selectedExamId === (int)$ex['id'] ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($ex['name'] . ' (' . $ex['batch_name'] . ' - ' . (int)$ex['total_marks'] . ' pts)', ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>

            <!-- MARKSHEET TABLE CARD -->
            <div class="marksheet_card" style="margin-top: 24px;">
                <div class="marksheet_header">
                    <h3>Exam Marksheet</h3>
                </div>

                <form method="post">
                    <input type="hidden" name="save_marks" value="1">
                    <input type="hidden" name="exam_id" value="<?= $selectedExamId; ?>">

                    <div class="table_container">
                        <table class="custom_table">
                            <thead>
                                <tr>
                                    <th>Roll / ID</th>
                                    <th>Student Name</th>
                                    <th>Max Marks</th>
                                    <th>Marks Obtained</th>
                                    <th>Current Grade</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($selectedExamId === 0): ?>
                                    <tr>
                                        <td colspan="5" style="text-align: center; color: #6b7280; padding: 24px;">Please select an exam from the dropdown above to load the student list.</td>
                                    </tr>
                                <?php elseif (empty($studentsToGrade)): ?>
                                    <tr>
                                        <td colspan="5" style="text-align: center; color: #6b7280; padding: 24px;">No students enrolled in this exam's batch.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($studentsToGrade as $s): ?>
                                        <tr>
                                            <td>#<?= (int)$s['id']; ?> (Roll: <?= htmlspecialchars($s['roll'], ENT_QUOTES, 'UTF-8'); ?>)</td>
                                            <td><strong><?= htmlspecialchars($s['name'], ENT_QUOTES, 'UTF-8'); ?></strong></td>
                                            <td><?= (float)$s['total_marks']; ?></td>
                                            <td>
                                                <input type="number" name="marks[<?= (int)$s['id']; ?>]" value="<?= $s['marks'] !== null ? (float)$s['marks'] : ''; ?>" min="0" max="<?= (float)$s['total_marks']; ?>" step="0.5" style="width: 90px; padding: 6px; border: 1px solid #d1d5db; border-radius: 6px;" placeholder="Marks">
                                            </td>
                                            <td><strong><?= htmlspecialchars($s['grade'] ?? '—', ENT_QUOTES, 'UTF-8'); ?></strong></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if (!empty($studentsToGrade)): ?>
                        <div class="save_bar" style="padding: 16px; border-top: 1px solid #e5e7eb; text-align: right;">
                            <button type="submit" class="action_btn" style="background:#4f46e5; color:#fff; border:none; padding:10px 20px; border-radius:8px; font-weight:600; cursor:pointer;">💾 Submit & Save Results</button>
                        </div>
                    <?php endif; ?>
                </form>
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
