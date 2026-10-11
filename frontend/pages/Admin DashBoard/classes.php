<?php
require_once __DIR__ . '/../../../backend/middleware/auth.php';
require_once __DIR__ . '/../../../backend/controllers/ClassController.php';

checkAuth(['admin']);

$classController = new ClassController();

$message = '';
$messageType = 'success';

$postResult = $classController->handleAddClass();
if ($postResult['message'] !== '') {
    $message = $postResult['message'];
    $messageType = $postResult['messageType'];
}

$deleteResult = $classController->handleDeleteClass();
if ($deleteResult['message'] !== '') {
    $message = $deleteResult['message'];
    $messageType = $deleteResult['messageType'];
}

$batches = $classController->getActiveBatches();
$subjects = $classController->getActiveSubjects();
$teachers = $classController->getActiveTeachers();
$classes = $classController->getAllClasses();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classes & Routines - Coaching Management System</title>
    <link rel="stylesheet" href="admin_dashboard.css">
    <link rel="stylesheet" href="classes.css">
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
                <li class="active"><a href="classes.php">📅 <span>Classes</span></a></li>
                <li><a href="exams.php">📝 <span>Exams</span></a></li>
                <li><a href="results.php">📊 <span>Results</span></a></li>
                <li><a href="fees.php">💰 <span>Fees</span></a></li>
                <li><a href="reports.php">📑 <span>Reports</span></a></li>
                <li><a href="admin_profile.php">⚙️ <span>Profile</span></a></li>
                <li class="logout"><a href="../login_page/loginPage.php">🚪 <span>Logout</span></a></li>
            </ul>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="main_content">
            <!-- হেডার ও অ্যাকশন বাটন -->
            <div class="page_header">
                <div>
                    <h1>Class Routine & Schedules</h1>
                    <p>Configure day-to-day class timetable, subject allocations, faculty and venues.</p>
                </div>
                <button class="action_btn" id="openAddModal"><span>+</span> Schedule New Class</button>
            </div>

            <!-- সার্চ ও ফিল্টার বার -->
            <div class="table_controls">
                <input type="text" id="searchInput" class="search_input" placeholder="🔍 Search by batch, subject, faculty or room...">
                <select id="dayFilter" class="filter_select">
                    <option value="">All Days</option>
                    <option value="Saturday">Saturday</option>
                    <option value="Sunday">Sunday</option>
                    <option value="Monday">Monday</option>
                    <option value="Tuesday">Tuesday</option>
                    <option value="Wednesday">Wednesday</option>
                    <option value="Thursday">Thursday</option>
                    <option value="Friday">Friday</option>
                </select>
                <select id="batchFilter" class="filter_select">
                    <option value="">All Batches</option>
                    <?php foreach ($batches as $b): ?>
                        <option value="<?= htmlspecialchars($b['name'], ENT_QUOTES, 'UTF-8'); ?>"><?= htmlspecialchars($b['name'], ENT_QUOTES, 'UTF-8'); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <?php if ($message !== ''): ?>
                <p class="form_message <?= $messageType; ?>"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endif; ?>

            <!-- ক্লাস রুটিন টেবিল -->
            <div class="table_container">
                <table class="custom_table" id="classTable">
                    <thead>
                        <tr>
                            <th>Day</th>
                            <th>Time Slot</th>
                            <th>Batch Name</th>
                            <th>Subject</th>
                            <th>Assigned Faculty</th>
                            <th>Venue / Room</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($classes)): ?>
                            <tr>
                                <td colspan="7" style="text-align: center; color: #6b7280; padding: 24px;">No class schedule slots found. Click "Schedule New Class" to create one.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($classes as $c): ?>
                                <tr>
                                    <td><span class="day_badge"><?= htmlspecialchars($c['day_of_week'], ENT_QUOTES, 'UTF-8'); ?></span></td>
                                    <td><strong>⏰ <?= date('h:i A', strtotime($c['start_time'])); ?> – <?= date('h:i A', strtotime($c['end_time'])); ?></strong></td>
                                    <td><span class="badge_batch"><?= htmlspecialchars($c['batch_name'], ENT_QUOTES, 'UTF-8'); ?></span></td>
                                    <td><strong><?= htmlspecialchars($c['subject_name'], ENT_QUOTES, 'UTF-8'); ?></strong></td>
                                    <td>👨‍🏫 <?= htmlspecialchars($c['teacher_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><span class="venue_badge">📍 <?= htmlspecialchars($c['room_number'], ENT_QUOTES, 'UTF-8'); ?></span></td>
                                    <td>
                                        <form method="post" style="display:inline;" onsubmit="return confirm('Are you sure you want to remove this class schedule slot?');">
                                            <input type="hidden" name="action" value="delete_class">
                                            <input type="hidden" name="class_id" value="<?= (int)$c['id']; ?>">
                                            <button class="btn_icon delete_btn" type="submit" title="Delete Slot">🗑️</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- ৩. SCHEDULE CLASS MODAL -->
    <div class="modal_overlay" id="classModal">
        <div class="modal_box">
            <div class="modal_header">
                <h3>Schedule New Class</h3>
                <button class="close_modal" id="closeModal">&times;</button>
            </div>
            <form class="modal_form" method="post">
                <input type="hidden" name="action" value="add_class">
                <div class="form_group">
                    <label>Target Batch</label>
                    <select name="batch_id" required>
                        <option value="">-- Select Batch --</option>
                        <?php foreach ($batches as $b): ?>
                            <option value="<?= (int)$b['id']; ?>" <?= (isset($_POST['batch_id']) && (int)$_POST['batch_id'] === (int)$b['id']) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($b['name'], ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form_group">
                    <label>Subject</label>
                    <select name="subject_id" required>
                        <option value="">-- Select Subject --</option>
                        <?php foreach ($subjects as $s): ?>
                            <option value="<?= (int)$s['id']; ?>" <?= (isset($_POST['subject_id']) && (int)$_POST['subject_id'] === (int)$s['id']) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($s['name'], ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form_group">
                    <label>Assigned Faculty</label>
                    <select name="teacher_id" required>
                        <option value="">-- Select Teacher --</option>
                        <?php foreach ($teachers as $t): ?>
                            <option value="<?= (int)$t['id']; ?>" <?= (isset($_POST['teacher_id']) && (int)$_POST['teacher_id'] === (int)$t['id']) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($t['name'], ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form_group">
                    <label>Day of Week</label>
                    <select name="day_of_week" required>
                        <?php foreach (['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'] as $day): ?>
                            <option value="<?= $day; ?>" <?= (($_POST['day_of_week'] ?? 'Saturday') === $day) ? 'selected' : ''; ?>><?= $day; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:12px;">
                    <div class="form_group">
                        <label>Start Time</label>
                        <input type="time" name="start_time" value="<?= htmlspecialchars($_POST['start_time'] ?? '10:00', ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                    <div class="form_group">
                        <label>End Time</label>
                        <input type="time" name="end_time" value="<?= htmlspecialchars($_POST['end_time'] ?? '11:30', ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                </div>
                <div class="form_group">
                    <label>Venue / Room Number</label>
                    <input type="text" name="room_number" value="<?= htmlspecialchars($_POST['room_number'] ?? 'Room 101', ENT_QUOTES, 'UTF-8'); ?>" placeholder="e.g. Room 101 / Lab 2" required>
                </div>
                <div class="modal_buttons">
                    <button type="button" class="btn_cancel" id="cancelModal">Cancel</button>
                    <button type="submit" class="action_btn">Save Schedule</button>
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
        const classModal = document.getElementById('classModal');

        openAddModal.addEventListener('click', () => classModal.classList.add('show'));
        closeModal.addEventListener('click', () => classModal.classList.remove('show'));
        cancelModal.addEventListener('click', () => classModal.classList.remove('show'));
        classModal.addEventListener('click', (e) => {
            if (e.target === classModal) classModal.classList.remove('show');
        });

        <?php if ($messageType === 'error' && isset($_POST['action']) && $_POST['action'] === 'add_class'): ?>
            classModal.classList.add('show');
        <?php endif; ?>

        // Client-side instant Search & Filter
        const searchInput = document.getElementById('searchInput');
        const dayFilter = document.getElementById('dayFilter');
        const batchFilter = document.getElementById('batchFilter');
        const table = document.getElementById('classTable');

        function filterClasses() {
            const query = searchInput.value.toLowerCase().trim();
            const selectedDay = dayFilter.value.toLowerCase().trim();
            const selectedBatch = batchFilter.value.toLowerCase().trim();
            const rows = table.querySelectorAll('tbody tr');

            rows.forEach(row => {
                if (row.cells.length < 6) return;
                const dayText = row.cells[0].textContent.toLowerCase();
                const batchText = row.cells[2].textContent.toLowerCase();
                const fullText = row.textContent.toLowerCase();

                const matchesQuery = query === '' || fullText.includes(query);
                const matchesDay = selectedDay === '' || dayText.includes(selectedDay);
                const matchesBatch = selectedBatch === '' || batchText.includes(selectedBatch);

                if (matchesQuery && matchesDay && matchesBatch) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        searchInput.addEventListener('input', filterClasses);
        dayFilter.addEventListener('change', filterClasses);
        batchFilter.addEventListener('change', filterClasses);
    </script>
</body>

</html>
