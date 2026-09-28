<?php
require_once __DIR__ . '/../../../backend/middleware/auth.php';
require_once __DIR__ . '/../../../backend/controllers/ProfileController.php';

checkAuth(['admin']);

$adminId = (int) ($_SESSION['user_id'] ?? 1);
$profileController = new ProfileController();
$postResult = $profileController->handleAdminProfileUpdate($adminId);
$message = $postResult['message'];
$messageType = $postResult['messageType'];

$admin = $profileController->getAdminDetails($adminId);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile & Settings - Coaching Management System</title>
    <link rel="stylesheet" href="admin_dashboard.css">
    <link rel="stylesheet" href="admin_profile.css">
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
            <a href="admin_profile.php" class="admin_profile" style="text-decoration:none;">
                <span class="profile_icon">👤</span>
                <span class="admin_name"><?= htmlspecialchars($admin['name'] ?? 'Admin', ENT_QUOTES, 'UTF-8'); ?> ▾</span>
            </a>
        </div>
    </header>

    <!-- ২. মূল লেআউট -->
    <div class="dashboard_container">
        <!-- SIDEBAR -->
        <aside class="sidebar" id="sidebar">
            <ul class="sidebar_menu">
                <li><a href="admin_dashboard.php">🏠 <span>Dashboard</span></a></li>
                <li><a href="students.php">🎓 <span>Students</span></a></li>
                <li><a href="teachers.php">👨‍🏫 <span>Teachers</span></a></li>
                <li><a href="batches.php">📚 <span>Batches</span></a></li>
                <li><a href="exams.php">📝 <span>Exams</span></a></li>
                <li><a href="results.php">📊 <span>Results</span></a></li>
                <li><a href="fees.php">💰 <span>Fees</span></a></li>
                <li class="active"><a href="admin_profile.php">⚙️ <span>Profile</span></a></li>
                <li class="logout"><a href="../login_page/loginPage.php">🚪 <span>Logout</span></a></li>
            </ul>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="main_content">
            <div class="page_header">
                <h1>Admin Profile & System Settings</h1>
                <p>Manage your administrator credentials, system configuration, and institute preferences.</p>
            </div>

            <?php if ($message !== ''): ?>
                <p class="form_message <?= $messageType; ?>"><?= $message; ?></p>
            <?php endif; ?>

            <div class="profile_layout">
                <!-- LEFT PROFILE SUMMARY CARD -->
                <div class="profile_card">
                    <div class="profile_avatar">👤</div>
                    <h2><?= htmlspecialchars($admin['name'] ?? 'Administrator', ENT_QUOTES, 'UTF-8'); ?></h2>
                    <span class="role_badge"><?= htmlspecialchars(ucfirst($admin['role'] ?? 'Admin'), ENT_QUOTES, 'UTF-8'); ?></span>

                    <ul class="profile_info_list">
                        <li>
                            <span class="info_label">Admin ID</span>
                            <span class="info_value">#A<?= (int) ($admin['id'] ?? 1); ?></span>
                        </li>
                        <li>
                            <span class="info_label">Email Address</span>
                            <span class="info_value"><?= htmlspecialchars($admin['email'] ?? '—', ENT_QUOTES, 'UTF-8'); ?></span>
                        </li>
                        <li>
                            <span class="info_label">Account Status</span>
                            <span class="info_value" style="color: #10b981; font-weight: 600;"><?= htmlspecialchars(ucfirst($admin['status'] ?? 'Active'), ENT_QUOTES, 'UTF-8'); ?></span>
                        </li>
                        <li>
                            <span class="info_label">Institute</span>
                            <span class="info_value">EduManage Coaching Academy</span>
                        </li>
                        <li>
                            <span class="info_label">System Version</span>
                            <span class="info_value">v1.0.0</span>
                        </li>
                    </ul>
                </div>

                <!-- RIGHT SETTINGS CONTAINER -->
                <div class="settings_container">
                    <!-- PERSONAL INFO FORM -->
                    <div class="settings_card">
                        <h3>Administrator Information</h3>
                        <form class="settings_form" method="post">
                            <input type="hidden" name="action" value="update_profile">
                            <div class="form_row">
                                <div class="form_group">
                                    <label>Admin Display Name</label>
                                    <input type="text" name="name" value="<?= htmlspecialchars($admin['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" minlength="2" maxlength="100" required>
                                </div>
                                <div class="form_group">
                                    <label>Admin Email</label>
                                    <input type="email" name="email" value="<?= htmlspecialchars($admin['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" maxlength="150" required>
                                </div>
                            </div>
                            <div class="form_row">
                                <div class="form_group">
                                    <label>System Role</label>
                                    <input type="text" value="Super Administrator" readonly style="background:#f9fafb;">
                                </div>
                            </div>
                            <button type="submit" class="btn_save">Save Profile</button>
                        </form>
                    </div>

                    <!-- SECURITY / PASSWORD CHANGE -->
                    <div class="settings_card">
                        <h3>Security & Change Password</h3>
                        <form class="settings_form" method="post">
                            <input type="hidden" name="action" value="change_password">
                            <div class="form_group">
                                <label>Current Password</label>
                                <input type="password" name="current_password" placeholder="Enter current password" required>
                            </div>
                            <div class="form_row">
                                <div class="form_group">
                                    <label>New Password</label>
                                    <input type="password" name="new_password" placeholder="At least 8 characters" minlength="8" required>
                                </div>
                                <div class="form_group">
                                    <label>Confirm New Password</label>
                                    <input type="password" name="confirm_password" placeholder="Re-type new password" minlength="8" required>
                                </div>
                            </div>
                            <button type="submit" class="btn_save">Update Password</button>
                        </form>
                    </div>

                    <!-- INSTITUTE SETTINGS -->
                    <div class="settings_card">
                        <h3>Institute & Coaching Settings</h3>
                        <form class="settings_form" onsubmit="event.preventDefault(); alert('Institute settings saved!');">
                            <div class="form_row">
                                <div class="form_group">
                                    <label>Coaching / Academy Name</label>
                                    <input type="text" value="EduManage Coaching Academy" required>
                                </div>
                                <div class="form_group">
                                    <label>Currency Symbol</label>
                                    <input type="text" value="BDT (৳)" required>
                                </div>
                            </div>
                            <div class="form_group">
                                <label>Campus Address</label>
                                <input type="text" value="House #12, Road #4, Dhanmondi, Dhaka, Bangladesh" required>
                            </div>
                            <button type="submit" class="btn_save">Save Settings</button>
                        </form>
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
