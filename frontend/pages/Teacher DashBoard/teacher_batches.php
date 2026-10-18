<?php
require_once __DIR__ . '/../../../backend/middleware/auth.php';
require_once __DIR__ . '/../../../backend/controllers/BatchController.php';
require_once __DIR__ . '/../../../backend/controllers/StudentController.php';

checkAuth(['teacher', 'admin']);

$teacher = getCurrentTeacherSession();
$teacherId = $teacher['id'];
$teacherName = $teacher['name'];

$batchController = new BatchController();
$studentController = new StudentController();

$assignedBatches = $batchController->getTeacherBatches((int) $teacherId);
$assignedStudents = $studentController->getTeacherStudents((int) $teacherId);
$teacherSchedules = $batchController->getTeacherClassSchedules((int) $teacherId);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Batches - Teacher Panel</title>
    <link rel="stylesheet" href="teacher_dashboard.css">
    <link rel="stylesheet" href="teacher_batches.css">
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
                <li class="active"><a href="teacher_batches.php">📚 <span>My Batches</span></a></li>
                <li><a href="teacher_students.php">🎓 <span>My Students</span></a></li>
                <li><a href="teacher_attendance.php">📋 <span>Attendance</span></a></li>
                <li><a href="teacher_results.php">📊 <span>Enter Results</span></a></li>
                <li><a href="teacher_profile.php">👤 <span>My Profile</span></a></li>
                <li class="logout"><a href="../logout.php">🚪 <span>Logout</span></a></li>
            </ul>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="main_content">
            <!-- PAGE HEADER -->
            <div class="page_header">
                <div>
                    <h1>My Assigned Batches & Class Schedule</h1>
                    <p>Overview of batches, timings, routines and students assigned under your instruction.</p>
                </div>
            </div>

            <!-- BATCH CARDS GRID -->
            <div class="batch_cards_grid">
                <?php if (empty($assignedBatches)): ?>
                    <p style="grid-column: 1 / -1; text-align: center; color: #6b7280; padding: 24px;">No batches assigned to you yet.</p>
                <?php else: ?>
                    <?php foreach ($assignedBatches as $batch): ?>
                        <div class="batch_card" style="background:#fff; border-radius:10px; border:1px solid #e5e7eb; padding:20px; box-shadow:0 2px 6px rgba(0,0,0,0.04);">
                            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                                <h3 style="font-size:18px; color:#111827; margin:0;"><?= htmlspecialchars($batch['name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                                <span class="status_active" style="background:#dcfce7; color:#15803d; font-size:11px; padding:3px 8px; border-radius:12px; font-weight:600;">
                                    <?= htmlspecialchars(ucfirst($batch['status']), ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            </div>
                            <p style="color:#4f46e5; font-size:13px; font-weight:600; margin:0 0 4px 0;">⏰ Timing: <?= htmlspecialchars($batch['schedule_time'], ENT_QUOTES, 'UTF-8'); ?></p>
                            <p style="color:#6b7280; font-size:12px; margin:0 0 16px 0;">📍 Venue: <?= htmlspecialchars($batch['room_number'], ENT_QUOTES, 'UTF-8'); ?> &bull; Start Date: <?= htmlspecialchars($batch['start_date'], ENT_QUOTES, 'UTF-8'); ?></p>
                            <div style="display:flex; justify-content:space-between; align-items:center; border-top:1px solid #f3f4f6; padding-top:12px;">
                                <span style="font-size:13px; font-weight:600; color:#4f46e5;">👥 <?= (int) $batch['student_count']; ?> Students</span>
                                <a href="teacher_students.php?batch_id=<?= (int)$batch['id']; ?>" style="color:#4f46e5; text-decoration:none; font-size:13px; font-weight:600;">View Students &rarr;</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- WEEKLY TEACHING ROUTINE TABLE -->
            <?php if (!empty($teacherSchedules)): ?>
                <div style="margin-top: 32px; background:#fff; border-radius:10px; border:1px solid #e5e7eb; padding:20px; box-shadow:0 2px 6px rgba(0,0,0,0.04);">
                    <h3 style="font-size:18px; margin:0 0 16px 0; color:#111827;">📅 My Weekly Class Routine</h3>
                    <div class="table_container" style="overflow-x:auto;">
                        <table class="custom_table" style="width:100%; border-collapse:collapse;">
                            <thead>
                                <tr style="border-bottom:1px solid #e5e7eb; text-align:left;">
                                    <th style="padding:10px 14px; font-size:13px; color:#4b5563;">Day</th>
                                    <th style="padding:10px 14px; font-size:13px; color:#4b5563;">Batch</th>
                                    <th style="padding:10px 14px; font-size:13px; color:#4b5563;">Subject</th>
                                    <th style="padding:10px 14px; font-size:13px; color:#4b5563;">Class Time</th>
                                    <th style="padding:10px 14px; font-size:13px; color:#4b5563;">Venue</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($teacherSchedules as $ts): ?>
                                    <tr style="border-bottom:1px solid #f3f4f6;">
                                        <td style="padding:10px 14px; font-weight:600; color:#4f46e5;"><?= htmlspecialchars($ts['day_of_week'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td style="padding:10px 14px;"><span class="badge" style="background:#eef2ff; color:#4f46e5; padding:3px 8px; border-radius:12px; font-size:12px; font-weight:600;"><?= htmlspecialchars($ts['batch_name'], ENT_QUOTES, 'UTF-8'); ?></span></td>
                                        <td style="padding:10px 14px; font-weight:600;"><?= htmlspecialchars($ts['subject_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td style="padding:10px 14px;">⏰ <?= date('h:i A', strtotime($ts['start_time'])); ?> – <?= date('h:i A', strtotime($ts['end_time'])); ?></td>
                                        <td style="padding:10px 14px; color:#6b7280;">📍 <?= htmlspecialchars($ts['room_number'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>

            <!-- BATCH STUDENTS TABLE -->
            <div class="students_section" id="batchStudents" style="margin-top: 32px;">
                <h3 class="section_title" style="font-size:18px; margin-bottom:16px;">Assigned Students Overview</h3>
                <div class="table_container">
                    <table class="custom_table">
                        <thead>
                            <tr>
                                <th>Roll / ID</th>
                                <th>Student Name</th>
                                <th>Batch</th>
                                <th>Email</th>
                                <th>Phone</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($assignedStudents)): ?>
                                <tr>
                                    <td colspan="5" style="text-align: center; color: #6b7280; padding: 24px;">No students assigned to your batches yet.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($assignedStudents as $s): ?>
                                    <tr>
                                        <td>#<?= (int) $s['id']; ?> (Roll: <?= htmlspecialchars($s['roll'], ENT_QUOTES, 'UTF-8'); ?>)</td>
                                        <td><strong><?= htmlspecialchars($s['name'], ENT_QUOTES, 'UTF-8'); ?></strong></td>
                                        <td><span class="badge" style="background:#eef2ff; color:#4f46e5; padding:3px 8px; border-radius:12px; font-size:12px;"><?= htmlspecialchars($s['batch_name'], ENT_QUOTES, 'UTF-8'); ?></span></td>
                                        <td><?= htmlspecialchars($s['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?= htmlspecialchars($s['phone'], ENT_QUOTES, 'UTF-8'); ?></td>
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
