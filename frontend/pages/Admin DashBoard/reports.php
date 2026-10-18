<?php
require_once __DIR__ . '/../../../backend/middleware/auth.php';
require_once __DIR__ . '/../../../backend/controllers/ReportController.php';

checkAuth(['admin']);

$reportController = new ReportController();

// Filter parameters
$currentTab = $_GET['tab'] ?? 'financial';
if (!in_array($currentTab, ['financial', 'attendance', 'exams', 'operations'], true)) {
    $currentTab = 'financial';
}

$filters = [
    'batch_id' => !empty($_GET['batch_id']) ? (int) $_GET['batch_id'] : null,
    'month' => !empty($_GET['month']) ? trim($_GET['month']) : null,
    'status' => !empty($_GET['status']) ? trim($_GET['status']) : null,
    'from_date' => !empty($_GET['from_date']) ? trim($_GET['from_date']) : null,
    'to_date' => !empty($_GET['to_date']) ? trim($_GET['to_date']) : null,
    'subject_id' => !empty($_GET['subject_id']) ? (int) $_GET['subject_id'] : null,
];

$filterOptions = $reportController->getFilterOptions();
$finData = $reportController->getFinancialReport($filters);
$attData = $reportController->getAttendanceReport($filters);
$examData = $reportController->getExamReport($filters);
$opsData = $reportController->getOperationsReport($filters);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Reports & Analytics - Coaching Management System</title>
    <link rel="stylesheet" href="admin_dashboard.css">
    <link rel="stylesheet" href="reports.css">
</head>

