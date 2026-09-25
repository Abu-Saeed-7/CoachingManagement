<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Batches - Teacher Panel</title>
    <link rel="stylesheet" href="teacher_dashboard.css">
    <link rel="stylesheet" href="teacher_batches.css">
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
                <li class="active"><a href="teacher_batches.php">📚 <span>My Batches</span></a></li>
                <li><a href="teacher_students.php">🎓 <span>My Students</span></a></li>
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
                    <h1>My Assigned Batches</h1>
                    <p>Overview of classes and students assigned under your instruction.</p>
                </div>
            </div>

            <!-- BATCH CARDS GRID (STEP 4.2) -->
            <div class="batch_cards_grid">
                <!-- Batch 1 -->
                <div class="batch_card">
                    <div>
                        <div class="batch_header">
                            <div>
                                <div class="batch_name">Batch A (HSC 2026)</div>
                                <div class="batch_subject">Higher Mathematics</div>
                            </div>
                            <span class="badge">Active</span>
                        </div>
                        <ul class="batch_details">
                            <li>👥 <strong>45 Students</strong> enrolled</li>
                            <li>⏰ <strong>Mon, Wed, Fri</strong> (4:00 PM - 6:00 PM)</li>
                            <li>📍 Room 302, Science Building</li>
                        </ul>
                    </div>
                    <div class="batch_actions">
                        <a href="teacher_attendance.php" class="action_btn" style="flex:1; justify-content:center;">Take Attendance</a>
                        <a href="#batchStudents" class="btn_secondary">View Students</a>
                    </div>
                </div>

                <!-- Batch 2 -->
                <div class="batch_card">
                    <div>
                        <div class="batch_header">
                            <div>
                                <div class="batch_name">Batch C (Special Math)</div>
                                <div class="batch_subject">Calculus & Geometry</div>
                            </div>
                            <span class="badge">Active</span>
                        </div>
                        <ul class="batch_details">
                            <li>👥 <strong>25 Students</strong> enrolled</li>
                            <li>⏰ <strong>Saturday</strong> (10:00 AM - 1:00 PM)</li>
                            <li>📍 Room 104, Main Campus</li>
                        </ul>
                    </div>
                    <div class="batch_actions">
                        <a href="teacher_attendance.php" class="action_btn" style="flex:1; justify-content:center;">Take Attendance</a>
                        <a href="#batchStudents" class="btn_secondary">View Students</a>
                    </div>
                </div>
            </div>

            <!-- BATCH STUDENTS TABLE (STEP 4.3) -->
            <div class="students_section" id="batchStudents">
                <h3 class="section_title">Students in Batch A (HSC 2026)</h3>
                <div class="table_container">
                    <table class="custom_table">
                        <thead>
                            <tr>
                                <th>Roll / ID</th>
                                <th>Student Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Attendance Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#101</td>
                                <td><strong>Rahim Ahmed</strong></td>
                                <td>rahim@gmail.com</td>
                                <td>01711-XXXXXX</td>
                                <td><span class="badge" style="background:#dcfce7; color:#15803d;">95% Present</span></td>
                            </tr>
                            <tr>
                                <td>#102</td>
                                <td><strong>Karim Khan</strong></td>
                                <td>karim@gmail.com</td>
                                <td>01822-XXXXXX</td>
                                <td><span class="badge" style="background:#dcfce7; color:#15803d;">88% Present</span></td>
                            </tr>
                            <tr>
                                <td>#103</td>
                                <td><strong>Sakib Hasan</strong></td>
                                <td>sakib@gmail.com</td>
                                <td>01933-XXXXXX</td>
                                <td><span class="badge" style="background:#fee2e2; color:#dc2626;">65% Present</span></td>
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
