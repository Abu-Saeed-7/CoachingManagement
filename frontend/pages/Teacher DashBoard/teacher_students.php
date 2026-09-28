<?php
require_once __DIR__ . '/../../../backend/middleware/auth.php';
require_once __DIR__ . '/../../../backend/controllers/BatchController.php';
require_once __DIR__ . '/../../../backend/controllers/StudentController.php';

checkAuth(['teacher', 'admin']);

$teacher = getCurrentTeacherSession();
$teacherId = $teacher['id'];
$teacherName = $teacher['name'];

$batchController = new BatchController();
$studentController = new StudentController();

$myBatches = $batchController->getTeacherBatches((int) $teacherId);
$students = $studentController->getTeacherStudents((int) $teacherId);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Students - Teacher Panel</title>
    <link rel="stylesheet" href="teacher_dashboard.css">
    <link rel="stylesheet" href="teacher_students.css">
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
            <a href="teacher_profile.php" class="teacher_profile_btn">
                <span>👨‍🏫</span>
                <span class="teacher_name"><?= htmlspecialchars($teacherName, ENT_QUOTES, 'UTF-8'); ?> ▾</span>
            </a>
        </div>
    </header>

    <!-- ২. মূল লেআউট -->
    <div class="dashboard_container">
        <!-- SIDEBAR -->
        <aside class="sidebar" id="sidebar">
            <ul class="sidebar_menu">
                <li><a href="teacher_dashboard.php">🏠 <span>Dashboard</span></a></li>
                <li><a href="teacher_batches.php">📚 <span>My Batches</span></a></li>
                <li class="active"><a href="teacher_students.php">🎓 <span>My Students</span></a></li>
                <li><a href="teacher_attendance.php">📋 <span>Attendance</span></a></li>
                <li><a href="teacher_results.php">📊 <span>Enter Results</span></a></li>
                <li><a href="teacher_profile.php">👤 <span>My Profile</span></a></li>
                <li class="logout"><a href="../login_page/loginPage.php">🚪 <span>Logout</span></a></li>
            </ul>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="main_content">
            <!-- PAGE HEADER -->
            <div class="page_header">
                <div>
                    <h1>My Assigned Students</h1>
                    <p>Viewing students currently enrolled in your batches.</p>
                </div>
            </div>

            <!-- SEARCH & ASSIGNED BATCHES FILTER -->
            <div class="table_controls">
                <input type="text" id="searchInput" class="search_input" placeholder="🔍 Search by student name, roll or phone...">
                <select id="batchFilter" class="filter_select">
                    <option value="">All My Batches</option>
                    <?php foreach ($myBatches as $b): ?>
                        <option value="<?= htmlspecialchars($b['name'], ENT_QUOTES, 'UTF-8'); ?>"><?= htmlspecialchars($b['name'], ENT_QUOTES, 'UTF-8'); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- ASSIGNED STUDENTS TABLE -->
            <div class="table_container">
                <table class="custom_table" id="studentsTable">
                    <thead>
                        <tr>
                            <th>Roll / ID</th>
                            <th>Student Name</th>
                            <th>Enrolled Batch</th>
                            <th>Email Address</th>
                            <th>Phone</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($students)): ?>
                            <tr>
                                <td colspan="5" style="text-align: center; color: #6b7280; padding: 24px;">No students enrolled in your batches yet.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($students as $s): ?>
                                <tr>
                                    <td>#<?= (int) $s['id']; ?> (Roll: <?= htmlspecialchars($s['roll'], ENT_QUOTES, 'UTF-8'); ?>)</td>
                                    <td><strong><?= htmlspecialchars($s['name'], ENT_QUOTES, 'UTF-8'); ?></strong></td>
                                    <td><span class="badge" style="background:#eef2ff; color:#4f46e5; padding:3px 8px; border-radius:12px; font-size:12px;"><?= htmlspecialchars($s['batch_name'], ENT_QUOTES, 'UTF-8'); ?></span></td>
                                    <td><?= htmlspecialchars($s['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?= htmlspecialchars($s['phone'], ENT_QUOTES, 'UTF-8'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
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

        document.addEventListener('click', function (e) {
            if (window.innerWidth <= 768 && sidebar.classList.contains('mobile_open')) {
                if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                    sidebar.classList.remove('mobile_open');
                }
            }
        });

        // Search & Filter
        const searchInput = document.getElementById('searchInput');
        const batchFilter = document.getElementById('batchFilter');

        function filterTable() {
            const term = searchInput.value.toLowerCase();
            const batch = batchFilter.value.toLowerCase();
            const rows = document.querySelectorAll('#studentsTable tbody tr');

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
