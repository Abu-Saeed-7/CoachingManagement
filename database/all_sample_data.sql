-- ========================================================
-- COMPLETE ALL SAMPLE DATA FOR COACHING MANAGEMENT SYSTEM
-- Combines base and extended sample data:
--   - 14 Users (1 Admin, 3 Teachers, 10 Students)
--   - 10 Students with complete contact & guardian info
--   - 3 Teachers with subject specializations
--   - 5 Batches (4 Active, 1 Completed)
--   - 5 Subjects
--   - 5 Batch-Teacher mappings
--   - 5 Batch-Subject mappings
--   - 8 Weekly Class Schedules
--   - 19 Batch Enrollments
--   - 9 Exams (Midterms, Quizzes, Finals across Sep - Oct 2026)
--   - 34 Exam Results (Merit rankers, grade distributions, low scorers)
--   - 74 Attendance Records (Multi-month: July, August, September, October 2026)
--   - 28 Fee Invoices (Multi-month revenue: July, August, September, October 2026)
-- ========================================================

USE coaching_management;

-- ========================================================
-- 1. USERS (1 Admin, 3 Teachers, 10 Students)
-- ========================================================
INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `status`) VALUES
(1, 'Admin User', 'admin@gmail.com', 'admin123', 'admin', 'active'),
(2, 'Rahim Ahmed', 'rahim@gmail.com', 'teacher123', 'teacher', 'active'),
(3, 'Karim Hasan', 'karim@gmail.com', 'teacher123', 'teacher', 'active'),
(4, 'Nusrat Jahan', 'nusrat@gmail.com', 'teacher123', 'teacher', 'active'),
(5, 'Rifat Islam', 'rifat@gmail.com', 'student123', 'student', 'active'),
(6, 'Sakib Hossain', 'sakib@gmail.com', 'student123', 'student', 'active'),
(7, 'Mim Akter', 'mim@gmail.com', 'student123', 'student', 'active'),
(8, 'Tanvir Rahman', 'tanvir@gmail.com', 'student123', 'student', 'active'),
(9, 'Anika Tabassum', 'anika@gmail.com', 'student123', 'student', 'active'),
(10, 'Mahin Chowdhury', 'mahin@gmail.com', 'student123', 'student', 'active'),
(11, 'Sadia Afrin', 'sadia@gmail.com', 'student123', 'student', 'active'),
(12, 'Farhan Ahmed', 'farhan@gmail.com', 'student123', 'student', 'active'),
(13, 'Jannat Ferdous', 'jannat@gmail.com', 'student123', 'student', 'active'),
(14, 'Hasan Mahmud', 'hasan@gmail.com', 'student123', 'student', 'active')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `status`=VALUES(`status`);


-- ========================================================
-- 2. TEACHERS (3 Teachers)
-- ========================================================
INSERT INTO `teachers` (`id`, `user_id`, `subject_specialist`, `phone`, `gender`, `address`, `joining_date`) VALUES
(1, 2, 'Computer Science & Database', '01611111111', 'male', 'Khulna, Bangladesh', '2025-01-10'),
(2, 3, 'Data Structures & Algorithms', '01622222222', 'male', 'Dhaka, Bangladesh', '2025-02-15'),
(3, 4, 'Web Technologies & Networking', '01633333333', 'female', 'Jessore, Bangladesh', '2025-03-20')
ON DUPLICATE KEY UPDATE `phone`=VALUES(`phone`), `subject_specialist`=VALUES(`subject_specialist`);


