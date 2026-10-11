

-- =========================
-- USERS
-- =========================

INSERT INTO users (id, name, email, password, role, status) VALUES
(1, 'Admin User', 'admin@gmail.com', 'admin123', 'admin', 'active'),
(2, 'Rahim Ahmed', 'rahim@gmail.com', 'teacher123', 'teacher', 'active'),
(3, 'Karim Hasan', 'karim@gmail.com', 'teacher123', 'teacher', 'active'),
(4, 'Nusrat Jahan', 'nusrat@gmail.com', 'teacher123', 'teacher', 'active'),
(5, 'Rifat Islam', 'rifat@gmail.com', 'student123', 'student', 'active'),
(6, 'Sakib Hossain', 'sakib@gmail.com', 'student123', 'student', 'active'),
(7, 'Mim Akter', 'mim@gmail.com', 'student123', 'student', 'active');


-- =========================
-- STUDENTS
-- =========================

INSERT INTO students
(id, user_id, roll, gender, phone, address, guardian_phone) VALUES
(1, 5, '101', 'male', '01711111111', 'Khulna, Bangladesh', '01811111111'),
(2, 6, '102', 'male', '01722222222', 'Dhaka, Bangladesh', '01822222222'),
(3, 7, '103', 'female', '01733333333', 'Jessore, Bangladesh', '01833333333');


-- =========================
-- TEACHERS
-- =========================

INSERT INTO teachers
(id, user_id, phone, gender, address, joining_date) VALUES
(1, 2, '01611111111', 'male', 'Khulna, Bangladesh', '2025-01-10'),
(2, 3, '01622222222', 'male', 'Dhaka, Bangladesh', '2025-02-15'),
(3, 4, '01633333333', 'female', 'Jessore, Bangladesh', '2025-03-20');


-- =========================
-- BATCHES
-- =========================

INSERT INTO batches
(id, name, start_date, schedule_time, room_number, status) VALUES
(1, 'CSE Batch 2026-A', '2026-01-10', 'Sat, Mon, Wed (10:00 AM - 11:30 AM)', 'Room 101', 'active'),
(2, 'CSE Batch 2026-B', '2026-02-01', 'Sun, Tue (11:30 AM - 01:00 PM)', 'Room 103', 'active'),
(3, 'Science Batch 2026', '2026-03-01', 'Sat, Thu (02:00 PM - 03:30 PM)', 'Lab 1', 'active'),
(4, 'Summer Batch 2026', '2026-06-01', 'Mon, Wed (04:00 PM - 05:30 PM)', 'Room 104', 'active'),
(5, 'Spring Batch 2025', '2025-01-15', 'Completed', 'Room 102', 'completed');


-- =========================
-- SUBJECTS
-- =========================

INSERT INTO subjects
(id, name, status) VALUES
(1, 'Database Systems', 'active'),
(2, 'Data Structures', 'active'),
(3, 'Computer Networks', 'active'),
(4, 'Web Development', 'active'),
(5, 'Software Engineering', 'active');


-- =========================
-- BATCH TEACHERS
-- =========================

INSERT INTO batch_teachers
(id, batch_id, teacher_id) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 2, 2),
(4, 3, 3),
(5, 4, 1);


-- =========================
-- BATCH SUBJECTS
-- =========================

INSERT INTO batch_subjects
(id, batch_id, subject_id) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 2, 3),
(4, 3, 4),
(5, 4, 5);


-- =========================
-- ENROLLMENTS
-- =========================

INSERT INTO enrollments
(id, student_id, batch_id, enrollment_date, status) VALUES
(1, 1, 1, '2026-01-12', 'active'),
(2, 2, 1, '2026-01-13', 'active'),
(3, 3, 2, '2026-02-03', 'active'),
(4, 1, 3, '2026-03-05', 'active'),
(5, 2, 4, '2026-06-05', 'active');


-- =========================
-- ATTENDANCE
-- =========================

INSERT INTO attendance
(id, student_id, batch_id, date, status) VALUES
(1, 1, 1, '2026-09-01', 'present'),
(2, 2, 1, '2026-09-01', 'late'),
(3, 3, 2, '2026-09-01', 'present'),
(4, 1, 3, '2026-09-02', 'absent'),
(5, 2, 4, '2026-09-02', 'present');


-- =========================
-- EXAMS
-- =========================

INSERT INTO exams
(id, batch_id, subject_id, name, exam_date, total_marks) VALUES
(1, 1, 1, 'Database Midterm', '2026-09-05', 100.00),
(2, 1, 2, 'Data Structures Quiz', '2026-09-07', 50.00),
(3, 2, 3, 'Networks Midterm', '2026-09-10', 100.00),
(4, 3, 4, 'Web Development Exam', '2026-09-12', 100.00),
(5, 4, 5, 'Software Engineering Exam', '2026-09-15', 100.00);


-- =========================
-- RESULTS
-- =========================

INSERT INTO results
(id, student_id, exam_id, marks, grade) VALUES
(1, 1, 1, 85.00, 'A+'),
(2, 2, 1, 78.00, 'A'),
(3, 3, 3, 72.00, 'A-'),
(4, 1, 4, 88.00, 'A+'),
(5, 2, 5, 81.00, 'A+');


-- =========================
-- FEES
-- =========================

INSERT INTO fees
(id, student_id, amount, month, status, payment_date) VALUES
(1, 1, 3000.00, '2026-09-01', 'paid', '2026-09-02'),
(2, 2, 3000.00, '2026-09-01', 'paid', '2026-09-03'),
(3, 3, 3000.00, '2026-09-01', 'partial', '2026-09-04'),
(4, 1, 3000.00, '2026-08-01', 'paid', '2026-08-05'),
(5, 2, 3000.00, '2026-08-01', 'unpaid', NULL);


