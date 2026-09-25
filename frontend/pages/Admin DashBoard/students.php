<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students Management - Coaching Management System</title>
    <link rel="stylesheet" href="admin_dashboard.css">
    <link rel="stylesheet" href="students.css">
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
                <li class="active"><a href="students.php">🎓 <span>Students</span></a></li>
                <li><a href="teachers.php">👨‍🏫 <span>Teachers</span></a></li>
                <li><a href="batches.php">📚 <span>Batches</span></a></li>
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
                    <h1>Students Management</h1>
                    <p>Manage and view all enrolled students.</p>
                </div>
                <button class="action_btn" id="openAddModal"><span>+</span> Add New Student</button>
            </div>

            <!-- সার্চ ও ফিল্টার বার -->
            <div class="table_controls">
                <input type="text" class="search_input" placeholder="🔍 Search by name, roll or phone...">
                <select class="filter_select">
                    <option value="">All Batches</option>
                    <option value="Batch A">Batch A</option>
                    <option value="Batch B">Batch B</option>
                    <option value="Batch C">Batch C</option>
                </select>
            </div>

            <!-- স্টুডেন্ট টেবিল -->
            <div class="table_container">
                <table class="custom_table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Student Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Batch</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#101</td>
                            <td><strong>Rahim Ahmed</strong></td>
                            <td>rahim@gmail.com</td>
                            <td>01711-XXXXXX</td>
                            <td><span class="badge">Batch A</span></td>
                            <td><span class="status_active">Active</span></td>
                            <td>
                                <button class="btn_icon edit_btn" title="Edit">✏️</button>
                                <button class="btn_icon delete_btn" title="Delete">🗑️</button>
                            </td>
                        </tr>
                        <tr>
                            <td>#102</td>
                            <td><strong>Karim Khan</strong></td>
                            <td>karim@gmail.com</td>
                            <td>01822-XXXXXX</td>
                            <td><span class="badge">Batch B</span></td>
                            <td><span class="status_active">Active</span></td>
                            <td>
                                <button class="btn_icon edit_btn" title="Edit">✏️</button>
                                <button class="btn_icon delete_btn" title="Delete">🗑️</button>
                            </td>
                        </tr>
                        <tr>
                            <td>#103</td>
                            <td><strong>Sakib Hasan</strong></td>
                            <td>sakib@gmail.com</td>
                            <td>01933-XXXXXX</td>
                            <td><span class="badge">Batch A</span></td>
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

    <!-- ৩. ADD STUDENT MODAL (পপ-আপ ফর্ম) -->
    <div class="modal_overlay" id="studentModal">
        <div class="modal_box">
            <div class="modal_header">
                <h3>Add New Student</h3>
                <button class="close_modal" id="closeModal">&times;</button>
            </div>
            <form class="modal_form">
                <div class="form_group">
                    <label>Full Name</label>
                    <input type="text" placeholder="e.g. Rahim Ahmed" required>
                </div>
                <div class="form_group">
                    <label>Email Address</label>
                    <input type="email" placeholder="name@example.com" required>
                </div>
                <div class="form_group">
                    <label>Phone Number</label>
                    <input type="tel" placeholder="017XXXXXXXX" required>
                </div>
                <div class="form_group">
                    <label>Select Batch</label>
                    <select required>
                        <option value="">Select a batch</option>
                        <option value="Batch A">Batch A</option>
                        <option value="Batch B">Batch B</option>
                    </select>
                </div>
                <div class="form_group">
                    <label>Password</label>
                    <input type="password" placeholder="Create password" required>
                </div>
                <div class="modal_buttons">
                    <button type="button" class="btn_cancel" id="cancelModal">Cancel</button>
                    <button type="submit" class="action_btn">Save Student</button>
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
        const studentModal = document.getElementById('studentModal');

        openAddModal.addEventListener('click', () => studentModal.classList.add('show'));
        closeModal.addEventListener('click', () => studentModal.classList.remove('show'));
        cancelModal.addEventListener('click', () => studentModal.classList.remove('show'));
    </script>
</body>

</html>