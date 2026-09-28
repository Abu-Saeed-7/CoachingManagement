<?php
require_once __DIR__ . '/../../../backend/middleware/auth.php';
require_once __DIR__ . '/../../../backend/controllers/TeacherController.php';

checkAuth(['admin']);

$teacherController = new TeacherController();
$postResult = $teacherController->handleAddTeacher();
$message = $postResult['message'];
$messageType = $postResult['messageType'];

$teachers = $teacherController->getAllTeachers();
?>
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
                <input type="text" id="searchInput" class="search_input" placeholder="🔍 Search by name, subject or phone...">
            </div>

            <!-- টিচার টেবিল -->
            <?php if (!empty($errors)): ?>
                <div class="form_message error" style="background:#fee2e2; border-left:4px solid #ef4444; color:#b91c1c; padding:12px 16px; border-radius:6px; margin-bottom:16px;">
                    <?php foreach ($errors as $err): ?>
                        <div style="margin-bottom: 4px;">⚠️ <?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8'); ?></div>
                    <?php endforeach; ?>
                </div>
            <?php elseif ($message !== ''): ?>
                <div class="form_message <?= $messageType; ?>"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></div>
            <?php endif; ?>

            <div class="table_container">
                <table class="custom_table" id="teachersTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Teacher Name</th>
                            <th>Subject Specialist</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Assigned Batches</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($teachers)): ?>
                            <tr>
                                <td colspan="7" style="text-align: center; color: #6b7280; padding: 24px;">No teachers registered yet. Click "Add New Teacher" above to add one.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($teachers as $teacher): ?>
                                <tr>
                                    <td>#T<?= (int) $teacher['id']; ?></td>
                                    <td><strong><?= htmlspecialchars($teacher['name'], ENT_QUOTES, 'UTF-8'); ?></strong></td>
                                    <td><?= htmlspecialchars($teacher['subject_specialist'] ?: 'Not assigned', ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?= htmlspecialchars($teacher['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?= htmlspecialchars($teacher['phone'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><span class="badge"><?= htmlspecialchars($teacher['assigned_batches'], ENT_QUOTES, 'UTF-8'); ?></span></td>
                                    <td>
                                        <button class="btn_icon edit_btn" title="Edit" type="button">✏️</button>
                                        <button class="btn_icon delete_btn" title="Delete" type="button">🗑️</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
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
            <form class="modal_form" method="post">
                <div class="form_row">
                    <div class="form_group">
                        <label>Full Name</label>
                        <input type="text" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="e.g. Prof. Abdul Karim" minlength="2" maxlength="100" required>
                    </div>
                    <div class="form_group">
                        <label>Email Address</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="teacher@example.com" maxlength="150" required>
                    </div>
                </div>

                <div class="form_row">
                    <div class="form_group">
                        <label>Phone Number</label>
                        <input type="tel" name="phone" value="<?= htmlspecialchars($_POST['phone'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="e.g. 017XXXXXXXX" pattern="^\+?[0-9]{10,15}$" title="10 to 15 digit phone number" required>
                    </div>
                    <div class="form_group">
                        <label>Subject Specialist (Optional)</label>
                        <input type="text" name="subject_specialist" value="<?= htmlspecialchars($_POST['subject_specialist'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="e.g. Physics, Higher Mathematics" maxlength="100">
                    </div>
                </div>

                <div class="form_row">
                    <div class="form_group">
                        <label>Gender</label>
                        <select name="gender" required>
                            <option value="">Select gender</option>
                            <option value="male" <?= ($_POST['gender'] ?? '') === 'male' ? 'selected' : ''; ?>>Male</option>
                            <option value="female" <?= ($_POST['gender'] ?? '') === 'female' ? 'selected' : ''; ?>>Female</option>
                            <option value="other" <?= ($_POST['gender'] ?? '') === 'other' ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>
                    <div class="form_group">
                        <label>Joining Date</label>
                        <input type="date" name="joining_date" value="<?= htmlspecialchars($_POST['joining_date'] ?? date('Y-m-d'), ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                </div>

                <div class="form_row">
                    <div class="form_group">
                        <label>Password</label>
                        <input type="password" name="password" placeholder="At least 8 characters" minlength="8" required>
                    </div>
                    <div class="form_group">
                        <label>Address</label>
                        <input type="text" name="address" value="<?= htmlspecialchars($_POST['address'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="Teacher address" maxlength="255">
                    </div>
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

        document.addEventListener('click', function (e) {
            if (window.innerWidth <= 768 && sidebar.classList.contains('mobile_open')) {
                if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                    sidebar.classList.remove('mobile_open');
                }
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
        teacherModal.addEventListener('click', (e) => {
            if (e.target === teacherModal) teacherModal.classList.remove('show');
        });

        <?php if ($messageType === 'error'): ?>
        teacherModal.classList.add('show');
        <?php endif; ?>

        // Quick Search Filter
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('keyup', function () {
                const term = this.value.toLowerCase();
                const rows = document.querySelectorAll('#teachersTable tbody tr');
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(term) ? '' : 'none';
                });
            });
        }
    </script>
</body>

</html>
