<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teachers Management - Coaching Management System</title>
    <link rel="stylesheet" href="admin_dashboard.css">
    <link rel="stylesheet" href="teachers.css">
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
                <li class="active"><a href="teachers.php">👨‍🏫 <span>Teachers</span></a></li>
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
                    <h1>Teachers Management</h1>
                    <p>Manage and view all registered teachers and instructors.</p>
                </div>
                <button class="action_btn" id="openAddModal"><span>+</span> Add New Teacher</button>
            </div>

            <!-- সার্চ ও ফিল্টার বার -->
            <div class="table_controls">
                <input type="text" class="search_input" placeholder="🔍 Search by name, subject or phone...">
                <select class="filter_select">
                    <option value="">All Subjects</option>
                    <option value="Mathematics">Mathematics</option>
                    <option value="Physics">Physics</option>
                    <option value="Chemistry">Chemistry</option>
                    <option value="English">English</option>
                </select>
            </div>

            <!-- টিচার টেবিল -->
            <div class="table_container">
                <table class="custom_table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Teacher Name</th>
                            <th>Subject</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Assigned Batches</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#T01</td>
                            <td><strong>Prof. Abdul Karim</strong></td>
                            <td><span class="subject_badge">Mathematics</span></td>
                            <td>karim.teacher@gmail.com</td>
                            <td>01711-223344</td>
                            <td>Batch A, Batch B</td>
                            <td>
                                <button class="btn_icon edit_btn" title="Edit">✏️</button>
                                <button class="btn_icon delete_btn" title="Delete">🗑️</button>
                            </td>
                        </tr>
                        <tr>
                            <td>#T02</td>
                            <td><strong>Dr. Nusrat Jahan</strong></td>
                            <td><span class="subject_badge">Physics</span></td>
                            <td>nusrat.phy@gmail.com</td>
                            <td>01822-556677</td>
                            <td>Batch A</td>
                            <td>
                                <button class="btn_icon edit_btn" title="Edit">✏️</button>
                                <button class="btn_icon delete_btn" title="Delete">🗑️</button>
                            </td>
                        </tr>
                        <tr>
                            <td>#T03</td>
                            <td><strong>Mohammad Ali</strong></td>
                            <td><span class="subject_badge">Chemistry</span></td>
                            <td>ali.chem@gmail.com</td>
                            <td>01933-889900</td>
                            <td>Batch B, Batch C</td>
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

    <!-- ৩. ADD TEACHER MODAL (পপ-আপ ফর্ম) -->
    <div class="modal_overlay" id="teacherModal">
        <div class="modal_box">
            <div class="modal_header">
                <h3>Add New Teacher</h3>
                <button class="close_modal" id="closeModal">&times;</button>
            </div>
            <form class="modal_form">
                <div class="form_group">
                    <label>Full Name</label>
                    <input type="text" placeholder="e.g. Prof. Abdul Karim" required>
                </div>
                <div class="form_group">
                    <label>Subject Specialization</label>
                    <select required>
                        <option value="">Select primary subject</option>
                        <option value="Mathematics">Mathematics</option>
                        <option value="Physics">Physics</option>
                        <option value="Chemistry">Chemistry</option>
                        <option value="English">English</option>
                    </select>
                </div>
                <div class="form_group">
                    <label>Email Address</label>
                    <input type="email" placeholder="teacher@example.com" required>
                </div>
                <div class="form_group">
                    <label>Phone Number</label>
                    <input type="tel" placeholder="017XXXXXXXX" required>
                </div>
                <div class="form_group">
                    <label>Assign Batches</label>
                    <input type="text" placeholder="e.g. Batch A, Batch B">
                </div>
                <div class="form_group">
                    <label>Password</label>
                    <input type="password" placeholder="Create login password" required>
                </div>
                <div class="modal_buttons">
                    <button type="button" class="btn_cancel" id="cancelModal">Cancel</button>
                    <button type="submit" class="action_btn">Save Teacher</button>
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
        const teacherModal = document.getElementById('teacherModal');

        openAddModal.addEventListener('click', () => teacherModal.classList.add('show'));
        closeModal.addEventListener('click', () => teacherModal.classList.remove('show'));
        cancelModal.addEventListener('click', () => teacherModal.classList.remove('show'));
    </script>
</body>

</html>
