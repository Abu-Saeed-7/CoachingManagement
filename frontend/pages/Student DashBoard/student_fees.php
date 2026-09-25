<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Fees - Student Panel</title>
    <link rel="stylesheet" href="student_dashboard.css">
    <link rel="stylesheet" href="student_fees.css">
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
            <a href="student_profile.php" class="student_profile_btn">
                <span>🎓</span>
                <span class="student_name">Rahim Ahmed ▾</span>
            </a>
        </div>
    </header>

    <!-- ২. মূল লেআউট -->
    <div class="dashboard_container">
        <!-- SIDEBAR -->
        <aside class="sidebar" id="sidebar">
            <ul class="sidebar_menu">
                <li><a href="student_dashboard.php">🏠 <span>Dashboard</span></a></li>
                <li><a href="student_profile.php">👤 <span>My Profile</span></a></li>
                <li><a href="student_classes_exams.php">📅 <span>Classes & Exams</span></a></li>
                <li><a href="student_attendance.php">📋 <span>My Attendance</span></a></li>
                <li><a href="student_results.php">📊 <span>My Results</span></a></li>
                <li class="active"><a href="student_fees.php">💰 <span>My Fees</span></a></li>
                <li class="logout"><a href="../login_page/loginPage.php">🚪 <span>Logout</span></a></li>
            </ul>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="main_content">
            <div class="page_header">
                <h1>My Tuition Fees & Receipts</h1>
                <p>Check your monthly tuition fees status, payment receipts, and outstanding dues.</p>
            </div>

            <!-- SUMMARY STATS -->
            <div class="fee_summary_row">
                <div class="fee_stat_box">
                    <h4>Monthly Tuition Fee</h4>
                    <p class="number">৳ 1,500</p>
                </div>
                <div class="fee_stat_box">
                    <h4>Last Payment (August)</h4>
                    <p class="number paid">Paid (৳ 1,500)</p>
                </div>
                <div class="fee_stat_box">
                    <h4>Total Paid to Date</h4>
                    <p class="number" style="color:#4d38c4;">৳ 12,000</p>
                </div>
                <div class="fee_stat_box">
                    <h4>Outstanding Dues</h4>
                    <p class="number due">৳ 0.00</p>
                </div>
            </div>

            <!-- FEES TABLE (STEP 5.5) -->
            <div class="fees_table_card">
                <div class="card_header">
                    <h3>Payment History</h3>
                </div>

                <div class="table_container">
                    <table class="custom_table">
                        <thead>
                            <tr>
                                <th>Invoice No</th>
                                <th>Month</th>
                                <th>Amount</th>
                                <th>Payment Date</th>
                                <th>Method</th>
                                <th>Status</th>
                                <th>Receipt</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#INV-081</td>
                                <td>August 2026</td>
                                <td><strong>৳ 1,500</strong></td>
                                <td>05 Aug 2026</td>
                                <td>bKash</td>
                                <td><span class="status_paid">Paid</span></td>
                                <td><button class="btn_receipt" onclick="alert('Downloading Receipt #INV-081...')">🖨️ Download</button></td>
                            </tr>
                            <tr>
                                <td>#INV-071</td>
                                <td>July 2026</td>
                                <td><strong>৳ 1,500</strong></td>
                                <td>06 Jul 2026</td>
                                <td>Cash</td>
                                <td><span class="status_paid">Paid</span></td>
                                <td><button class="btn_receipt" onclick="alert('Downloading Receipt #INV-071...')">🖨️ Download</button></td>
                            </tr>
                            <tr>
                                <td>#INV-061</td>
                                <td>June 2026</td>
                                <td><strong>৳ 1,500</strong></td>
                                <td>04 Jun 2026</td>
                                <td>bKash</td>
                                <td><span class="status_paid">Paid</span></td>
                                <td><button class="btn_receipt" onclick="alert('Downloading Receipt #INV-061...')">🖨️ Download</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- JavaScript -->
    <script>
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        sidebarToggle.addEventListener('click', function () {
            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('mobile_open');
            } else {
                sidebar.classList.toggle('collapsed');
            }
        });
    </script>
</body>

</html>