<body>
    <!-- ১. TOP NAVBAR -->
    <header class="top_navbar">
        <div class="nav_left">
            <button id="sidebarToggle" class="toggle_btn" type="button">☰</button>
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

    <!-- ২. মূল লেআউট (Sidebar + Main Content) -->
    <div class="dashboard_container">
        <!-- SIDEBAR -->
        <aside class="sidebar" id="sidebar">
            <ul class="sidebar_menu">
                <li><a href="admin_dashboard.php">🏠 <span>Dashboard</span></a></li>
                <li><a href="students.php">🎓 <span>Students</span></a></li>
                <li><a href="teachers.php">👨‍🏫 <span>Teachers</span></a></li>
                <li><a href="batches.php">📚 <span>Batches</span></a></li>
                <li><a href="classes.php">📅 <span>Classes</span></a></li>
                <li><a href="exams.php">📝 <span>Exams</span></a></li>
                <li><a href="results.php">📊 <span>Results</span></a></li>
                <li><a href="fees.php">💰 <span>Fees</span></a></li>
                <li class="active"><a href="reports.php">📑 <span>Reports</span></a></li>
                <li><a href="admin_profile.php">⚙️ <span>Profile</span></a></li>
                <li class="logout"><a href="../logout.php">🚪 <span>Logout</span></a></li>
            </ul>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <main class="main_content">

            <!-- PRINT-ONLY OFFICIAL HEADER (hidden on screen, visible in PDF/print) -->
            <div class="print_only_header" id="printOnlyHeader" aria-hidden="true">
                <div class="print_brand">COACHING MANAGEMENT SYSTEM</div>
                <h2 class="print_title" id="printReportTitle">Financial Reports</h2>
                <div class="print_meta">
                    <span id="printFilterLine">All Records</span>
                    <span class="print_sep">•</span>
                    <span>Printed on: <span id="printDateLine"></span></span>
                </div>
            </div>

            <!-- PAGE HEADER & ACTIONS -->
            <div class="report_page_header">
                <div>
                    <h1>Admin Reports & Analytics</h1>
                    <p>Executive reports, financial recoveries, attendance monitoring & academic performance.</p>
                </div>
                <div class="report_header_actions">
                    <button type="button" class="btn_action btn_excel" id="exportCsvBtn">
                        <span>📥</span> Export to Excel (CSV)
                    </button>
                    <button type="button" class="btn_action btn_print" onclick="window.print()">
                        <span>🖨️</span> Print / Save PDF
                    </button>
                </div>
            </div>

            <!-- TABS NAVIGATION -->
            <div class="report_tabs_container">
                <div class="report_tabs">
                    <button type="button" class="report_tab_btn <?= $currentTab === 'financial' ? 'active' : ''; ?>" data-target="tab-financial">
                        💳 Financial Reports
                    </button>
                    <button type="button" class="report_tab_btn <?= $currentTab === 'attendance' ? 'active' : ''; ?>" data-target="tab-attendance">
                        📅 Attendance Analytics
                    </button>
                    <button type="button" class="report_tab_btn <?= $currentTab === 'exams' ? 'active' : ''; ?>" data-target="tab-exams">
                        📝 Exam & Results
                    </button>
                    <button type="button" class="report_tab_btn <?= $currentTab === 'operations' ? 'active' : ''; ?>" data-target="tab-operations">
                        👥 Batch & Faculty
                    </button>
                </div>
            </div>

            <!-- GLOBAL / CONTEXTUAL FILTER TOOLBAR -->
            <div class="filter_card">
                <form method="GET" action="reports.php" class="filter_form" id="reportFilterForm">
                    <input type="hidden" name="tab" id="activeTabInput" value="<?= htmlspecialchars($currentTab); ?>">

                    <div class="filter_field">
                        <label class="filter_label" for="batch_id">Batch</label>
                        <select name="batch_id" id="batch_id" class="filter_select">
                            <option value="">All Batches</option>
                            <?php foreach ($filterOptions['batches'] as $batch): ?>
                                <option value="<?= $batch['id']; ?>" <?= ($filters['batch_id'] === (int)$batch['id']) ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($batch['name']); ?> (<?= ucfirst($batch['status']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="filter_field">
                        <label class="filter_label" for="month">Month</label>
                        <select name="month" id="month" class="filter_select">
                            <option value="">All Months</option>
                            <?php foreach ($filterOptions['months'] as $m): ?>
                                <option value="<?= $m['value']; ?>" <?= ($filters['month'] === $m['value']) ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($m['label']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="filter_field">
                        <label class="filter_label" for="from_date">From Date</label>
                        <input type="date" name="from_date" id="from_date" class="filter_input" value="<?= htmlspecialchars($filters['from_date'] ?? ''); ?>">
                    </div>

                    <div class="filter_field">
                        <label class="filter_label" for="to_date">To Date</label>
                        <input type="date" name="to_date" id="to_date" class="filter_input" value="<?= htmlspecialchars($filters['to_date'] ?? ''); ?>">
                    </div>

                    <div class="filter_field auto_width">
                        <button type="submit" class="btn_apply">Apply Filter</button>
                    </div>

                    <div class="filter_field auto_width">
                        <a href="reports.php?tab=<?= urlencode($currentTab); ?>" class="btn_reset">Reset</a>
                    </div>
                </form>
            </div>

            <?php if (!empty($filters['month']) || !empty($filters['batch_id']) || !empty($filters['from_date']) || !empty($filters['to_date'])): ?>
                <div class="active_filter_bar">
                    <span class="active_filter_label">⚡ Active Filters:</span>
                    <?php if (!empty($filters['month'])): ?>
                        <span class="filter_chip">📅 Month: <strong><?= date('F Y', strtotime($filters['month'] . '-01')); ?></strong></span>
                    <?php endif; ?>
                    <?php if (!empty($filters['batch_id'])): ?>
                        <?php 
                        $bName = 'Batch #' . $filters['batch_id'];
                        foreach ($filterOptions['batches'] as $b) {
                            if ((int)$b['id'] === (int)$filters['batch_id']) { $bName = $b['name']; break; }
                        }
                        ?>
                        <span class="filter_chip">📚 Batch: <strong><?= htmlspecialchars($bName); ?></strong></span>
                    <?php endif; ?>
                    <?php if (!empty($filters['from_date'])): ?>
                        <span class="filter_chip">From: <strong><?= htmlspecialchars($filters['from_date']); ?></strong></span>
                    <?php endif; ?>
                    <?php if (!empty($filters['to_date'])): ?>
                        <span class="filter_chip">To: <strong><?= htmlspecialchars($filters['to_date']); ?></strong></span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- ========================================================
                 TAB 1: FINANCIAL REPORTS
                 ======================================================== -->
            <div class="report_tab_pane <?= $currentTab === 'financial' ? 'active' : ''; ?>" id="tab-financial">
                <!-- KPI CARDS -->
                <div class="kpi_grid">
                    <div class="kpi_card success">
                        <div class="kpi_top">
                            <span class="kpi_label">Total Collected</span>
                            <span class="kpi_icon_badge">💰</span>
                        </div>
                        <div class="kpi_val">৳ <?= number_format($finData['kpi']['total_collected'], 0); ?></div>
                        <div class="kpi_sub">Efficiency: <strong><?= $finData['kpi']['collection_rate']; ?>%</strong></div>
                    </div>

                    <div class="kpi_card danger">
                        <div class="kpi_top">
                            <span class="kpi_label">Outstanding Dues</span>
                            <span class="kpi_icon_badge">⚠️</span>
                        </div>
                        <div class="kpi_val">৳ <?= number_format($finData['kpi']['total_pending'], 0); ?></div>
                        <div class="kpi_sub">Overdue unpaid fees</div>
                    </div>

                    <div class="kpi_card warning">
                        <div class="kpi_top">
                            <span class="kpi_label">Partial Payments</span>
                            <span class="kpi_icon_badge">⏳</span>
                        </div>
                        <div class="kpi_val">৳ <?= number_format($finData['kpi']['total_partial'], 0); ?></div>
                        <div class="kpi_sub">Partially cleared invoices</div>
                    </div>

                    <div class="kpi_card info">
                        <div class="kpi_top">
                            <span class="kpi_label">Total Invoices</span>
                            <span class="kpi_icon_badge">📄</span>
                        </div>
                        <div class="kpi_val"><?= $finData['kpi']['total_invoices']; ?></div>
                        <div class="kpi_sub">Billed monthly records</div>
                    </div>
                </div>

                <!-- ANALYTICS ROW: MONTHLY REVENUE & BATCH REVENUE -->
                <div class="analytics_row">
                    <div class="analytics_box">
                        <div class="analytics_box_header">
                            <h3>Monthly Collection vs Dues Trend</h3>
                            <?php
                            $trendBadge = 'Last 12 Months';
                            if (!empty($filters['month'])) {
                                $mTime = strtotime($filters['month'] . '-01');
                                $trendBadge = $mTime ? date('F Y', $mTime) . ' (Selected Month)' : htmlspecialchars($filters['month']);
                            } elseif (!empty($filters['from_date']) && !empty($filters['to_date'])) {
                                $trendBadge = date('M Y', strtotime($filters['from_date'])) . ' - ' . date('M Y', strtotime($filters['to_date']));
                            } elseif (!empty($filters['from_date'])) {
                                $trendBadge = 'From ' . date('d M Y', strtotime($filters['from_date']));
                            } elseif (!empty($filters['to_date'])) {
                                $trendBadge = 'Until ' . date('d M Y', strtotime($filters['to_date']));
                            } elseif (!empty($filters['batch_id'])) {
                                $trendBadge = 'Selected Batch Trend';
                            }
                            ?>
                            <span class="analytics_badge">
                                <?= htmlspecialchars($trendBadge); ?>
                            </span>
                        </div>
                        <div class="chart_wrapper">
                            <canvas id="revenueTrendChart" height="200"></canvas>
                        </div>
                    </div>

                    <div class="analytics_box">
                        <div class="analytics_box_header">
                            <h3>Revenue by Batch</h3>
                            <span class="analytics_badge">Tuition Ledger</span>
                        </div>
                        <div class="table_scroll" style="max-height: 220px;">
                            <table class="report_table">
                                <thead>
                                    <tr>
                                        <th>Batch</th>
                                        <th>Collected</th>
                                        <th>Pending Dues</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($finData['batch_revenue'])): ?>
                                        <tr><td colspan="3" class="empty_state_row">No batch revenue data found.</td></tr>
                                    <?php else: ?>
                                        <?php foreach ($finData['batch_revenue'] as $br): ?>
                                            <tr>
                                                <td><strong><?= htmlspecialchars($br['batch_name']); ?></strong></td>
                                                <td style="color: #15803d; font-weight: 600;">৳ <?= number_format($br['collected'], 0); ?></td>
                                                <td style="color: #b91c1c; font-weight: 600;">৳ <?= number_format($br['dues'], 0); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- URGENT FEE DEFAULTERS LIST -->
                <div class="report_section">
                    <div class="report_section_header">
                        <h3>
                            <span>⚠️ Urgent Fee Defaulters List</span>
                            <span class="section_badge_alert"><?= count($finData['defaulters']); ?> Students Due</span>
                        </h3>
                        <div class="table_search_wrap">
                            <span class="table_search_icon">🔍</span>
                            <input type="text" class="table_search_input" placeholder="Search defaulter..." data-table="defaultersTable">
                        </div>
                    </div>
                    <div class="table_scroll">
                        <table class="report_table" id="defaultersTable">
                            <thead>
                                <tr>
                                    <th>Roll</th>
                                    <th>Student Name</th>
                                    <th>Batch</th>
                                    <th>Fee Month</th>
                                    <th>Due Amount</th>
                                    <th>Status</th>
                                    <th>Guardian Contact</th>
                                    <th class="action_col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($finData['defaulters'])): ?>
                                    <tr><td colspan="8" class="empty_state_row">🎉 No outstanding fee defaulters found for this filter!</td></tr>
                                <?php else: ?>
                                    <?php foreach ($finData['defaulters'] as $d): ?>
                                        <tr>
                                            <td><strong><?= htmlspecialchars($d['roll']); ?></strong></td>
                                            <td><?= htmlspecialchars($d['student_name']); ?></td>
                                            <td><?= htmlspecialchars($d['batch_name']); ?></td>
                                            <td><?= date('F Y', strtotime($d['month'])); ?></td>
                                            <td style="font-weight: 700; color: #b91c1c;">৳ <?= number_format($d['amount'], 0); ?></td>
                                            <td>
                                                <span class="badge badge_<?= $d['status']; ?>">
                                                    <?= ucfirst($d['status']); ?>
                                                </span>
                                            </td>
                                            <td><?= htmlspecialchars($d['guardian_phone']); ?></td>
                                            <td class="action_col">
                                                <a href="tel:<?= urlencode($d['guardian_phone']); ?>" class="btn_call" title="Call Guardian">
                                                    📞 Call
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- COMPLETE TRANSACTION LEDGER -->
                <div class="report_section">
                    <div class="report_section_header">
                        <h3>
                            <span>📄 Fee Payment Ledger</span>
                            <span class="analytics_badge" style="margin-left: 10px; font-weight: normal;">
                                <?= count($finData['transactions']); ?> Invoices
                            </span>
                        </h3>
                        <div class="table_search_wrap">
                            <span class="table_search_icon">🔍</span>
                            <input type="text" class="table_search_input" placeholder="Search ledger..." data-table="financialLedgerTable">
                        </div>
                    </div>
                    <div class="table_scroll">
                        <table class="report_table" id="financialLedgerTable">
                            <thead>
                                <tr>
                                    <th>Invoice #</th>
                                    <th>Student Name</th>
                                    <th>Roll</th>
                                    <th>Batch</th>
                                    <th>Month</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Payment Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($finData['transactions'])): ?>
                                    <tr><td colspan="8" class="empty_state_row">No fee transactions found for this filter.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($finData['transactions'] as $tx): ?>
                                        <tr>
                                            <td>#FEE-<?= str_pad((string)$tx['id'], 4, '0', STR_PAD_LEFT); ?></td>
                                            <td><strong><?= htmlspecialchars($tx['student_name']); ?></strong></td>
                                            <td><?= htmlspecialchars($tx['roll']); ?></td>
                                            <td><?= htmlspecialchars($tx['batch_name']); ?></td>
                                            <td><?= date('M Y', strtotime($tx['month'])); ?></td>
                                            <td>৳ <?= number_format($tx['amount'], 0); ?></td>
                                            <td>
                                                <span class="badge badge_<?= $tx['status']; ?>">
                                                    <?= ucfirst($tx['status']); ?>
                                                </span>
                                            </td>
                                            <td><?= !empty($tx['payment_date']) ? date('d M, Y', strtotime($tx['payment_date'])) : '—'; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ========================================================
                 TAB 2: ATTENDANCE ANALYTICS
                 ======================================================== -->
            <div class="report_tab_pane <?= $currentTab === 'attendance' ? 'active' : ''; ?>" id="tab-attendance">
                <?php
                $attFilterBadge = 'All Records';
                if (!empty($filters['month'])) {
                    $mTime = strtotime($filters['month'] . '-01');
                    $attFilterBadge = $mTime ? date('F Y', $mTime) : htmlspecialchars($filters['month']);
                } elseif (!empty($filters['from_date']) && !empty($filters['to_date'])) {
                    $attFilterBadge = date('d M Y', strtotime($filters['from_date'])) . ' - ' . date('d M Y', strtotime($filters['to_date']));
                } elseif (!empty($filters['from_date'])) {
                    $attFilterBadge = 'From ' . date('d M Y', strtotime($filters['from_date']));
                } elseif (!empty($filters['to_date'])) {
                    $attFilterBadge = 'Up to ' . date('d M Y', strtotime($filters['to_date']));
                }
                ?>
                <!-- KPI CARDS -->
                <div class="kpi_grid">
                    <div class="kpi_card success">
                        <div class="kpi_top">
                            <span class="kpi_label">Present Rate</span>
                            <span class="kpi_icon_badge">✅</span>
                        </div>
                        <div class="kpi_val"><?= $attData['kpi']['present_rate']; ?>%</div>
                        <div class="kpi_sub"><?= $attData['kpi']['present_count']; ?> Present logs</div>
                    </div>

                    <div class="kpi_card danger">
                        <div class="kpi_top">
                            <span class="kpi_label">Absences</span>
                            <span class="kpi_icon_badge">❌</span>
                        </div>
                        <div class="kpi_val"><?= $attData['kpi']['absent_count']; ?></div>
                        <div class="kpi_sub"><?= $attData['kpi']['absent_rate']; ?>% absence rate</div>
                    </div>

                    <div class="kpi_card warning">
                        <div class="kpi_top">
                            <span class="kpi_label">Late Arrivals</span>
                            <span class="kpi_icon_badge">⏱️</span>
                        </div>
                        <div class="kpi_val"><?= $attData['kpi']['late_count']; ?></div>
                        <div class="kpi_sub"><?= $attData['kpi']['late_rate']; ?>% late entries</div>
                    </div>

                    <div class="kpi_card info">
                        <div class="kpi_top">
                            <span class="kpi_label">Total Recorded</span>
                            <span class="kpi_icon_badge">📊</span>
                        </div>
                        <div class="kpi_val"><?= $attData['kpi']['total_logs']; ?></div>
                        <div class="kpi_sub">Individual attendance marks</div>
                    </div>
                </div>

                <!-- ANALYTICS ROW: DOUGHNUT & BATCH COMPARISON -->
                <div class="analytics_row">
                    <div class="analytics_box">
                        <div class="analytics_box_header">
                            <h3>Center-wide Attendance Distribution</h3>
                            <span class="analytics_badge"><?= $attFilterBadge; ?></span>
                        </div>
                        <div class="chart_wrapper">
                            <canvas id="attendanceDoughnutChart" height="200"></canvas>
                        </div>
                    </div>

                    <div class="analytics_box">
                        <div class="analytics_box_header">
                            <h3>Batch Attendance Rates</h3>
                            <span class="analytics_badge">Comparison</span>
                        </div>
                        <div class="table_scroll" style="max-height: 220px;">
                            <table class="report_table">
                                <thead>
                                    <tr>
                                        <th>Batch</th>
                                        <th>Present Rate</th>
                                        <th>Absences</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($attData['batch_comparison'])): ?>
                                        <tr><td colspan="3" class="empty_state_row">No batch attendance logs.</td></tr>
                                    <?php else: ?>
                                        <?php foreach ($attData['batch_comparison'] as $bc): ?>
                                            <tr>
                                                <td><strong><?= htmlspecialchars($bc['batch_name']); ?></strong></td>
                                                <td>
                                                    <span style="font-weight: 700; color: <?= $bc['rate'] >= 75 ? '#15803d' : '#b91c1c'; ?>;">
                                                        <?= $bc['rate']; ?>%
                                                    </span>
                                                </td>
                                                <td><?= $bc['absent_count']; ?> students</td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- CHRONIC ABSENTEEISM & LOW ATTENDANCE ALERT -->
                <div class="report_section">
                    <div class="report_section_header">
                        <h3>
                            <span>🚨 Low Attendance Alert (&lt; 80%)</span>
                            <span class="section_badge_alert"><?= count($attData['low_attendance']); ?> Students Flagged</span>
                        </h3>
                        <div class="table_search_wrap">
                            <span class="table_search_icon">🔍</span>
                            <input type="text" class="table_search_input" placeholder="Search student..." data-table="lowAttendanceTable">
                        </div>
                    </div>
                    <div class="table_scroll">
                        <table class="report_table" id="lowAttendanceTable">
                            <thead>
                                <tr>
                                    <th>Roll</th>
                                    <th>Student Name</th>
                                    <th>Batch</th>
                                    <th>Total Sessions</th>
                                    <th>Present</th>
                                    <th>Absent</th>
                                    <th>Attendance %</th>
                                    <th>Guardian Phone</th>
                                    <th class="action_col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($attData['low_attendance'])): ?>
                                    <tr><td colspan="9" class="empty_state_row">🎉 Excellent! No students below the 80% attendance threshold.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($attData['low_attendance'] as $la): ?>
                                        <tr>
                                            <td><strong><?= htmlspecialchars($la['roll']); ?></strong></td>
                                            <td><?= htmlspecialchars($la['student_name']); ?></td>
                                            <td><?= htmlspecialchars($la['batch_name']); ?></td>
                                            <td><?= $la['total_sessions']; ?></td>
                                            <td style="color: #15803d; font-weight: 600;"><?= $la['present_count']; ?></td>
                                            <td style="color: #b91c1c; font-weight: 600;"><?= $la['absent_count']; ?></td>
                                            <td>
                                                <span class="badge badge_<?= $la['rate'] >= 75 ? 'present' : 'absent'; ?>">
                                                    <?= $la['rate']; ?>%
                                                </span>
                                            </td>
                                            <td><?= htmlspecialchars($la['guardian_phone']); ?></td>
                                            <td class="action_col">
                                                <a href="tel:<?= urlencode($la['guardian_phone']); ?>" class="btn_call">
                                                    📞 Call
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- RECENT ATTENDANCE LOG -->
                <div class="report_section">
                    <div class="report_section_header">
                        <h3><span>📋 Detailed Attendance Log</span></h3>
                        <div class="table_search_wrap">
                            <span class="table_search_icon">🔍</span>
                            <input type="text" class="table_search_input" placeholder="Search log..." data-table="attendanceLogTable">
                        </div>
                    </div>
                    <div class="table_scroll">
                        <table class="report_table" id="attendanceLogTable">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Roll</th>
                                    <th>Student Name</th>
                                    <th>Batch</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($attData['logs'])): ?>
                                    <tr><td colspan="5" class="empty_state_row">No attendance logs found.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($attData['logs'] as $log): ?>
                                        <tr>
                                            <td><?= date('d M, Y', strtotime($log['date'])); ?></td>
                                            <td><strong><?= htmlspecialchars($log['roll']); ?></strong></td>
                                            <td><?= htmlspecialchars($log['student_name']); ?></td>
                                            <td><?= htmlspecialchars($log['batch_name']); ?></td>
                                            <td>
                                                <span class="badge badge_<?= $log['status']; ?>">
                                                    <?= ucfirst($log['status']); ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ========================================================
                 TAB 3: EXAM & RESULTS PERFORMANCE
                 ======================================================== -->
            <div class="report_tab_pane <?= $currentTab === 'exams' ? 'active' : ''; ?>" id="tab-exams">
                <?php
                $examFilterBadge = 'All Records';
                if (!empty($filters['month'])) {
                    $mTime = strtotime($filters['month'] . '-01');
                    $examFilterBadge = $mTime ? date('F Y', $mTime) : htmlspecialchars($filters['month']);
                } elseif (!empty($filters['from_date']) && !empty($filters['to_date'])) {
                    $examFilterBadge = date('d M Y', strtotime($filters['from_date'])) . ' - ' . date('d M Y', strtotime($filters['to_date']));
                } elseif (!empty($filters['from_date'])) {
                    $examFilterBadge = 'From ' . date('d M Y', strtotime($filters['from_date']));
                } elseif (!empty($filters['to_date'])) {
                    $examFilterBadge = 'Up to ' . date('d M Y', strtotime($filters['to_date']));
                }
                ?>
                <!-- KPI CARDS -->
                <div class="kpi_grid">
                    <div class="kpi_card success">
                        <div class="kpi_top">
                            <span class="kpi_label">Overall Pass Rate</span>
                            <span class="kpi_icon_badge">🎯</span>
                        </div>
                        <div class="kpi_val"><?= $examData['kpi']['pass_rate']; ?>%</div>
                        <div class="kpi_sub"><?= $examData['kpi']['passed_count']; ?> Passed / <?= $examData['kpi']['failed_count']; ?> Failed</div>
                    </div>

                    <div class="kpi_card info">
                        <div class="kpi_top">
                            <span class="kpi_label">Average Marks</span>
                            <span class="kpi_icon_badge">📈</span>
                        </div>
                        <div class="kpi_val"><?= $examData['kpi']['avg_marks']; ?></div>
                        <div class="kpi_sub">Across all student exams</div>
                    </div>

                    <div class="kpi_card warning">
                        <div class="kpi_top">
                            <span class="kpi_label">Total Exams Held</span>
                            <span class="kpi_icon_badge">📝</span>
                        </div>
                        <div class="kpi_val"><?= $examData['kpi']['total_exams']; ?></div>
                        <div class="kpi_sub">Scheduled assessments</div>
                    </div>

                    <div class="kpi_card danger">
                        <div class="kpi_top">
                            <span class="kpi_label">Evaluated Papers</span>
                            <span class="kpi_icon_badge">📑</span>
                        </div>
                        <div class="kpi_val"><?= $examData['kpi']['total_results']; ?></div>
                        <div class="kpi_sub">Graded student scorecards</div>
                    </div>
                </div>

                <!-- ANALYTICS ROW: GRADE DISTRIBUTION & SUBJECT PERFORMANCE -->
                <div class="analytics_row">
                    <div class="analytics_box">
                        <div class="analytics_box_header">
                            <h3>Grade Distribution</h3>
                            <span class="analytics_badge"><?= $examFilterBadge; ?></span>
                        </div>
                        <div class="chart_wrapper">
                            <canvas id="gradeDistributionChart" height="200"></canvas>
                        </div>
                    </div>

                    <div class="analytics_box">
                        <div class="analytics_box_header">
                            <h3>Subject Performance Average</h3>
                            <span class="analytics_badge">Curriculum</span>
                        </div>
                        <div class="table_scroll" style="max-height: 220px;">
                            <table class="report_table">
                                <thead>
                                    <tr>
                                        <th>Subject</th>
                                        <th>Exams</th>
                                        <th>Avg Score</th>
                                        <th>Percentage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($examData['subject_performance'])): ?>
                                        <tr><td colspan="4" class="empty_state_row">No subjects evaluated yet.</td></tr>
                                    <?php else: ?>
                                        <?php foreach ($examData['subject_performance'] as $sp): ?>
                                            <tr>
                                                <td><strong><?= htmlspecialchars($sp['subject_name']); ?></strong></td>
                                                <td><?= $sp['exam_count']; ?></td>
                                                <td><?= $sp['avg_score']; ?></td>
                                                <td style="font-weight: 700; color: #4d38c4;"><?= $sp['avg_percentage']; ?>%</td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- MERIT LIST & TOP ACADEMIC PERFORMERS -->
                <div class="report_section">
                    <div class="report_section_header">
                        <h3>
                            <span>🏆 Academic Merit List (Top Performers)</span>
                            <span class="analytics_badge" style="font-size: 11px; padding: 2px 8px; margin-left: 8px;"><?= $examFilterBadge; ?></span>
                        </h3>
                        <div class="table_search_wrap">
                            <span class="table_search_icon">🔍</span>
                            <input type="text" class="table_search_input" placeholder="Search merit list..." data-table="meritTable">
                        </div>
                    </div>
                    <div class="table_scroll">
                        <table class="report_table" id="meritTable">
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>Roll</th>
                                    <th>Student Name</th>
                                    <th>Batch</th>
                                    <th>Exam</th>
                                    <th>Subject</th>
                                    <th>Marks</th>
                                    <th>Percentage</th>
                                    <th>Grade</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($examData['merit_list'])): ?>
                                    <tr><td colspan="9" class="empty_state_row">No exam marks entered yet.</td></tr>
                                <?php else: ?>
                                    <?php $rank = 1; foreach ($examData['merit_list'] as $m): ?>
                                        <tr>
                                            <td>
                                                <span class="rank_badge rank_<?= $rank <= 3 ? $rank : 'other'; ?>">
                                                    <?= $rank++; ?>
                                                </span>
                                            </td>
                                            <td><strong><?= htmlspecialchars($m['roll']); ?></strong></td>
                                            <td><?= htmlspecialchars($m['student_name']); ?></td>
                                            <td><?= htmlspecialchars($m['batch_name']); ?></td>
                                            <td><?= htmlspecialchars($m['exam_name']); ?></td>
                                            <td><?= htmlspecialchars($m['subject_name']); ?></td>
                                            <td><strong><?= $m['marks']; ?></strong> / <?= $m['total_marks']; ?></td>
                                            <td style="font-weight: 700; color: #15803d;"><?= $m['percentage']; ?>%</td>
                                            <td>
                                                <span class="badge" style="background-color: #ede9fe; color: #5b21b6; font-weight: 700;">
                                                    <?= htmlspecialchars($m['grade'] ?? '—'); ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- AT-RISK / WEAK STUDENTS ALERT -->
                <div class="report_section">
                    <div class="report_section_header">
                        <h3>
                            <span>⚠️ Weak / At-Risk Students Alert (&lt; 50% Marks)</span>
                            <span class="section_badge_alert"><?= count($examData['weak_students']); ?> Flagged</span>
                            <span class="analytics_badge" style="font-size: 11px; padding: 2px 8px; margin-left: 8px;"><?= $examFilterBadge; ?></span>
                        </h3>
                        <div class="table_search_wrap">
                            <span class="table_search_icon">🔍</span>
                            <input type="text" class="table_search_input" placeholder="Search weak students..." data-table="weakStudentsTable">
                        </div>
                    </div>
                    <div class="table_scroll">
                        <table class="report_table" id="weakStudentsTable">
                            <thead>
                                <tr>
                                    <th>Roll</th>
                                    <th>Student Name</th>
                                    <th>Batch</th>
                                    <th>Exam</th>
                                    <th>Subject</th>
                                    <th>Marks</th>
                                    <th>Percentage</th>
                                    <th>Grade</th>
                                    <th>Guardian Phone</th>
                                    <th class="action_col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($examData['weak_students'])): ?>
                                    <tr><td colspan="10" class="empty_state_row">🎉 No students scored below 50%! Excellent performance.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($examData['weak_students'] as $w): ?>
                                        <tr>
                                            <td><strong><?= htmlspecialchars($w['roll']); ?></strong></td>
                                            <td><?= htmlspecialchars($w['student_name']); ?></td>
                                            <td><?= htmlspecialchars($w['batch_name']); ?></td>
                                            <td><?= htmlspecialchars($w['exam_name']); ?></td>
                                            <td><?= htmlspecialchars($w['subject_name']); ?></td>
                                            <td style="color: #b91c1c; font-weight: 700;"><?= $w['marks']; ?> / <?= $w['total_marks']; ?></td>
                                            <td style="color: #b91c1c; font-weight: 700;"><?= $w['percentage']; ?>%</td>
                                            <td>
                                                <span class="badge badge_unpaid">
                                                    <?= htmlspecialchars($w['grade'] ?? 'F'); ?>
                                                </span>
                                            </td>
                                            <td><?= htmlspecialchars($w['guardian_phone']); ?></td>
                                            <td class="action_col">
                                                <a href="tel:<?= urlencode($w['guardian_phone']); ?>" class="btn_call">
                                                    📞 Call
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ========================================================
                 TAB 4: BATCH & OPERATIONS
                 ======================================================== -->
            <div class="report_tab_pane <?= $currentTab === 'operations' ? 'active' : ''; ?>" id="tab-operations">
                <?php
                $opsFilterBadge = 'All Records';
                if (!empty($filters['month'])) {
                    $mTime = strtotime($filters['month'] . '-01');
                    $opsFilterBadge = $mTime ? date('F Y', $mTime) : htmlspecialchars($filters['month']);
                } elseif (!empty($filters['from_date']) && !empty($filters['to_date'])) {
                    $opsFilterBadge = date('d M Y', strtotime($filters['from_date'])) . ' - ' . date('d M Y', strtotime($filters['to_date']));
                } elseif (!empty($filters['from_date'])) {
                    $opsFilterBadge = 'From ' . date('d M Y', strtotime($filters['from_date']));
                } elseif (!empty($filters['to_date'])) {
                    $opsFilterBadge = 'Up to ' . date('d M Y', strtotime($filters['to_date']));
                }
                ?>
                <!-- KPI CARDS -->
                <div class="kpi_grid">
                    <div class="kpi_card info">
                        <div class="kpi_top">
                            <span class="kpi_label"><?= !empty($filters['batch_id']) ? 'Selected Batch' : 'Active Batches'; ?></span>
                            <span class="kpi_icon_badge">📚</span>
                        </div>
                        <div class="kpi_val"><?= $opsData['kpi']['total_batches']; ?></div>
                        <div class="kpi_sub"><?= htmlspecialchars($opsData['kpi']['batches_sub']); ?></div>
                    </div>

                    <div class="kpi_card success">
                        <div class="kpi_top">
                            <span class="kpi_label"><?= htmlspecialchars($opsData['kpi']['enrolled_label']); ?></span>
                            <span class="kpi_icon_badge">🎓</span>
                        </div>
                        <div class="kpi_val"><?= $opsData['kpi']['total_enrolled']; ?></div>
                        <div class="kpi_sub"><?= htmlspecialchars($opsData['kpi']['enrolled_sub']); ?></div>
                    </div>

                    <div class="kpi_card warning">
                        <div class="kpi_top">
                            <span class="kpi_label">Classes Conducted</span>
                            <span class="kpi_icon_badge">🗓️</span>
                        </div>
                        <div class="kpi_val"><?= $opsData['kpi']['classes_conducted']; ?></div>
                        <div class="kpi_sub"><?= htmlspecialchars($opsData['kpi']['classes_sub']); ?></div>
                    </div>

                    <div class="kpi_card danger">
                        <div class="kpi_top">
                            <span class="kpi_label">Active Teachers</span>
                            <span class="kpi_icon_badge">👨‍🏫</span>
                        </div>
                        <div class="kpi_val"><?= $opsData['kpi']['total_teachers']; ?></div>
                        <div class="kpi_sub"><?= htmlspecialchars($opsData['kpi']['teachers_sub']); ?></div>
                    </div>
                </div>

                <!-- BATCH CAPACITY & ENROLLMENT TABLE -->
                <div class="report_section">
                    <div class="report_section_header">
                        <h3>
                            <span>📚 Batch Capacity & Enrollment Status</span>
                            <span class="analytics_badge" style="font-size: 11px; padding: 2px 8px; margin-left: 8px;"><?= $opsFilterBadge; ?></span>
                        </h3>
                        <div class="table_search_wrap">
                            <span class="table_search_icon">🔍</span>
                            <input type="text" class="table_search_input" placeholder="Search batch..." data-table="batchRosterTable">
                        </div>
                    </div>
                    <div class="table_scroll">
                        <table class="report_table" id="batchRosterTable">
                            <thead>
                                <tr>
                                    <th>Batch Name</th>
                                    <th>Start Date</th>
                                    <th>Schedule Time</th>
                                    <th>Room</th>
                                    <th>Total Enrolled</th>
                                    <th>New Admissions (<?= $opsFilterBadge; ?>)</th>
                                    <th>Classes Held (<?= $opsFilterBadge; ?>)</th>
                                    <th>Assigned Teachers</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($opsData['batch_roster'])): ?>
                                    <tr><td colspan="9" class="empty_state_row">No batches found.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($opsData['batch_roster'] as $b): ?>
                                        <tr>
                                            <td><strong><?= htmlspecialchars($b['name']); ?></strong></td>
                                            <td><?= date('d M, Y', strtotime($b['start_date'])); ?></td>
                                            <td><?= htmlspecialchars($b['schedule_time'] ?? '—'); ?></td>
                                            <td><?= htmlspecialchars($b['room_number'] ?? '—'); ?></td>
                                            <td style="font-weight: 700; color: #4d38c4;"><?= $b['enrolled_students']; ?> Students</td>
                                            <td>
                                                <span class="badge" style="background-color: #dbeafe; color: #1e40af; font-weight: 700;">
                                                    +<?= $b['period_admissions']; ?> New
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge" style="background-color: #fef3c7; color: #92400e; font-weight: 700;">
                                                    <?= $b['classes_conducted']; ?> Sessions
                                                </span>
                                            </td>
                                            <td><?= $b['teacher_count']; ?> Faculty</td>
                                            <td>
                                                <span class="badge badge_<?= $b['status']; ?>">
                                                    <?= ucfirst($b['status']); ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- TEACHER WORKLOAD REPORT -->
                <div class="report_section">
                    <div class="report_section_header">
                        <h3>
                            <span>👨‍🏫 Teacher Workload & Assigned Batches</span>
                            <span class="analytics_badge" style="font-size: 11px; padding: 2px 8px; margin-left: 8px;"><?= $opsFilterBadge; ?></span>
                        </h3>
                        <div class="table_search_wrap">
                            <span class="table_search_icon">🔍</span>
                            <input type="text" class="table_search_input" placeholder="Search teacher..." data-table="teacherWorkloadTable">
                        </div>
                    </div>
                    <div class="table_scroll">
                        <table class="report_table" id="teacherWorkloadTable">
                            <thead>
                                <tr>
                                    <th>Teacher Name</th>
                                    <th>Specialization</th>
                                    <th>Phone</th>
                                    <th>Batches Assigned</th>
                                    <th>Weekly Quota</th>
                                    <th>Sessions in Period (<?= $opsFilterBadge; ?>)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($opsData['teacher_workload'])): ?>
                                    <tr><td colspan="6" class="empty_state_row">No teachers found.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($opsData['teacher_workload'] as $tw): ?>
                                        <tr>
                                            <td><strong><?= htmlspecialchars($tw['teacher_name']); ?></strong></td>
                                            <td><?= htmlspecialchars($tw['subject_specialist'] ?? 'General'); ?></td>
                                            <td><?= htmlspecialchars($tw['phone']); ?></td>
                                            <td><span class="badge badge_active"><?= $tw['assigned_batches']; ?> Batches</span></td>
                                            <td style="font-weight: 700; color: #15803d;"><?= $tw['weekly_classes']; ?> Classes / Week</td>
                                            <td>
                                                <span class="badge" style="background-color: #e0e7ff; color: #4338ca; font-weight: 700;">
                                                    <?= $tw['period_sessions']; ?> Sessions
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ROOM UTILIZATION & ROUTINE MATRIX -->
                <div class="report_section">
                    <div class="report_section_header">
                        <h3><span>🗓️ Weekly Room & Routine Utilization Schedule</span></h3>
                        <div class="table_search_wrap">
                            <span class="table_search_icon">🔍</span>
                            <input type="text" class="table_search_input" placeholder="Search routine..." data-table="routineMatrixTable">
                        </div>
                    </div>
                    <div class="table_scroll">
                        <table class="report_table" id="routineMatrixTable">
                            <thead>
                                <tr>
                                    <th>Day</th>
                                    <th>Time</th>
                                    <th>Room</th>
                                    <th>Batch</th>
                                    <th>Subject</th>
                                    <th>Teacher</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($opsData['routine_matrix'])): ?>
                                    <tr><td colspan="6" class="empty_state_row">No class schedules mapped yet.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($opsData['routine_matrix'] as $rm): ?>
                                        <tr>
                                            <td><strong><?= htmlspecialchars($rm['day_of_week']); ?></strong></td>
                                            <td><?= date('h:i A', strtotime($rm['start_time'])); ?> - <?= date('h:i A', strtotime($rm['end_time'])); ?></td>
                                            <td><span class="badge" style="background-color: #f1f5f9; color: #334155; font-weight: 600;"><?= htmlspecialchars($rm['room_number']); ?></span></td>
                                            <td><?= htmlspecialchars($rm['batch_name']); ?></td>
                                            <td><strong><?= htmlspecialchars($rm['subject_name']); ?></strong></td>
                                            <td><?= htmlspecialchars($rm['teacher_name']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- PRINT-ONLY FOOTER (hidden on screen, visible in PDF/print) -->
            <div class="print_only_footer" aria-hidden="true">
                System generated report — Coaching Management System • Verify data with admin office
            </div>

        </main>
    </div>

    <!-- CLIENT LOGIC: TABS, SEARCH, EXPORT, CHARTS & TOGGLE -->
    <script>
        // 1. Sidebar Toggle
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');

        if (sidebarToggle && sidebar) {
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
        }

        // 2. Tab Navigation
        const tabButtons = document.querySelectorAll('.report_tab_btn');
        const tabPanes = document.querySelectorAll('.report_tab_pane');
        const activeTabInput = document.getElementById('activeTabInput');

        // Sync the print/PDF header with the currently visible report part
        function updatePrintHeader() {
            const activeBtn = document.querySelector('.report_tab_btn.active');
            const titleEl = document.getElementById('printReportTitle');
            const filterEl = document.getElementById('printFilterLine');
            const dateEl = document.getElementById('printDateLine');

            const tabName = activeBtn ? activeBtn.innerText.replace(/[\n\r]+/g, ' ').trim() : 'Report';
            if (titleEl) titleEl.textContent = tabName;

            const chips = document.querySelectorAll('.active_filter_bar .filter_chip');
            if (filterEl) {
                if (chips.length > 0) {
                    filterEl.textContent = Array.from(chips).map(c => c.innerText.trim()).join(' | ');
                } else {
                    filterEl.textContent = 'All Records (No filter applied)';
                }
            }

            if (dateEl) dateEl.textContent = new Date().toLocaleString();

            // Helpful PDF filename: Coaching_Reports-Financial_Reports.pdf etc.
            try {
                document.title = 'Coaching_Reports-' + tabName.replace(/[^a-zA-Z0-9]+/g, '_');
            } catch (e) { /* ignore */ }
        }

        tabButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                tabButtons.forEach(b => b.classList.remove('active'));
                tabPanes.forEach(p => p.classList.remove('active'));

                this.classList.add('active');
                const targetId = this.getAttribute('data-target');
                const targetPane = document.getElementById(targetId);
                if (targetPane) {
                    targetPane.classList.add('active');
                }

                // Update filter form hidden input
                const cleanTabName = targetId.replace('tab-', '');
                if (activeTabInput) {
                    activeTabInput.value = cleanTabName;
                }

                // Update URL parameter without reload
                const url = new URL(window.location.href);
                url.searchParams.set('tab', cleanTabName);
                window.history.replaceState({}, '', url);

                // Render charts on tab switch if needed
                if (targetId === 'tab-financial') renderRevenueChart();
                if (targetId === 'tab-attendance') renderAttendanceChart();
                if (targetId === 'tab-exams') renderGradeChart();

                updatePrintHeader();
            });
        });

        // 2b. Synchronize Month selector and Date range inputs
        const filterMonthSelect = document.getElementById('month');
        const filterFromDate = document.getElementById('from_date');
        const filterToDate = document.getElementById('to_date');

        if (filterMonthSelect && filterFromDate && filterToDate) {
            filterMonthSelect.addEventListener('change', function () {
                if (this.value) {
                    filterFromDate.value = '';
                    filterToDate.value = '';
                }
                const filterForm = document.getElementById('reportFilterForm');
                if (filterForm) filterForm.submit();
            });

            const filterBatchSelect = document.getElementById('batch_id');
            if (filterBatchSelect) {
                filterBatchSelect.addEventListener('change', function () {
                    const filterForm = document.getElementById('reportFilterForm');
                    if (filterForm) filterForm.submit();
                });
            }

            filterFromDate.addEventListener('change', function () {
                if (this.value) {
                    filterMonthSelect.value = '';
                }
            });

            filterToDate.addEventListener('change', function () {
                if (this.value) {
                    filterMonthSelect.value = '';
                }
            });
        }

        // 3. Live Table Search across all tables
        const searchInputs = document.querySelectorAll('.table_search_input');
        searchInputs.forEach(input => {
            input.addEventListener('input', function () {
                const targetTableId = this.getAttribute('data-table');
                const table = document.getElementById(targetTableId);
                if (!table) return;

                const query = this.value.toLowerCase().trim();
                const rows = table.querySelectorAll('tbody tr');

                rows.forEach(row => {
                    if (row.classList.contains('empty_state_row')) return;
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(query) ? '' : 'none';
                });
            });
        });

        // 4. One-Click Export to Excel (CSV) — exports ALL tables + KPIs of the active tab
        document.getElementById('exportCsvBtn').addEventListener('click', function () {
            const activePane = document.querySelector('.report_tab_pane.active');
            if (!activePane) return;

            const tables = activePane.querySelectorAll('table.report_table');
            if (!tables || tables.length === 0) {
                alert('No table data available to export in this tab.');
                return;
            }

            const csvEscape = (text) => '"' + String(text ?? '').replace(/"/g, '""').trim() + '"';

            let csvContent = '\uFEFF'; // UTF-8 BOM for Excel Bangla/৳ support
            const activeTabName = document.querySelector('.report_tab_btn.active')
                ? document.querySelector('.report_tab_btn.active').innerText.trim()
                : 'Report';

            // Report title + date
            csvContent += csvEscape(activeTabName) + '\r\n';
            csvContent += csvEscape('Exported on: ' + new Date().toLocaleString()) + '\r\n';

            // Active filters (if shown)
            const filterChips = document.querySelectorAll('.active_filter_bar .filter_chip');
            if (filterChips.length > 0) {
                const chipTexts = Array.from(filterChips).map(c => c.innerText.trim());
                csvContent += csvEscape('Filters: ' + chipTexts.join(' | ')) + '\r\n';
            }
            csvContent += '\r\n';

            // KPI summary of active tab
            const kpiCards = activePane.querySelectorAll('.kpi_card');
            if (kpiCards.length > 0) {
                csvContent += csvEscape('Summary') + '\r\n';
                kpiCards.forEach(card => {
                    const label = card.querySelector('.kpi_label') ? card.querySelector('.kpi_label').innerText.trim() : '';
                    const val = card.querySelector('.kpi_val') ? card.querySelector('.kpi_val').innerText.trim() : '';
                    const sub = card.querySelector('.kpi_sub') ? card.querySelector('.kpi_sub').innerText.trim() : '';
                    if (label || val) {
                        csvContent += [csvEscape(label), csvEscape(val), csvEscape(sub)].join(',') + '\r\n';
                    }
                });
                csvContent += '\r\n';
            }

            // Every table in the active tab as its own section
            tables.forEach((table, idx) => {
                let sectionTitle = 'Table ' + (idx + 1);
                const section = table.closest('.report_section, .analytics_box');
                if (section) {
                    const heading = section.querySelector('h3');
                    if (heading) sectionTitle = heading.innerText.trim().replace(/\s+/g, ' ');
                } else if (table.id) {
                    sectionTitle = table.id;
                }

                csvContent += csvEscape(sectionTitle) + '\r\n';

                const rows = table.querySelectorAll('tr');
                let exportedRows = 0;

                rows.forEach(row => {
                    // Skip hidden rows from live search filter
                    if (row.style.display === 'none') return;
                    // Skip "no data" placeholder rows — handled below
                    if (row.querySelector('.empty_state_row')) return;

                    const cols = row.querySelectorAll('th, td');
                    const rowData = [];

                    cols.forEach(col => {
                        // Skip call/action column (phone buttons)
                        if (col.classList.contains('action_col')) return;
                        rowData.push(csvEscape(col.innerText));
                    });

                    if (rowData.length > 0) {
                        csvContent += rowData.join(',') + '\r\n';
                        exportedRows++;
                    }
                });

                if (exportedRows === 0) {
                    csvContent += csvEscape('No data available for this filter.') + '\r\n';
                }
                csvContent += '\r\n';
            });

            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const url = URL.createObjectURL(blob);
            const link = document.createElement('a');
            const filename = 'Coaching_Report_' + activeTabName.replace(/[^a-zA-Z0-9]/g, '_') + '_' + new Date().toISOString().slice(0, 10) + '.csv';

            link.href = url;
            link.setAttribute('download', filename);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            setTimeout(() => URL.revokeObjectURL(url), 1000);
        });

        // 5. SELF-CONTAINED INTERACTIVE CANVAS CHARTS WITH PRECISION MOUSE HOVER
        // Chart A: Monthly Revenue Bar Chart with Month-Column Hit-Testing
        let revenueChartInitialized = false;
        let activeRevenueGroups = [];
        let activeRevenueMeta = { width: 0, height: 0, padding: null, maxVal: 0, chartH: 0 };

        function renderRevenueChart() {
            const canvas = document.getElementById('revenueTrendChart');
            if (!canvas) return;
            const wrapper = canvas.closest('.chart_wrapper');
            if (!wrapper) return;

            let tooltip = wrapper.querySelector('.chart_tooltip');
            if (!tooltip) {
                tooltip = document.createElement('div');
                tooltip.className = 'chart_tooltip';
                wrapper.appendChild(tooltip);
            }

            const rect = canvas.getBoundingClientRect();
            if (rect.width <= 0) return;

            const ctx = canvas.getContext('2d');
            const dpr = window.devicePixelRatio || 1;
            canvas.width = Math.round(rect.width * dpr);
            canvas.height = Math.round(rect.height * dpr);
            ctx.resetTransform ? ctx.resetTransform() : ctx.setTransform(1, 0, 0, 1, 0, 0);
            ctx.scale(dpr, dpr);

            const width = rect.width;
            const height = rect.height;

            const trendData = <?= json_encode($finData['monthly_trends'] ?? []); ?>;
            if (!trendData || trendData.length === 0) {
                ctx.fillStyle = '#9ca3af';
                ctx.font = '13px Inter, sans-serif';
                ctx.textAlign = 'center';
                ctx.fillText('No monthly revenue records available for this filter.', width / 2, height / 2);
                activeRevenueGroups = [];
                return;
            }

            const padding = { top: 30, right: 20, bottom: 40, left: 55 };
            const chartW = width - padding.left - padding.right;
            const chartH = height - padding.top - padding.bottom;

            let maxVal = 1000;
            trendData.forEach(d => {
                const tot = Math.max(parseFloat(d.collected || 0), parseFloat(d.unpaid || 0));
                if (tot > maxVal) maxVal = tot;
            });
            maxVal = Math.ceil(maxVal * 1.15);

            activeRevenueMeta = { width, height, padding, maxVal, chartH };

            // Compute month groups & bars positions
            const barCount = trendData.length;
            const barGroupW = chartW / barCount;
            // Bar width: wider when single or few months, balanced when multiple
            const barW = barCount === 1 ? 42 : (barCount <= 3 ? 30 : Math.min(barGroupW * 0.35, 24));
            const groups = [];

            trendData.forEach((d, idx) => {
                const groupLeft = padding.left + idx * barGroupW;
                const groupRight = groupLeft + barGroupW;
                const xGroup = groupLeft + barGroupW / 2;

                const collectedVal = parseFloat(d.collected || 0);
                const unpaidVal = parseFloat(d.unpaid || 0);

                const collectedH = (collectedVal / maxVal) * chartH;
                const unpaidH = (unpaidVal / maxVal) * chartH;

                const collectedBar = {
                    x: xGroup - barW - 3,
                    y: padding.top + chartH - collectedH,
                    w: barW,
                    h: collectedH,
                    val: collectedVal,
                    color: '#10b981'
                };

                const unpaidBar = {
                    x: xGroup + 3,
                    y: padding.top + chartH - unpaidH,
                    w: barW,
                    h: unpaidH,
                    val: unpaidVal,
                    color: '#ef4444'
                };

                groups.push({
                    idx: idx,
                    label: d.month_name || d.ym,
                    xGroup: xGroup,
                    groupLeft: groupLeft,
                    groupRight: groupRight,
                    collected: collectedVal,
                    unpaid: unpaidVal,
                    collectedBar: collectedBar,
                    unpaidBar: unpaidBar,
                    topY: Math.min(collectedBar.y, unpaidBar.y)
                });
            });

            activeRevenueGroups = groups;

            function draw(hoveredGroup = null, hoveredType = null) {
                ctx.clearRect(0, 0, width, height);

                // Grid Lines
                ctx.strokeStyle = '#f3f4f6';
                ctx.lineWidth = 1;
                const steps = 4;
                for (let i = 0; i <= steps; i++) {
                    const y = padding.top + (chartH / steps) * i;
                    ctx.beginPath();
                    ctx.moveTo(padding.left, y);
                    ctx.lineTo(width - padding.right, y);
                    ctx.stroke();

                    const val = Math.round(maxVal - (maxVal / steps) * i);
                    ctx.fillStyle = '#9ca3af';
                    ctx.font = '11px Inter, sans-serif';
                    ctx.textAlign = 'right';
                    ctx.fillText('৳' + (val >= 1000 ? (val / 1000).toFixed(0) + 'k' : val), padding.left - 8, y + 4);
                }

                // Month Columns & Bars
                groups.forEach(g => {
                    const isHovered = (hoveredGroup === g);

                    if (isHovered) {
                        // Subtle highlighted column zone behind hovered month
                        ctx.fillStyle = 'rgba(77, 56, 196, 0.05)';
                        ctx.fillRect(g.groupLeft + 1, padding.top, barGroupW - 2, chartH);
                    }

                    // Month label
                    ctx.fillStyle = isHovered ? '#111827' : '#6b7280';
                    ctx.font = isHovered ? 'bold 11px Inter, sans-serif' : '11px Inter, sans-serif';
                    ctx.textAlign = 'center';
                    ctx.fillText(g.label, g.xGroup, height - 12);

                    // Collected Bar
                    const cb = g.collectedBar;
                    ctx.fillStyle = cb.color;
                    ctx.beginPath();
                    if (ctx.roundRect) {
                        ctx.roundRect(cb.x, cb.y, cb.w, cb.h, [4, 4, 0, 0]);
                    } else {
                        ctx.rect(cb.x, cb.y, cb.w, cb.h);
                    }
                    ctx.fill();

                    if (isHovered) {
                        ctx.strokeStyle = (hoveredType === 'collected') ? '#ffffff' : 'rgba(255, 255, 255, 0.6)';
                        ctx.lineWidth = 2.5;
                        ctx.stroke();
                        ctx.fillStyle = (hoveredType === 'collected') ? 'rgba(255, 255, 255, 0.35)' : 'rgba(255, 255, 255, 0.15)';
                        ctx.fill();
                    }

                    // Unpaid Bar
                    const ub = g.unpaidBar;
                    ctx.fillStyle = ub.color;
                    ctx.beginPath();
                    if (ctx.roundRect) {
                        ctx.roundRect(ub.x, ub.y, ub.w, ub.h, [4, 4, 0, 0]);
                    } else {
                        ctx.rect(ub.x, ub.y, ub.w, ub.h);
                    }
                    ctx.fill();

                    if (isHovered) {
                        ctx.strokeStyle = (hoveredType === 'unpaid') ? '#ffffff' : 'rgba(255, 255, 255, 0.6)';
                        ctx.lineWidth = 2.5;
                        ctx.stroke();
                        ctx.fillStyle = (hoveredType === 'unpaid') ? 'rgba(255, 255, 255, 0.35)' : 'rgba(255, 255, 255, 0.15)';
                        ctx.fill();
                    }
                });

                // Top Legend
                ctx.fillStyle = '#10b981';
                ctx.fillRect(width - 150, 10, 10, 10);
                ctx.fillStyle = '#374151';
                ctx.font = '11px Inter, sans-serif';
                ctx.textAlign = 'left';
                ctx.fillText('Collected', width - 135, 19);

                ctx.fillStyle = '#ef4444';
                ctx.fillRect(width - 70, 10, 10, 10);
                ctx.fillStyle = '#374151';
                ctx.fillText('Due', width - 55, 19);
            }

            draw();

            if (!revenueChartInitialized) {
                revenueChartInitialized = true;

                canvas.addEventListener('mousemove', (e) => {
                    const cRect = canvas.getBoundingClientRect();
                    const wRect = wrapper.getBoundingClientRect();
                    const mx = e.clientX - cRect.left;
                    const my = e.clientY - cRect.top;

                    // Check if mouse is within chart bounds
                    if (my < padding.top - 15 || my > height - padding.bottom + 15 || activeRevenueGroups.length === 0) {
                        canvas.style.cursor = 'default';
                        draw(null);
                        if (tooltip) tooltip.classList.remove('visible');
                        return;
                    }

                    // Hit test by month column (guaranteed hit with zero missed hover zones!)
                    const foundGroup = activeRevenueGroups.find(g => mx >= g.groupLeft && mx <= g.groupRight);

                    if (foundGroup && tooltip) {
                        canvas.style.cursor = 'pointer';
                        const hoveredType = (mx <= foundGroup.xGroup) ? 'collected' : 'unpaid';
                        draw(foundGroup, hoveredType);

                        tooltip.innerHTML = `
                            <div class="tt_title">${foundGroup.label}</div>
                            <div class="tt_row">
                                <span class="tt_dot" style="background:#10b981"></span>
                                <span style="${hoveredType === 'collected' ? 'font-weight:700; color:#fff;' : 'color:#d1d5db;'}">Collected:</span>
                                <span class="tt_val" style="color:#34d399;">৳ ${foundGroup.collected.toLocaleString()}</span>
                            </div>
                            <div class="tt_row">
                                <span class="tt_dot" style="background:#ef4444"></span>
                                <span style="${hoveredType === 'unpaid' ? 'font-weight:700; color:#fff;' : 'color:#d1d5db;'}">Pending Due:</span>
                                <span class="tt_val" style="color:#f87171;">৳ ${foundGroup.unpaid.toLocaleString()}</span>
                            </div>
                            <div class="tt_divider"></div>
                            <div class="tt_sub_row">
                                <span>Total Invoiced:</span>
                                <span class="tt_val">৳ ${(foundGroup.collected + foundGroup.unpaid).toLocaleString()}</span>
                            </div>
                        `;

                        // Tooltip centered precisely above the month's bars
                        const barCenterX = (cRect.left - wRect.left) + foundGroup.xGroup;
                        const barTopY = (cRect.top - wRect.top) + foundGroup.topY;

                        tooltip.style.left = barCenterX + 'px';
                        tooltip.style.top = barTopY + 'px';

                        if (barTopY < 75) {
                            tooltip.classList.add('bottom_flipped');
                        } else {
                            tooltip.classList.remove('bottom_flipped');
                        }

                        tooltip.classList.add('visible');
                    } else {
                        canvas.style.cursor = 'default';
                        draw(null);
                        if (tooltip) tooltip.classList.remove('visible');
                    }
                });

                canvas.addEventListener('mouseleave', () => {
                    canvas.style.cursor = 'default';
                    draw(null);
                    if (tooltip) tooltip.classList.remove('visible');
                });
            }
        }

        // Chart B: Attendance Distribution Donut with Mouse Hover
        let attChartInitialized = false;
        let activeAttSlices = [];
        let activeAttMeta = { centerX: 0, centerY: 0, innerR: 0, outerR: 0 };

        function renderAttendanceChart() {
            const canvas = document.getElementById('attendanceDoughnutChart');
            if (!canvas) return;
            const wrapper = canvas.closest('.chart_wrapper');
            if (!wrapper) return;

            let tooltip = wrapper.querySelector('.chart_tooltip');
            if (!tooltip) {
                tooltip = document.createElement('div');
                tooltip.className = 'chart_tooltip';
                wrapper.appendChild(tooltip);
            }

            const rect = canvas.getBoundingClientRect();
            if (rect.width <= 0) return;

            const ctx = canvas.getContext('2d');
            const dpr = window.devicePixelRatio || 1;
            canvas.width = Math.round(rect.width * dpr);
            canvas.height = Math.round(rect.height * dpr);
            ctx.resetTransform ? ctx.resetTransform() : ctx.setTransform(1, 0, 0, 1, 0, 0);
            ctx.scale(dpr, dpr);

            const width = rect.width;
            const height = rect.height;

            const present = <?= (int) ($attData['kpi']['present_count'] ?? 0); ?>;
            const absent = <?= (int) ($attData['kpi']['absent_count'] ?? 0); ?>;
            const late = <?= (int) ($attData['kpi']['late_count'] ?? 0); ?>;
            const total = present + absent + late;

            if (total === 0) {
                ctx.fillStyle = '#9ca3af';
                ctx.font = '13px Inter, sans-serif';
                ctx.textAlign = 'center';
                ctx.fillText('No attendance records to chart.', width / 2, height / 2);
                activeAttSlices = [];
                return;
            }

            const rawSlices = [
                { val: present, color: '#10b981', label: 'Present' },
                { val: absent, color: '#ef4444', label: 'Absent' },
                { val: late, color: '#f59e0b', label: 'Late' }
            ];

            const centerX = width * 0.4;
            const centerY = height / 2;
            const outerR = Math.min(centerX, centerY) - 15;
            const innerR = outerR * 0.6;

            activeAttMeta = { centerX, centerY, innerR, outerR };

            const slices = [];
            let curAngle = -0.5 * Math.PI;
            rawSlices.forEach(s => {
                const sliceAngle = (s.val / total) * 2 * Math.PI;
                slices.push({
                    ...s,
                    startAngle: curAngle,
                    endAngle: curAngle + sliceAngle,
                    percentage: ((s.val / total) * 100).toFixed(1)
                });
                curAngle += sliceAngle;
            });

            activeAttSlices = slices;

            function draw(hoveredSlice = null) {
                ctx.clearRect(0, 0, width, height);

                slices.forEach(s => {
                    const isHovered = (hoveredSlice === s);
                    const rOut = isHovered ? outerR + 6 : outerR;

                    ctx.beginPath();
                    ctx.arc(centerX, centerY, rOut, s.startAngle, s.endAngle);
                    ctx.arc(centerX, centerY, innerR, s.endAngle, s.startAngle, true);
                    ctx.closePath();
                    ctx.fillStyle = s.color;
                    ctx.fill();

                    if (isHovered) {
                        ctx.strokeStyle = '#ffffff';
                        ctx.lineWidth = 2.5;
                        ctx.stroke();
                    }
                });

                // Center Percentage & Label
                ctx.fillStyle = '#111827';
                ctx.font = 'bold 20px Inter, sans-serif';
                ctx.textAlign = 'center';
                if (hoveredSlice) {
                    ctx.fillText(hoveredSlice.percentage + '%', centerX, centerY + 3);
                    ctx.font = '11px Inter, sans-serif';
                    ctx.fillStyle = '#6b7280';
                    ctx.fillText(hoveredSlice.label, centerX, centerY + 18);
                } else {
                    ctx.fillText('<?= $attData['kpi']['present_rate']; ?>%', centerX, centerY + 7);
                }

                // Legend on right
                let legY = height / 2 - 35;
                slices.forEach(s => {
                    ctx.fillStyle = s.color;
                    ctx.fillRect(width * 0.72, legY, 12, 12);
                    ctx.fillStyle = '#374151';
                    ctx.font = '12px Inter, sans-serif';
                    ctx.textAlign = 'left';
                    ctx.fillText(s.label + ' (' + s.percentage + '%)', width * 0.72 + 18, legY + 10);
                    legY += 28;
                });
            }

            draw();

            if (!attChartInitialized) {
                attChartInitialized = true;

                canvas.addEventListener('mousemove', (e) => {
                    const cRect = canvas.getBoundingClientRect();
                    const wRect = wrapper.getBoundingClientRect();
                    const mx = e.clientX - cRect.left;
                    const my = e.clientY - cRect.top;

                    const dx = mx - activeAttMeta.centerX;
                    const dy = my - activeAttMeta.centerY;
                    const dist = Math.sqrt(dx * dx + dy * dy);

                    let found = null;
                    if (dist >= activeAttMeta.innerR - 2 && dist <= activeAttMeta.outerR + 10 && activeAttSlices.length > 0) {
                        let angle = Math.atan2(dy, dx);
                        if (angle < -0.5 * Math.PI) {
                            angle += 2 * Math.PI;
                        }
                        found = activeAttSlices.find(s => angle >= s.startAngle && angle < s.endAngle);
                    }

                    if (found && tooltip) {
                        canvas.style.cursor = 'pointer';
                        draw(found);

                        tooltip.innerHTML = `
                            <div class="tt_title">Attendance Rate</div>
                            <div class="tt_row">
                                <span class="tt_dot" style="background:${found.color}"></span>
                                <span>${found.label}:</span>
                                <span class="tt_val">${found.val} Sessions (${found.percentage}%)</span>
                            </div>
                        `;
                        const mouseWrapperX = (cRect.left - wRect.left) + mx;
                        const mouseWrapperY = (cRect.top - wRect.top) + my;
                        tooltip.style.left = mouseWrapperX + 'px';
                        tooltip.style.top = mouseWrapperY + 'px';
                        tooltip.classList.remove('bottom_flipped');
                        tooltip.classList.add('visible');
                    } else {
                        canvas.style.cursor = 'default';
                        draw(null);
                        if (tooltip) tooltip.classList.remove('visible');
                    }
                });

                canvas.addEventListener('mouseleave', () => {
                    canvas.style.cursor = 'default';
                    draw(null);
                    if (tooltip) tooltip.classList.remove('visible');
                });
            }
        }

        // Chart C: Grade Distribution with Mouse Hover
        let gradeChartInitialized = false;
        let activeGradeBars = [];

        function renderGradeChart() {
            const canvas = document.getElementById('gradeDistributionChart');
            if (!canvas) return;
            const wrapper = canvas.closest('.chart_wrapper');
            if (!wrapper) return;

            let tooltip = wrapper.querySelector('.chart_tooltip');
            if (!tooltip) {
                tooltip = document.createElement('div');
                tooltip.className = 'chart_tooltip';
                wrapper.appendChild(tooltip);
            }

            const rect = canvas.getBoundingClientRect();
            if (rect.width <= 0) return;

            const ctx = canvas.getContext('2d');
            const dpr = window.devicePixelRatio || 1;
            canvas.width = Math.round(rect.width * dpr);
            canvas.height = Math.round(rect.height * dpr);
            ctx.resetTransform ? ctx.resetTransform() : ctx.setTransform(1, 0, 0, 1, 0, 0);
            ctx.scale(dpr, dpr);

            const width = rect.width;
            const height = rect.height;

            const gradeData = <?= json_encode($examData['grade_dist'] ?? []); ?>;
            if (!gradeData || gradeData.length === 0) {
                ctx.fillStyle = '#9ca3af';
                ctx.font = '13px Inter, sans-serif';
                ctx.textAlign = 'center';
                ctx.fillText('No grade distribution data available.', width / 2, height / 2);
                activeGradeBars = [];
                return;
            }

            const padding = { top: 25, right: 20, bottom: 40, left: 40 };
            const chartW = width - padding.left - padding.right;
            const chartH = height - padding.top - padding.bottom;

            let maxCount = 1;
            gradeData.forEach(g => {
                if (parseInt(g.grade_count) > maxCount) maxCount = parseInt(g.grade_count);
            });
            maxCount += 1;

            const barW = chartW / (gradeData.length * 1.5);
            const bars = [];

            gradeData.forEach((g, idx) => {
                const barH = (parseInt(g.grade_count) / maxCount) * chartH;
                const x = padding.left + idx * (barW * 1.5) + (barW * 0.25);
                const y = padding.top + chartH - barH;

                bars.push({
                    x: x,
                    y: y,
                    w: barW,
                    h: barH,
                    count: parseInt(g.grade_count),
                    grade_name: g.grade_name || 'Ungraded'
                });
            });

            activeGradeBars = bars;

            function draw(hoveredBar = null) {
                ctx.clearRect(0, 0, width, height);

                // Grid lines
                ctx.strokeStyle = '#f3f4f6';
                ctx.lineWidth = 1;
                const steps = 3;
                for (let i = 0; i <= steps; i++) {
                    const y = padding.top + (chartH / steps) * i;
                    ctx.beginPath();
                    ctx.moveTo(padding.left, y);
                    ctx.lineTo(width - padding.right, y);
                    ctx.stroke();
                }

                bars.forEach(b => {
                    const isHovered = (hoveredBar === b);
                    ctx.fillStyle = isHovered ? '#6366f1' : '#4d38c4';
                    ctx.beginPath();
                    if (ctx.roundRect) {
                        ctx.roundRect(b.x, b.y, b.w, b.h, [4, 4, 0, 0]);
                    } else {
                        ctx.rect(b.x, b.y, b.w, b.h);
                    }
                    ctx.fill();

                    if (isHovered) {
                        ctx.strokeStyle = '#ffffff';
                        ctx.lineWidth = 2.5;
                        ctx.stroke();
                        ctx.fillStyle = 'rgba(255, 255, 255, 0.25)';
                        ctx.fill();
                    }

                    // Count label above bar
                    ctx.fillStyle = '#111827';
                    ctx.font = 'bold 11px Inter, sans-serif';
                    ctx.textAlign = 'center';
                    ctx.fillText(b.count, b.x + b.w / 2, b.y - 5);

                    // Grade label below bar
                    ctx.fillStyle = '#6b7280';
                    ctx.font = '12px Inter, sans-serif';
                    ctx.fillText(b.grade_name, b.x + b.w / 2, height - 15);
                });
            }

            draw();

            if (!gradeChartInitialized) {
                gradeChartInitialized = true;

                canvas.addEventListener('mousemove', (e) => {
                    const cRect = canvas.getBoundingClientRect();
                    const wRect = wrapper.getBoundingClientRect();
                    const mx = e.clientX - cRect.left;
                    const my = e.clientY - cRect.top;

                    const found = activeGradeBars.find(b => mx >= b.x - 6 && mx <= b.x + b.w + 6 && my >= b.y - 20 && my <= height - padding.bottom + 10);

                    if (found && tooltip) {
                        canvas.style.cursor = 'pointer';
                        draw(found);

                        tooltip.innerHTML = `
                            <div class="tt_title">Grade Assessment</div>
                            <div class="tt_row">
                                <span class="tt_dot" style="background:#4d38c4"></span>
                                <span>Grade ${found.grade_name}:</span>
                                <span class="tt_val">${found.count} Students</span>
                            </div>
                        `;

                        const barCenterX = (cRect.left - wRect.left) + found.x + (found.w / 2);
                        const barTopY = (cRect.top - wRect.top) + found.y;

                        tooltip.style.left = barCenterX + 'px';
                        tooltip.style.top = barTopY + 'px';

                        if (barTopY < 60) {
                            tooltip.classList.add('bottom_flipped');
                        } else {
                            tooltip.classList.remove('bottom_flipped');
                        }

                        tooltip.classList.add('visible');
                    } else {
                        canvas.style.cursor = 'default';
                        draw(null);
                        if (tooltip) tooltip.classList.remove('visible');
                    }
                });

                canvas.addEventListener('mouseleave', () => {
                    canvas.style.cursor = 'default';
                    draw(null);
                    if (tooltip) tooltip.classList.remove('visible');
                });
            }
        }

        // Initialize on load & handle resize
        function renderActiveTabCharts() {
            const activePane = document.querySelector('.report_tab_pane.active');
            if (!activePane) return;
            if (activePane.id === 'tab-financial') renderRevenueChart();
            if (activePane.id === 'tab-attendance') renderAttendanceChart();
            if (activePane.id === 'tab-exams') renderGradeChart();
        }

        function initAllReportCharts() {
            renderActiveTabCharts();
            if (typeof updatePrintHeader === 'function') updatePrintHeader();
        }

        window.addEventListener('DOMContentLoaded', initAllReportCharts);
        window.addEventListener('load', initAllReportCharts);
        setTimeout(initAllReportCharts, 80);
        setTimeout(initAllReportCharts, 250);

        // Re-render active chart + refresh header before printing so canvas is not blank
        window.addEventListener('beforeprint', () => {
            renderActiveTabCharts();
            if (typeof updatePrintHeader === 'function') updatePrintHeader();
        });

        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                renderRevenueChart();
                renderAttendanceChart();
                renderGradeChart();
            }, 120);
        });
    </script>
</body>

</html>
