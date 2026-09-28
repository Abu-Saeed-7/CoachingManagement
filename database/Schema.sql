CREATE TABLE `users` (
    `id` BIGINT PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('admin', 'teacher', 'student') NOT NULL,
    `status` ENUM('active', 'inactive') NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE `students` (
    `id` BIGINT PRIMARY KEY AUTO_INCREMENT,
    `user_id` BIGINT NOT NULL UNIQUE,
    `roll` VARCHAR(30) NOT NULL,
    `gender` ENUM('male', 'female', 'other') NOT NULL,
    `phone` VARCHAR(20) NOT NULL,
    `address` VARCHAR(255),
    `guardian_phone` VARCHAR(20) NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE `teachers` (
    `id` BIGINT PRIMARY KEY AUTO_INCREMENT,
    `user_id` BIGINT NOT NULL UNIQUE,
    `subject_id` BIGINT,
    `phone` VARCHAR(20) NOT NULL,
    `gender` ENUM('male', 'female', 'other') NOT NULL,
    `address` VARCHAR(255),
    `joining_date` DATE NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE `batches` (
    `id` BIGINT PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `start_date` DATE NOT NULL,
    `status` ENUM('active', 'inactive', 'completed') NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE `subjects` (
    `id` BIGINT PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `status` ENUM('active', 'inactive') NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE `batch_teachers` (
    `id` BIGINT PRIMARY KEY AUTO_INCREMENT,
    `batch_id` BIGINT NOT NULL,
    `teacher_id` BIGINT NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    UNIQUE (`batch_id`, `teacher_id`)
);


CREATE TABLE `batch_subjects` (
    `id` BIGINT PRIMARY KEY AUTO_INCREMENT,
    `batch_id` BIGINT NOT NULL,
    `subject_id` BIGINT NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    UNIQUE (`batch_id`, `subject_id`)
);


CREATE TABLE `enrollments` (
    `id` BIGINT PRIMARY KEY AUTO_INCREMENT,
    `student_id` BIGINT NOT NULL,
    `batch_id` BIGINT NOT NULL,
    `enrollment_date` DATE NOT NULL,
    `status` ENUM('active', 'inactive', 'completed') NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    UNIQUE (`student_id`, `batch_id`)
);


CREATE TABLE `attendance` (
    `id` BIGINT PRIMARY KEY AUTO_INCREMENT,
    `student_id` BIGINT NOT NULL,
    `batch_id` BIGINT NOT NULL,
    `date` DATE NOT NULL,
    `status` ENUM('present', 'absent', 'late') NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    UNIQUE (`student_id`, `batch_id`, `date`)
);


CREATE TABLE `exams` (
    `id` BIGINT PRIMARY KEY AUTO_INCREMENT,
    `batch_id` BIGINT NOT NULL,
    `subject_id` BIGINT NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `exam_date` DATE NOT NULL,
    `total_marks` DECIMAL(5,2) NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE `results` (
    `id` BIGINT PRIMARY KEY AUTO_INCREMENT,
    `student_id` BIGINT NOT NULL,
    `exam_id` BIGINT NOT NULL,
    `marks` DECIMAL(5,2) NOT NULL,
    `grade` VARCHAR(5),
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    UNIQUE (`student_id`, `exam_id`)
);


CREATE TABLE `fees` (
    `id` BIGINT PRIMARY KEY AUTO_INCREMENT,
    `student_id` BIGINT NOT NULL,
    `amount` DECIMAL(10,2) NOT NULL,
    `month` DATE NOT NULL,
    `status` ENUM('paid', 'unpaid', 'partial') NOT NULL,
    `payment_date` DATE,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);


-- =========================
-- FOREIGN KEY RELATIONSHIPS
-- =========================

ALTER TABLE `students`
ADD FOREIGN KEY (`user_id`)
REFERENCES `users` (`id`);


ALTER TABLE `teachers`
ADD FOREIGN KEY (`user_id`)
REFERENCES `users` (`id`);


ALTER TABLE `teachers`
ADD FOREIGN KEY (`subject_id`)
REFERENCES `subjects` (`id`);


ALTER TABLE `batch_teachers`
ADD FOREIGN KEY (`batch_id`)
REFERENCES `batches` (`id`);


ALTER TABLE `batch_teachers`
ADD FOREIGN KEY (`teacher_id`)
REFERENCES `teachers` (`id`);


ALTER TABLE `batch_subjects`
ADD FOREIGN KEY (`batch_id`)
REFERENCES `batches` (`id`);


ALTER TABLE `batch_subjects`
ADD FOREIGN KEY (`subject_id`)
REFERENCES `subjects` (`id`);


ALTER TABLE `enrollments`
ADD FOREIGN KEY (`student_id`)
REFERENCES `students` (`id`);


ALTER TABLE `enrollments`
ADD FOREIGN KEY (`batch_id`)
REFERENCES `batches` (`id`);


ALTER TABLE `attendance`
ADD FOREIGN KEY (`student_id`)
REFERENCES `students` (`id`);


ALTER TABLE `attendance`
ADD FOREIGN KEY (`batch_id`)
REFERENCES `batches` (`id`);


ALTER TABLE `exams`
ADD FOREIGN KEY (`batch_id`)
REFERENCES `batches` (`id`);


ALTER TABLE `exams`
ADD FOREIGN KEY (`subject_id`)
REFERENCES `subjects` (`id`);


ALTER TABLE `results`
ADD FOREIGN KEY (`student_id`)
REFERENCES `students` (`id`);


ALTER TABLE `results`
ADD FOREIGN KEY (`exam_id`)
REFERENCES `exams` (`id`);


ALTER TABLE `fees`
ADD FOREIGN KEY (`student_id`)
REFERENCES `students` (`id`);