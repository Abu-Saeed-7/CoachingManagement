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
                    <h4>Total Collected (This Month)</h4>
                    <p class="amount collected">৳ 1,25,000</p>
                </div>
                <div class="fee_stat_box">
                    <h4>Pending Dues (Unpaid)</h4>
                    <p class="amount pending">৳ 22,500</p>
                </div>
                <div class="fee_stat_box">
                    <h4>Total Fee Records</h4>
                    <p class="amount">120 Invoices</p>
                </div>
            </div>

            <!-- সার্চ ও ফিল্টার বার -->
            <div class="table_controls">
                <input type="text" class="search_input" placeholder="🔍 Search student name or invoice...">
                <select class="filter_select">
                    <option value="">All Batches</option>
                    <option value="Batch A">Batch A</option>
                    <option value="Batch B">Batch B</option>
                    <option value="Batch C">Batch C</option>
                </select>
                <select class="filter_select">
                    <option value="">Payment Status</option>
                    <option value="Paid">Paid</option>
                    <option value="Unpaid">Unpaid</option>
                </select>
            </div>

            <!-- ফিস টেবিল -->
            <div class="table_container">
                <table class="custom_table">
                    <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Student Name</th>
                            <th>Batch</th>
                            <th>Month</th>
                            <th>Amount</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#INV-081</td>
                            <td><strong>Rahim Ahmed</strong></td>
                            <td><span class="badge">Batch A</span></td>
                            <td>August 2026</td>
                            <td><strong>৳ 1,500</strong></td>
                            <td>10 Aug 2026</td>
                            <td><span class="status_paid">Paid</span></td>
                            <td>
                                <button class="btn_icon" title="Print Receipt">🖨️</button>
                                <button class="btn_icon edit_btn" title="Edit">✏️</button>
                            </td>
                        </tr>
                        <tr>
                            <td>#INV-082</td>
                            <td><strong>Karim Khan</strong></td>
                            <td><span class="badge">Batch B</span></td>
                            <td>August 2026</td>
                            <td><strong>৳ 1,500</strong></td>
                            <td>10 Aug 2026</td>
                            <td><span class="status_paid">Paid</span></td>
                            <td>
                                <button class="btn_icon" title="Print Receipt">🖨️</button>
                                <button class="btn_icon edit_btn" title="Edit">✏️</button>
                            </td>
                        </tr>
                        <tr>
                            <td>#INV-083</td>
                            <td><strong>Sakib Hasan</strong></td>
                            <td><span class="badge">Batch A</span></td>
                            <td>August 2026</td>
                            <td><strong>৳ 1,500</strong></td>
                            <td>10 Aug 2026</td>
                            <td><span class="status_unpaid">Unpaid</span></td>
                            <td>
                                <button class="btn_pay">Collect</button>
                                <button class="btn_icon edit_btn" title="Edit">✏️</button>
                            </td>
                        </tr>
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
            <form class="modal_form">
                <div class="form_group">
                    <label>Select Student</label>
                    <select required>
                        <option value="">Select Student</option>
                        <option value="101">Rahim Ahmed (#101 - Batch A)</option>
                        <option value="102">Karim Khan (#102 - Batch B)</option>
                        <option value="103">Sakib Hasan (#103 - Batch A)</option>
                    </select>
                </div>
                <div class="form_row">
                    <div class="form_group">
                        <label>Fee Month</label>
                        <select required>
                            <option value="August 2026">August 2026</option>
                            <option value="September 2026">September 2026</option>
                            <option value="October 2026">October 2026</option>
                        </select>
                    </div>
                    <div class="form_group">
                        <label>Fee Amount (BDT)</label>
                        <input type="number" placeholder="1500" min="0" required>
                    </div>
                </div>
                <div class="form_row">
                    <div class="form_group">
                        <label>Payment Method</label>
                        <select required>
                            <option value="Cash">Cash</option>
                            <option value="bKash / Nagad">bKash / Nagad</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                        </select>
                    </div>
                    <div class="form_group">
                        <label>Payment Status</label>
                        <select required>
                            <option value="Paid">Paid</option>
                            <option value="Unpaid">Unpaid / Due</option>
                        </select>
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

        // Modal Open / Close
        const openAddModal = document.getElementById('openAddModal');
        const closeModal = document.getElementById('closeModal');
        const cancelModal = document.getElementById('cancelModal');
        const feeModal = document.getElementById('feeModal');

        openAddModal.addEventListener('click', () => feeModal.classList.add('show'));
        closeModal.addEventListener('click', () => feeModal.classList.remove('show'));
        cancelModal.addEventListener('click', () => feeModal.classList.remove('show'));
    </script>
</body>

</html>
