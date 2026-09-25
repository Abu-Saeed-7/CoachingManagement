<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exams Management - Coaching Management System</title>
    <link rel="stylesheet" href="admin_dashboard.css">
    <link rel="stylesheet" href="exams.css">
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
                <li class="active"><a href="exams.php">📝 <span>Exams</span></a></li>
                <li><a href="results.php">📊 <span>Results</span></a></li>
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
                    <h1>Exams Management</h1>
                    <p>Schedule, create, and manage upcoming and past exams for batches.</p>
                </div>
                <button class="action_btn" id="openAddModal"><span>+</span> Create New Exam</button>
            </div>

            <!-- সার্চ ও ফিল্টার বার -->
            <div class="table_controls">
                <input type="text" class="search_input" placeholder="🔍 Search exam name or subject...">
                <select class="filter_select">
                    <option value="">All Batches</option>
                    <option value="Batch A">Batch A</option>
                    <option value="Batch B">Batch B</option>
                    <option value="Batch C">Batch C</option>
                </select>
                <select class="filter_select">
                    <option value="">All Status</option>
                    <option value="Upcoming">Upcoming</option>
                    <option value="Completed">Completed</option>
                </select>
            </div>

            <!-- এক্সাম টেবিল -->
            <div class="table_container">
                <table class="custom_table">
                    <thead>
                        <tr>
                            <th>Exam ID</th>
                            <th>Exam Name</th>
                            <th>Subject</th>
                            <th>Batch</th>
                            <th>Date & Time</th>
                            <th>Total Marks</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#E01</td>
                            <td><strong>Monthly Assessment</strong></td>
                            <td>Physics</td>
                            <td><span class="badge">Batch A</span></td>
                            <td>📅 25 Aug 2026 (10:00 AM)</td>
                            <td><span class="marks_badge">50 Marks</span></td>
                            <td><span class="status_upcoming">Upcoming</span></td>
                            <td>
                                <button class="btn_icon edit_btn" title="Edit">✏️</button>
                                <button class="btn_icon delete_btn" title="Delete">🗑️</button>
                            </td>
                        </tr>
                        <tr>
                            <td>#E02</td>
                            <td><strong>Term Final Examination</strong></td>
                            <td>Mathematics</td>
                            <td><span class="badge">Batch A & B</span></td>
                            <td>📅 30 Aug 2026 (11:00 AM)</td>
                            <td><span class="marks_badge">100 Marks</span></td>
                            <td><span class="status_upcoming">Upcoming</span></td>
                            <td>
                                <button class="btn_icon edit_btn" title="Edit">✏️</button>
                                <button class="btn_icon delete_btn" title="Delete">🗑️</button>
                            </td>
                        </tr>
                        <tr>
                            <td>#E03</td>
                            <td><strong>Quiz 01 (Periodic Table)</strong></td>
                            <td>Chemistry</td>
                            <td><span class="badge">Batch B</span></td>
                            <td>📅 15 Aug 2026 (04:00 PM)</td>
                            <td><span class="marks_badge">25 Marks</span></td>
                            <td><span class="status_completed">Completed</span></td>
                            <td>
                                <button class="btn_icon edit_btn" title="Edit">✏️</button>
                                <button class="btn_icon delete_btn" title="Delete">🗑️</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- ৩. CREATE EXAM MODAL (পপ-আপ ফর্ম) -->
    <div class="modal_overlay" id="examModal">
        <div class="modal_box">
            <div class="modal_header">
                <h3>Schedule New Exam</h3>
                <button class="close_modal" id="closeModal">&times;</button>
            </div>
            <form class="modal_form">
                <div class="form_group">
                    <label>Exam Title / Name</label>
                    <input type="text" placeholder="e.g. Monthly Physics Assessment" required>
                </div>
                <div class="form_row">
                    <div class="form_group">
                        <label>Subject</label>
                        <select required>
                            <option value="">Select Subject</option>
                            <option value="Physics">Physics</option>
                            <option value="Mathematics">Mathematics</option>
                            <option value="Chemistry">Chemistry</option>
                            <option value="English">English</option>
                        </select>
                    </div>
                    <div class="form_group">
                        <label>Target Batch</label>
                        <select required>
                            <option value="">Select Batch</option>
                            <option value="Batch A">Batch A</option>
                            <option value="Batch B">Batch B</option>
                            <option value="Batch C">Batch C</option>
                        </select>
                    </div>
                </div>
                <div class="form_row">
                    <div class="form_group">
                        <label>Exam Date</label>
                        <input type="date" required>
                    </div>
                    <div class="form_group">
                        <label>Start Time</label>
                        <input type="time" required>
                    </div>
                </div>
                <div class="form_row">
                    <div class="form_group">
                        <label>Total Marks</label>
                        <input type="number" placeholder="100" min="1" required>
                    </div>
                    <div class="form_group">
                        <label>Passing Marks</label>
                        <input type="number" placeholder="40" min="1" required>
                    </div>
                </div>
                <div class="modal_buttons">
                    <button type="button" class="btn_cancel" id="cancelModal">Cancel</button>
                    <button type="submit" class="action_btn">Save & Schedule</button>
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
        const examModal = document.getElementById('examModal');

        openAddModal.addEventListener('click', () => examModal.classList.add('show'));
        closeModal.addEventListener('click', () => examModal.classList.remove('show'));
        cancelModal.addEventListener('click', () => examModal.classList.remove('show'));
    </script>
</body>

</html>
