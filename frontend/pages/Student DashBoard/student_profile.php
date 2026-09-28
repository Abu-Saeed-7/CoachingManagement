<?php
require_once __DIR__ . '/../../../backend/middleware/auth.php';
require_once __DIR__ . '/../../../backend/controllers/ProfileController.php';

checkAuth(['student', 'admin']);

$studentSession = getCurrentStudentSession();
$studentId = $studentSession['id'];
$studentName = $studentSession['name'];

$profileController = new ProfileController();
$profileUpdate = $profileController->handleStudentProfileUpdate((int) $studentId);
$message = $profileUpdate['message'];
$messageType = $profileUpdate['messageType'];

$student = $profileController->getStudentDetails((int) $studentId);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Student Panel</title>
    <link rel="stylesheet" href="student_dashboard.css">
    <link rel="stylesheet" href="student_profile.css">
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
                <li class="active"><a href="student_profile.php">👤 <span>My Profile</span></a></li>
                <li><a href="student_classes_exams.php">📅 <span>Classes & Exams</span></a></li>
                <li><a href="student_attendance.php">📋 <span>My Attendance</span></a></li>
                <li><a href="student_results.php">📊 <span>My Results</span></a></li>
                <li><a href="student_fees.php">💰 <span>My Fees</span></a></li>
                <li class="logout"><a href="../login_page/loginPage.php">🚪 <span>Logout</span></a></li>
            </ul>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="main_content">
            <div class="page_header">
                <h1>Student Profile</h1>
                <p>View and manage your academic registration details.</p>
            </div>

            <?php if ($message !== ''): ?>
                <p style="padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; background: <?= $messageType === 'success' ? '#dcfce7' : '#fee2e2'; ?>; color: <?= $messageType === 'success' ? '#166534' : '#991b1b'; ?>;">
                    <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
                </p>
            <?php endif; ?>

            <div class="profile_layout">
                <!-- LEFT PROFILE CARD -->
                <div class="profile_card">
                    <div class="profile_avatar">🎓</div>
                    <h2><?= htmlspecialchars($student['name'] ?? $studentName, ENT_QUOTES, 'UTF-8'); ?></h2>
                    <span class="role_badge">Student</span>

                    <ul class="profile_info_list">
                        <li>
                            <span class="info_label">Student Roll / ID</span>
                            <span class="info_value">#<?= (int)($student['id'] ?? 0); ?> (Roll: <?= htmlspecialchars($student['roll'] ?? '—', ENT_QUOTES, 'UTF-8'); ?>)</span>
                        </li>
                        <li>
                            <span class="info_label">Enrolled Batch</span>
                            <span class="info_value"><?= htmlspecialchars($student['batch_name'] ?? 'None', ENT_QUOTES, 'UTF-8'); ?></span>
                        </li>
                        <li>
                            <span class="info_label">Email Address</span>
                            <span class="info_value"><?= htmlspecialchars($student['email'] ?? '—', ENT_QUOTES, 'UTF-8'); ?></span>
                        </li>
                        <li>
                            <span class="info_label">Phone Number</span>
                            <span class="info_value"><?= htmlspecialchars($student['phone'] ?? '—', ENT_QUOTES, 'UTF-8'); ?></span>
                        </li>
                        <li>
                            <span class="info_label">Guardian Phone</span>
                            <span class="info_value"><?= htmlspecialchars($student['guardian_phone'] ?? '—', ENT_QUOTES, 'UTF-8'); ?></span>
                        </li>
                        <li>
                            <span class="info_label">Address</span>
                            <span class="info_value"><?= htmlspecialchars($student['address'] ?? '—', ENT_QUOTES, 'UTF-8'); ?></span>
                        </li>
                    </ul>
                </div>

                <!-- RIGHT EDIT DETAILS CARD -->
                <div class="profile_details_card">
                    <h3>Update Contact Details</h3>
                    <form class="profile_form" method="post">
                        <div class="form_row">
                            <div class="form_group">
                                <label>Full Name</label>
                                <input type="text" value="<?= htmlspecialchars($student['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" readonly style="background:#f9fafb;">
                            </div>
                            <div class="form_group">
                                <label>Roll Number</label>
                                <input type="text" value="<?= htmlspecialchars($student['roll'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" readonly style="background:#f9fafb;">
                            </div>
                        </div>
                        <div class="form_row">
                            <div class="form_group">
                                <label>Phone Number</label>
                                <input type="tel" name="phone" value="<?= htmlspecialchars($student['phone'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" pattern="^\+?[0-9]{10,15}$" title="10 to 15 digit phone number" required>
                            </div>
                            <div class="form_group">
                                <label>Guardian Phone</label>
                                <input type="tel" name="guardian_phone" value="<?= htmlspecialchars($student['guardian_phone'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" pattern="^\+?[0-9]{10,15}$" title="10 to 15 digit phone number" required>
                            </div>
                        </div>
                        <div class="form_group">
                            <label>Address</label>
                            <input type="text" name="address" value="<?= htmlspecialchars($student['address'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="Residential address" maxlength="255">
                        </div>
                        <div class="form_group">
                            <label>New Password (Optional)</label>
                            <input type="password" name="password" placeholder="Leave blank to keep same" minlength="8">
                        </div>
                        <div style="display:flex; justify-content:flex-end; margin-top:10px;">
                            <button type="submit" class="action_btn">Save Changes</button>
                        </div>
                    </form>
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
