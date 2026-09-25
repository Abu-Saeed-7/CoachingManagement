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
                <span class="teacher_name">Prof. Karim ▾</span>
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

            <div class="profile_layout">
                <!-- LEFT PROFILE CARD -->
                <div class="profile_card">
                    <div class="profile_avatar">👨‍🏫</div>
                    <h2>Prof. Abdul Karim</h2>
                    <span class="role_badge">Senior Instructor (Mathematics)</span>

                    <ul class="profile_info_list">
                        <li>
                            <span class="info_label">Teacher ID</span>
                            <span class="info_value">#T01</span>
                        </li>
                        <li>
                            <span class="info_label">Email Address</span>
                            <span class="info_value">karim.teacher@gmail.com</span>
                        </li>
                        <li>
                            <span class="info_label">Phone Number</span>
                            <span class="info_value">+880 1711-223344</span>
                        </li>
                        <li>
                            <span class="info_label">Assigned Batches</span>
                            <span class="info_value">Batch A, Batch C</span>
                        </li>
                        <li>
                            <span class="info_label">Joining Date</span>
                            <span class="info_value">15 January 2024</span>
                        </li>
                    </ul>
                </div>

                <!-- RIGHT EDIT DETAILS CARD -->
                <div class="profile_details_card">
                    <h3>Update Profile Information</h3>
                    <form class="profile_form">
                        <div class="form_row">
                            <div class="form_group">
                                <label>Full Name</label>
                                <input type="text" value="Prof. Abdul Karim">
                            </div>
                            <div class="form_group">
                                <label>Designation / Subject</label>
                                <input type="text" value="Higher Mathematics" readonly style="background:#f9fafb;">
                            </div>
                        </div>
                        <div class="form_row">
                            <div class="form_group">
                                <label>Email Address</label>
                                <input type="email" value="karim.teacher@gmail.com">
                            </div>
                            <div class="form_group">
                                <label>Phone Number</label>
                                <input type="tel" value="01711223344">
                            </div>
                        </div>
                        <div class="form_row">
                            <div class="form_group">
                                <label>New Password</label>
                                <input type="password" placeholder="Leave blank to keep same">
                            </div>
                            <div class="form_group">
                                <label>Confirm Password</label>
                                <input type="password" placeholder="Confirm new password">
                            </div>
                        </div>
                        <div style="display:flex; justify-content:flex-end; margin-top:10px;">
                            <button type="button" class="action_btn" onclick="alert('Profile updated successfully!')">Save Changes</button>
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
    </script>
</body>

</html>
