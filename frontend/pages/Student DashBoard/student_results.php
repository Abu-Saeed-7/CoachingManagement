<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Results - Student Panel</title>
    <link rel="stylesheet" href="student_dashboard.css">
    <link rel="stylesheet" href="student_results.css">
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
                <li><a href="student_attendance.php">📋 <span>My Attendance</span></a></li>
                <li class="active"><a href="student_results.php">📊 <span>My Results</span></a></li>
                <li><a href="student_fees.php">💰 <span>My Fees</span></a></li>
                <li class="logout"><a href="../login_page/loginPage.php">🚪 <span>Logout</span></a></li>
            </ul>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="main_content">
            <div class="page_header">
                <h1>My Academic Results & Grades</h1>
                <p>View your marks, grades, and teacher remarks for all completed exams.</p>
            </div>

            <!-- SUMMARY STATS -->
            <div class="results_stat_row">
                <div class="res_stat_box">
                    <h4>Total Exams Taken</h4>
                    <p class="number">3 Exams</p>
                </div>
                <div class="res_stat_box">
                    <h4>Average Score</h4>
                    <p class="number" style="color:#15803d;">88.5%</p>
                </div>
                <div class="res_stat_box">
                    <h4>Highest Grade</h4>
                    <p class="number" style="color:#4d38c4;">A+ (45/50)</p>
                </div>
            </div>

            <!-- RESULTS TABLE (STEP 5.4) -->
            <div class="results_table_card">
                <div class="card_header">
                    <h3>Exam Performance Marksheet</h3>
                </div>

                <div class="table_container">
                    <table class="custom_table">
                        <thead>
                            <tr>
                                <th>Exam Title</th>
                                <th>Subject</th>
                                <th>Exam Date</th>
                                <th>Marks Obtained</th>
                                <th>Grade</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Monthly Assessment</strong></td>
                                <td>Higher Mathematics</td>
                                <td>25 Aug 2026</td>
                                <td><strong>45</strong> / 50</td>
                                <td><span class="grade_badge">A+</span></td>
                                <td><span style="font-size:12px; color:#4b5563;">Excellent in calculus</span></td>
                            </tr>
                            <tr>
                                <td><strong>Quiz 01 (Periodic Table)</strong></td>
                                <td>Chemistry</td>
                                <td>15 Aug 2026</td>
                                <td><strong>22</strong> / 25</td>
                                <td><span class="grade_badge">A</span></td>
                                <td><span style="font-size:12px; color:#4b5563;">Good performance</span></td>
                            </tr>
                            <tr>
                                <td><strong>Weekly Physics Test</strong></td>
                                <td>Physics</td>
                                <td>10 Aug 2026</td>
                                <td><strong>42</strong> / 50</td>
                                <td><span class="grade_badge">A</span></td>
                                <td><span style="font-size:12px; color:#4b5563;">Keep practicing formulas</span></td>
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