-- ========================================================
-- 3. STUDENTS (10 Students)
-- ========================================================
INSERT INTO `students` (`id`, `user_id`, `roll`, `gender`, `phone`, `address`, `guardian_phone`) VALUES
(1, 5, '101', 'male', '01711111111', 'Khulna, Bangladesh', '01811111111'),
(2, 6, '102', 'male', '01722222222', 'Dhaka, Bangladesh', '01822222222'),
(3, 7, '103', 'female', '01733333333', 'Jessore, Bangladesh', '01833333333'),
(4, 8, '104', 'male', '01744444444', 'Dhaka, Bangladesh', '01844444444'),
(5, 9, '105', 'female', '01755555555', 'Chittagong, Bangladesh', '01855555555'),
(6, 10, '106', 'male', '01766666666', 'Sylhet, Bangladesh', '01866666666'),
(7, 11, '107', 'female', '01777777777', 'Rajshahi, Bangladesh', '01877777777'),
(8, 12, '108', 'male', '01788888888', 'Barisal, Bangladesh', '01888888888'),
(9, 13, '109', 'female', '01799999999', 'Comilla, Bangladesh', '01899999999'),
(10, 14, '110', 'male', '01700000000', 'Mymensingh, Bangladesh', '01800000000')
ON DUPLICATE KEY UPDATE `roll`=VALUES(`roll`), `guardian_phone`=VALUES(`guardian_phone`);


-- ========================================================
-- 4. BATCHES (4 Active, 1 Completed)
-- ========================================================
INSERT INTO `batches` (`id`, `name`, `start_date`, `schedule_time`, `room_number`, `status`) VALUES
(1, 'CSE Batch 2026-A', '2026-01-10', 'Sat, Mon, Wed (10:00 AM - 11:30 AM)', 'Room 101', 'active'),
(2, 'CSE Batch 2026-B', '2026-02-01', 'Sun, Tue (11:30 AM - 01:00 PM)', 'Room 103', 'active'),
(3, 'Science Batch 2026', '2026-03-01', 'Sat, Thu (02:00 PM - 03:30 PM)', 'Lab 1', 'active'),
(4, 'Summer Batch 2026', '2026-06-01', 'Mon, Wed (04:00 PM - 05:30 PM)', 'Room 104', 'active'),
(5, 'Spring Batch 2025', '2025-01-15', 'Completed', 'Room 102', 'completed')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `status`=VALUES(`status`);


-- ========================================================
-- 5. SUBJECTS (5 Subjects)
-- ========================================================
INSERT INTO `subjects` (`id`, `name`, `status`) VALUES
(1, 'Database Systems', 'active'),
(2, 'Data Structures', 'active'),
(3, 'Computer Networks', 'active'),
(4, 'Web Development', 'active'),
(5, 'Software Engineering', 'active')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `status`=VALUES(`status`);


-- ========================================================
-- 6. BATCH TEACHERS
-- ========================================================
INSERT INTO `batch_teachers` (`id`, `batch_id`, `teacher_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 2, 2),
(4, 3, 3),
(5, 4, 1)
ON DUPLICATE KEY UPDATE `batch_id`=VALUES(`batch_id`), `teacher_id`=VALUES(`teacher_id`);


-- ========================================================
-- 7. BATCH SUBJECTS
-- ========================================================
INSERT INTO `batch_subjects` (`id`, `batch_id`, `subject_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 2, 3),
(4, 3, 4),
(5, 4, 5)
ON DUPLICATE KEY UPDATE `batch_id`=VALUES(`batch_id`), `subject_id`=VALUES(`subject_id`);


-- ========================================================
-- 8. CLASS SCHEDULES (Weekly Routines)
-- ========================================================
INSERT INTO `class_schedules` (`id`, `batch_id`, `subject_id`, `teacher_id`, `day_of_week`, `start_time`, `end_time`, `room_number`) VALUES
(1, 1, 1, 1, 'Saturday', '10:00:00', '11:30:00', 'Room 101'),
(2, 1, 2, 2, 'Monday', '10:00:00', '11:30:00', 'Room 102'),
(3, 1, 1, 1, 'Wednesday', '10:00:00', '11:30:00', 'Room 101'),
(4, 2, 3, 2, 'Sunday', '11:30:00', '13:00:00', 'Room 103'),
(5, 2, 3, 2, 'Tuesday', '11:30:00', '13:00:00', 'Room 103'),
(6, 3, 4, 3, 'Saturday', '14:00:00', '15:30:00', 'Lab 1'),
(7, 3, 4, 3, 'Thursday', '14:00:00', '15:30:00', 'Lab 1'),
(8, 4, 5, 1, 'Monday', '16:00:00', '17:30:00', 'Room 104')
ON DUPLICATE KEY UPDATE `start_time`=VALUES(`start_time`), `end_time`=VALUES(`end_time`);


