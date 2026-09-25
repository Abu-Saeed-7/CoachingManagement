<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Take Attendance - Teacher Panel</title>
    <link rel="stylesheet" href="teacher_dashboard.css">
    <link rel="stylesheet" href="teacher_attendance.css">
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
                <li class="active"><a href="teacher_attendance.php">📋 <span>Attendance</span></a></li>
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
                    <h1>Take Daily Attendance</h1>
                    <p>Select your batch and date to record present/absent status.</p>
                </div>
            </div>

            <!-- CONTROLS BAR (SELECT BATCH & DATE) -->
            <div class="attendance_controls">
                <div class="control_group">
                    <label>Select Batch</label>
                    <select id="batchSelect">
                        <option value="Batch A">Batch A (HSC 2026 - Higher Math)</option>
                        <option value="Batch C">Batch C (Special Math)</option>
                    </select>
                </div>
                <div class="control_group">
                    <label>Attendance Date</label>
                    <input type="date" id="attendanceDate" value="2026-09-01">
                </div>
                <button class="action_btn" style="height:42px;"><span>🔄</span> Load Students</button>
            </div>

            <!-- ATTENDANCE TABLE CARD -->
            <div class="attendance_card">
                <div class="attendance_header">
                    <h3>Student Attendance Sheet (Batch A)</h3>
                    <div class="quick_mark_all">
                        <button class="btn_mark_all" onclick="markAll('present')">✅ Mark All Present</button>
                        <button class="btn_mark_all" onclick="markAll('absent')">❌ Mark All Absent</button>
                    </div>
                </div>

                <div class="table_container">
                    <table class="custom_table">
                        <thead>
                            <tr>
                                <th>Roll / ID</th>
                                <th>Student Name</th>
                                <th>Email</th>
                                <th>Attendance Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#101</td>
                                <td><strong>Rahim Ahmed</strong></td>
                                <td>rahim@gmail.com</td>
                                <td>
                                    <div class="attendance_toggle">
                                        <label class="toggle_label toggle_present">
                                            <input type="radio" name="att_101" value="P" checked> Present
                                        </label>
                                        <label class="toggle_label toggle_absent">
                                            <input type="radio" name="att_101" value="A"> Absent
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>#102</td>
                                <td><strong>Karim Khan</strong></td>
                                <td>karim@gmail.com</td>
                                <td>
                                    <div class="attendance_toggle">
                                        <label class="toggle_label toggle_present">
                                            <input type="radio" name="att_102" value="P" checked> Present
                                        </label>
                                        <label class="toggle_label toggle_absent">
                                            <input type="radio" name="att_102" value="A"> Absent
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>#103</td>
                                <td><strong>Sakib Hasan</strong></td>
                                <td>sakib@gmail.com</td>
                                <td>
                                    <div class="attendance_toggle">
                                        <label class="toggle_label toggle_present">
                                            <input type="radio" name="att_103" value="P"> Present
                                        </label>
                                        <label class="toggle_label toggle_absent">
                                            <input type="radio" name="att_103" value="A" checked> Absent
                                        </label>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- SAVE BAR -->
                <div class="save_bar">
                    <span style="font-size:13px; color:#6b7280;">Total: 3 Students | Present: 2 | Absent: 1</span>
                    <button class="action_btn" onclick="alert('Attendance saved successfully!')">💾 Save Attendance</button>
                </div>
            </div>
        </main>
    </div>

    <!-- JavaScript -->
    <script>
        // Sidebar Toggle
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        sidebarToggle.addEventListener('click', function () {
            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('mobile_open');
            } else {
                sidebar.classList.toggle('collapsed');
            }
        });

        // Quick Mark All Helper
        function markAll(status) {
            const val = (status === 'present') ? 'P' : 'A';
            document.querySelectorAll(`input[type="radio"][value="${val}"]`).forEach(el => el.checked = true);
        }
    </script>
</body>

</html>
