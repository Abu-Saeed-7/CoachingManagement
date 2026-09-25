<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard - Coaching Management System</title>
    <link rel="stylesheet" href="teacher_dashboard.css">
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
                <li class="active"><a href="teacher_dashboard.php">🏠 <span>Dashboard</span></a></li>
                <li><a href="teacher_batches.php">📚 <span>My Batches</span></a></li>
                <li><a href="teacher_students.php">🎓 <span>My Students</span></a></li>
                <li><a href="teacher_attendance.php">📋 <span>Attendance</span></a></li>
                <li><a href="teacher_results.php">📊 <span>Enter Results</span></a></li>
                <li><a href="teacher_profile.php">👤 <span>My Profile</span></a></li>
                <li class="logout"><a href="../login_page/loginPage.php">🚪 <span>Logout</span></a></li>
            </ul>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="main_content">
            <!-- WELCOME SECTION -->
            <div class="welcome_section">
                <h1>Welcome Back, Prof. Abdul Karim 👋</h1>
                <p>Here is your teaching schedule and student overview today.</p>
            </div>

            <!-- STATISTICS CARDS -->
            <div class="stats_grid">
                <div class="stat_card">
                    <div class="card_icon">📚</div>
                    <div class="card_info">
                        <h3>Assigned Batches</h3>
                        <p class="card_value">3 Batches</p>
                    </div>
                </div>
                <div class="stat_card">
                    <div class="card_icon">👥</div>
                    <div class="card_info">
                        <h3>Total Students</h3>
                        <p class="card_value">83 Students</p>
                    </div>
                </div>
                <div class="stat_card">
                    <div class="card_icon">📅</div>
                    <div class="card_info">
                        <h3>Classes Today</h3>
                        <p class="card_value">2 Sessions</p>
                    </div>
                </div>
            </div>

            <!-- QUICK ACTIONS -->
            <div class="quick_actions_section">
                <h2>QUICK ACTIONS</h2>
                <div class="action_buttons">
                    <a href="teacher_attendance.php" class="action_btn"><span>📋</span> Take Attendance</a>
                    <a href="teacher_results.php" class="action_btn"><span>📊</span> Input Exam Marks</a>
                    <a href="teacher_batches.php" class="action_btn"><span>👥</span> View My Students</a>
                </div>
            </div>

            <!-- TODAY'S SCHEDULE & RECENT ACTIVITIES -->
            <div class="info_grid">
                <!-- Schedule Card -->
                <div class="info_card">
                    <div class="info_card_header">
                        <h3>TODAY'S CLASS SCHEDULE</h3>
                    </div>
                    <ul class="info_list">
                        <li>
                            <div class="schedule_item">
                                <div>
                                    <div class="batch_title">Batch A (HSC 2026)</div>
                                    <span class="badge">Higher Math - Calculus</span>
                                </div>
                                <span class="batch_time">⏰ 04:00 PM</span>
                            </div>
                        </li>
                        <li>
                            <div class="schedule_item">
                                <div>
                                    <div class="batch_title">Batch C (Special Math)</div>
                                    <span class="badge">Trigonometry</span>
                                </div>
                                <span class="batch_time">⏰ 06:30 PM</span>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Upcoming Exams to Grade -->
                <div class="info_card">
                    <div class="info_card_header">
                        <h3>PENDING EXAM RESULTS</h3>
                    </div>
                    <ul class="info_list">
                        <li>
                            <div class="schedule_item">
                                <div>
                                    <div class="batch_title">Monthly Math Assessment</div>
                                    <span class="badge">Batch A</span>
                                </div>
                                <a href="teacher_results.php" class="badge" style="background:#fef3c7; color:#b45309; text-decoration:none;">Enter Marks ➜</a>
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
