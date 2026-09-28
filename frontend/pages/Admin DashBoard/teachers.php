<?php
require_once __DIR__ . '/../../../backend/config/database.php';

$message = '';
$messageType = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $subjectId = (int) ($_POST['subject_id'] ?? 0);
    $phone = trim($_POST['phone'] ?? '');
    $gender = $_POST['gender'] ?? '';
    $address = trim($_POST['address'] ?? '');
    $joiningDate = $_POST['joining_date'] ?? '';

    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 8 ||
        $subjectId < 1 || $phone === '' || !in_array($gender, ['male', 'female', 'other'], true) || $joiningDate === '') {
        $message = 'Please provide valid values. Password must contain at least 8 characters.';
        $messageType = 'error';
    } else {
        try {
            $db->beginTransaction();

            $userStatement = $db->prepare(
                'INSERT INTO users (name, email, password, role, status) VALUES (?, ?, ?, \'teacher\', \'active\')'
            );
            $userStatement->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT)]);

            $teacherStatement = $db->prepare(
                'INSERT INTO teachers (user_id, subject_id, phone, gender, address, joining_date) VALUES (?, ?, ?, ?, ?, ?)'
            );
            $teacherStatement->execute([$db->lastInsertId(), $subjectId, $phone, $gender, $address ?: null, $joiningDate]);

            $db->commit();
            $message = 'Teacher added successfully.';
        } catch (PDOException $error) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            $message = $error->errorInfo[1] === 1062 ? 'This email is already registered.' : 'Unable to add teacher.';
            $messageType = 'error';
        }
    }
}

$subjects = $db->query("SELECT id, name FROM subjects WHERE status = 'active' ORDER BY name")->fetchAll();

$teachers = $db->query(
    'SELECT teachers.id, users.name, users.email, subjects.name AS subject_name, teachers.phone, teachers.gender, teachers.joining_date
     FROM teachers
     INNER JOIN users ON users.id = teachers.user_id
     LEFT JOIN subjects ON subjects.id = teachers.subject_id
     ORDER BY teachers.id DESC'
)->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teachers Management - Coaching Management System</title>
    <link rel="stylesheet" href="admin_dashboard.css">
    <link rel="stylesheet" href="teachers.css">
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
                <li class="active"><a href="teachers.php">👨‍🏫 <span>Teachers</span></a></li>
                <li><a href="batches.php">📚 <span>Batches</span></a></li>
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
                    <h1>Teachers Management</h1>
                    <p>Manage and view all registered teachers and instructors.</p>
                </div>
                <button class="action_btn" id="openAddModal"><span>+</span> Add New Teacher</button>
            </div>

            <!-- সার্চ ও ফিল্টার বার -->
            <div class="table_controls">
                <input type="text" class="search_input" placeholder="🔍 Search by name, subject or phone...">
                <select class="filter_select">
                    <option value="">All Subjects</option>
                    <option value="Mathematics">Mathematics</option>
                    <option value="Physics">Physics</option>
                    <option value="Chemistry">Chemistry</option>
                    <option value="English">English</option>
                </select>
            </div>

            <!-- টিচার টেবিল -->
            <?php if ($message !== ''): ?>
                <p class="form_message <?= $messageType; ?>"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endif; ?>

            <div class="table_container">
                <table class="custom_table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Teacher Name</th>
                            <th>Subject</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Assigned Batches</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($teachers as $teacher): ?>
                            <tr>
                                <td>#T<?= (int) $teacher['id']; ?></td>
                                <td><strong><?= htmlspecialchars($teacher['name'], ENT_QUOTES, 'UTF-8'); ?></strong></td>
                                <td><?= htmlspecialchars($teacher['subject_name'] ?? 'Not assigned', ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?= htmlspecialchars($teacher['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?= htmlspecialchars($teacher['phone'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td>Not assigned</td>
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

    <!-- ৩. ADD TEACHER MODAL (পপ-আপ ফর্ম) -->
    <div class="modal_overlay" id="teacherModal">
        <div class="modal_box">
            <div class="modal_header">
                <h3>Add New Teacher</h3>
                <button class="close_modal" id="closeModal">&times;</button>
            </div>
            <form class="modal_form" method="post">
                <div class="form_group">
                    <label>Full Name</label>
                    <input type="text" name="name" placeholder="e.g. Prof. Abdul Karim" required>
                </div>
                <div class="form_group">
                    <label>Subject Specialization</label>
                    <select name="subject_id" required>
                        <option value="">Select primary subject</option>
                        <?php foreach ($subjects as $subject): ?>
                            <option value="<?= (int) $subject['id']; ?>"><?= htmlspecialchars($subject['name'], ENT_QUOTES, 'UTF-8'); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form_group">
                    <label>Gender</label>
                    <select name="gender" required>
                        <option value="">Select gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="form_group">
                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="teacher@example.com" required>
                </div>
                <div class="form_group">
                    <label>Phone Number</label>
                    <input type="tel" name="phone" placeholder="017XXXXXXXX" required>
                </div>
                <div class="form_group">
                    <label>Address</label>
                    <input type="text" name="address" placeholder="Teacher address">
                </div>
                <div class="form_group">
                    <label>Joining Date</label>
                    <input type="date" name="joining_date" required>
                </div>
                <div class="form_group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="At least 8 characters" minlength="8" required>
                </div>
                <div class="modal_buttons">
                    <button type="button" class="btn_cancel" id="cancelModal">Cancel</button>
                    <button type="submit" class="action_btn">Save Teacher</button>
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
        const teacherModal = document.getElementById('teacherModal');

        openAddModal.addEventListener('click', () => teacherModal.classList.add('show'));
        closeModal.addEventListener('click', () => teacherModal.classList.remove('show'));
        cancelModal.addEventListener('click', () => teacherModal.classList.remove('show'));
    </script>
</body>

</html>
