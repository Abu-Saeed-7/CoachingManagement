<?php
require_once __DIR__ . '/../../../backend/middleware/auth.php';
require_once __DIR__ . '/../../../backend/controllers/AttendanceController.php';

checkAuth(['teacher', 'admin']);

$teacher = getCurrentTeacherSession();
$teacherId = $teacher['id'];
$teacherName = $teacher['name'];

$attendanceController = new AttendanceController();
$postResult = $attendanceController->handleSaveTeacherAttendance((int) $teacherId);
$message = $postResult['message'];
$messageType = $postResult['messageType'];

$selectedDate = $_GET['date'] ?? date('Y-m-d');
$selectedBatchId = (int) ($_GET['batch_id'] ?? 0);

$attData = $attendanceController->getTeacherAttendanceData((int) $teacherId, $selectedBatchId, $selectedDate);
$batches = $attData['batches'];
$selectedBatchId = $attData['selectedBatchId'];
$studentsToAttend = $attData['studentsToAttend'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Take Attendance - Teacher Panel</title>
    <link rel="stylesheet" href="teacher_dashboard.css">
    <link rel="stylesheet" href="teacher_attendance.css">
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
                <li class="active"><a href="teacher_attendance.php">📋 <span>Attendance</span></a></li>
                <li><a href="teacher_results.php">📊 <span>Enter Results</span></a></li>
                <li><a href="teacher_profile.php">👤 <span>My Profile</span></a></li>
                <li class="logout"><a href="../login_page/loginPage.php">🚪 <span>Logout</span></a></li>
            </ul>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="main_content">
            <!-- PAGE HEADER -->
            <div class="page_header">
                <div>
                    <h1>Take Daily Attendance</h1>
                    <p>Select your batch and date to record present/absent status.</p>
                </div>
            </div>

            <?php if ($message !== ''): ?>
                <p style="padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; background: <?= $messageType === 'success' ? '#dcfce7' : '#fee2e2'; ?>; color: <?= $messageType === 'success' ? '#166534' : '#991b1b'; ?>;">
                    <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
                </p>
            <?php endif; ?>

            <!-- CONTROLS BAR (SELECT BATCH & DATE) -->
            <form method="get" class="attendance_controls">
                <div class="control_group">
                    <label>Select Batch</label>
                    <select name="batch_id" onchange="this.form.submit()">
                        <option value="">Select Batch</option>
                        <?php foreach ($batches as $b): ?>
                            <option value="<?= (int)$b['id']; ?>" <?= $selectedBatchId === (int)$b['id'] ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($b['name'], ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="control_group">
                    <label>Attendance Date</label>
                    <input type="date" name="date" value="<?= htmlspecialchars($selectedDate, ENT_QUOTES, 'UTF-8'); ?>" onchange="this.form.submit()">
                </div>
            </form>

            <!-- ATTENDANCE TABLE CARD -->
            <div class="attendance_card" style="margin-top: 24px;">
                <div class="attendance_header">
                    <h3>Student Attendance Sheet</h3>
                    <div class="quick_mark_all">
                        <button type="button" class="btn_mark_all" onclick="markAll('present')">✅ Mark All Present</button>
                        <button type="button" class="btn_mark_all" onclick="markAll('absent')">❌ Mark All Absent</button>
                    </div>
                </div>

                <form method="post">
                    <input type="hidden" name="save_attendance" value="1">
                    <input type="hidden" name="batch_id" value="<?= $selectedBatchId; ?>">
                    <input type="hidden" name="date" value="<?= htmlspecialchars($selectedDate, ENT_QUOTES, 'UTF-8'); ?>">

                    <div class="table_container">
                        <table class="custom_table">
                            <thead>
                                <tr>
                                    <th>Roll / ID</th>
                                    <th>Student Name</th>
                                    <th>Email</th>
                                    <th>Attendance Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($studentsToAttend)): ?>
                                    <tr>
                                        <td colspan="4" style="text-align: center; color: #6b7280; padding: 24px;">No active students found in this batch.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($studentsToAttend as $s): ?>
                                        <tr>
                                            <td>#<?= (int)$s['id']; ?> (Roll: <?= htmlspecialchars($s['roll'], ENT_QUOTES, 'UTF-8'); ?>)</td>
                                            <td><strong><?= htmlspecialchars($s['name'], ENT_QUOTES, 'UTF-8'); ?></strong></td>
                                            <td><?= htmlspecialchars($s['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td>
                                                <select name="status[<?= (int)$s['id']; ?>]" class="status_select" style="padding: 6px 12px; border: 1px solid #d1d5db; border-radius: 6px;">
                                                    <option value="present" <?= ($s['status'] ?? 'present') === 'present' ? 'selected' : ''; ?>>Present</option>
                                                    <option value="absent" <?= ($s['status'] ?? '') === 'absent' ? 'selected' : ''; ?>>Absent</option>
                                                    <option value="late" <?= ($s['status'] ?? '') === 'late' ? 'selected' : ''; ?>>Late</option>
                                                </select>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if (!empty($studentsToAttend)): ?>
                        <div class="save_bar" style="padding: 16px; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #e5e7eb;">
                            <span style="font-size:13px; color:#6b7280;">Total: <?= count($studentsToAttend); ?> Students</span>
                            <button type="submit" class="action_btn" style="background:#4f46e5; color:#fff; border:none; padding:10px 20px; border-radius:8px; font-weight:600; cursor:pointer;">💾 Save Attendance</button>
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

        function markAll(status) {
            const selects = document.querySelectorAll('.status_select');
            selects.forEach(select => {
                select.value = status;
            });
        }
    </script>
</body>

</html>
