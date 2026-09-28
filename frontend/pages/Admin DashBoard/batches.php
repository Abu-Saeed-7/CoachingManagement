<?php
require_once __DIR__ . '/../../../backend/middleware/auth.php';
require_once __DIR__ . '/../../../backend/controllers/BatchController.php';

checkAuth(['admin']);

$batchController = new BatchController();
$postResult = $batchController->handleAddBatch();
$message = $postResult['message'];
$messageType = $postResult['messageType'];

$teachers = $batchController->getActiveTeachers();
$batches = $batchController->getAllBatches();
?>
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
                    <option value="Inactive">Inactive</option>
                    <option value="Completed">Completed</option>
                </select>
            </div>

            <?php if ($message !== ''): ?>
                <p class="form_message <?= $messageType; ?>"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endif; ?>

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
                        <?php foreach ($batches as $batch): ?>
                            <tr>
                                <td>#B<?= (int) $batch['id']; ?></td>
                                <td><strong><?= htmlspecialchars($batch['name'], ENT_QUOTES, 'UTF-8'); ?></strong></td>
                                <td><?= htmlspecialchars($batch['teacher_names'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?= htmlspecialchars($batch['start_date'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><span class="student_count_badge">👥 <?= (int) $batch['student_count']; ?> Students</span></td>
                                <td><span class="status_active"><?= htmlspecialchars(ucfirst($batch['status']), ENT_QUOTES, 'UTF-8'); ?></span></td>
                                <td>
                                    <button class="btn_icon edit_btn" title="Edit" type="button">✏️</button>
                                    <button class="btn_icon delete_btn" title="Delete" type="button">🗑️</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
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
            <form class="modal_form" method="post">
                <div class="form_group">
                    <label>Batch Name</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="e.g. Batch A (HSC 2026)" minlength="2" maxlength="100" required>
                </div>
                <div class="form_group">
                    <label>Assign Teacher</label>
                    <select name="teacher_id">
                        <option value="0">No teacher yet</option>
                        <?php foreach ($teachers as $teacher): ?>
                            <option value="<?= (int) $teacher['id']; ?>" <?= (isset($_POST['teacher_id']) && (int)$_POST['teacher_id'] === (int)$teacher['id']) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($teacher['name'], ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form_group">
                    <label>Start Date</label>
                    <input type="date" name="start_date" value="<?= htmlspecialchars($_POST['start_date'] ?? date('Y-m-d'), ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>
                <div class="form_group">
                    <label>Status</label>
                    <select name="status" required>
                        <option value="active" <?= (($_POST['status'] ?? 'active') === 'active') ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?= (($_POST['status'] ?? '') === 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                        <option value="completed" <?= (($_POST['status'] ?? '') === 'completed') ? 'selected' : ''; ?>>Completed</option>
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
        const batchModal = document.getElementById('batchModal');

        openAddModal.addEventListener('click', () => batchModal.classList.add('show'));
        closeModal.addEventListener('click', () => batchModal.classList.remove('show'));
        cancelModal.addEventListener('click', () => batchModal.classList.remove('show'));
        batchModal.addEventListener('click', (e) => {
            if (e.target === batchModal) batchModal.classList.remove('show');
        });

        <?php if ($messageType === 'error'): ?>
        batchModal.classList.add('show');
        <?php endif; ?>
    </script>
</body>

</html>
