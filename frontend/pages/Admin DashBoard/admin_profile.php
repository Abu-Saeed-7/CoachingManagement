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
                <span class="admin_name">Admin ▾</span>
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

            <div class="profile_layout">
                <!-- LEFT PROFILE SUMMARY CARD -->
                <div class="profile_card">
                    <div class="profile_avatar">👤</div>
                    <h2>System Administrator</h2>
                    <span class="role_badge">Super Admin / Owner</span>

                    <ul class="profile_info_list">
                        <li>
                            <span class="info_label">Username</span>
                            <span class="info_value">@admin</span>
                        </li>
                        <li>
                            <span class="info_label">Email Address</span>
                            <span class="info_value">admin@edumanage.com</span>
                        </li>
                        <li>
                            <span class="info_label">Phone</span>
                            <span class="info_value">+880 1700-000000</span>
                        </li>
                        <li>
                            <span class="info_label">Institute</span>
                            <span class="info_value">EduManage Coaching Academy</span>
                        </li>
                        <li>
                            <span class="info_label">System Version</span>
                            <span class="info_value">v1.0.0 (Lightweight)</span>
                        </li>
                        <li>
                            <span class="info_label">Last Login</span>
                            <span class="info_value">01 Sep 2026, 09:30 AM</span>
                        </li>
                    </ul>
                </div>

                <!-- RIGHT SETTINGS CONTAINER -->
                <div class="settings_container">
                    <!-- PERSONAL INFO FORM -->
                    <div class="settings_card">
                        <h3>Administrator Information</h3>
                        <form class="settings_form">
                            <div class="form_row">
                                <div class="form_group">
                                    <label>Admin Display Name</label>
                                    <input type="text" value="System Administrator">
                                </div>
                                <div class="form_group">
                                    <label>Admin Email</label>
                                    <input type="email" value="admin@edumanage.com">
                                </div>
                            </div>
                            <div class="form_row">
                                <div class="form_group">
                                    <label>Contact Phone</label>
                                    <input type="tel" value="+8801700000000">
                                </div>
                                <div class="form_group">
                                    <label>System Role</label>
                                    <input type="text" value="Super Administrator" readonly style="background:#f9fafb;">
                                </div>
                            </div>
                            <button type="button" class="btn_save" onclick="alert('Admin info updated!')">Save Profile</button>
                        </form>
                    </div>

                    <!-- SECURITY / PASSWORD CHANGE -->
                    <div class="settings_card">
                        <h3>Security & Change Password</h3>
                        <form class="settings_form">
                            <div class="form_group">
                                <label>Current Password</label>
                                <input type="password" placeholder="Enter current password">
                            </div>
                            <div class="form_row">
                                <div class="form_group">
                                    <label>New Password</label>
                                    <input type="password" placeholder="Enter new password">
                                </div>
                                <div class="form_group">
                                    <label>Confirm New Password</label>
                                    <input type="password" placeholder="Re-type new password">
                                </div>
                            </div>
                            <button type="button" class="btn_save" onclick="alert('Password updated successfully!')">Update Password</button>
                        </form>
                    </div>

                    <!-- INSTITUTE SETTINGS -->
                    <div class="settings_card">
                        <h3>Institute & Coaching Settings</h3>
                        <form class="settings_form">
                            <div class="form_row">
                                <div class="form_group">
                                    <label>Coaching / Academy Name</label>
                                    <input type="text" value="EduManage Coaching Academy">
                                </div>
                                <div class="form_group">
                                    <label>Currency Symbol</label>
                                    <input type="text" value="BDT (৳)">
                                </div>
                            </div>
                            <div class="form_group">
                                <label>Campus Address</label>
                                <input type="text" value="House #12, Road #4, Dhanmondi, Dhaka, Bangladesh">
                            </div>
                            <button type="button" class="btn_save" onclick="alert('Institute settings saved!')">Save Settings</button>
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
    </script>
</body>

</html>
