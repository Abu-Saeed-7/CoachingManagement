<?php
/**
 * One-time database migration script for schema updates.
 * Can be run from CLI (php database/migrate.php) or once during deployment setup.
 */
if (php_sapi_name() !== 'cli' && !in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1', '::1', 'localhost'], true)) {
    http_response_code(403);
    die('Forbidden: Migration scripts cannot be executed remotely.');
}

require_once __DIR__ . '/../backend/config/database.php';

echo "Running migrations...\n";

try {
    // 1. Ensure batches columns schedule_time & room_number exist
    $batchCols = $db->query("SHOW COLUMNS FROM `batches` LIKE 'schedule_time'")->fetch();
    if (!$batchCols) {
        $db->exec("ALTER TABLE `batches` ADD COLUMN `schedule_time` VARCHAR(150) NULL DEFAULT 'Sat, Mon, Wed (10:00 AM - 11:30 AM)' AFTER `start_date`");
        echo "- Added schedule_time column to batches table.\n";
    }

    $roomCols = $db->query("SHOW COLUMNS FROM `batches` LIKE 'room_number'")->fetch();
    if (!$roomCols) {
        $db->exec("ALTER TABLE `batches` ADD COLUMN `room_number` VARCHAR(50) NULL DEFAULT 'Room 101' AFTER `schedule_time`");
        echo "- Added room_number column to batches table.\n";
    }

    // 2. Ensure class_schedules table exists
    $db->exec("
        CREATE TABLE IF NOT EXISTS `class_schedules` (
            `id` BIGINT PRIMARY KEY AUTO_INCREMENT,
            `batch_id` BIGINT NOT NULL,
            `subject_id` BIGINT NOT NULL,
            `teacher_id` BIGINT NOT NULL,
            `day_of_week` ENUM('Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday') NOT NULL,
            `start_time` TIME NOT NULL,
            `end_time` TIME NOT NULL,
            `room_number` VARCHAR(50) DEFAULT 'Room 101',
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
    echo "- Ensured class_schedules table exists.\n";

    // 3. Seed initial schedules if empty and batches exist
    $scheduleCount = (int) $db->query("SELECT COUNT(*) FROM `class_schedules`")->fetchColumn();
    if ($scheduleCount === 0) {
        $hasBatches = (int) $db->query("SELECT COUNT(*) FROM `batches`")->fetchColumn();
        if ($hasBatches > 0) {
            $db->exec("
                INSERT INTO `class_schedules` (`batch_id`, `subject_id`, `teacher_id`, `day_of_week`, `start_time`, `end_time`, `room_number`) VALUES
                (1, 1, 1, 'Saturday', '10:00:00', '11:30:00', 'Room 101'),
                (1, 2, 2, 'Monday', '10:00:00', '11:30:00', 'Room 102'),
                (1, 1, 1, 'Wednesday', '10:00:00', '11:30:00', 'Room 101'),
                (2, 3, 2, 'Sunday', '11:30:00', '13:00:00', 'Room 103'),
                (2, 3, 2, 'Tuesday', '11:30:00', '13:00:00', 'Room 103'),
                (3, 4, 3, 'Saturday', '14:00:00', '15:30:00', 'Lab 1'),
                (3, 4, 3, 'Thursday', '14:00:00', '15:30:00', 'Lab 1');
            ");
            echo "- Seeded initial schedule records.\n";
        }
    }

    echo "Migration completed successfully!\n";
} catch (Throwable $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
}
