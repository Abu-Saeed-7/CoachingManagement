<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Attendance - Student Panel</title>
    <link rel="stylesheet" href="student_dashboard.css">
    <link rel="stylesheet" href="student_attendance.css">
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
                <li><a href="student_profile.php">👤 <span>My Profile</span></a></li>
                <li><a href="student_classes_exams.php">📅 <span>Classes & Exams</span></a></li>
                <li class="active"><a href="student_attendance.php">📋 <span>My Attendance</span></a></li>
                <li><a href="student_results.php">📊 <span>My Results</span></a></li>
                <li><a href="student_fees.php">💰 <span>My Fees</span></a></li>
                <li class="logout"><a href="../login_page/loginPage.php">🚪 <span>Logout</span></a></li>
            </ul>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="main_content">
            <div class="page_header">
                <h1>My Attendance History</h1>
                <p>Track your daily class attendance and overall attendance percentage.</p>
            </div>

            <!-- SUMMARY STATS -->
            <div class="att_summary_row">
                <div class="att_stat_box">
                    <h4>Total Classes</h4>
                    <p class="number">24</p>
                </div>
                <div class="att_stat_box">
                    <h4>Classes Attended</h4>
                    <p class="number present">22 Days</p>
                </div>
                <div class="att_stat_box">
                    <h4>Absences</h4>
                    <p class="number absent">2 Days</p>
                </div>
                <div class="att_stat_box">
                    <h4>Attendance Percentage</h4>
                    <p class="number" style="color:#4d38c4;">91.6%</p>
                </div>
            </div>

            <!-- ATTENDANCE LOG TABLE (STEP 5.3) -->
            <div class="attendance_table_card">
                <div class="card_header">
                    <h3>Detailed Attendance Log</h3>
                    <select class="filter_select">
                        <option value="August">August 2026</option>
                        <option value="July">July 2026</option>
                    </select>
                </div>

                <div class="table_container">
                    <table class="custom_table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Day</th>
                                <th>Subject</th>
                                <th>Teacher</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>01 Sep 2026</td>
                                <td>Tuesday</td>
                                <td>Higher Mathematics</td>
                                <td>Prof. Abdul Karim</td>
                                <td><span class="status_p">Present</span></td>
                            </tr>
                            <tr>
                                <td>30 Aug 2026</td>
                                <td>Sunday</td>
                                <td>Physics</td>
                                <td>Dr. Nusrat Jahan</td>
                                <td><span class="status_p">Present</span></td>
                            </tr>
                            <tr>
                                <td>28 Aug 2026</td>
                                <td>Friday</td>
                                <td>Chemistry</td>
                                <td>Mohammad Ali</td>
                                <td><span class="status_p">Present</span></td>
                            </tr>
                            <tr>
                                <td>25 Aug 2026</td>
                                <td>Tuesday</td>
                                <td>Higher Mathematics</td>
                                <td>Prof. Abdul Karim</td>
                                <td><span class="status_a">Absent</span></td>
                            </tr>
                            <tr>
                                <td>23 Aug 2026</td>
                                <td>Sunday</td>
                                <td>Physics</td>
                                <td>Dr. Nusrat Jahan</td>
                                <td><span class="status_p">Present</span></td>
                            </tr>
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
    </script>
</body>

</html>
