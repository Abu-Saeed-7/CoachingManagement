<?php
require_once __DIR__ . '/../../../backend/config/database.php';

$message = '';
$messageType = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $roll = trim($_POST['roll'] ?? '');
    $gender = $_POST['gender'] ?? '';
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $guardianPhone = trim($_POST['guardian_phone'] ?? '');
    $batchId = (int) ($_POST['batch_id'] ?? 0);

    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 8 ||
        $roll === '' || !in_array($gender, ['male', 'female', 'other'], true) ||
        $phone === '' || $guardianPhone === '' || $batchId < 1) {
        $message = 'Please provide valid values. Password must contain at least 8 characters.';
        $messageType = 'error';
    } else {
        try {
            $db->beginTransaction();

            $userStatement = $db->prepare(
                'INSERT INTO users (name, email, password, role, status) VALUES (?, ?, ?, \'student\', \'active\')'
            );
            $userStatement->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT)]);

            $studentStatement = $db->prepare(
                'INSERT INTO students (user_id, roll, gender, phone, address, guardian_phone) VALUES (?, ?, ?, ?, ?, ?)'
            );
            $studentStatement->execute([
                $db->lastInsertId(),
                $roll,
                $gender,
                $phone,
                $address ?: null,
                $guardianPhone,
            ]);

            $enrollmentStatement = $db->prepare(
                'INSERT INTO enrollments (student_id, batch_id, enrollment_date, status) VALUES (?, ?, CURDATE(), \'active\')'
            );
            $enrollmentStatement->execute([$db->lastInsertId(), $batchId]);

            $db->commit();
            $message = 'Student added successfully.';
        } catch (PDOException $error) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            $message = $error->errorInfo[1] === 1062 ? 'This email or roll is already registered.' : 'Unable to add student.';
            $messageType = 'error';
        }
    }
}

$batches = $db->query("SELECT id, name FROM batches WHERE status = 'active' ORDER BY name")->fetchAll();
$students = $db->query(
    'SELECT students.id, students.roll, users.name, users.email, students.phone, users.status, batches.name AS batch_name
     FROM students
     INNER JOIN users ON users.id = students.user_id
     LEFT JOIN enrollments ON enrollments.student_id = students.id AND enrollments.status = \'active\'
     LEFT JOIN batches ON batches.id = enrollments.batch_id
     ORDER BY students.id DESC'
)->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students Management - Coaching Management System</title>
    <link rel="stylesheet" href="admin_dashboard.css">
    <link rel="stylesheet" href="students.css">
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
                <li class="active"><a href="students.php">🎓 <span>Students</span></a></li>
                <li><a href="teachers.php">👨‍🏫 <span>Teachers</span></a></li>
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
                    <h1>Students Management</h1>
                    <p>Manage and view all enrolled students.</p>
                </div>
                <button class="action_btn" id="openAddModal"><span>+</span> Add New Student</button>
            </div>

            <!-- সার্চ ও ফিল্টার বার -->
            <div class="table_controls">
                <input type="text" class="search_input" placeholder="🔍 Search by name, roll or phone...">
                <select class="filter_select">
                    <option value="">All Batches</option>
                    <option value="Batch A">Batch A</option>
                    <option value="Batch B">Batch B</option>
                    <option value="Batch C">Batch C</option>
                </select>
            </div>

            <!-- স্টুডেন্ট টেবিল -->
            <?php if ($message !== ''): ?>
                <p class="form_message <?= $messageType; ?>"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endif; ?>

            <div class="table_container">
                <table class="custom_table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Student Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Batch</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td>#<?= (int) $student['id']; ?></td>
                                <td><strong><?= htmlspecialchars($student['name'], ENT_QUOTES, 'UTF-8'); ?></strong></td>
                                <td><?= htmlspecialchars($student['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?= htmlspecialchars($student['phone'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><span class="badge"><?= htmlspecialchars($student['batch_name'] ?? 'Not assigned', ENT_QUOTES, 'UTF-8'); ?></span></td>
                                <td><span class="status_active"><?= htmlspecialchars(ucfirst($student['status']), ENT_QUOTES, 'UTF-8'); ?></span></td>
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

    <!-- ৩. ADD STUDENT MODAL (পপ-আপ ফর্ম) -->
    <div class="modal_overlay" id="studentModal">
        <div class="modal_box">
            <div class="modal_header">
                <h3>Add New Student</h3>
                <button class="close_modal" id="closeModal">&times;</button>
            </div>
            <form class="modal_form" method="post">
                <div class="form_group">
                    <label>Full Name</label>
                    <input type="text" name="name" placeholder="e.g. Rahim Ahmed" required>
                </div>
                <div class="form_group">
                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="name@example.com" required>
                </div>
                <div class="form_group">
                    <label>Phone Number</label>
                    <input type="tel" name="phone" placeholder="017XXXXXXXX" required>
                </div>
                <div class="form_group">
                    <label>Roll</label>
                    <input type="text" name="roll" placeholder="e.g. 101" required>
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
                    <label>Guardian Phone</label>
                    <input type="tel" name="guardian_phone" placeholder="017XXXXXXXX" required>
                </div>
                <div class="form_group">
                    <label>Address</label>
                    <input type="text" name="address" placeholder="Student address">
                </div>
                <div class="form_group">
                    <label>Select Batch</label>
                    <select name="batch_id" required>
                        <option value="">Select a batch</option>
                        <?php foreach ($batches as $batch): ?>
                            <option value="<?= (int) $batch['id']; ?>"><?= htmlspecialchars($batch['name'], ENT_QUOTES, 'UTF-8'); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form_group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="At least 8 characters" minlength="8" required>
                </div>
                <div class="modal_buttons">
                    <button type="button" class="btn_cancel" id="cancelModal">Cancel</button>
                    <button type="submit" class="action_btn">Save Student</button>
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
        const studentModal = document.getElementById('studentModal');

        openAddModal.addEventListener('click', () => studentModal.classList.add('show'));
        closeModal.addEventListener('click', () => studentModal.classList.remove('show'));
        cancelModal.addEventListener('click', () => studentModal.classList.remove('show'));
    </script>
</body>

</html>