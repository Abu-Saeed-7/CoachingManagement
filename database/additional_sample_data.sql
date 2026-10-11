-- ========================================================
-- ADDITIONAL SAMPLE DATA FOR COACHING MANAGEMENT SYSTEM
-- Use this data to verify all 22 features in Admin Reports:
--   1. Multi-month Revenue Bar Charts (July - Oct 2026)
--   2. Urgent Fee Defaulters & Partial Ledgers
--   3. Low Attendance Alerts (<80%) & Attendance Doughnut
--   4. Merit Lists (Rank 1, 2, 3), Grade Counts & Weak Student Alerts
--   5. Batch Capacities & Teacher Workloads
-- ========================================================

-- 1. MORE USERS (STUDENTS)
INSERT INTO users (id, name, email, password, role, status) VALUES
(8, 'Tanvir Rahman', 'tanvir@gmail.com', 'student123', 'student', 'active'),
(9, 'Anika Tabassum', 'anika@gmail.com', 'student123', 'student', 'active'),
(10, 'Mahin Chowdhury', 'mahin@gmail.com', 'student123', 'student', 'active'),
(11, 'Sadia Afrin', 'sadia@gmail.com', 'student123', 'student', 'active'),
(12, 'Farhan Ahmed', 'farhan@gmail.com', 'student123', 'student', 'active'),
(13, 'Jannat Ferdous', 'jannat@gmail.com', 'student123', 'student', 'active'),
(14, 'Hasan Mahmud', 'hasan@gmail.com', 'student123', 'student', 'active')
ON DUPLICATE KEY UPDATE name=VALUES(name);


-- 2. MORE STUDENTS
INSERT INTO students (id, user_id, roll, gender, phone, address, guardian_phone) VALUES
(4, 8, '104', 'male', '01744444444', 'Dhaka, Bangladesh', '01844444444'),
(5, 9, '105', 'female', '01755555555', 'Chittagong, Bangladesh', '01855555555'),
(6, 10, '106', 'male', '01766666666', 'Sylhet, Bangladesh', '01866666666'),
(7, 11, '107', 'female', '01777777777', 'Rajshahi, Bangladesh', '01877777777'),
(8, 12, '108', 'male', '01788888888', 'Barisal, Bangladesh', '01888888888'),
(9, 13, '109', 'female', '01799999999', 'Comilla, Bangladesh', '01899999999'),
(10, 14, '110', 'male', '01700000000', 'Mymensingh, Bangladesh', '01800000000')
ON DUPLICATE KEY UPDATE roll=VALUES(roll);


-- 3. BATCH ENROLLMENTS
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


-- 4. ATTENDANCE LOGS (Multi-month: July, August, September, October 2026)
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


-- 5. EXAM RESULTS (High scorers for Merit List & Low scorers for Weak Students Alert)
INSERT INTO results (id, student_id, exam_id, marks, grade) VALUES
-- Exam 1: Database Midterm (Total: 100)
(6, 4, 1, 94.00, 'A+'), -- Rank 1 Performer
(7, 5, 1, 62.00, 'B'),
(8, 6, 1, 34.00, 'F'),  -- Weak student alert!

-- Exam 2: Data Structures Quiz (Total: 50)
(9, 1, 2, 47.00, 'A+'),
(10, 2, 2, 39.00, 'A'),
(11, 4, 2, 49.00, 'A+'), -- Top score
(12, 5, 2, 18.00, 'F'),  -- Weak student alert!

-- Exam 3: Networks Midterm (Total: 100)
(13, 6, 3, 76.00, 'A'),
(14, 7, 3, 89.00, 'A+'),
(15, 4, 3, 91.00, 'A+'),

-- Exam 4: Web Development Exam (Total: 100)
(16, 8, 4, 93.00, 'A+'), -- Rank 2 Performer
(17, 9, 4, 67.00, 'B'),
(18, 5, 4, 41.00, 'D'),  -- At-risk student!

-- Exam 5: Software Engineering Exam (Total: 100)
(19, 10, 5, 87.00, 'A+'),
(20, 6, 5, 29.00, 'F')   -- Weak student alert!
ON DUPLICATE KEY UPDATE marks=VALUES(marks), grade=VALUES(grade);


-- 5b. MULTI-MONTH EXAMS (October 2026 Exams & Results for Testing Month Filters)
INSERT INTO exams (id, batch_id, subject_id, name, exam_date, total_marks) VALUES
(6, 1, 1, 'Database Final Exam', '2026-10-04', 100.00),
(7, 1, 2, 'Data Structures Assessment', '2026-10-06', 100.00),
(8, 2, 3, 'Network Security Test', '2026-10-08', 50.00),
(9, 3, 4, 'React & Node Project Exam', '2026-10-10', 100.00)
ON DUPLICATE KEY UPDATE name=VALUES(name);

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


-- 6. FEES & REVENUE LEDGER (Multi-month trends for July, August, September, October 2026)
INSERT INTO fees (id, student_id, amount, month, status, payment_date) VALUES
-- July 2026 (৳12,000 collected)
(6, 1, 3000.00, '2026-07-01', 'paid', '2026-07-03'),
(7, 2, 3000.00, '2026-07-01', 'paid', '2026-07-04'),
(8, 3, 3000.00, '2026-07-01', 'paid', '2026-07-05'),
(9, 4, 3000.00, '2026-07-01', 'paid', '2026-07-06'),

-- August 2026 (৳9,000 collected, ৳6,000 due)
(10, 3, 3000.00, '2026-08-01', 'paid', '2026-08-04'),
(11, 4, 3000.00, '2026-08-01', 'paid', '2026-08-06'),
(12, 5, 3000.00, '2026-08-01', 'paid', '2026-08-07'),
(13, 6, 3000.00, '2026-08-01', 'unpaid', NULL), -- Defaulter

-- September 2026 (৳6,000 collected, ৳4,500 due/partial)
(14, 4, 3000.00, '2026-09-01', 'paid', '2026-09-02'),
(15, 5, 3000.00, '2026-09-01', 'paid', '2026-09-03'),
(16, 6, 3000.00, '2026-09-01', 'unpaid', NULL), -- Defaulter
(17, 7, 3000.00, '2026-09-01', 'partial', '2026-09-05'),
(18, 8, 3000.00, '2026-09-01', 'unpaid', NULL), -- Defaulter

-- October 2026 (Current month with high activity)
(19, 1, 3000.00, '2026-10-01', 'paid', '2026-10-02'),
(20, 2, 3000.00, '2026-10-01', 'paid', '2026-10-03'),
(21, 3, 3000.00, '2026-10-01', 'unpaid', NULL), -- Defaulter
(22, 4, 3000.00, '2026-10-01', 'paid', '2026-10-04'),
(23, 5, 3000.00, '2026-10-01', 'partial', '2026-10-05'),
(24, 6, 3000.00, '2026-10-01', 'unpaid', NULL), -- Defaulter (2 months consecutive)
(25, 7, 3000.00, '2026-10-01', 'unpaid', NULL), -- Defaulter
(26, 8, 3000.00, '2026-10-01', 'unpaid', NULL), -- Defaulter
(27, 9, 3000.00, '2026-10-01', 'paid', '2026-10-06'),
(28, 10, 3000.00, '2026-10-01', 'paid', '2026-10-07')
ON DUPLICATE KEY UPDATE status=VALUES(status), amount=VALUES(amount);
