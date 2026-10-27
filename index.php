<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduManage - Modern Coaching & Academic Management System</title>
    <meta name="description" content="EduManage is an all-in-one coaching center management system. Manage student admissions, batch scheduling, daily attendance, exam marksheets, and automated tuition fee ledgers.">
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="landing.css">
</head>
<body>

    <!-- ==========================================================================
         TOP NAVIGATION BAR
         ========================================================================== -->
    <header class="site-nav">
        <div class="container nav-wrap">
            <a href="index.php" class="brand-logo" aria-label="EduManage Home">
                <div class="logo-cap-box">
                    <svg viewBox="0 0 640 640" xmlns="http://www.w3.org/2000/svg">
                        <path d="M80 259.8L289.2 345.9C299 349.9 309.4 352 320 352C330.6 352 341 349.9 350.8 345.9L593.2 246.1C602.2 242.4 608 233.7 608 224C608 214.3 602.2 205.6 593.2 201.9L350.8 102.1C341 98.1 330.6 96 320 96C309.4 96 299 98.1 289.2 102.1L46.8 201.9C37.8 205.6 32 214.3 32 224L32 520C32 533.3 42.7 544 56 544C69.3 544 80 533.3 80 520L80 259.8zM128 331.5L128 448C128 501 214 544 320 544C426 544 512 501 512 448L512 331.4L369.1 390.3C353.5 396.7 336.9 400 320 400C303.1 400 286.5 396.7 270.9 390.3L128 331.4z"/>
                    </svg>
                </div>
                <div class="brand-text">
                    <span class="brand-title">EduManage</span>
                    <span class="brand-subtitle">Coaching ERP System</span>
                </div>
            </a>

            <nav>
                <ul class="nav-menu" id="navMenu">
                    <li><a href="#features" class="nav-link">Features</a></li>
                    <li><a href="#portals" class="nav-link">Role Portals</a></li>
                    <li><a href="#workflow" class="nav-link">How It Works</a></li>
                    <li><a href="#comparison" class="nav-link">Why EduManage</a></li>
                    <li><a href="#faq" class="nav-link">FAQ</a></li>
                </ul>
            </nav>

            <div class="nav-actions">
                <a href="frontend/pages/login_page/loginPage.php" class="btn-portal-login" id="navLoginBtn">
                    <span>Portal Sign In</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </a>
                <button class="mobile-menu-btn" id="mobileMenuToggle" aria-label="Toggle navigation menu">☰</button>
            </div>
        </div>
    </header>

    <!-- ==========================================================================
         HERO SECTION
         ========================================================================== -->
    <section class="hero-section">
        <div class="hero-glow-bg"></div>
        <div class="container hero-content">
            <div class="badge-pill">
                <span class="badge-pulse"></span>
                <span>Next-Generation Coaching Center Management ERP</span>
            </div>

            <h1 class="hero-title">
                The Smartest Way to Run Your Coaching Center on <span class="gradient-text">One Unified Platform</span>
            </h1>

            <p class="hero-desc">
                Eliminate paper registers and error-prone spreadsheets. EduManage seamlessly unites 
                <strong>Administrators</strong>, <strong>Faculty</strong>, and <strong>Students</strong> with real-time admissions, 
                automated attendance, instant grade calculations, and transparent fee ledgers.
            </p>

            <div class="hero-cta-group">
                <a href="frontend/pages/login_page/loginPage.php" class="btn-primary-lg" id="heroLoginBtn">
                    <span>Sign In to Portal</span>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </a>
                <a href="#portals" class="btn-secondary-lg">Explore Portals ↓</a>
            </div>

            <div class="hero-trust-bar">
                <div class="trust-item"><span class="trust-icon">✓</span> 3 Dedicated Portals</div>
                <div class="trust-item"><span class="trust-icon">✓</span> 1-Click Daily Attendance</div>
                <div class="trust-item"><span class="trust-icon">✓</span> Zero Duplicate Billing</div>
                <div class="trust-item"><span class="trust-icon">✓</span> Auto-Graded Marksheets</div>
            </div>

            <!-- INTERACTIVE DASHBOARD PREVIEW MOCKUP -->
            <div class="hero-mockup-wrap">
                <div class="mockup-window">
                    <div class="mockup-window-bar">
                        <div class="mockup-dots">
                            <span class="mockup-dot red"></span>
                            <span class="mockup-dot yellow"></span>
                            <span class="mockup-dot green"></span>
                        </div>
                        <div class="mockup-address">
                            🔒 https://edumanage.portal/dashboard/admin
                        </div>
                        <div style="font-size: 11px; color: #94a3b8; font-weight: 500;">Live Preview</div>
                    </div>
                    <div class="mockup-body">
                        <!-- Stat counters -->
                        <div class="mockup-stats-grid">
                            <div class="mockup-stat-card">
                                <div class="mockup-stat-label">Total Enrolled Students</div>
                                <div class="mockup-stat-value">256</div>
                                <span class="mockup-stat-tag tag-blue">+18 this month</span>
                            </div>
                            <div class="mockup-stat-card">
                                <div class="mockup-stat-label">Active Academic Batches</div>
                                <div class="mockup-stat-value">12</div>
                                <span class="mockup-stat-tag tag-purple">100% capacity</span>
                            </div>
                            <div class="mockup-stat-card">
                                <div class="mockup-stat-label">Today's Attendance Rate</div>
                                <div class="mockup-stat-value">98.4%</div>
                                <span class="mockup-stat-tag tag-green">Verified on-time</span>
                            </div>
                            <div class="mockup-stat-card">
                                <div class="mockup-stat-label">Tuition Fee Realization</div>
                                <div class="mockup-stat-value">95.2%</div>
                                <span class="mockup-stat-tag tag-amber">Automated ledger</span>
                            </div>
                        </div>

                        <!-- Panels preview -->
                        <div class="mockup-preview-panels">
                            <div class="preview-table-card">
                                <div class="preview-table-head">
                                    <span>Recent Student Admissions & Batch Allotment</span>
                                    <span style="font-size: 12px; color: #4d38c4; font-weight: 600;">View All →</span>
                                </div>
                                <table class="preview-table">
                                    <thead>
                                        <tr>
                                            <th>Roll</th>
                                            <th>Student Name</th>
                                            <th>Batch Code</th>
                                            <th>Fee Status</th>
                                            <th>Latest Exam</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><strong>#101</strong></td>
                                            <td>Rahim Ahmed</td>
                                            <td>HSC Physics Batch-A</td>
                                            <td><span class="badge-status badge-paid">Paid</span></td>
                                            <td><span class="badge-status badge-grade">94/100 (A+)</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>#102</strong></td>
                                            <td>Sadia Karim</td>
                                            <td>HSC Math Batch-B</td>
                                            <td><span class="badge-status badge-paid">Paid</span></td>
                                            <td><span class="badge-status badge-grade">88/100 (A+)</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>#103</strong></td>
                                            <td>Tanvir Hassan</td>
                                            <td>SSC Chemistry Batch-1</td>
                                            <td><span class="badge-status badge-active">Active</span></td>
                                            <td><span class="badge-status badge-grade">82/100 (A)</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="preview-quick-actions">
                                <div>
                                    <h3 style="font-size: 14px; font-weight: 700; margin-bottom: 12px; color: #0f172a;">Direct Portal Quick Access</h3>
                                    <div class="action-role-box">
                                        <h4>👑 Administrator Hub</h4>
                                        <p>Faculty setup, tuition receipts, student admissions & audit reports.</p>
                                    </div>
                                    <div class="action-role-box" style="background: #ecfdf5; border-color: #a7f3d0;">
                                        <h4 style="color: #059669;">👨‍🏫 Teacher Workspace</h4>
                                        <p>1-click attendance marking and fast exam marks entry.</p>
                                    </div>
                                </div>
                                <a href="frontend/pages/login_page/loginPage.php" class="btn-portal-login" style="width: 100%; justify-content: center;">
                                    Sign In to Your Account
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==========================================================================
         ROLE-BASED PORTALS SHOWCASE (TABS)
         ========================================================================== -->
    <section class="roles-section" id="portals">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">Role-Based Access Control</span>
                <h2 class="section-title">Built for Everyone in Your Institute</h2>
                <p class="section-desc">
                    Each stakeholder gets an intuitive, custom-tailored workspace equipped with precisely the tools they need to succeed.
                </p>
            </div>

            <div class="role-tabs-wrapper">
                <button class="role-tab-btn active" data-tab="admin">
                    <span>👑</span> Administrator Portal
                </button>
                <button class="role-tab-btn" data-tab="teacher">
                    <span>👨‍🏫</span> Faculty & Teachers
                </button>
                <button class="role-tab-btn" data-tab="student">
                    <span>🎓</span> Students & Guardians
                </button>
            </div>

            <!-- ADMIN TAB CONTENT -->
            <div class="role-content-card active" id="tab-admin">
                <div class="role-info-content">
                    <div class="role-info-head">
                        <div class="role-badge-icon">👑</div>
                        <div>
                            <h3 class="role-headline">Administrator Control Center</h3>
                            <span class="role-subhead">Total Oversight & Financial Management</span>
                        </div>
                    </div>
                    <p class="role-desc-text">
                        Complete institutional visibility in one place. Admit new students, manage faculty allocations, monitor batch capacities, generate tuition vouchers, and inspect comprehensive audit reports.
                    </p>
                    <ul class="role-feature-list">
                        <li class="role-feature-item">
                            <span class="check-icon">✓</span>
                            <span><strong>Single-step Admissions:</strong> Creates login credentials and student profiles in a single atomic database transaction.</span>
                        </li>
                        <li class="role-feature-item">
                            <span class="check-icon">✓</span>
                            <span><strong>Tuition Fee Ledger:</strong> Real-time payment verification (Paid / Unpaid / Partial) with instant receipt generation.</span>
                        </li>
                        <li class="role-feature-item">
                            <span class="check-icon">✓</span>
                            <span><strong>Batch & Timetable Management:</strong> Assign teachers to multiple subject cohorts with zero scheduling overlaps.</span>
                        </li>
                        <li class="role-feature-item">
                            <span class="check-icon">✓</span>
                            <span><strong>Executive Reports:</strong> Monthly financial summaries, attendance trends, and academic performance graphs.</span>
                        </li>
                    </ul>
                    <a href="frontend/pages/login_page/loginPage.php?role=admin" class="role-cta-btn">
                        <span>Sign In as Admin</span>
                        <span>→</span>
                    </a>
                </div>
                <div class="role-visual-panel">
                    <div class="role-card-mini">
                        <div class="mini-head">
                            <span>Admission Desk</span>
                            <span class="badge-status badge-paid">Automated</span>
                        </div>
                        <p class="mini-desc">Auto-links student biodata, guardian emergency contact, and batch enrollment.</p>
                    </div>
                    <div class="role-card-mini">
                        <div class="mini-head">
                            <span>Financial Ledger</span>
                            <span class="badge-status badge-active">Real-time</span>
                        </div>
                        <p class="mini-desc">Live calculation of monthly tuition collections and overdue fee balances.</p>
                    </div>
                    <div class="role-card-mini">
                        <div class="mini-head">
                            <span>Faculty Allocations</span>
                            <span class="badge-status badge-paid">Multi-subject</span>
                        </div>
                        <p class="mini-desc">Assign senior teachers to specialized batches with timetable routine sync.</p>
                    </div>
                </div>
            </div>

            <!-- TEACHER TAB CONTENT -->
            <div class="role-content-card" id="tab-teacher">
                <div class="role-info-content">
                    <div class="role-info-head">
                        <div class="role-badge-icon teacher">👨‍🏫</div>
                        <div>
                            <h3 class="role-headline">Faculty & Teacher Workspace</h3>
                            <span class="role-subhead">Effortless Classroom Management</span>
                        </div>
                    </div>
                    <p class="role-desc-text">
                        Teachers spend less time on paperwork and more time teaching. Log attendance for whole batches in seconds and publish exam marks directly to students.
                    </p>
                    <ul class="role-feature-list">
                        <li class="role-feature-item">
                            <span class="check-icon">✓</span>
                            <span><strong>1-Click Batch Attendance:</strong> Fast daily roster check-in with database-level composite unique enforcement preventing duplicate logs.</span>
                        </li>
                        <li class="role-feature-item">
                            <span class="check-icon">✓</span>
                            <span><strong>Exam Marks Evaluation:</strong> Enter assessment scores with built-in maximum mark validation and automated grade conversion (A+, A, B, etc.).</span>
                        </li>
                        <li class="role-feature-item">
                            <span class="check-icon">✓</span>
                            <span><strong>Assigned Batch Rosters:</strong> Instant access to student directory and performance histories across all assigned courses.</span>
                        </li>
                        <li class="role-feature-item">
                            <span class="check-icon">✓</span>
                            <span><strong>Class Routine & Timetables:</strong> Stay updated with weekly classroom routines and scheduled evaluation dates.</span>
                        </li>
                    </ul>
                    <a href="frontend/pages/login_page/loginPage.php?role=teacher" class="role-cta-btn" style="background: #059669;">
                        <span>Sign In as Teacher</span>
                        <span>→</span>
                    </a>
                </div>
                <div class="role-visual-panel">
                    <div class="role-card-mini">
                        <div class="mini-head">
                            <span>Daily Attendance</span>
                            <span class="badge-status badge-paid" style="background:#dcfce7; color:#15803d;">Instant</span>
                        </div>
                        <p class="mini-desc">Present/Absent toggles with single-tap batch submission and zero paperwork.</p>
                    </div>
                    <div class="role-card-mini">
                        <div class="mini-head">
                            <span>Automated Gradebook</span>
                            <span class="badge-status badge-grade">Auto-Calculated</span>
                        </div>
                        <p class="mini-desc">Input raw scores; EduManage auto-calculates percentages and official letter grades.</p>
                    </div>
                    <div class="role-card-mini">
                        <div class="mini-head">
                            <span>Class Roster</span>
                            <span class="badge-status badge-active">Active</span>
                        </div>
                        <p class="mini-desc">Searchable list of enrolled students per batch with emergency guardian contacts.</p>
                    </div>
                </div>
            </div>

            <!-- STUDENT TAB CONTENT -->
            <div class="role-content-card" id="tab-student">
                <div class="role-info-content">
                    <div class="role-info-head">
                        <div class="role-badge-icon student">🎓</div>
                        <div>
                            <h3 class="role-headline">Student & Guardian Portal</h3>
                            <span class="role-subhead">Academic Progress & Transparency</span>
                        </div>
                    </div>
                    <p class="role-desc-text">
                        Empower students and parents with 24/7 visibility into their educational progress. View routine schedules, personal attendance records, exam scorecards, and tuition receipts.
                    </p>
                    <ul class="role-feature-list">
                        <li class="role-feature-item">
                            <span class="check-icon">✓</span>
                            <span><strong>Digital Gradebook & Scorecards:</strong> Instant notification and breakdown of published test marks with letter grade rankings.</span>
                        </li>
                        <li class="role-feature-item">
                            <span class="check-icon">✓</span>
                            <span><strong>Personal Attendance History:</strong> Track day-by-day attendance percentages to maintain consistent classroom commitment.</span>
                        </li>
                        <li class="role-feature-item">
                            <span class="check-icon">✓</span>
                            <span><strong>Tuition Invoices & Payment Ledger:</strong> Clear invoice histories displaying paid vouchers, monthly amounts, and due dates.</span>
                        </li>
                        <li class="role-feature-item">
                            <span class="check-icon">✓</span>
                            <span><strong>Timetable & Exam Notices:</strong> Never miss a class or exam with a clear personalized timetable.</span>
                        </li>
                    </ul>
                    <a href="frontend/pages/login_page/loginPage.php?role=student" class="role-cta-btn" style="background: #2563eb;">
                        <span>Sign In as Student</span>
                        <span>→</span>
                    </a>
                </div>
                <div class="role-visual-panel">
                    <div class="role-card-mini">
                        <div class="mini-head">
                            <span>Exam Scorecard</span>
                            <span class="badge-status badge-grade">Grade A+ (94%)</span>
                        </div>
                        <p class="mini-desc">Immediate feedback on quizzes, midterms, and final model tests.</p>
                    </div>
                    <div class="role-card-mini">
                        <div class="mini-head">
                            <span>Attendance Score</span>
                            <span class="badge-status badge-paid" style="background:#dcfce7; color:#15803d;">98% Present</span>
                        </div>
                        <p class="mini-desc">Transparent monthly records accessible to students and guardians alike.</p>
                    </div>
                    <div class="role-card-mini">
                        <div class="mini-head">
                            <span>Fee Receipt History</span>
                            <span class="badge-status badge-paid">Verified</span>
                        </div>
                        <p class="mini-desc">Download digital payment vouchers and confirm monthly balance clearances.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==========================================================================
         CORE FEATURES SECTION
         ========================================================================== -->
    <section class="features-section" id="features">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">System Capabilities</span>
                <h2 class="section-title">Engineered for Academic Excellence</h2>
                <p class="section-desc">
                    Explore the full spectrum of automated tools designed to eliminate operational friction and elevate your coaching center.
                </p>
            </div>

            <div class="features-grid">
                <!-- Feature 1 -->
                <div class="feature-box">
                    <div class="feature-icon-box">🎓</div>
                    <h3 class="feature-title">Single-Step Admissions</h3>
                    <p class="feature-desc">
                        Admit students with full biodata, guardian emergency contacts, and batch enrollment in one atomic transaction with zero duplicate entries.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="feature-box color-emerald">
                    <div class="feature-icon-box">📋</div>
                    <h3 class="feature-title">1-Click Daily Attendance</h3>
                    <p class="feature-desc">
                        Fast attendance marking for full batches. Enforces strict composite unique rules per date and batch to eliminate loose-sheet data loss.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="feature-box color-blue">
                    <div class="feature-icon-box">💳</div>
                    <h3 class="feature-title">Automated Tuition Ledgers</h3>
                    <p class="feature-desc">
                        Track monthly fees, paid receipts, partial payments, and overdue balances with instant audit summaries and printable receipts.
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="feature-box color-amber">
                    <div class="feature-icon-box">📊</div>
                    <h3 class="feature-title">Exams & Automated Grading</h3>
                    <p class="feature-desc">
                        Schedule batch examinations with subject weighting. Evaluation automatically checks max marks and assigns standard letter grades.
                    </p>
                </div>

                <!-- Feature 5 -->
                <div class="feature-box color-rose">
                    <div class="feature-icon-box">📅</div>
                    <h3 class="feature-title">Conflict-Free Timetables</h3>
                    <p class="feature-desc">
                        Manage weekly batch routines, room allocations, and subject teachers with automated collision prevention across time slots.
                    </p>
                </div>

                <!-- Feature 6 -->
                <div class="feature-box color-cyan">
                    <div class="feature-icon-box">📑</div>
                    <h3 class="feature-title">Executive Analytics & Reports</h3>
                    <p class="feature-desc">
                        Real-time KPI metrics, revenue breakdowns, batch health indicators, and student attendance averages formatted for decision makers.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- ==========================================================================
         WORKFLOW: HOW IT WORKS
         ========================================================================== -->
    <section class="workflow-section" id="workflow">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">Smooth Onboarding</span>
                <h2 class="section-title">How EduManage Works</h2>
                <p class="section-desc">
                    A streamlined lifecycle from initial student intake to final scorecard publication.
                </p>
            </div>

            <div class="workflow-steps-grid">
                <div class="workflow-card">
                    <div class="workflow-num">1</div>
                    <h3 class="workflow-title">Admit & Enroll</h3>
                    <p class="workflow-desc">Admin creates batches, sets subject timetables, and enrolls students with system-generated accounts.</p>
                </div>
                <div class="workflow-card">
                    <div class="workflow-num">2</div>
                    <h3 class="workflow-title">Teach & Track</h3>
                    <p class="workflow-desc">Teachers access their classroom dashboard, check schedules, and record daily batch attendance.</p>
                </div>
                <div class="workflow-card">
                    <div class="workflow-num">3</div>
                    <h3 class="workflow-title">Assess & Grade</h3>
                    <p class="workflow-desc">Exams are scheduled. Teachers input student marks, and the system computes verified grades.</p>
                </div>
                <div class="workflow-card">
                    <div class="workflow-num">4</div>
                    <h3 class="workflow-title">Settle & Report</h3>
                    <p class="workflow-desc">Tuition receipts are reconciled and administrators generate complete institutional reports.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ==========================================================================
         COMPARISON SECTION: MANUAL VS EDUMANAGE
         ========================================================================== -->
    <section class="comparison-section" id="comparison">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">The Difference</span>
                <h2 class="section-title">Manual Registers vs. EduManage ERP</h2>
                <p class="section-desc">
                    See why leading coaching institutes are upgrading from scattered spreadsheets to EduManage.
                </p>
            </div>

            <div class="comparison-table-wrap">
                <table class="comparison-table">
                    <thead>
                        <tr>
                            <th class="col-feature">Institutional Feature</th>
                            <th class="col-bad">Traditional Registers & Sheets</th>
                            <th class="col-good">EduManage System</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Student Admissions</strong></td>
                            <td class="col-bad">Repeated manual entries, misplaced paper files</td>
                            <td class="col-good">Single-transaction database creation with login</td>
                        </tr>
                        <tr>
                            <td><strong>Attendance Tracking</strong></td>
                            <td class="col-bad">Loose sheets prone to damage & duplicate records</td>
                            <td class="col-good">1-Click batch check-in with duplicate prevention</td>
                        </tr>
                        <tr>
                            <td><strong>Fee & Ledger Accounting</strong></td>
                            <td class="col-bad">64% reported fee tracking as most error-prone task</td>
                            <td class="col-good">Automated voucher records (Paid / Unpaid / Due)</td>
                        </tr>
                        <tr>
                            <td><strong>Exam Evaluation & Grading</strong></td>
                            <td class="col-bad">Manual manual grade calculation mistakes</td>
                            <td class="col-good">Auto-calculated letter grades & instant portal viewing</td>
                        </tr>
                        <tr>
                            <td><strong>Data Security & Access</strong></td>
                            <td class="col-bad">Anyone can view or lose physical registers</td>
                            <td class="col-good">Encrypted passwords & strict role-based isolation</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- ==========================================================================
         FAQ SECTION
         ========================================================================== -->
    <section class="faq-section" id="faq">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">Got Questions?</span>
                <h2 class="section-title">Frequently Asked Questions</h2>
                <p class="section-desc">
                    Everything you need to know about navigating and utilizing the EduManage platform.
                </p>
            </div>

            <div class="faq-list">
                <div class="faq-item open">
                    <button class="faq-question-btn">
                        <span>How do students and teachers receive their login accounts?</span>
                        <span class="faq-icon-cross">+</span>
                    </button>
                    <div class="faq-answer">
                        When an administrator admits a new student or registers a faculty member, the system automatically creates their secure login credentials. Users simply select their respective role on the portal login page and sign in with their registered email address.
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question-btn">
                        <span>Can a student enroll in multiple batches or subjects simultaneously?</span>
                        <span class="faq-icon-cross">+</span>
                    </button>
                    <div class="faq-answer">
                        Yes. EduManage is built on a normalized relational database supporting Many-to-Many (M:N) student enrollments. Students can belong to multiple batches (e.g., HSC Physics Batch-A and HSC Chemistry Batch-B) without any data conflicts.
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question-btn">
                        <span>How does the system prevent duplicate attendance entries?</span>
                        <span class="faq-icon-cross">+</span>
                    </button>
                    <div class="faq-answer">
                        EduManage enforces composite unique constraints at both the database and controller levels. This ensures that attendance for a specific batch on any given date can only be recorded once, completely eliminating duplicate logs.
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question-btn">
                        <span>What should I do if a student or teacher forgets their password?</span>
                        <span class="faq-icon-cross">+</span>
                    </button>
                    <div class="faq-answer">
                        For institutional security, password resets are centrally managed by the System Administrator. Users can click the "Forgot password?" or "Contact Administrator" link on the login page to submit a reset request.
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question-btn">
                        <span>Is the platform mobile-responsive?</span>
                        <span class="faq-icon-cross">+</span>
                    </button>
                    <div class="faq-answer">
                        Yes! All dashboards, attendance entry sheets, marksheets, and the landing page are fully optimized for smartphones, tablets, laptops, and ultra-wide desktop monitors.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==========================================================================
         CALL TO ACTION BANNER
         ========================================================================== -->
    <section class="cta-banner-section">
        <div class="container">
            <div class="cta-banner-box">
                <h2 class="cta-banner-title">Ready to Experience Modern Coaching Management?</h2>
                <p class="cta-banner-desc">
                    Sign in to your personalized portal and take complete control of admissions, schedules, attendance, grades, and finance.
                </p>
                <a href="frontend/pages/login_page/loginPage.php" class="cta-button-white" id="ctaLoginBtn">
                    <span>Sign In to EduManage Portal</span>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- ==========================================================================
         FOOTER
         ========================================================================== -->
    <footer class="site-footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <div class="brand-logo" style="margin-bottom: 8px;">
                        <div class="logo-cap-box">
                            <svg viewBox="0 0 640 640" xmlns="http://www.w3.org/2000/svg">
                                <path d="M80 259.8L289.2 345.9C299 349.9 309.4 352 320 352C330.6 352 341 349.9 350.8 345.9L593.2 246.1C602.2 242.4 608 233.7 608 224C608 214.3 602.2 205.6 593.2 201.9L350.8 102.1C341 98.1 330.6 96 320 96C309.4 96 299 98.1 289.2 102.1L46.8 201.9C37.8 205.6 32 214.3 32 224L32 520C32 533.3 42.7 544 56 544C69.3 544 80 533.3 80 520L80 259.8zM128 331.5L128 448C128 501 214 544 320 544C426 544 512 501 512 448L512 331.4L369.1 390.3C353.5 396.7 336.9 400 320 400C303.1 400 286.5 396.7 270.9 390.3L128 331.4z"/>
                            </svg>
                        </div>
                        <span class="brand-title">EduManage</span>
                    </div>
                    <p class="footer-desc">
                        A modern, enterprise-grade coaching and academy management system connecting administrators, faculty, and students seamlessly.
                    </p>
                </div>

                <div class="footer-col">
                    <h4 class="footer-col-title">Portals</h4>
                    <ul class="footer-links">
                        <li><a href="frontend/pages/login_page/loginPage.php?role=admin">Administrator Portal</a></li>
                        <li><a href="frontend/pages/login_page/loginPage.php?role=teacher">Faculty / Teacher Portal</a></li>
                        <li><a href="frontend/pages/login_page/loginPage.php?role=student">Student & Parent Portal</a></li>
                        <li><a href="frontend/pages/login_page/loginPage.php">Unified Sign In</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h4 class="footer-col-title">Navigation</h4>
                    <ul class="footer-links">
                        <li><a href="#features">Key Features</a></li>
                        <li><a href="#portals">Role Portals</a></li>
                        <li><a href="#workflow">Workflow</a></li>
                        <li><a href="#comparison">Manual vs ERP</a></li>
                        <li><a href="#faq">FAQ</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h4 class="footer-col-title">Support & Admin</h4>
                    <ul class="footer-links">
                        <li><a href="mailto:admin@edumanage.com">Contact Administrator</a></li>
                        <li><a href="frontend/pages/login_page/loginPage.php">Password Assistance</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms of Service</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <div>&copy; <?= date('Y'); ?> EduManage Coaching Management System. All rights reserved.</div>
                <div class="footer-bottom-links">
                    <a href="#">Privacy Policy</a>
                    <span>•</span>
                    <a href="#">Terms of Service</a>
                    <span>•</span>
                    <a href="#">Help Center</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- ==========================================================================
         LIGHTWEIGHT INTERACTIVE JAVASCRIPT
         ========================================================================== -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 1. Mobile Menu Toggle
            const mobileBtn = document.getElementById('mobileMenuToggle');
            const navMenu = document.getElementById('navMenu');
            if (mobileBtn && navMenu) {
                mobileBtn.addEventListener('click', function () {
                    navMenu.classList.toggle('open');
                });
                // Close menu when clicking any nav link
                navMenu.querySelectorAll('a').forEach(link => {
                    link.addEventListener('click', () => navMenu.classList.remove('open'));
                });
            }

            // 2. Role Showcase Tab Switching
            const roleButtons = document.querySelectorAll('.role-tab-btn');
            const roleCards = document.querySelectorAll('.role-content-card');

            roleButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const targetTab = this.getAttribute('data-tab');

                    // Active button state
                    roleButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');

                    // Active card display
                    roleCards.forEach(card => {
                        card.classList.remove('active');
                        if (card.id === 'tab-' + targetTab) {
                            card.classList.add('active');
                        }
                    });
                });
            });

            // 3. FAQ Accordion Toggle
            const faqItems = document.querySelectorAll('.faq-item');
            faqItems.forEach(item => {
                const btn = item.querySelector('.faq-question-btn');
                btn.addEventListener('click', function () {
                    const isOpen = item.classList.contains('open');

                    // Close all others for accordion behavior
                    faqItems.forEach(otherItem => otherItem.classList.remove('open'));

                    // Toggle current
                    if (!isOpen) {
                        item.classList.add('open');
                    }
                });
            });

            // 4. Smooth Anchor Scrolling
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    const targetId = this.getAttribute('href');
                    if (targetId === '#') return;
                    const targetEl = document.querySelector(targetId);
                    if (targetEl) {
                        e.preventDefault();
                        const offsetTop = targetEl.getBoundingClientRect().top + window.pageYOffset - 80;
                        window.scrollTo({
                            top: offsetTop,
                            behavior: 'smooth'
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>
