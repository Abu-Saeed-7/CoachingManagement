-- =========================================================
-- StudyArena Database
-- Sample Data
-- Based on the submitted schema.sql
-- 3-5 sample records per table
-- created_at is omitted so DEFAULT CURRENT_TIMESTAMP is used
-- =========================================================

-- =========================
-- USERS
-- =========================

INSERT INTO users (id, name, email, password, role, status) VALUES
(1, 'Admin User', 'admin@studyarena.com', 'admin123', 'admin', 'active'),
(2, 'Rahim Ahmed', 'rahim@studyarena.com', 'teacher123', 'teacher', 'active'),
(3, 'Karim Hasan', 'karim@studyarena.com', 'teacher123', 'teacher', 'active'),
(4, 'Nusrat Jahan', 'nusrat@studyarena.com', 'teacher123', 'teacher', 'active'),
(5, 'Rifat Islam', 'rifat@student.com', 'student123', 'student', 'active'),
(6, 'Sakib Hossain', 'sakib@student.com', 'student123', 'student', 'active'),
(7, 'Mim Akter', 'mim@student.com', 'student123', 'student', 'active');


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
(id, name, start_date, status) VALUES
(1, 'CSE Batch 2026-A', '2026-01-10', 'active'),
(2, 'CSE Batch 2026-B', '2026-02-01', 'active'),
(3, 'Science Batch 2026', '2026-03-01', 'active'),
(4, 'Summer Batch 2026', '2026-06-01', 'active'),
(5, 'Spring Batch 2025', '2025-01-15', 'completed');


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


-- =========================================================
-- END OF SAMPLE DATA
-- =========================================================
