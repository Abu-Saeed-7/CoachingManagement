<?php
require_once __DIR__ . '/../../../backend/middleware/auth.php';
require_once __DIR__ . '/../../../backend/controllers/ExamController.php';

checkAuth(['admin']);

$examController = new ExamController();
$postResult = $examController->handleAddExam();
$message = $postResult['message'];
$messageType = $postResult['messageType'];

$batches = $examController->getActiveBatches();
$subjects = $examController->getActiveSubjects();
$exams = $examController->getAllExams();
?>
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
                <input type="text" id="searchInput" class="search_input" placeholder="🔍 Search exam name or subject...">
                <select id="batchFilter" class="filter_select">
                    <option value="">All Batches</option>
                    <?php foreach ($batches as $batch): ?>
                        <option value="<?= htmlspecialchars($batch['name'], ENT_QUOTES, 'UTF-8'); ?>"><?= htmlspecialchars($batch['name'], ENT_QUOTES, 'UTF-8'); ?></option>
                    <?php endforeach; ?>
                </select>
                <select id="statusFilter" class="filter_select">
                    <option value="">All Status</option>
                    <option value="Upcoming">Upcoming</option>
                    <option value="Completed">Completed</option>
                </select>
            </div>

            <?php if ($message !== ''): ?>
                <p class="form_message <?= $messageType; ?>"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endif; ?>

            <!-- এক্সাম টেবিল -->
            <div class="table_container">
                <table class="custom_table" id="examsTable">
                    <thead>
                        <tr>
                            <th>Exam ID</th>
                            <th>Exam Name</th>
                            <th>Subject</th>
                            <th>Batch</th>
                            <th>Date</th>
                            <th>Total Marks</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($exams)): ?>
                            <tr>
                                <td colspan="8" style="text-align: center; color: #6b7280; padding: 24px;">No exams scheduled yet. Click "Create New Exam" above to schedule one.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($exams as $exam): ?>
                                <tr>
                                    <td>#E<?= (int) $exam['id']; ?></td>
                                    <td><strong><?= htmlspecialchars($exam['name'], ENT_QUOTES, 'UTF-8'); ?></strong></td>
                                    <td><?= htmlspecialchars($exam['subject_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><span class="badge"><?= htmlspecialchars($exam['batch_name'], ENT_QUOTES, 'UTF-8'); ?></span></td>
                                    <td><?= htmlspecialchars($exam['exam_date'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?= htmlspecialchars(number_format((float) $exam['total_marks'], 0), ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td>
                                        <span class="<?= $exam['status'] === 'Upcoming' ? 'status_active' : 'status_inactive'; ?>">
                                            <?= htmlspecialchars($exam['status'], ENT_QUOTES, 'UTF-8'); ?>
                                        </span>
                                    </td>
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

    <!-- ৩. CREATE EXAM MODAL (পপ-আপ ফর্ম) -->
    <div class="modal_overlay" id="examModal">
        <div class="modal_box">
            <div class="modal_header">
                <h3>Schedule New Exam</h3>
                <button class="close_modal" id="closeModal">&times;</button>
            </div>
            <form class="modal_form" method="post">
                <div class="form_group">
                    <label>Exam Title / Name</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="e.g. Monthly Physics Assessment" minlength="2" maxlength="100" required>
                </div>
                <div class="form_row">
                    <div class="form_group">
                        <label>Subject</label>
                        <select name="subject_id" required>
                            <option value="">Select Subject</option>
                            <?php foreach ($subjects as $subject): ?>
                                <option value="<?= (int) $subject['id']; ?>" <?= ((int)($_POST['subject_id'] ?? 0) === (int)$subject['id']) ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($subject['name'], ENT_QUOTES, 'UTF-8'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form_group">
                        <label>Target Batch</label>
                        <select name="batch_id" required>
                            <option value="">Select Batch</option>
                            <?php foreach ($batches as $batch): ?>
                                <option value="<?= (int) $batch['id']; ?>" <?= ((int)($_POST['batch_id'] ?? 0) === (int)$batch['id']) ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($batch['name'], ENT_QUOTES, 'UTF-8'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form_row">
                    <div class="form_group">
                        <label>Exam Date</label>
                        <input type="date" name="exam_date" value="<?= htmlspecialchars($_POST['exam_date'] ?? date('Y-m-d'), ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                    <div class="form_group">
                        <label>Total Marks</label>
                        <input type="number" name="total_marks" value="<?= htmlspecialchars($_POST['total_marks'] ?? '100', ENT_QUOTES, 'UTF-8'); ?>" placeholder="100" min="1" max="1000" step="0.5" required>
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
        const examModal = document.getElementById('examModal');

        openAddModal.addEventListener('click', () => examModal.classList.add('show'));
        closeModal.addEventListener('click', () => examModal.classList.remove('show'));
        cancelModal.addEventListener('click', () => examModal.classList.remove('show'));
        examModal.addEventListener('click', (e) => {
            if (e.target === examModal) examModal.classList.remove('show');
        });

        <?php if ($messageType === 'error'): ?>
        examModal.classList.add('show');
        <?php endif; ?>

        // Table Search & Filter
        const searchInput = document.getElementById('searchInput');
        const batchFilter = document.getElementById('batchFilter');
        const statusFilter = document.getElementById('statusFilter');

        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase();
            const selectedBatch = batchFilter.value.toLowerCase();
            const selectedStatus = statusFilter.value.toLowerCase();
            const rows = document.querySelectorAll('#examsTable tbody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const matchesSearch = text.includes(searchTerm);
                const matchesBatch = !selectedBatch || text.includes(selectedBatch);
                const matchesStatus = !selectedStatus || text.includes(selectedStatus);
                row.style.display = (matchesSearch && matchesBatch && matchesStatus) ? '' : 'none';
            });
        }

        if (searchInput) searchInput.addEventListener('keyup', filterTable);
        if (batchFilter) batchFilter.addEventListener('change', filterTable);
        if (statusFilter) statusFilter.addEventListener('change', filterTable);
    </script>
</body>

</html>
