<?php
require_once __DIR__ . '/../config/database.php';

class FeeController {
    private PDO $db;

    public function __construct(?PDO $db = null) {
        global $db;
        $this->db = $db ?? $GLOBALS['db'];
    }

    public function getFeeStats(): array {
        $totalCollected = (float) $this->db->query("SELECT COALESCE(SUM(amount), 0) FROM fees WHERE status = 'paid'")->fetchColumn();
        $pendingDues = (float) $this->db->query("SELECT COALESCE(SUM(amount), 0) FROM fees WHERE status = 'unpaid'")->fetchColumn();
        $totalInvoices = (int) $this->db->query("SELECT COUNT(*) FROM fees")->fetchColumn();

        return [
            'totalCollected' => $totalCollected,
            'pendingDues' => $pendingDues,
            'totalInvoices' => $totalInvoices,
        ];
    }

    public function getStudentsDropdown(): array {
        return $this->db->query(
            'SELECT students.id, users.name, students.roll
             FROM students
             INNER JOIN users ON users.id = students.user_id
             ORDER BY users.name'
        )->fetchAll();
    }

    public function getAllFees(): array {
        return $this->db->query(
            'SELECT fees.id, users.name AS student_name, students.roll,
                COALESCE((
                    SELECT batches.name
                    FROM enrollments
                    INNER JOIN batches ON batches.id = enrollments.batch_id
                    WHERE enrollments.student_id = students.id
                    LIMIT 1
                ), \'Not assigned\') AS batch_name,
                fees.month, fees.amount, fees.status, fees.payment_date
             FROM fees
             INNER JOIN students ON students.id = fees.student_id
             INNER JOIN users ON users.id = students.user_id
             ORDER BY fees.id DESC'
        )->fetchAll();
    }

    public function handleAddFee(): array {
        $result = ['message' => '', 'messageType' => 'success'];

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $result;
        }

        $studentId = (int) ($_POST['student_id'] ?? 0);
        $amount = isset($_POST['amount']) ? (float) $_POST['amount'] : 0;
        $month = trim($_POST['month'] ?? '');
        $status = $_POST['status'] ?? 'unpaid';
        $paymentDateInput = trim($_POST['payment_date'] ?? '');
        $errors = [];

        if ($studentId < 1) {
            $errors[] = 'Please select a valid student.';
        } else {
            $chkStudent = $this->db->prepare('SELECT id FROM students WHERE id = ? LIMIT 1');
            $chkStudent->execute([$studentId]);
            if (!$chkStudent->fetch()) {
                $errors[] = 'The selected student does not exist.';
            }
        }

        if ($month === '' || !preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $month)) {
            $errors[] = 'Please select a valid billing month (e.g. 2026-09).';
        }

        if ($amount <= 0 || $amount > 500000) {
            $errors[] = 'Fee amount must be a positive number between 1 and 500,000.';
        }

        if (!in_array($status, ['paid', 'unpaid', 'partial'], true)) {
            $errors[] = 'Please select a valid payment status (Paid, Unpaid, or Partial).';
        }

        $paymentDate = null;
        if ($status === 'paid' || $status === 'partial') {
            if ($paymentDateInput !== '') {
                if (!strtotime($paymentDateInput)) {
                    $errors[] = 'Please enter a valid payment date.';
                } else {
                    $paymentDate = $paymentDateInput;
                }
            } else {
                $paymentDate = date('Y-m-d');
            }
        }

        if (empty($errors)) {
            $formattedMonth = $month . '-01';
            $chkDup = $this->db->prepare('SELECT id FROM fees WHERE student_id = ? AND month = ? LIMIT 1');
            $chkDup->execute([$studentId, $formattedMonth]);
            if ($chkDup->fetch()) {
                $errors[] = 'A fee invoice for this student already exists for ' . date('F Y', strtotime($formattedMonth)) . '.';
            }
        }

        if (!empty($errors)) {
            $result['message'] = implode('<br>', $errors);
            $result['messageType'] = 'error';
            return $result;
        }

        try {
            $stmt = $this->db->prepare(
                'INSERT INTO fees (student_id, amount, month, status, payment_date) VALUES (?, ?, ?, ?, ?)'
            );
            $stmt->execute([$studentId, $amount, $month . '-01', $status, $paymentDate]);
            $result['message'] = 'Fee record created successfully.';
            $result['messageType'] = 'success';
            $_POST = [];
        } catch (PDOException $e) {
            $result['message'] = 'Unable to save fee: ' . $e->getMessage();
            $result['messageType'] = 'error';
        }

        return $result;
    }

    public function getStudentFees(int $studentId): array {
        $stmt = $this->db->prepare(
            'SELECT COALESCE(SUM(amount), 0) FROM fees WHERE student_id = ? AND status = \'paid\''
        );
        $stmt->execute([$studentId]);
        $totalPaid = (float) $stmt->fetchColumn();

        $stmt = $this->db->prepare(
            'SELECT COALESCE(SUM(amount), 0) FROM fees WHERE student_id = ? AND status = \'unpaid\''
        );
        $stmt->execute([$studentId]);
        $totalDue = (float) $stmt->fetchColumn();

        $stmt = $this->db->prepare(
            'SELECT fees.id, fees.month, fees.amount, fees.status, fees.payment_date,
                    batches.name AS batch_name
             FROM fees
             LEFT JOIN enrollments ON enrollments.student_id = fees.student_id AND enrollments.status = \'active\'
             LEFT JOIN batches ON batches.id = enrollments.batch_id
             WHERE fees.student_id = ?
             ORDER BY fees.month DESC, fees.id DESC'
        );
        $stmt->execute([$studentId]);
        $fees = $stmt->fetchAll();

        return [
            'totalPaid' => $totalPaid,
            'totalDue' => $totalDue,
            'fees' => $fees,
        ];
    }
}
