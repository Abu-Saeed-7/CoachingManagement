<?php
require_once __DIR__ . '/../../../backend/middleware/auth.php';
require_once __DIR__ . '/../../../backend/controllers/ProfileController.php';

checkAuth(['teacher', 'admin']);

$teacher = getCurrentTeacherSession();
$teacherId = $teacher['id'];
$teacherName = $teacher['name'];

$profileController = new ProfileController();
$postResult = $profileController->handleTeacherProfileUpdate((int) $teacherId);
$message = $postResult['message'];
$messageType = $postResult['messageType'];

$teacher = $profileController->getTeacherDetails((int) $teacherId);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Profile - Teacher Panel</title>
    <link rel="stylesheet" href="teacher_dashboard.css">
    <link rel="stylesheet" href="teacher_profile.css">
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
                <li><a href="teacher_results.php">📊 <span>Enter Results</span></a></li>
                <li class="active"><a href="teacher_profile.php">👤 <span>My Profile</span></a></li>
                <li class="logout"><a href="../login_page/loginPage.php">🚪 <span>Logout</span></a></li>
            </ul>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="main_content">
            <!-- PAGE HEADER -->
            <div class="page_header">
                <h1>Teacher Profile</h1>
                <p>View your instructor details, contact information, and assigned courses.</p>
            </div>

            <?php if ($message !== ''): ?>
                <p style="padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; background: <?= $messageType === 'success' ? '#dcfce7' : '#fee2e2'; ?>; color: <?= $messageType === 'success' ? '#166534' : '#991b1b'; ?>;">
                    <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
                </p>
            <?php endif; ?>

            <div class="profile_layout">
                <!-- LEFT PROFILE CARD -->
                <div class="profile_card">
                    <div class="profile_avatar">👨‍🏫</div>
                    <h2><?= htmlspecialchars($teacher['name'] ?? $teacherName, ENT_QUOTES, 'UTF-8'); ?></h2>
                    <span class="role_badge"><?= htmlspecialchars($teacher['subject_specialist'] ?? 'Instructor', ENT_QUOTES, 'UTF-8'); ?></span>

                    <ul class="profile_info_list">
                        <li>
                            <span class="info_label">Teacher ID</span>
                            <span class="info_value">#T<?= (int) ($teacher['id'] ?? 0); ?></span>
                        </li>
                        <li>
                            <span class="info_label">Email Address</span>
                            <span class="info_value"><?= htmlspecialchars($teacher['email'] ?? '—', ENT_QUOTES, 'UTF-8'); ?></span>
                        </li>
                        <li>
                            <span class="info_label">Phone Number</span>
                            <span class="info_value"><?= htmlspecialchars($teacher['phone'] ?? '—', ENT_QUOTES, 'UTF-8'); ?></span>
                        </li>
                        <li>
                            <span class="info_label">Assigned Batches</span>
                            <span class="info_value"><?= htmlspecialchars($teacher['assigned_batches'] ?? 'None', ENT_QUOTES, 'UTF-8'); ?></span>
                        </li>
                        <li>
                            <span class="info_label">Joining Date</span>
                            <span class="info_value"><?= htmlspecialchars($teacher['joining_date'] ?? '—', ENT_QUOTES, 'UTF-8'); ?></span>
                        </li>
                    </ul>
                </div>

                <!-- RIGHT EDIT DETAILS CARD -->
                <div class="profile_details_card">
                    <h3>Update Profile Information</h3>
                    <form class="profile_form" method="post">
                        <div class="form_row">
                            <div class="form_group">
                                <label>Full Name</label>
                                <input type="text" name="name" value="<?= htmlspecialchars($teacher['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" minlength="2" maxlength="100" required>
                            </div>
                            <div class="form_group">
                                <label>Subject Specialist</label>
                                <input type="text" value="<?= htmlspecialchars($teacher['subject_specialist'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" readonly style="background:#f9fafb;">
                            </div>
                        </div>
                        <div class="form_row">
                            <div class="form_group">
                                <label>Email Address</label>
                                <input type="email" value="<?= htmlspecialchars($teacher['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" readonly style="background:#f9fafb;">
                            </div>
                            <div class="form_group">
                                <label>Phone Number</label>
                                <input type="tel" name="phone" value="<?= htmlspecialchars($teacher['phone'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" pattern="^\+?[0-9]{10,15}$" title="10 to 15 digit phone number" required>
                            </div>
                        </div>
                        <div class="form_row">
                            <div class="form_group">
                                <label>New Password (Optional)</label>
                                <input type="password" name="password" placeholder="Leave blank to keep same" minlength="8">
                            </div>
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
