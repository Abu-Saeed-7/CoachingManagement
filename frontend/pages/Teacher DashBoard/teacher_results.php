<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Results - Teacher Panel</title>
    <link rel="stylesheet" href="teacher_dashboard.css">
    <link rel="stylesheet" href="teacher_results.css">
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
                <li><a href="teacher_attendance.php">📋 <span>Attendance</span></a></li>
                <li class="active"><a href="teacher_results.php">📊 <span>Enter Results</span></a></li>
                <li><a href="teacher_profile.php">👤 <span>My Profile</span></a></li>
                <li class="logout"><a href="../login_page/loginPage.php">🚪 <span>Logout</span></a></li>
            </ul>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="main_content">
            <!-- PAGE HEADER -->
            <div class="page_header">
                <div>
                    <h1>Enter Exam Results</h1>
                    <p>Select exam and batch to enter or update students marks.</p>
                </div>
            </div>

            <!-- CONTROLS BAR -->
            <div class="results_controls">
                <div class="control_group">
                    <label>Select Exam</label>
                    <select>
                        <option value="Monthly Assessment">Monthly Math Assessment (50 Marks)</option>
                        <option value="Term Final">Term Final Examination (100 Marks)</option>
                    </select>
                </div>
                <div class="control_group">
                    <label>Select Batch</label>
                    <select>
                        <option value="Batch A">Batch A (HSC 2026)</option>
                        <option value="Batch C">Batch C (Special Math)</option>
                    </select>
                </div>
                <button class="action_btn" style="height:42px;"><span>🔄</span> Load Marksheet</button>
            </div>

            <!-- MARKSHEET TABLE CARD -->
            <div class="marksheet_card">
                <div class="marksheet_header">
                    <h3>Marksheet: Monthly Math Assessment (Total: 50 Marks)</h3>
                    <span class="badge">Higher Math</span>
                </div>

                <div class="table_container">
                    <table class="custom_table">
                        <thead>
                            <tr>
                                <th>Roll / ID</th>
                                <th>Student Name</th>
                                <th>Max Marks</th>
                                <th>Marks Obtained</th>
                                <th>Teacher's Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#101</td>
                                <td><strong>Rahim Ahmed</strong></td>
                                <td>50</td>
                                <td>
                                    <input type="number" class="marks_input" value="45" max="50" min="0">
                                </td>
                                <td>
                                    <input type="text" class="remarks_input" value="Excellent in calculus" placeholder="Add remarks...">
                                </td>
                            </tr>
                            <tr>
                                <td>#102</td>
                                <td><strong>Karim Khan</strong></td>
                                <td>50</td>
                                <td>
                                    <input type="number" class="marks_input" value="38" max="50" min="0">
                                </td>
                                <td>
                                    <input type="text" class="remarks_input" value="Good attempt" placeholder="Add remarks...">
                                </td>
                            </tr>
                            <tr>
                                <td>#103</td>
                                <td><strong>Sakib Hasan</strong></td>
                                <td>50</td>
                                <td>
                                    <input type="number" class="marks_input" value="22" max="50" min="0">
                                </td>
                                <td>
                                    <input type="text" class="remarks_input" value="Needs improvement in vectors" placeholder="Add remarks...">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- SAVE BAR -->
                <div class="save_bar">
                    <button class="action_btn" onclick="alert('Results saved successfully!')">💾 Submit & Save Results</button>
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