-- =========================
-- CLASS SCHEDULES
-- =========================

INSERT INTO class_schedules
(id, batch_id, subject_id, teacher_id, day_of_week, start_time, end_time, room_number) VALUES
(1, 1, 1, 1, 'Saturday', '10:00:00', '11:30:00', 'Room 101'),
(2, 1, 2, 2, 'Monday', '10:00:00', '11:30:00', 'Room 102'),
(3, 1, 1, 1, 'Wednesday', '10:00:00', '11:30:00', 'Room 101'),
(4, 2, 3, 2, 'Sunday', '11:30:00', '13:00:00', 'Room 103'),
(5, 2, 3, 2, 'Tuesday', '11:30:00', '13:00:00', 'Room 103'),
(6, 3, 4, 3, 'Saturday', '14:00:00', '15:30:00', 'Lab 1'),
(7, 3, 4, 3, 'Thursday', '14:00:00', '15:30:00', 'Lab 1'),
(8, 4, 5, 1, 'Monday', '16:00:00', '17:30:00', 'Room 104');


-- =========================
-- ADDITIONAL EXTENDED DATA (REPORTS VERIFICATION)
-- =========================

-- USERS (STUDENTS 8 - 14)
INSERT INTO users (id, name, email, password, role, status) VALUES
(8, 'Tanvir Rahman', 'tanvir@gmail.com', 'student123', 'student', 'active'),
(9, 'Anika Tabassum', 'anika@gmail.com', 'student123', 'student', 'active'),
(10, 'Mahin Chowdhury', 'mahin@gmail.com', 'student123', 'student', 'active'),
(11, 'Sadia Afrin', 'sadia@gmail.com', 'student123', 'student', 'active'),
(12, 'Farhan Ahmed', 'farhan@gmail.com', 'student123', 'student', 'active'),
(13, 'Jannat Ferdous', 'jannat@gmail.com', 'student123', 'student', 'active'),
(14, 'Hasan Mahmud', 'hasan@gmail.com', 'student123', 'student', 'active')
ON DUPLICATE KEY UPDATE name=VALUES(name);

-- STUDENTS (4 - 10)
INSERT INTO students (id, user_id, roll, gender, phone, address, guardian_phone) VALUES
(4, 8, '104', 'male', '01744444444', 'Dhaka, Bangladesh', '01844444444'),
(5, 9, '105', 'female', '01755555555', 'Chittagong, Bangladesh', '01855555555'),
(6, 10, '106', 'male', '01766666666', 'Sylhet, Bangladesh', '01866666666'),
(7, 11, '107', 'female', '01777777777', 'Rajshahi, Bangladesh', '01877777777'),
(8, 12, '108', 'male', '01788888888', 'Barisal, Bangladesh', '01888888888'),
(9, 13, '109', 'female', '01799999999', 'Comilla, Bangladesh', '01899999999'),
(10, 14, '110', 'male', '01700000000', 'Mymensingh, Bangladesh', '01800000000')
ON DUPLICATE KEY UPDATE roll=VALUES(roll);

-- ENROLLMENTS
INSERT INTO enrollments (id, student_id, batch_id, enrollment_date, status) VALUES
(6, 4, 1, '2026-01-15', 'active'),
(7, 5, 1, '2026-01-18', 'active'),
(8, 6, 2, '2026-02-10', 'active'),
(9, 7, 2, '2026-02-12', 'active'),
(10, 8, 3, '2026-03-10', 'active'),
(11, 9, 3, '2026-03-15', 'active'),
(12, 10, 4, '2026-06-10', 'active'),
(13, 4, 2, '2026-02-15', 'active'),
(14, 5, 3, '2026-03-20', 'active')
ON DUPLICATE KEY UPDATE status=VALUES(status);

-- ATTENDANCE LOGS
INSERT INTO attendance (id, student_id, batch_id, date, status) VALUES
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
(28, 10, 4, '2026-09-08', 'present')
ON DUPLICATE KEY UPDATE status=VALUES(status);

-- RESULTS
INSERT INTO results (id, student_id, exam_id, marks, grade) VALUES
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
(20, 6, 5, 29.00, 'F')
ON DUPLICATE KEY UPDATE marks=VALUES(marks), grade=VALUES(grade);

-- FEES (JULY, AUGUST, SEPTEMBER, OCTOBER 2026)
INSERT INTO fees (id, student_id, amount, month, status, payment_date) VALUES
(6, 1, 3000.00, '2026-07-01', 'paid', '2026-07-03'),
(7, 2, 3000.00, '2026-07-01', 'paid', '2026-07-04'),
(8, 3, 3000.00, '2026-07-01', 'paid', '2026-07-05'),
(9, 4, 3000.00, '2026-07-01', 'paid', '2026-07-06'),
(10, 3, 3000.00, '2026-08-01', 'paid', '2026-08-04'),
(11, 4, 3000.00, '2026-08-01', 'paid', '2026-08-06'),
(12, 5, 3000.00, '2026-08-01', 'paid', '2026-08-07'),
(13, 6, 3000.00, '2026-08-01', 'unpaid', NULL),
(14, 4, 3000.00, '2026-09-01', 'paid', '2026-09-02'),
(15, 5, 3000.00, '2026-09-01', 'paid', '2026-09-03'),
(16, 6, 3000.00, '2026-09-01', 'unpaid', NULL),
(17, 7, 3000.00, '2026-09-01', 'partial', '2026-09-05'),
(18, 8, 3000.00, '2026-09-01', 'unpaid', NULL),
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
ON DUPLICATE KEY UPDATE status=VALUES(status), amount=VALUES(amount);

-- =========================================================
-- END OF SAMPLE DATA
-- =========================================================
