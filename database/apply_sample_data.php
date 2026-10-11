<?php
// Set headers
header('Content-Type: text/html; charset=utf-8');
require_once __DIR__ . '/../backend/config/database.php';

$response = [
    'success' => false,
    'message' => '',
    'inserted' => [],
    'counts' => []
];

try {
    // 1. MORE USERS
    $db->exec("
        INSERT INTO users (id, name, email, password, role, status) VALUES
        (8, 'Tanvir Rahman', 'tanvir@gmail.com', 'student123', 'student', 'active'),
        (9, 'Anika Tabassum', 'anika@gmail.com', 'student123', 'student', 'active'),
        (10, 'Mahin Chowdhury', 'mahin@gmail.com', 'student123', 'student', 'active'),
        (11, 'Sadia Afrin', 'sadia@gmail.com', 'student123', 'student', 'active'),
        (12, 'Farhan Ahmed', 'farhan@gmail.com', 'student123', 'student', 'active'),
        (13, 'Jannat Ferdous', 'jannat@gmail.com', 'student123', 'student', 'active'),
        (14, 'Hasan Mahmud', 'hasan@gmail.com', 'student123', 'student', 'active')
        ON DUPLICATE KEY UPDATE name=VALUES(name), status=VALUES(status);
    ");
    $response['inserted']['users'] = 7;

    // 2. MORE STUDENTS
    $db->exec("
        INSERT INTO students (id, user_id, roll, gender, phone, address, guardian_phone) VALUES
        (4, 8, '104', 'male', '01744444444', 'Dhaka, Bangladesh', '01844444444'),
        (5, 9, '105', 'female', '01755555555', 'Chittagong, Bangladesh', '01855555555'),
        (6, 10, '106', 'male', '01766666666', 'Sylhet, Bangladesh', '01866666666'),
        (7, 11, '107', 'female', '01777777777', 'Rajshahi, Bangladesh', '01877777777'),
        (8, 12, '108', 'male', '01788888888', 'Barisal, Bangladesh', '01888888888'),
        (9, 13, '109', 'female', '01799999999', 'Comilla, Bangladesh', '01899999999'),
        (10, 14, '110', 'male', '01700000000', 'Mymensingh, Bangladesh', '01800000000')
        ON DUPLICATE KEY UPDATE roll=VALUES(roll), guardian_phone=VALUES(guardian_phone);
    ");
    $response['inserted']['students'] = 7;

    // 3. ENROLLMENTS
    $db->exec("
        INSERT INTO enrollments (id, student_id, batch_id, enrollment_date, status) VALUES
        (6, 4, 1, '2026-01-15', 'active'),
        (7, 5, 1, '2026-01-18', 'active'),
        (8, 6, 2, '2026-02-10', 'active'),
        (9, 7, 2, '2026-02-12', 'active'),
        (10, 8, 3, '2026-03-10', 'active'),
        (11, 9, 3, '2026-03-15', 'active'),
        (12, 10, 4, '2026-06-10', 'active'),
        (13, 4, 2, '2026-02-15', 'active'),
        (14, 5, 3, '2026-03-20', 'active'),
        -- New Admissions in September 2026
        (15, 3, 1, '2026-09-02', 'active'),
        (16, 6, 3, '2026-09-05', 'active'),
        -- New Admissions in October 2026
        (17, 7, 1, '2026-10-01', 'active'),
        (18, 8, 4, '2026-10-03', 'active'),
        (19, 10, 2, '2026-10-05', 'active')
        ON DUPLICATE KEY UPDATE status=VALUES(status), enrollment_date=VALUES(enrollment_date);
    ");
    $response['inserted']['enrollments'] = 14;

    // 4. ATTENDANCE LOGS (July, August, September, October 2026)
    $db->exec("
        INSERT INTO attendance (id, student_id, batch_id, date, status) VALUES
        -- JULY 2026
        (35, 1, 1, '2026-07-07', 'present'),
        (36, 2, 1, '2026-07-07', 'present'),
        (37, 4, 1, '2026-07-07', 'present'),
        (38, 5, 1, '2026-07-07', 'present'),
        (39, 3, 2, '2026-07-07', 'present'),
        (40, 4, 2, '2026-07-07', 'present'),
        (41, 6, 2, '2026-07-07', 'late'),
        (42, 7, 2, '2026-07-07', 'present'),
        (43, 1, 1, '2026-07-14', 'present'),
        (44, 2, 1, '2026-07-14', 'present'),
        (45, 4, 1, '2026-07-14', 'late'),
        (46, 5, 1, '2026-07-14', 'present'),
        (47, 3, 2, '2026-07-14', 'present'),
        (48, 6, 2, '2026-07-14', 'present'),
        (49, 7, 2, '2026-07-14', 'present'),

        -- AUGUST 2026
        (50, 1, 1, '2026-08-04', 'present'),
        (51, 2, 1, '2026-08-04', 'present'),
        (52, 4, 1, '2026-08-04', 'present'),
        (53, 5, 1, '2026-08-04', 'late'),
        (54, 3, 2, '2026-08-04', 'absent'), -- Student 3 flagged
        (55, 4, 2, '2026-08-04', 'present'),
        (56, 6, 2, '2026-08-04', 'present'),
        (57, 7, 2, '2026-08-04', 'present'),
        (58, 1, 1, '2026-08-11', 'present'),
        (59, 2, 1, '2026-08-11', 'present'),
        (60, 4, 1, '2026-08-11', 'present'),
        (61, 5, 1, '2026-08-11', 'present'),
        (62, 1, 3, '2026-08-11', 'present'),
        (63, 5, 3, '2026-08-11', 'present'),
        (64, 8, 3, '2026-08-11', 'late'),
        (65, 9, 3, '2026-08-11', 'present'),
        (66, 3, 2, '2026-08-18', 'absent'), -- Student 3 flagged again
        (67, 4, 2, '2026-08-18', 'present'),
        (68, 6, 2, '2026-08-18', 'present'),
        (69, 7, 2, '2026-08-18', 'present'),
        (70, 2, 4, '2026-08-18', 'present'),
        (71, 10, 4, '2026-08-18', 'late'),

        -- SEPTEMBER 2026
        (6, 1, 1, '2026-09-03', 'present'),
        (7, 2, 1, '2026-09-03', 'present'),
        (8, 4, 1, '2026-09-03', 'present'),
        (9, 5, 1, '2026-09-03', 'present'),
        (10, 3, 2, '2026-09-03', 'late'),
        (11, 6, 2, '2026-09-03', 'absent'),
        (12, 7, 2, '2026-09-03', 'present'),
        (13, 1, 1, '2026-09-05', 'present'),
        (14, 2, 1, '2026-09-05', 'present'),
        (15, 4, 1, '2026-09-05', 'present'),
        (16, 5, 1, '2026-09-05', 'late'),
        (17, 3, 2, '2026-09-05', 'present'),
        (18, 6, 2, '2026-09-05', 'absent'),
        (19, 7, 2, '2026-09-05', 'present'),
        (20, 8, 3, '2026-09-05', 'absent'),
        (21, 1, 1, '2026-09-08', 'present'),
        (22, 2, 1, '2026-09-08', 'present'),
        (23, 4, 1, '2026-09-08', 'present'),
        (24, 6, 2, '2026-09-08', 'late'),
        (25, 7, 2, '2026-09-08', 'present'),
        (26, 8, 3, '2026-09-08', 'absent'),
        (27, 9, 3, '2026-09-08', 'present'),
        (28, 10, 4, '2026-09-08', 'present'),

        -- OCTOBER 2026
        (72, 1, 1, '2026-10-02', 'present'),
        (73, 2, 1, '2026-10-02', 'present'),
        (74, 4, 1, '2026-10-02', 'present'),
        (75, 5, 1, '2026-10-02', 'late'),
        (76, 7, 1, '2026-10-02', 'present'),
        (77, 3, 2, '2026-10-02', 'present'),
        (78, 4, 2, '2026-10-02', 'present'),
        (79, 6, 2, '2026-10-02', 'absent'), -- Student 6 absent in October
        (80, 7, 2, '2026-10-02', 'present'),
        (81, 10, 2, '2026-10-02', 'present'),
        (82, 1, 1, '2026-10-05', 'present'),
        (83, 2, 1, '2026-10-05', 'late'),
        (84, 4, 1, '2026-10-05', 'present'),
        (85, 5, 1, '2026-10-05', 'present'),
        (86, 7, 1, '2026-10-05', 'present'),
        (87, 1, 3, '2026-10-05', 'present'),
        (88, 5, 3, '2026-10-05', 'present'),
        (89, 6, 3, '2026-10-05', 'late'),
        (90, 8, 3, '2026-10-05', 'absent'), -- Student 8 absent in October
        (91, 9, 3, '2026-10-05', 'present'),
        (92, 3, 2, '2026-10-07', 'present'),
        (93, 4, 2, '2026-10-07', 'present'),
        (94, 6, 2, '2026-10-07', 'absent'), -- Student 6 absent again
        (95, 7, 2, '2026-10-07', 'present'),
        (96, 10, 2, '2026-10-07', 'late'),
        (97, 2, 4, '2026-10-07', 'present'),
        (98, 8, 4, '2026-10-07', 'late'),
        (99, 10, 4, '2026-10-07', 'present'),
        (100, 1, 1, '2026-10-09', 'present'),
        (101, 2, 1, '2026-10-09', 'present'),
        (102, 4, 1, '2026-10-09', 'present'),
        (103, 5, 1, '2026-10-09', 'present'),
        (104, 7, 1, '2026-10-09', 'present'),
        (105, 1, 3, '2026-10-09', 'present'),
        (106, 5, 3, '2026-10-09', 'present'),
        (107, 6, 3, '2026-10-09', 'present'),
        (108, 8, 3, '2026-10-09', 'absent'), -- Student 8 absent again
        (109, 9, 3, '2026-10-09', 'present')
        ON DUPLICATE KEY UPDATE status=VALUES(status);
    ");
    $response['inserted']['attendance_total'] = 75;

    // 5. EXAM RESULTS (September 2026)
    $db->exec("
        INSERT INTO results (id, student_id, exam_id, marks, grade) VALUES
        -- Exam 1: Database Midterm (Total: 100)
        (6, 4, 1, 94.00, 'A+'),
        (7, 5, 1, 62.00, 'B'),
        (8, 6, 1, 34.00, 'F'),

        -- Exam 2: Data Structures Quiz (Total: 50)
        (9, 1, 2, 47.00, 'A+'),
        (10, 2, 2, 39.00, 'A'),
        (11, 4, 2, 49.00, 'A+'),
        (12, 5, 2, 18.00, 'F'),

        -- Exam 3: Networks Midterm (Total: 100)
        (13, 6, 3, 76.00, 'A'),
        (14, 7, 3, 89.00, 'A+'),
        (15, 4, 3, 91.00, 'A+'),

        -- Exam 4: Web Development Exam (Total: 100)
        (16, 8, 4, 93.00, 'A+'),
        (17, 9, 4, 67.00, 'B'),
        (18, 5, 4, 41.00, 'D'),

        -- Exam 5: Software Engineering Exam (Total: 100)
        (19, 10, 5, 87.00, 'A+'),
        (20, 6, 5, 29.00, 'F')
        ON DUPLICATE KEY UPDATE marks=VALUES(marks), grade=VALUES(grade);
    ");
    $response['inserted']['results_september'] = 15;

    // 5b. MULTI-MONTH EXAMS & RESULTS (October 2026)
    $db->exec("
        INSERT INTO exams (id, batch_id, subject_id, name, exam_date, total_marks) VALUES
        (6, 1, 1, 'Database Final Exam', '2026-10-04', 100.00),
        (7, 1, 2, 'Data Structures Assessment', '2026-10-06', 100.00),
        (8, 2, 3, 'Network Security Test', '2026-10-08', 50.00),
        (9, 3, 4, 'React & Node Project Exam', '2026-10-10', 100.00)
        ON DUPLICATE KEY UPDATE name=VALUES(name), exam_date=VALUES(exam_date), total_marks=VALUES(total_marks);

        INSERT INTO results (id, student_id, exam_id, marks, grade) VALUES
        (21, 1, 6, 92.00, 'A+'),
        (22, 2, 6, 84.00, 'A'),
        (23, 4, 6, 96.00, 'A+'),
        (24, 6, 6, 32.00, 'F'),
        (25, 1, 7, 88.00, 'A+'),
        (26, 4, 7, 78.00, 'A'),
        (27, 5, 7, 45.00, 'C'),
        (28, 6, 7, 28.00, 'F'),
        (29, 6, 8, 44.00, 'A+'),
        (30, 7, 8, 38.00, 'A'),
        (31, 8, 8, 16.00, 'F'),
        (32, 8, 9, 91.00, 'A+'),
        (33, 9, 9, 74.00, 'B'),
        (34, 10, 9, 82.00, 'A')
        ON DUPLICATE KEY UPDATE marks=VALUES(marks), grade=VALUES(grade);
    ");
    $response['inserted']['exams_october'] = 4;
    $response['inserted']['results_october'] = 14;

    // 6. FEES & REVENUE LEDGER (Multi-month: July, August, September, October 2026)
    $db->exec("
        INSERT INTO fees (id, student_id, amount, month, status, payment_date) VALUES
        -- July 2026
        (6, 1, 3000.00, '2026-07-01', 'paid', '2026-07-03'),
        (7, 2, 3000.00, '2026-07-01', 'paid', '2026-07-04'),
        (8, 3, 3000.00, '2026-07-01', 'paid', '2026-07-05'),
        (9, 4, 3000.00, '2026-07-01', 'paid', '2026-07-06'),

        -- August 2026
        (10, 3, 3000.00, '2026-08-01', 'paid', '2026-08-04'),
        (11, 4, 3000.00, '2026-08-01', 'paid', '2026-08-06'),
        (12, 5, 3000.00, '2026-08-01', 'paid', '2026-08-07'),
        (13, 6, 3000.00, '2026-08-01', 'unpaid', NULL),

        -- September 2026
        (14, 4, 3000.00, '2026-09-01', 'paid', '2026-09-02'),
        (15, 5, 3000.00, '2026-09-01', 'paid', '2026-09-03'),
        (16, 6, 3000.00, '2026-09-01', 'unpaid', NULL),
        (17, 7, 3000.00, '2026-09-01', 'partial', '2026-09-05'),
        (18, 8, 3000.00, '2026-09-01', 'unpaid', NULL),

        -- October 2026
        (19, 1, 3000.00, '2026-10-01', 'paid', '2026-10-02'),
        (20, 2, 3000.00, '2026-10-01', 'paid', '2026-10-03'),
        (21, 3, 3000.00, '2026-10-01', 'unpaid', NULL),
        (22, 4, 3000.00, '2026-10-01', 'paid', '2026-10-04'),
        (23, 5, 3000.00, '2026-10-01', 'partial', '2026-10-05'),
        (24, 6, 3000.00, '2026-10-01', 'unpaid', NULL),
        (25, 7, 3000.00, '2026-10-01', 'unpaid', NULL),
        (26, 8, 3000.00, '2026-10-01', 'unpaid', NULL),
        (27, 9, 3000.00, '2026-10-01', 'paid', '2026-10-06'),
        (28, 10, 3000.00, '2026-10-01', 'paid', '2026-10-07')
        ON DUPLICATE KEY UPDATE status=VALUES(status), amount=VALUES(amount), payment_date=VALUES(payment_date);
    ");
    $response['inserted']['fees'] = 23;

    // Fetch live row counts
    $response['counts']['users'] = (int)$db->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $response['counts']['students'] = (int)$db->query("SELECT COUNT(*) FROM students")->fetchColumn();
    $response['counts']['enrollments'] = (int)$db->query("SELECT COUNT(*) FROM enrollments")->fetchColumn();
    $response['counts']['attendance'] = (int)$db->query("SELECT COUNT(*) FROM attendance")->fetchColumn();
    $response['counts']['exams'] = (int)$db->query("SELECT COUNT(*) FROM exams")->fetchColumn();
    $response['counts']['results'] = (int)$db->query("SELECT COUNT(*) FROM results")->fetchColumn();
    $response['counts']['fees'] = (int)$db->query("SELECT COUNT(*) FROM fees")->fetchColumn();

    $response['success'] = true;
    $response['message'] = "Database sample data synchronized successfully!";
} catch (Throwable $e) {
    $response['success'] = false;
    $response['message'] = "Error updating database: " . $e->getMessage();
}

if (isset($_GET['format']) && $_GET['format'] === 'json') {
    header('Content-Type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Database Sample Data Synchronization</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #f8fafc; padding: 40px 20px; color: #1e293b; }
        .container { max-width: 650px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.06); }
        .status-badge { display: inline-block; padding: 6px 14px; border-radius: 20px; font-weight: 600; font-size: 14px; margin-bottom: 15px; }
        .success { background: #dcfce7; color: #15803d; }
        .error { background: #fee2e2; color: #b91c1c; }
        h1 { margin-top: 0; font-size: 22px; color: #0f172a; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { text-align: left; padding: 10px 12px; border-bottom: 1px solid #e2e8f0; font-size: 14px; }
        th { background: #f1f5f9; color: #475569; }
        .back-btn { display: inline-block; margin-top: 25px; padding: 10px 18px; background: #4f46e5; color: #fff; text-decoration: none; border-radius: 8px; font-weight: 500; font-size: 14px; }
        .back-btn:hover { background: #4338ca; }
    </style>
</head>
<body>
    <div class="container">
        <span class="status-badge <?= $response['success'] ? 'success' : 'error'; ?>">
            <?= $response['success'] ? '✔ Database Updated Successfully' : '✖ Update Failed'; ?>
        </span>
        <h1>Coaching Management - Sample Data Sync</h1>
        <p><?= htmlspecialchars($response['message']); ?></p>

        <?php if ($response['success']): ?>
            <table>
                <thead>
                    <tr>
                        <th>Database Table</th>
                        <th>Total Records Now</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($response['counts'] as $table => $count): ?>
                        <tr>
                            <td><strong><?= ucfirst($table); ?></strong></td>
                            <td><?= $count; ?> rows</td>
                            <td><span style="color: #15803d; font-weight: 600;">✔ Synchronized</span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <a href="../frontend/pages/Admin%20DashBoard/reports.php" class="back-btn">Go to Admin Reports & Analytics →</a>
    </div>
</body>
</html>
