<?php
require_once __DIR__ . '/../../../backend/middleware/auth.php';
require_once __DIR__ . '/../../../backend/controllers/ResultController.php';
require_once __DIR__ . '/../../../backend/controllers/BatchController.php';

checkAuth(['admin']);

$resultController = new ResultController();
$batchController = new BatchController();
$postResult = $resultController->handleAddResult();
$message = $postResult['message'];
$messageType = $postResult['messageType'];

$exams = $resultController->getExamsDropdown();
$students = $resultController->getStudentsDropdown();
$batches = $batchController->getAllBatches();
$results = $resultController->getAllResults();
?>
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

            <!-- সার্চ ও ফিল্টার বার -->
            <div class="table_controls">
                <input type="text" id="searchInput" class="search_input" placeholder="🔍 Search student name or roll...">
                <select id="batchFilter" class="filter_select">
                    <option value="">All Batches</option>
                    <?php foreach ($batches as $batch): ?>
                        <option value="<?= htmlspecialchars($batch['name'], ENT_QUOTES, 'UTF-8'); ?>"><?= htmlspecialchars($batch['name'], ENT_QUOTES, 'UTF-8'); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <?php if ($message !== ''): ?>
                <p class="form_message <?= $messageType; ?>"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endif; ?>

            <!-- রেজাল্ট টেবিল -->
            <div class="table_container">
                <table class="custom_table" id="resultsTable">
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
                        <?php if (empty($results)): ?>
                            <tr>
                                <td colspan="8" style="text-align: center; color: #6b7280; padding: 24px;">No results recorded yet. Click "Enter New Result" above to add one.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($results as $res): ?>
                                <tr>
                                    <td>#<?= (int) $res['student_id']; ?> (Roll: <?= htmlspecialchars($res['roll'], ENT_QUOTES, 'UTF-8'); ?>)</td>
                                    <td><strong><?= htmlspecialchars($res['student_name'], ENT_QUOTES, 'UTF-8'); ?></strong></td>
                                    <td><?= htmlspecialchars($res['exam_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><span class="badge"><?= htmlspecialchars($res['batch_name'], ENT_QUOTES, 'UTF-8'); ?></span></td>
                                    <td><?= htmlspecialchars(number_format((float) $res['marks'], 1), ENT_QUOTES, 'UTF-8'); ?> / <?= htmlspecialchars(number_format((float) $res['total_marks'], 0), ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><strong><?= htmlspecialchars($res['grade'], ENT_QUOTES, 'UTF-8'); ?></strong></td>
                                    <td>
                                        <span class="<?= $res['status'] === 'Passed' ? 'status_active' : 'status_inactive'; ?>">
                                            <?= htmlspecialchars($res['status'], ENT_QUOTES, 'UTF-8'); ?>
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

    <!-- ৩. ENTER MARKS MODAL (পপ-আপ ফর্ম) -->
    <div class="modal_overlay" id="resultModal">
        <div class="modal_box">
            <div class="modal_header">
                <h3>Enter Student Result</h3>
                <button class="close_modal" id="closeModal">&times;</button>
            </div>
            <form class="modal_form" method="post">
                <div class="form_group">
                    <label>Select Exam</label>
                    <select name="exam_id" required>
                        <option value="">Select Exam</option>
                        <?php foreach ($exams as $exam): ?>
                            <option value="<?= (int) $exam['id']; ?>" <?= ((int)($_POST['exam_id'] ?? 0) === (int)$exam['id']) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($exam['name'] . ' (' . $exam['batch_name'] . ' - ' . (int)$exam['total_marks'] . ' marks)', ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form_group">
                    <label>Select Student</label>
                    <select name="student_id" required>
                        <option value="">Select Student</option>
                        <?php foreach ($students as $student): ?>
                            <option value="<?= (int) $student['id']; ?>" <?= ((int)($_POST['student_id'] ?? 0) === (int)$student['id']) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($student['name'] . ' (Roll: ' . $student['roll'] . ')', ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form_group">
                    <label>Marks Obtained</label>
                    <input type="number" name="marks" value="<?= htmlspecialchars($_POST['marks'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="e.g. 85" min="0" max="1000" step="0.5" required>
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
        const resultModal = document.getElementById('resultModal');

        openAddModal.addEventListener('click', () => resultModal.classList.add('show'));
        closeModal.addEventListener('click', () => resultModal.classList.remove('show'));
        cancelModal.addEventListener('click', () => resultModal.classList.remove('show'));
        resultModal.addEventListener('click', (e) => {
            if (e.target === resultModal) resultModal.classList.remove('show');
        });

        <?php if ($messageType === 'error'): ?>
        resultModal.classList.add('show');
        <?php endif; ?>

        // Search & Filter
        const searchInput = document.getElementById('searchInput');
        const batchFilter = document.getElementById('batchFilter');

        function filterTable() {
            const term = searchInput.value.toLowerCase();
            const batch = batchFilter.value.toLowerCase();
            const rows = document.querySelectorAll('#resultsTable tbody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const matchesSearch = text.includes(term);
                const matchesBatch = !batch || text.includes(batch);
                row.style.display = (matchesSearch && matchesBatch) ? '' : 'none';
            });
        }

        if (searchInput) searchInput.addEventListener('keyup', filterTable);
        if (batchFilter) batchFilter.addEventListener('change', filterTable);
    </script>
</body>

</html>
