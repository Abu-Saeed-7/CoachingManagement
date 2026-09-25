<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Coaching Management System</title>
    <link rel="stylesheet" href="admin_dashboard.css">
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
            <div class="admin_profile">
                <span class="profile_icon">👤</span>
                <span class="admin_name">Admin ▾</span>
            </div>
        </div>
    </header>

    <!-- ২. মূল লেআউট (Sidebar + Main Content) -->
    <div class="dashboard_container">
        <!-- SIDEBAR -->
        <aside class="sidebar" id="sidebar">
            <ul class="sidebar_menu">
                <li class="active"><a href="#">🏠 <span>Dashboard</span></a></li>
                <li><a href="students.php">🎓 <span>Students</span></a></li>
                <li><a href="teachers.php">👨‍🏫 <span>Teachers</span></a></li>
                <li><a href="batches.php">📚 <span>Batches</span></a></li>
                <li><a href="exams.php">📝 <span>Exams</span></a></li>
                <li><a href="results.php">📊 <span>Results</span></a></li>
                <li><a href="fees.php">💰 <span>Fees</span></a></li>
                <li><a href="admin_profile.php">⚙️ <span>Profile</span></a></li>
                <li class="logout"><a href="../login_page/loginPage.php">🚪 <span>Logout</span></a></li>
            </ul>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <main class="main_content">

            <!-- ১. WELCOME SECTION -->
            <div class="welcome_section">
                <h1>Good Morning, Admin 👋</h1>
                <p>Here's what's happening today.</p>
            </div>

            <!-- ২. STATISTICS CARDS -->
            <div class="stats_grid">
                <!-- Card 1 -->
                <div class="stat_card">
                    <div class="card_icon">🎓</div>
                    <div class="card_info">
                        <h3>Total Students</h3>
                        <p class="card_value">120</p>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="stat_card">
                    <div class="card_icon">👨‍🏫</div>
                    <div class="card_info">
                        <h3>Total Teachers</h3>
                        <p class="card_value">8</p>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="stat_card">
                    <div class="card_icon">📚</div>
                    <div class="card_info">
                        <h3>Active Batches</h3>
                        <p class="card_value">6</p>
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="stat_card">
                    <div class="card_icon">📝</div>
                    <div class="card_info">
                        <h3>Total Exams</h3>
                        <p class="card_value">4</p>
                    </div>
                </div>

                <!-- Card 5 -->
                <div class="stat_card fee_card">
                    <div class="card_icon">💰</div>
                    <div class="card_info">
                        <h3>Unpaid Fees</h3>
                        <p class="card_value">15</p>
                    </div>
                </div>
            </div>

            <!-- ৩. QUICK ACTIONS -->
            <div class="quick_actions_section">
                <h2>QUICK ACTIONS</h2>
                <div class="action_buttons">
                    <button class="action_btn" onclick="window.location.href='students.php'"><span>+</span> Add
                        Student</button>
                    <button class="action_btn" onclick="window.location.href='teachers.php'"><span>+</span> Add Teacher</button>
                    <button class="action_btn" onclick="window.location.href='batches.php'"><span>+</span> Create Batch</button>
                </div>
            </div>

            <!-- ৪. INFORMATION SECTION (Recent Students & Upcoming Exams) -->
            <div class="info_grid">
                <!-- Recent Students Card -->
                <div class="info_card">
                    <div class="info_card_header">
                        <h3>RECENT STUDENTS</h3>
                        <a href="students.php" class="view_all_link">View All</a>
                    </div>
                    <ul class="info_list">
                        <li>
                            <div class="student_item">
                                <span class="student_name">Rahim</span>
                                <span class="badge">Batch A</span>
                            </div>
                        </li>
                        <li>
                            <div class="student_item">
                                <span class="student_name">Karim</span>
                                <span class="badge">Batch B</span>
                            </div>
                        </li>
                        <li>
                            <div class="student_item">
                                <span class="student_name">Sakib</span>
                                <span class="badge">Batch A</span>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Upcoming Exams Card -->
                <div class="info_card">
                    <div class="info_card_header">
                        <h3>UPCOMING EXAMS</h3>
                    </div>
                    <ul class="info_list">
                        <li>
                            <div class="exam_item">
                                <span class="exam_name">Monthly Exam</span>
                                <span class="exam_date">📅 25 Aug</span>
                            </div>
                        </li>
                        <li>
                            <div class="exam_item">
                                <span class="exam_name">Final Exam</span>
                                <span class="exam_date">📅 30 Aug</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>


        </main>
    </div>

    <script>
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');

        sidebarToggle.addEventListener('click', function () {
            // স্ক্রিন ছোট (মোবাইল) হলে 'mobile_open' টগল করবে
            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('mobile_open');
            } else {
                // বড় স্ক্রিন (ডেস্কটপ) হলে 'collapsed' টগল করবে
                sidebar.classList.toggle('collapsed');
            }
        });
    </script>

</body>

</html>