-- ========================================================
-- 9. ENROLLMENTS (19 Batch Enrollments)
-- ========================================================
INSERT INTO `enrollments` (`id`, `student_id`, `batch_id`, `enrollment_date`, `status`) VALUES
(1, 1, 1, '2026-01-12', 'active'),
(2, 2, 1, '2026-01-13', 'active'),
(3, 3, 2, '2026-02-03', 'active'),
(4, 1, 3, '2026-03-05', 'active'),
(5, 2, 4, '2026-06-05', 'active'),
(6, 4, 1, '2026-01-15', 'active'),
(7, 5, 1, '2026-01-18', 'active'),
(8, 6, 2, '2026-02-10', 'active'),
(9, 7, 2, '2026-02-12', 'active'),
(10, 8, 3, '2026-03-10', 'active'),
(11, 9, 3, '2026-03-15', 'active'),
(12, 10, 4, '2026-06-10', 'active'),
(13, 4, 2, '2026-02-15', 'active'),
(14, 5, 3, '2026-03-20', 'active'),
(15, 3, 1, '2026-09-02', 'active'),
(16, 6, 3, '2026-09-05', 'active'),
(17, 7, 1, '2026-10-01', 'active'),
(18, 8, 4, '2026-10-03', 'active'),
(19, 10, 2, '2026-10-05', 'active')
ON DUPLICATE KEY UPDATE `status`=VALUES(`status`), `enrollment_date`=VALUES(`enrollment_date`);


-- ========================================================
-- 10. EXAMS (9 Exams: Sep - Oct 2026)
-- ========================================================
INSERT INTO `exams` (`id`, `batch_id`, `subject_id`, `name`, `exam_date`, `total_marks`) VALUES
(1, 1, 1, 'Database Midterm', '2026-09-05', 100.00),
(2, 1, 2, 'Data Structures Quiz', '2026-09-07', 50.00),
(3, 2, 3, 'Networks Midterm', '2026-09-10', 100.00),
(4, 3, 4, 'Web Development Exam', '2026-09-12', 100.00),
(5, 4, 5, 'Software Engineering Exam', '2026-09-15', 100.00),
(6, 1, 1, 'Database Final Exam', '2026-10-04', 100.00),
(7, 1, 2, 'Data Structures Assessment', '2026-10-06', 100.00),
(8, 2, 3, 'Network Security Test', '2026-10-08', 50.00),
(9, 3, 4, 'React & Node Project Exam', '2026-10-10', 100.00)
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `total_marks`=VALUES(`total_marks`);


-- ========================================================
-- 11. RESULTS (34 Exam Results)
-- ========================================================
INSERT INTO `results` (`id`, `student_id`, `exam_id`, `marks`, `grade`) VALUES
(1, 1, 1, 85.00, 'A+'),
(2, 2, 1, 78.00, 'A'),
(3, 3, 3, 72.00, 'A-'),
(4, 1, 4, 88.00, 'A+'),
(5, 2, 5, 81.00, 'A+'),
(6, 4, 1, 94.00, 'A+'),
(7, 5, 1, 62.00, 'B'),
(8, 6, 1, 34.00, 'F'),
(9, 1, 2, 47.00, 'A+'),
(10, 2, 2, 39.00, 'A'),
(11, 4, 2, 49.00, 'A+'),
(12, 5, 2, 18.00, 'F'),
(13, 6, 3, 76.00, 'A'),
(14, 7, 3, 89.00, 'A+'),
(15, 4, 3, 91.00, 'A+'),
(16, 8, 4, 93.00, 'A+'),
(17, 9, 4, 67.00, 'B'),
(18, 5, 4, 41.00, 'D'),
(19, 10, 5, 87.00, 'A+'),
(20, 6, 5, 29.00, 'F'),
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
ON DUPLICATE KEY UPDATE `marks`=VALUES(`marks`), `grade`=VALUES(`grade`);


