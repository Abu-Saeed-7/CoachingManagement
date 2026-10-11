<?php
require_once __DIR__ . '/../../../backend/middleware/auth.php';
require_once __DIR__ . '/../../../backend/controllers/ExamController.php';

checkAuth(['student', 'admin']);

$student = getCurrentStudentSession();
$studentId = $student['id'];
$studentName = $student['name'];

$examController = new ExamController();
$data = $examController->getStudentExamsAndClasses((int) $studentId);

$enrolledBatches = $data['enrolledBatches'];
$classSchedules = $data['classSchedules'] ?? [];
$upcomingExams = $data['upcomingExams'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classes & Exams Schedule - Student Panel</title>
    <link rel="stylesheet" href="student_dashboard.css">
    <link rel="stylesheet" href="student_classes_exams.css">
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
                <li class="active"><a href="student_classes_exams.php">📅 <span>Classes & Exams</span></a></li>
                <li><a href="student_attendance.php">📋 <span>My Attendance</span></a></li>
                <li><a href="student_results.php">📊 <span>My Results</span></a></li>
                <li><a href="student_fees.php">💰 <span>My Fees</span></a></li>
                <li class="logout"><a href="../login_page/loginPage.php">🚪 <span>Logout</span></a></li>
            </ul>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="main_content">
            <div class="page_header">
                <h1>Classes & Exams Schedule</h1>
                <p>View your enrolled batch routines, weekly class timetables and upcoming exams.</p>
            </div>

            <div class="schedule_sections">
                <!-- SECTION 1: WEEKLY CLASS ROUTINE -->
                <div class="section_card" style="background:#fff; border-radius:12px; border:1px solid #e5e7eb; padding:20px; box-shadow:0 2px 6px rgba(0,0,0,0.04);">
                    <div class="section_header" style="margin-bottom:16px;">
                        <h3>🗓️ Weekly Class Routine & Timetable</h3>
                    </div>

                    <?php if (empty($classSchedules)): ?>
                        <div style="padding: 24px; text-align: center; background: #f9fafb; border-radius: 8px; border: 1px dashed #d1d5db;">
                            <p style="color:#6b7280; margin:0 0 8px 0;">No specific class routine slots scheduled yet for your batches.</p>
                            <span style="font-size:12px; color:#9ca3af;">Check your enrolled batches below for general class timing.</span>
                        </div>
                    <?php else: ?>
                        <div class="timetable_grid">
                            <?php foreach ($classSchedules as $cs): ?>
                                <div class="class_box">
                                    <div style="display:flex; justify-content:space-between; align-items:center;">
                                        <span class="class_day"><?= htmlspecialchars($cs['day_of_week'], ENT_QUOTES, 'UTF-8'); ?></span>
                                        <span class="badge badge_batch"><?= htmlspecialchars($cs['batch_name'], ENT_QUOTES, 'UTF-8'); ?></span>
                                    </div>
                                    <div class="class_subject"><?= htmlspecialchars($cs['subject_name'], ENT_QUOTES, 'UTF-8'); ?></div>
                                    <div class="class_meta">
                                        <span class="time_tag">⏰ <?= date('h:i A', strtotime($cs['start_time'])); ?> – <?= date('h:i A', strtotime($cs['end_time'])); ?></span>
                                        <span>👨‍🏫 Faculty: <strong><?= htmlspecialchars($cs['teacher_name'], ENT_QUOTES, 'UTF-8'); ?></strong></span>
                                        <span>📍 Venue: <strong><?= htmlspecialchars($cs['room_number'], ENT_QUOTES, 'UTF-8'); ?></strong></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- SECTION 2: ENROLLED BATCHES -->
                <div class="section_card" style="background:#fff; border-radius:12px; border:1px solid #e5e7eb; padding:20px; box-shadow:0 2px 6px rgba(0,0,0,0.04);">
                    <div class="section_header" style="margin-bottom:16px;">
                        <h3>📖 My Enrolled Batches</h3>
                    </div>

                    <?php if (empty($enrolledBatches)): ?>
                        <p style="color:#6b7280;">You are not enrolled in any active batches yet.</p>
                    <?php else: ?>
                        <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap:16px;">
                            <?php foreach ($enrolledBatches as $b): ?>
                                <div style="border:1px solid #e5e7eb; border-radius:8px; padding:16px; background:#f9fafb; display:flex; flex-direction:column; gap:6px;">
                                    <div style="display:flex; justify-content:space-between; align-items:center;">
                                        <h4 style="margin:0; color:#111827; font-size:16px;"><?= htmlspecialchars($b['name'], ENT_QUOTES, 'UTF-8'); ?></h4>
                                        <span class="badge badge_batch">Enrolled</span>
                                    </div>
                                    <p style="margin:4px 0 0 0; font-size:13px; color:#4b5563;">Instructors: <strong><?= htmlspecialchars($b['teachers'], ENT_QUOTES, 'UTF-8'); ?></strong></p>
                                    <p style="margin:0; font-size:13px; color:#4f46e5; font-weight:600;">⏰ Timing: <?= htmlspecialchars($b['schedule_time'], ENT_QUOTES, 'UTF-8'); ?></p>
                                    <p style="margin:0; font-size:12px; color:#6b7280;">📍 Venue: <?= htmlspecialchars($b['room_number'], ENT_QUOTES, 'UTF-8'); ?> &bull; Started: <?= htmlspecialchars($b['start_date'], ENT_QUOTES, 'UTF-8'); ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- SECTION 3: UPCOMING EXAMS SCHEDULE -->
                <div class="section_card" style="background:#fff; border-radius:12px; border:1px solid #e5e7eb; padding:20px; box-shadow:0 2px 6px rgba(0,0,0,0.04);">
                    <div class="section_header" style="margin-bottom:16px;">
                        <h3>📝 Upcoming Scheduled Exams</h3>
                    </div>

                    <div class="table_container">
                        <table class="custom_table">
                            <thead>
                                <tr>
                                    <th>Exam Title</th>
                                    <th>Subject</th>
                                    <th>Batch</th>
                                    <th>Date</th>
                                    <th>Total Marks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($upcomingExams)): ?>
                                    <tr>
                                        <td colspan="5" style="text-align: center; color: #6b7280; padding: 24px;">No upcoming exams scheduled for your batches.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($upcomingExams as $ex): ?>
                                        <tr>
                                            <td><strong><?= htmlspecialchars($ex['name'], ENT_QUOTES, 'UTF-8'); ?></strong></td>
                                            <td><?= htmlspecialchars($ex['subject_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td><span class="badge" style="background:#eef2ff; color:#4f46e5; padding:3px 8px; border-radius:12px; font-size:12px;"><?= htmlspecialchars($ex['batch_name'], ENT_QUOTES, 'UTF-8'); ?></span></td>
                                            <td><?= htmlspecialchars(date('d M, Y', strtotime($ex['exam_date'])), ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td><?= htmlspecialchars(number_format((float)$ex['total_marks'], 0), ENT_QUOTES, 'UTF-8'); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
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
