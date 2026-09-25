<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Results Management - Coaching Management System</title>
    <link rel="stylesheet" href="admin_dashboard.css">
    <link rel="stylesheet" href="results.css">
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

    <!-- ২. মূল লেআউট -->
    <div class="dashboard_container">
        <!-- SIDEBAR -->
        <aside class="sidebar" id="sidebar">
            <ul class="sidebar_menu">
                <li><a href="admin_dashboard.php">🏠 <span>Dashboard</span></a></li>
                <li><a href="students.php">🎓 <span>Students</span></a></li>
                <li><a href="teachers.php">👨‍🏫 <span>Teachers</span></a></li>
                <li><a href="batches.php">📚 <span>Batches</span></a></li>
                <li><a href="exams.php">📝 <span>Exams</span></a></li>
                <li class="active"><a href="results.php">📊 <span>Results</span></a></li>
                <li><a href="fees.php">💰 <span>Fees</span></a></li>
                <li><a href="admin_profile.php">⚙️ <span>Profile</span></a></li>
                <li class="logout"><a href="../login_page/loginPage.php">🚪 <span>Logout</span></a></li>
            </ul>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="main_content">
            <!-- হেডার ও অ্যাকশন বাটন -->
            <div class="page_header">
                <div>
                    <h1>Results Management</h1>
                    <p>Enter marks, evaluate grades, and view exam results of students.</p>
                </div>
                <button class="action_btn" id="openAddModal"><span>+</span> Enter New Result</button>
            </div>

            <!-- সার্চ ও ফিল্টার বার (Select Batch -> Select Exam) -->
            <div class="table_controls">
                <input type="text" class="search_input" placeholder="🔍 Search student name or roll...">
                <select class="filter_select">
                    <option value="">Select Batch</option>
                    <option value="Batch A">Batch A</option>
                    <option value="Batch B">Batch B</option>
                    <option value="Batch C">Batch C</option>
                </select>
                <select class="filter_select">
                    <option value="">Select Exam</option>
                    <option value="Monthly Assessment">Monthly Assessment (Physics)</option>
                    <option value="Term Final Examination">Term Final Examination (Math)</option>
                    <option value="Quiz 01">Quiz 01 (Chemistry)</option>
                </select>
            </div>

            <!-- রেজাল্ট টেবিল -->
            <div class="table_container">
                <table class="custom_table">
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Student Name</th>
                            <th>Exam Name</th>
                            <th>Batch</th>
                            <th>Marks Obtained</th>
                            <th>Grade</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#101</td>
                            <td><strong>Rahim Ahmed</strong></td>
                            <td>Monthly Assessment (Physics)</td>
                            <td><span class="badge">Batch A</span></td>
                            <td><strong>45</strong> / 50</td>
                            <td><span class="grade_badge">A+</span></td>
                            <td><span class="status_pass">Passed</span></td>
                            <td>
                                <button class="btn_icon edit_btn" title="Edit Marks">✏️</button>
                                <button class="btn_icon delete_btn" title="Delete">🗑️</button>
                            </td>
                        </tr>
                        <tr>
                            <td>#102</td>
                            <td><strong>Karim Khan</strong></td>
                            <td>Monthly Assessment (Physics)</td>
                            <td><span class="badge">Batch A</span></td>
                            <td><strong>38</strong> / 50</td>
                            <td><span class="grade_badge">A</span></td>
                            <td><span class="status_pass">Passed</span></td>
                            <td>
                                <button class="btn_icon edit_btn" title="Edit Marks">✏️</button>
                                <button class="btn_icon delete_btn" title="Delete">🗑️</button>
                            </td>
                        </tr>
                        <tr>
                            <td>#103</td>
                            <td><strong>Sakib Hasan</strong></td>
                            <td>Monthly Assessment (Physics)</td>
                            <td><span class="badge">Batch A</span></td>
                            <td><strong>18</strong> / 50</td>
                            <td><span class="grade_badge">F</span></td>
                            <td><span class="status_fail">Failed</span></td>
                            <td>
                                <button class="btn_icon edit_btn" title="Edit Marks">✏️</button>
                                <button class="btn_icon delete_btn" title="Delete">🗑️</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- ৩. ENTER MARKS MODAL (পপ-আপ ফর্ম) -->
    <div class="modal_overlay" id="resultModal">
        <div class="modal_box">
            <div class="modal_header">
                <h3>Enter Student Result</h3>
                <button class="close_modal" id="closeModal">&times;</button>
            </div>
            <form class="modal_form">
                <div class="form_group">
                    <label>Select Exam</label>
                    <select required>
                        <option value="">Select Exam</option>
                        <option value="Monthly Assessment">Monthly Assessment (Physics)</option>
                        <option value="Term Final Examination">Term Final Examination (Math)</option>
                    </select>
                </div>
                <div class="form_row">
                    <div class="form_group">
                        <label>Select Batch</label>
                        <select required>
                            <option value="">Select Batch</option>
                            <option value="Batch A">Batch A</option>
                            <option value="Batch B">Batch B</option>
                        </select>
                    </div>
                    <div class="form_group">
                        <label>Select Student</label>
                        <select required>
                            <option value="">Select Student</option>
                            <option value="101">Rahim Ahmed (#101)</option>
                            <option value="102">Karim Khan (#102)</option>
                            <option value="103">Sakib Hasan (#103)</option>
                        </select>
                    </div>
                </div>
                <div class="form_row">
                    <div class="form_group">
                        <label>Marks Obtained</label>
                        <input type="number" placeholder="e.g. 45" min="0" required>
                    </div>
                    <div class="form_group">
                        <label>Total Marks</label>
                        <input type="number" placeholder="50" min="1" required>
                    </div>
                </div>
                <div class="form_group">
                    <label>Remarks / Feedback (Optional)</label>
                    <input type="text" placeholder="e.g. Excellent performance in optics">
                </div>
                <div class="modal_buttons">
                    <button type="button" class="btn_cancel" id="cancelModal">Cancel</button>
                    <button type="submit" class="action_btn">Save Result</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ৪. JavaScript -->
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

        // Modal Open / Close
        const openAddModal = document.getElementById('openAddModal');
        const closeModal = document.getElementById('closeModal');
        const cancelModal = document.getElementById('cancelModal');
        const resultModal = document.getElementById('resultModal');

        openAddModal.addEventListener('click', () => resultModal.classList.add('show'));
        closeModal.addEventListener('click', () => resultModal.classList.remove('show'));
        cancelModal.addEventListener('click', () => resultModal.classList.remove('show'));
    </script>
</body>

</html>
