<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Batches Management - Coaching Management System</title>
    <link rel="stylesheet" href="admin_dashboard.css">
    <link rel="stylesheet" href="batches.css">
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
                <li class="active"><a href="batches.php">📚 <span>Batches</span></a></li>
                <li><a href="exams.php">📝 <span>Exams</span></a></li>
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
                    <h1>Batches Management</h1>
                    <p>Manage and organize coaching batches, timings and assign teachers.</p>
                </div>
                <button class="action_btn" id="openAddModal"><span>+</span> Create New Batch</button>
            </div>

            <!-- সার্চ ও ফিল্টার বার -->
            <div class="table_controls">
                <input type="text" class="search_input" placeholder="🔍 Search batch name or teacher...">
                <select class="filter_select">
                    <option value="">All Status</option>
                    <option value="Active">Active</option>
                    <option value="Upcoming">Upcoming</option>
                    <option value="Completed">Completed</option>
                </select>
            </div>

            <!-- ব্যাচ টেবিল -->
            <div class="table_container">
                <table class="custom_table">
                    <thead>
                        <tr>
                            <th>Batch ID</th>
                            <th>Batch Name</th>
                            <th>Assigned Teachers</th>
                            <th>Schedule / Timing</th>
                            <th>Students</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#B01</td>
                            <td><strong>Batch A (HSC 2026)</strong></td>
                            <td>Prof. Abdul Karim, Dr. Nusrat</td>
                            <td>Mon, Wed, Fri (4:00 PM - 6:00 PM)</td>
                            <td><span class="student_count_badge">👥 45 Students</span></td>
                            <td><span class="status_active">Active</span></td>
                            <td>
                                <button class="btn_icon edit_btn" title="Edit">✏️</button>
                                <button class="btn_icon delete_btn" title="Delete">🗑️</button>
                            </td>
                        </tr>
                        <tr>
                            <td>#B02</td>
                            <td><strong>Batch B (SSC 2026)</strong></td>
                            <td>Mohammad Ali</td>
                            <td>Sun, Tue, Thu (5:30 PM - 7:30 PM)</td>
                            <td><span class="student_count_badge">👥 38 Students</span></td>
                            <td><span class="status_active">Active</span></td>
                            <td>
                                <button class="btn_icon edit_btn" title="Edit">✏️</button>
                                <button class="btn_icon delete_btn" title="Delete">🗑️</button>
                            </td>
                        </tr>
                        <tr>
                            <td>#B03</td>
                            <td><strong>Batch C (Special Math)</strong></td>
                            <td>Prof. Abdul Karim</td>
                            <td>Saturday (10:00 AM - 1:00 PM)</td>
                            <td><span class="student_count_badge">👥 25 Students</span></td>
                            <td><span class="status_active">Active</span></td>
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

    <!-- ৩. CREATE BATCH MODAL (পপ-আপ ফর্ম) -->
    <div class="modal_overlay" id="batchModal">
        <div class="modal_box">
            <div class="modal_header">
                <h3>Create New Batch</h3>
                <button class="close_modal" id="closeModal">&times;</button>
            </div>
            <form class="modal_form">
                <div class="form_group">
                    <label>Batch Name</label>
                    <input type="text" placeholder="e.g. Batch A (HSC 2026)" required>
                </div>
                <div class="form_group">
                    <label>Assign Primary Teacher</label>
                    <select required>
                        <option value="">Select Instructor</option>
                        <option value="Prof. Abdul Karim">Prof. Abdul Karim (Mathematics)</option>
                        <option value="Dr. Nusrat Jahan">Dr. Nusrat Jahan (Physics)</option>
                        <option value="Mohammad Ali">Mohammad Ali (Chemistry)</option>
                    </select>
                </div>
                <div class="form_group">
                    <label>Class Schedule / Days & Time</label>
                    <input type="text" placeholder="e.g. Mon, Wed, Fri (4:00 PM - 6:00 PM)" required>
                </div>
                <div class="form_group">
                    <label>Max Student Capacity</label>
                    <input type="number" placeholder="e.g. 50" min="1" required>
                </div>
                <div class="form_group">
                    <label>Status</label>
                    <select required>
                        <option value="Active">Active</option>
                        <option value="Upcoming">Upcoming</option>
                    </select>
                </div>
                <div class="modal_buttons">
                    <button type="button" class="btn_cancel" id="cancelModal">Cancel</button>
                    <button type="submit" class="action_btn">Create Batch</button>
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
        const batchModal = document.getElementById('batchModal');

        openAddModal.addEventListener('click', () => batchModal.classList.add('show'));
        closeModal.addEventListener('click', () => batchModal.classList.remove('show'));
        cancelModal.addEventListener('click', () => batchModal.classList.remove('show'));
    </script>
</body>

</html>
