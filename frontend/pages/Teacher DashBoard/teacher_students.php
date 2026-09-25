<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Students - Teacher Panel</title>
    <link rel="stylesheet" href="teacher_dashboard.css">
    <link rel="stylesheet" href="teacher_students.css">
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
                <li class="active"><a href="teacher_students.php">🎓 <span>My Students</span></a></li>
                <li><a href="teacher_attendance.php">📋 <span>Attendance</span></a></li>
                <li><a href="teacher_results.php">📊 <span>Enter Results</span></a></li>
                <li><a href="teacher_profile.php">👤 <span>My Profile</span></a></li>
                <li class="logout"><a href="../login_page/loginPage.php">🚪 <span>Logout</span></a></li>
            </ul>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="main_content">
            <!-- PAGE HEADER -->
            <div class="page_header">
                <div>
                    <h1>My Assigned Students</h1>
                    <p>Viewing students currently enrolled in your batches (Batch A & Batch C).</p>
                </div>
            </div>

            <!-- SEARCH & ASSIGNED BATCHES FILTER -->
            <div class="table_controls">
                <input type="text" class="search_input" placeholder="🔍 Search by student name, roll or phone...">
                <select class="filter_select">
                    <option value="">All My Batches</option>
                    <option value="Batch A">Batch A (HSC 2026 - Higher Math)</option>
                    <option value="Batch C">Batch C (Special Math)</option>
                </select>
            </div>

            <!-- ASSIGNED STUDENTS TABLE -->
            <div class="table_container">
                <table class="custom_table">
                    <thead>
                        <tr>
                            <th>Roll / ID</th>
                            <th>Student Name</th>
                            <th>Enrolled Batch</th>
                            <th>Email Address</th>
                            <th>Phone</th>
                            <th>Attendance Rate</th>
                            <th>Quick Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#101</td>
                            <td><strong>Rahim Ahmed</strong></td>
                            <td><span class="badge">Batch A</span></td>
                            <td>rahim@gmail.com</td>
                            <td>01711-XXXXXX</td>
                            <td><span class="att_rate att_high">95% (19/20)</span></td>
                            <td>
                                <a href="teacher_attendance.php" class="btn_action" title="View Attendance">📋 Attendance</a>
                                <a href="teacher_results.php" class="btn_action" title="View/Enter Marks">📊 Marks</a>
                            </td>
                        </tr>
                        <tr>
                            <td>#102</td>
                            <td><strong>Karim Khan</strong></td>
                            <td><span class="badge">Batch A</span></td>
                            <td>karim@gmail.com</td>
                            <td>01822-XXXXXX</td>
                            <td><span class="att_rate att_high">88% (18/20)</span></td>
                            <td>
                                <a href="teacher_attendance.php" class="btn_action" title="View Attendance">📋 Attendance</a>
                                <a href="teacher_results.php" class="btn_action" title="View/Enter Marks">📊 Marks</a>
                            </td>
                        </tr>
                        <tr>
                            <td>#103</td>
                            <td><strong>Sakib Hasan</strong></td>
                            <td><span class="badge">Batch A</span></td>
                            <td>sakib@gmail.com</td>
                            <td>01933-XXXXXX</td>
                            <td><span class="att_rate att_low">65% (13/20)</span></td>
                            <td>
                                <a href="teacher_attendance.php" class="btn_action" title="View Attendance">📋 Attendance</a>
                                <a href="teacher_results.php" class="btn_action" title="View/Enter Marks">📊 Marks</a>
                            </td>
                        </tr>
                        <tr>
                            <td>#104</td>
                            <td><strong>Nusrat Fariha</strong></td>
                            <td><span class="badge">Batch C</span></td>
                            <td>fariha@gmail.com</td>
                            <td>01644-XXXXXX</td>
                            <td><span class="att_rate att_high">90% (18/20)</span></td>
                            <td>
                                <a href="teacher_attendance.php" class="btn_action" title="View Attendance">📋 Attendance</a>
                                <a href="teacher_results.php" class="btn_action" title="View/Enter Marks">📊 Marks</a>
                            </td>
                        </tr>
                        <tr>
                            <td>#105</td>
                            <td><strong>Tanvir Mahmud</strong></td>
                            <td><span class="badge">Batch C</span></td>
                            <td>tanvir@gmail.com</td>
                            <td>01555-XXXXXX</td>
                            <td><span class="att_rate att_high">92% (19/20)</span></td>
                            <td>
                                <a href="teacher_attendance.php" class="btn_action" title="View Attendance">📋 Attendance</a>
                                <a href="teacher_results.php" class="btn_action" title="View/Enter Marks">📊 Marks</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
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