-- ========================================================
-- 12. ATTENDANCE (74 Multi-month Records: Jul, Aug, Sep, Oct 2026)
-- ========================================================
INSERT INTO `attendance` (`id`, `student_id`, `batch_id`, `date`, `status`) VALUES
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
(54, 3, 2, '2026-08-04', 'absent'),
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
(66, 3, 2, '2026-08-18', 'absent'),
(67, 4, 2, '2026-08-18', 'present'),
(68, 6, 2, '2026-08-18', 'present'),
(69, 7, 2, '2026-08-18', 'present'),
(70, 2, 4, '2026-08-18', 'present'),
(71, 10, 4, '2026-08-18', 'late'),

-- SEPTEMBER 2026
(1, 1, 1, '2026-09-01', 'present'),
(2, 2, 1, '2026-09-01', 'late'),
(3, 3, 2, '2026-09-01', 'present'),
(4, 1, 3, '2026-09-02', 'absent'),
(5, 2, 4, '2026-09-02', 'present'),
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
(79, 6, 2, '2026-10-02', 'absent'),
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
(90, 8, 3, '2026-10-05', 'absent'),
(91, 9, 3, '2026-10-05', 'present'),
(92, 3, 2, '2026-10-07', 'present'),
(93, 4, 2, '2026-10-07', 'present'),
(94, 6, 2, '2026-10-07', 'absent'),
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
(108, 8, 3, '2026-10-09', 'absent'),
(109, 9, 3, '2026-10-09', 'present')
ON DUPLICATE KEY UPDATE `status`=VALUES(`status`);


-- ========================================================
-- 13. FEES & INVOICES (28 Multi-month Records: Jul - Oct 2026)
-- ========================================================
INSERT INTO `fees` (`id`, `student_id`, `amount`, `month`, `status`, `payment_date`) VALUES
-- July 2026
(6, 1, 3000.00, '2026-07-01', 'paid', '2026-07-03'),
(7, 2, 3000.00, '2026-07-01', 'paid', '2026-07-04'),
(8, 3, 3000.00, '2026-07-01', 'paid', '2026-07-05'),
(9, 4, 3000.00, '2026-07-01', 'paid', '2026-07-06'),

-- August 2026
(4, 1, 3000.00, '2026-08-01', 'paid', '2026-08-05'),
(5, 2, 3000.00, '2026-08-01', 'unpaid', NULL),
(10, 3, 3000.00, '2026-08-01', 'paid', '2026-08-04'),
(11, 4, 3000.00, '2026-08-01', 'paid', '2026-08-06'),
(12, 5, 3000.00, '2026-08-01', 'paid', '2026-08-07'),
(13, 6, 3000.00, '2026-08-01', 'unpaid', NULL),

-- September 2026
(1, 1, 3000.00, '2026-09-01', 'paid', '2026-09-02'),
(2, 2, 3000.00, '2026-09-01', 'paid', '2026-09-03'),
(3, 3, 3000.00, '2026-09-01', 'partial', '2026-09-04'),
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
ON DUPLICATE KEY UPDATE `status`=VALUES(`status`), `amount`=VALUES(`amount`);

-- ========================================================
-- ALL SAMPLE DATA SUCCESSFULLY MERGED & READY
-- ========================================================
