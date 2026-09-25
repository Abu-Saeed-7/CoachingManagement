<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Coaching Management System</title>
    <link rel="stylesheet" href="student_dashboard.css">
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
                <li class="active"><a href="student_dashboard.php">🏠 <span>Dashboard</span></a></li>
                <li><a href="student_profile.php">👤 <span>My Profile</span></a></li>
                <li><a href="student_classes_exams.php">📅 <span>Classes & Exams</span></a></li>
                <li><a href="student_attendance.php">📋 <span>My Attendance</span></a></li>
                <li><a href="student_results.php">📊 <span>My Results</span></a></li>
                <li><a href="student_fees.php">💰 <span>My Fees</span></a></li>
                <li class="logout"><a href="../login_page/loginPage.php">🚪 <span>Logout</span></a></li>
            </ul>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="main_content">
            <!-- WELCOME BANNER WITH MULTIPLE BATCH SELECTOR (STEP 5.1 UPDATED) -->
            <div class="student_banner">
                <div class="banner_info">
                    <h1>Hello, Rahim Ahmed 👋</h1>
                    <p>Student ID: #101 | Enrolled in 2 Batches (Batch A & Batch B)</p>
                </div>
                <!-- BATCH SELECTOR DROPDOWN -->
                <div style="display:flex; align-items:center; gap:8px;">
                    <label style="font-size:13px; font-weight:600;">Active Batch View:</label>
                    <select class="batch_tag" style="border:none; outline:none; background:rgba(255,255,255,0.25); color:#fff; cursor:pointer; padding:6px 12px; border-radius:20px; font-weight:600;">
                        <option value="all" style="color:#000;">All Enrolled Batches (2)</option>
                        <option value="batch_a" style="color:#000;">Batch A (Higher Math, Physics)</option>
                        <option value="batch_b" style="color:#000;">Batch B (Chemistry)</option>
                    </select>
                </div>
            </div>

            <!-- STATISTICS CARDS -->
            <div class="stats_grid">
                <a href="student_attendance.php" class="stat_card">
                    <div class="card_icon">📋</div>
                    <div class="card_info">
                        <h3>Overall Attendance</h3>
                        <p class="card_value" style="color:#15803d;">92% (Present)</p>
                    </div>
                </a>
                <a href="student_results.php" class="stat_card">
                    <div class="card_icon">📊</div>
                    <div class="card_info">
                        <h3>Latest Result</h3>
                        <p class="card_value" style="color:#4d38c4;">A+ (45/50)</p>
                    </div>
                </a>
                <a href="student_classes_exams.php" class="stat_card">
                    <div class="card_icon">📅</div>
                    <div class="card_info">
                        <h3>Upcoming Exam</h3>
                        <p class="card_value" style="color:#b45309;">25 Aug (Math)</p>
                    </div>
                </a>
                <a href="student_fees.php" class="stat_card">
                    <div class="card_icon">💰</div>
                    <div class="card_info">
                        <h3>Fee Status (August)</h3>
                        <p class="card_value" style="color:#15803d;">Paid ৳ 1,500</p>
                    </div>
                </a>
            </div>

            <!-- QUICK INFORMATION GRIDS -->
            <div class="info_grid">
                <!-- Upcoming Exams & Schedule -->
                <div class="info_card">
                    <div class="info_card_header">
                        <h3>UPCOMING CLASSES & EXAMS</h3>
                        <a href="student_classes_exams.php" class="view_link">View Routine ➜</a>
                    </div>
                    <ul class="info_list">
                        <li>
                            <div class="list_item">
                                <div>
                                    <div class="item_title">Higher Math - Calculus (Batch A)</div>
                                    <span style="font-size:12px; color:#6b7280;">Prof. Abdul Karim</span>
                                </div>
                                <span class="badge badge_warning">Today 04:00 PM</span>
                            </div>
                        </li>
                        <li>
                            <div class="list_item">
                                <div>
                                    <div class="item_title">Chemistry - Organic Chem (Batch B)</div>
                                    <span style="font-size:12px; color:#6b7280;">Mohammad Ali</span>
                                </div>
                                <span class="badge badge_warning">Friday 09:30 AM</span>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Recent Attendance Activity -->
                <div class="info_card">
                    <div class="info_card_header">
                        <h3>RECENT ATTENDANCE</h3>
                        <a href="student_attendance.php" class="view_link">Full Report</a>
                    </div>
                    <ul class="info_list">
                        <li>
                            <div class="list_item">
                                <span class="item_title">01 Sep 2026 (Higher Math - Batch A)</span>
                                <span class="badge badge_success">Present</span>
                            </div>
                        </li>
                        <li>
                            <div class="list_item">
                                <span class="item_title">30 Aug 2026 (Physics - Batch A)</span>
                                <span class="badge badge_success">Present</span>
                            </div>
                        </li>
                        <li>
                            <div class="list_item">
                                <span class="item_title">28 Aug 2026 (Chemistry - Batch B)</span>
                                <span class="badge badge_success">Present</span>
                            </div>
                        </li>
                    </ul>
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
