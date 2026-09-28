<?php
require_once __DIR__ . '/../../../backend/middleware/auth.php';
require_once __DIR__ . '/../../../backend/controllers/FeeController.php';

checkAuth(['admin']);

$feeController = new FeeController();
$postResult = $feeController->handleAddFee();
$message = $postResult['message'];
$messageType = $postResult['messageType'];

$stats = $feeController->getFeeStats();
$totalCollected = $stats['totalCollected'];
$pendingDues = $stats['pendingDues'];
$totalInvoices = $stats['totalInvoices'];

$students = $feeController->getStudentsDropdown();
$fees = $feeController->getAllFees();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fee Management - Coaching Management System</title>
    <link rel="stylesheet" href="admin_dashboard.css">
    <link rel="stylesheet" href="fees.css">
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
                <li><a href="results.php">📊 <span>Results</span></a></li>
                <li class="active"><a href="fees.php">💰 <span>Fees</span></a></li>
                <li><a href="admin_profile.php">⚙️ <span>Profile</span></a></li>
                <li class="logout"><a href="../login_page/loginPage.php">🚪 <span>Logout</span></a></li>
            </ul>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="main_content">
            <!-- হেডার ও অ্যাকশন বাটন -->
            <div class="page_header">
                <div>
                    <h1>Fee Management</h1>
                    <p>Track student tuition fee payments, pending dues, and invoices.</p>
                </div>
                <button class="action_btn" id="openAddModal"><span>+</span> Collect / Add Fee</button>
            </div>

            <!-- MINI STATS SUMMARY ROW -->
            <div class="fee_stats_row">
                <div class="fee_stat_box">
                    <h4>Total Collected</h4>
                    <p class="amount collected">৳ <?= number_format($totalCollected, 0); ?></p>
                </div>
                <div class="fee_stat_box">
                    <h4>Pending Dues (Unpaid)</h4>
                    <p class="amount pending">৳ <?= number_format($pendingDues, 0); ?></p>
                </div>
                <div class="fee_stat_box">
                    <h4>Total Fee Records</h4>
                    <p class="amount"><?= $totalInvoices; ?> Invoices</p>
                </div>
            </div>

            <!-- সার্চ ও ফিল্টার বার -->
            <div class="table_controls">
                <input type="text" id="searchInput" class="search_input" placeholder="🔍 Search student name or roll...">
                <select id="statusFilter" class="filter_select">
                    <option value="">All Payment Status</option>
                    <option value="Paid">Paid</option>
                    <option value="Unpaid">Unpaid</option>
                    <option value="Partial">Partial</option>
                </select>
            </div>

            <?php if ($message !== ''): ?>
                <p class="form_message <?= $messageType; ?>"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endif; ?>

            <!-- ফিস টেবিল -->
            <div class="table_container">
                <table class="custom_table" id="feesTable">
                    <thead>
                        <tr>
                            <th>Invoice #</th>
                            <th>Student Name</th>
                            <th>Batch</th>
                            <th>Billing Month</th>
                            <th>Amount</th>
                            <th>Payment Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($fees)): ?>
                            <tr>
                                <td colspan="8" style="text-align: center; color: #6b7280; padding: 24px;">No fee records found. Click "Collect / Add Fee" above to create an invoice.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($fees as $fee): ?>
                                <tr>
                                    <td>#INV-<?= str_pad((string)$fee['id'], 4, '0', STR_PAD_LEFT); ?></td>
                                    <td><strong><?= htmlspecialchars($fee['student_name'], ENT_QUOTES, 'UTF-8'); ?></strong> (Roll: <?= htmlspecialchars($fee['roll'], ENT_QUOTES, 'UTF-8'); ?>)</td>
                                    <td><span class="badge"><?= htmlspecialchars($fee['batch_name'], ENT_QUOTES, 'UTF-8'); ?></span></td>
                                    <td><?= htmlspecialchars(date('F Y', strtotime($fee['month'])), ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><strong>৳ <?= number_format((float) $fee['amount'], 0); ?></strong></td>
                                    <td><?= htmlspecialchars($fee['payment_date'] ? date('d M, Y', strtotime($fee['payment_date'])) : '—', ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td>
                                        <span class="<?= $fee['status'] === 'paid' ? 'status_active' : 'status_inactive'; ?>">
                                            <?= htmlspecialchars(ucfirst($fee['status']), ENT_QUOTES, 'UTF-8'); ?>
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

    <!-- ৩. COLLECT / ADD FEE MODAL (পপ-আপ ফর্ম) -->
    <div class="modal_overlay" id="feeModal">
        <div class="modal_box">
            <div class="modal_header">
                <h3>Collect / Record Student Fee</h3>
                <button class="close_modal" id="closeModal">&times;</button>
            </div>
            <form class="modal_form" method="post">
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
                <div class="form_row">
                    <div class="form_group">
                        <label>Fee Month</label>
                        <input type="month" name="month" value="<?= htmlspecialchars($_POST['month'] ?? date('Y-m'), ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                    <div class="form_group">
                        <label>Fee Amount (BDT)</label>
                        <input type="number" name="amount" value="<?= htmlspecialchars($_POST['amount'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="e.g. 3000" min="1" max="500000" step="any" required>
                    </div>
                </div>
                <div class="form_row">
                    <div class="form_group">
                        <label>Payment Status</label>
                        <select name="status" required>
                            <option value="paid" <?= (($_POST['status'] ?? 'paid') === 'paid') ? 'selected' : ''; ?>>Paid</option>
                            <option value="unpaid" <?= (($_POST['status'] ?? '') === 'unpaid') ? 'selected' : ''; ?>>Unpaid / Due</option>
                            <option value="partial" <?= (($_POST['status'] ?? '') === 'partial') ? 'selected' : ''; ?>>Partial</option>
                        </select>
                    </div>
                    <div class="form_group">
                        <label>Payment Date</label>
                        <input type="date" name="payment_date" value="<?= htmlspecialchars($_POST['payment_date'] ?? date('Y-m-d'), ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                </div>
                <div class="modal_buttons">
                    <button type="button" class="btn_cancel" id="cancelModal">Cancel</button>
                    <button type="submit" class="action_btn">Save Receipt</button>
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
        const feeModal = document.getElementById('feeModal');

        openAddModal.addEventListener('click', () => feeModal.classList.add('show'));
        closeModal.addEventListener('click', () => feeModal.classList.remove('show'));
        cancelModal.addEventListener('click', () => feeModal.classList.remove('show'));
        feeModal.addEventListener('click', (e) => {
            if (e.target === feeModal) feeModal.classList.remove('show');
        });

        <?php if ($messageType === 'error'): ?>
        feeModal.classList.add('show');
        <?php endif; ?>

        // Search & Filter
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');

        function filterTable() {
            const term = searchInput.value.toLowerCase();
            const status = statusFilter.value.toLowerCase();
            const rows = document.querySelectorAll('#feesTable tbody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const matchesSearch = text.includes(term);
                const matchesStatus = !status || text.includes(status);
                row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
            });
        }

        if (searchInput) searchInput.addEventListener('keyup', filterTable);
        if (statusFilter) statusFilter.addEventListener('change', filterTable);
    </script>
</body>

</html>
