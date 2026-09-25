<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classes & Exams Schedule - Student Panel</title>
    <link rel="stylesheet" href="student_dashboard.css">
    <link rel="stylesheet" href="student_classes_exams.css">
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
                <li class="active"><a href="student_classes_exams.php">📅 <span>Classes & Exams</span></a></li>
                <li><a href="student_attendance.php">📋 <span>My Attendance</span></a></li>
                <li><a href="student_results.php">📊 <span>My Results</span></a></li>
                <li><a href="student_fees.php">💰 <span>My Fees</span></a></li>
                <li class="logout"><a href="../login_page/loginPage.php">🚪 <span>Logout</span></a></li>
            </ul>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="main_content">
            <div class="page_header">
                <h1>Classes & Exams Schedule</h1>
                <p>View your weekly class routine and upcoming exam schedules for all your enrolled batches.</p>
            </div>

            <!-- CONTROLS & BATCH FILTER BAR -->
            <div class="schedule_controls">
                <div class="batch_filter_group">
                    <label>Filter by Batch:</label>
                    <select class="batch_select" id="batchSelector">
                        <option value="all">All Enrolled Batches (Batch A & Batch B)</option>
                        <option value="batch_a">Batch A (Higher Math & Physics)</option>
                        <option value="batch_b">Batch B (Chemistry)</option>
                    </select>
                </div>
                <div class="schedule_tabs">
                    <button class="tab_btn active" onclick="switchTab('all')">All Schedules</button>
                    <button class="tab_btn" onclick="switchTab('classes')">Classes Only</button>
                    <button class="tab_btn" onclick="switchTab('exams')">Exams Only</button>
                </div>
            </div>

            <div class="schedule_sections">
                <!-- SECTION 1: WEEKLY CLASS ROUTINE -->
                <div class="section_card" id="classesSection">
                    <div class="section_header">
                        <h3>📖 Weekly Class Schedule & Routine</h3>
                        <span class="badge badge_batch">3 Active Classes / Week</span>
                    </div>

                    <div class="timetable_grid">
                        <!-- Class 1 -->
                        <div class="class_box">
                            <span class="class_day">Monday & Wednesday</span>
                            <div class="class_subject">Higher Mathematics (Calculus)</div>
                            <div class="class_meta">
                                <div>👨‍🏫 Prof. Abdul Karim</div>
                                <div>🏷️ <span class="badge badge_batch">Batch A</span></div>
                                <div class="time_tag">⏰ 04:00 PM - 06:00 PM (Room 302)</div>
                            </div>
                        </div>

                        <!-- Class 2 -->
                        <div class="class_box physics">
                            <span class="class_day">Sunday & Tuesday</span>
                            <div class="class_subject">Physics (Mechanics & Waves)</div>
                            <div class="class_meta">
                                <div>👨‍🏫 Dr. Nusrat Jahan</div>
                                <div>🏷️ <span class="badge badge_batch">Batch A</span></div>
                                <div class="time_tag" style="color:#0284c7;">⏰ 05:30 PM - 07:00 PM (Room 201)</div>
                            </div>
                        </div>

                        <!-- Class 3 -->
                        <div class="class_box chemistry">
                            <span class="class_day">Friday</span>
                            <div class="class_subject">Chemistry (Organic Chemistry)</div>
                            <div class="class_meta">
                                <div>👨‍🏫 Mohammad Ali</div>
                                <div>🏷️ <span class="badge badge_batch">Batch B</span></div>
                                <div class="time_tag" style="color:#059669;">⏰ 09:30 AM - 11:30 AM (Room 105)</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: UPCOMING EXAMS SCHEDULE -->
                <div class="section_card" id="examsSection">
                    <div class="section_header">
                        <h3>📝 Upcoming Exams Schedule</h3>
                        <span class="badge badge_upcoming">2 Scheduled Exams</span>
                    </div>

                    <div class="table_container">
                        <table class="custom_table">
                            <thead>
                                <tr>
                                    <th>Exam Title</th>
                                    <th>Subject</th>
                                    <th>Batch</th>
                                    <th>Date & Time</th>
                                    <th>Total Marks</th>
                                    <th>Room / Venue</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Monthly Calculus Assessment</strong></td>
                                    <td>Higher Mathematics</td>
                                    <td><span class="badge badge_batch">Batch A</span></td>
                                    <td>📅 25 Aug 2026 (10:00 AM)</td>
                                    <td><strong>50 Marks</strong></td>
                                    <td>Exam Hall 01</td>
                                </tr>
                                <tr>
                                    <td><strong>Physics Weekly Quiz</strong></td>
                                    <td>Physics</td>
                                    <td><span class="badge badge_batch">Batch A</span></td>
                                    <td>📅 30 Aug 2026 (11:30 AM)</td>
                                    <td><strong>30 Marks</strong></td>
                                    <td>Room 201</td>
                                </tr>
                                <tr>
                                    <td><strong>Chemistry Term Final</strong></td>
                                    <td>Chemistry</td>
                                    <td><span class="badge badge_batch">Batch B</span></td>
                                    <td>📅 05 Sep 2026 (04:00 PM)</td>
                                    <td><strong>100 Marks</strong></td>
                                    <td>Main Auditorium</td>
                                </tr>
                            </tbody>
                        </table>
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

        // Tab Switcher
        function switchTab(type) {
            const tabs = document.querySelectorAll('.tab_btn');
            tabs.forEach(t => t.classList.remove('active'));
            event.target.classList.add('active');

            const classesSec = document.getElementById('classesSection');
            const examsSec = document.getElementById('examsSection');

            if (type === 'all') {
                classesSec.style.display = 'block';
                examsSec.style.display = 'block';
            } else if (type === 'classes') {
                classesSec.style.display = 'block';
                examsSec.style.display = 'none';
            } else if (type === 'exams') {
                classesSec.style.display = 'none';
                examsSec.style.display = 'block';
            }
        }
    </script>
</body>

</html>
