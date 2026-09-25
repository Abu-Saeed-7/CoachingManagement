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
                <span class="student_name">Rahim Ahmed ▾</span>
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

            <div class="profile_layout">
                <!-- LEFT PROFILE CARD -->
                <div class="profile_card">
                    <div class="profile_avatar">🎓</div>
                    <h2>Rahim Ahmed</h2>
                    <span class="role_badge">Student (HSC 2026)</span>

                    <ul class="profile_info_list">
                        <li>
                            <span class="info_label">Student Roll / ID</span>
                            <span class="info_value">#101</span>
                        </li>
                        <li>
                            <span class="info_label">Enrolled Batch</span>
                            <span class="info_value">Batch A (Higher Math, Physics, Chem)</span>
                        </li>
                        <li>
                            <span class="info_label">Email Address</span>
                            <span class="info_value">rahim@gmail.com</span>
                        </li>
                        <li>
                            <span class="info_label">Phone Number</span>
                            <span class="info_value">01711-223344</span>
                        </li>
                        <li>
                            <span class="info_label">Guardian Phone</span>
                            <span class="info_value">01811-998877 (Father)</span>
                        </li>
                    </ul>
                </div>

                <!-- RIGHT EDIT FORM -->
                <div class="profile_details_card">
                    <h3>Personal Information</h3>
                    <form class="profile_form">
                        <div class="form_row">
                            <div class="form_group">
                                <label>Full Name</label>
                                <input type="text" value="Rahim Ahmed">
                            </div>
                            <div class="form_group">
                                <label>Student ID</label>
                                <input type="text" value="#101" readonly style="background:#f9fafb;">
                            </div>
                        </div>
                        <div class="form_row">
                            <div class="form_group">
                                <label>Email Address</label>
                                <input type="email" value="rahim@gmail.com">
                            </div>
                            <div class="form_group">
                                <label>Phone Number</label>
                                <input type="tel" value="01711223344">
                            </div>
                        </div>
                        <div class="form_row">
                            <div class="form_group">
                                <label>Guardian Name</label>
                                <input type="text" value="Md. Faruk Ahmed">
                            </div>
                            <div class="form_group">
                                <label>Guardian Contact</label>
                                <input type="tel" value="01811998877">
                            </div>
                        </div>
                        <div style="display:flex; justify-content:flex-end; margin-top:10px;">
                            <button type="button" class="action_btn" onclick="alert('Profile changes saved!')">Update Profile</button>
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
