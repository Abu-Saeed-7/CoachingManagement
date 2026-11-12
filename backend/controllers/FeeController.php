<?php
require_once __DIR__ . '/../config/database.php';

class FeeController {
    private PDO $db;

    public function __construct(?PDO $db = null) {
        if ($db !== null) {
            $this->db = $db;
        } else {
            global $db;
            $this->db = $db ?? $GLOBALS['db'];
        }
    }

    /**
     * Automatically ensure all active enrolled students have fee records (default 'unpaid')
     * for every month they have been active up to the current month.
     */
    public function syncActiveStudentMonthlyFees(): int {
        $sql = "SELECT DISTINCT e.student_id, e.enrollment_date 
                FROM enrollments e 
                JOIN students s ON s.id = e.student_id
                WHERE e.status = 'active'";
        $activeEnrollments = $this->db->query($sql)->fetchAll();

        $currentMonth = date('Y-m-01');
        $insertedCount = 0;

        foreach ($activeEnrollments as $enr) {
            $studentId = (int) $enr['student_id'];
            $startDateStr = (!empty($enr['enrollment_date']) && $enr['enrollment_date'] !== '0000-00-00')
                ? $enr['enrollment_date']
                : date('Y-m-01');
            $startMonthStr = date('Y-m-01', strtotime($startDateStr));

            $cursor = strtotime($startMonthStr);
            $end = strtotime($currentMonth);

            while ($cursor <= $end) {
                $monthDate = date('Y-m-01', $cursor);
                $deletedKey = $studentId . '|' . $monthDate;

                if (!empty($_SESSION['deleted_fee_months'][$deletedKey])) {
                    $cursor = strtotime("+1 month", $cursor);
                    continue;
                }

                $check = $this->db->prepare("SELECT id FROM fees WHERE student_id = ? AND month = ? LIMIT 1");
                $check->execute([$studentId, $monthDate]);
                if (!$check->fetch()) {
                    $insert = $this->db->prepare(
                        "INSERT INTO fees (student_id, amount, month, status, payment_date) 
                         VALUES (?, 3000.00, ?, 'unpaid', NULL)"
                    );
                    $insert->execute([$studentId, $monthDate]);
                    $insertedCount++;
                }

                $cursor = strtotime("+1 month", $cursor);
            }
        }

        return $insertedCount;
    }

    public function getFeeStats(): array {
        $this->syncActiveStudentMonthlyFees();

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
        $this->syncActiveStudentMonthlyFees();

        return $this->db->query(
            'SELECT fees.id, fees.student_id, users.name AS student_name, students.roll,
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

    /**
     * Validate and normalize fee input shared by Add and Edit handlers.
     */
    private function validateFeePayload(array $input, ?int $excludeFeeId = null): array {
        $studentId = (int) ($input['student_id'] ?? 0);
        $amount = isset($input['amount']) ? (float) $input['amount'] : 0;
        $month = trim($input['month'] ?? '');
        $status = $input['status'] ?? 'unpaid';
        $paymentDateInput = trim($input['payment_date'] ?? '');
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

        $formattedMonth = $month !== '' ? ($month . '-01') : '';
        $existingCollectibleId = null;
        if (empty($errors)) {
            if ($excludeFeeId !== null) {
                $chkDup = $this->db->prepare('SELECT id, status FROM fees WHERE student_id = ? AND month = ? AND id <> ? LIMIT 1');
                $chkDup->execute([$studentId, $formattedMonth, $excludeFeeId]);
                $existingFee = $chkDup->fetch();
                if ($existingFee) {
                    $errors[] = 'A fee invoice for this student already exists for ' . date('F Y', strtotime($formattedMonth)) . '.';
                }
            } else {
                $chkDup = $this->db->prepare('SELECT id, status FROM fees WHERE student_id = ? AND month = ? LIMIT 1');
                $chkDup->execute([$studentId, $formattedMonth]);
                $existingFee = $chkDup->fetch();
                if ($existingFee) {
                    if ($existingFee['status'] !== 'paid' && in_array($status, ['paid', 'partial'], true)) {
                        $existingCollectibleId = (int) $existingFee['id'];
                    } else {
                        $errors[] = 'A ' . $existingFee['status'] . ' fee invoice for this student already exists for ' . date('F Y', strtotime($formattedMonth)) . '. Use Edit to modify it.';
                    }
                }
            }
        }

        return [
            'errors' => $errors,
            'studentId' => $studentId,
            'amount' => $amount,
            'formattedMonth' => $formattedMonth,
            'status' => $status,
            'paymentDate' => $paymentDate,
            'existingCollectibleId' => $existingCollectibleId,
        ];
    }

    public function handleAddFee(): array {
        $result = ['message' => '', 'messageType' => 'success', 'errors' => []];

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || (isset($_POST['action']) && $_POST['action'] !== 'add_fee')) {
            return $result;
        }

        $validated = $this->validateFeePayload($_POST);
        if (!empty($validated['errors'])) {
            $result['errors'] = $validated['errors'];
            $result['message'] = implode(' ', $validated['errors']);
            $result['messageType'] = 'error';
            return $result;
        }

        try {
            if (!empty($validated['existingCollectibleId'])) {
                $stmt = $this->db->prepare(
                    'UPDATE fees SET amount = ?, status = ?, payment_date = ? WHERE id = ?'
                );
                $stmt->execute([
                    $validated['amount'],
                    $validated['status'],
                    $validated['paymentDate'],
                    $validated['existingCollectibleId'],
                ]);
                $result['message'] = 'Fee collected and existing invoice updated successfully.';
            } else {
                $stmt = $this->db->prepare(
                    'INSERT INTO fees (student_id, amount, month, status, payment_date) VALUES (?, ?, ?, ?, ?)'
                );
                $stmt->execute([
                    $validated['studentId'],
                    $validated['amount'],
                    $validated['formattedMonth'],
                    $validated['status'],
                    $validated['paymentDate'],
                ]);
                $result['message'] = 'Fee record created successfully.';
            }
            if (session_status() === PHP_SESSION_ACTIVE) {
                unset($_SESSION['deleted_fee_months'][$validated['studentId'] . '|' . $validated['formattedMonth']]);
            }
            $result['messageType'] = 'success';
            $_POST = [];
        } catch (PDOException $e) {
            $errMsg = 'Unable to save fee: ' . $e->getMessage();
            $result['message'] = $errMsg;
            $result['errors'] = [$errMsg];
            $result['messageType'] = 'error';
        }

        return $result;
    }

    /**
     * Update an existing fee invoice record.
     */
    public function handleEditFee(): array {
        $result = ['message' => '', 'messageType' => 'success', 'errors' => []];

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || ($_POST['action'] ?? '') !== 'edit_fee') {
            return $result;
        }

        $feeId = (int) ($_POST['fee_id'] ?? 0);
        if ($feeId < 1) {
            $errMsg = 'Invalid fee invoice selected.';
            return ['message' => $errMsg, 'messageType' => 'error', 'errors' => [$errMsg]];
        }

        $existsStmt = $this->db->prepare('SELECT id FROM fees WHERE id = ? LIMIT 1');
        $existsStmt->execute([$feeId]);
        if (!$existsStmt->fetch()) {
            $errMsg = 'Fee record not found.';
            return ['message' => $errMsg, 'messageType' => 'error', 'errors' => [$errMsg]];
        }

        $validated = $this->validateFeePayload($_POST, $feeId);
        if (!empty($validated['errors'])) {
            $result['errors'] = $validated['errors'];
            $result['message'] = implode(' ', $validated['errors']);
            $result['messageType'] = 'error';
            return $result;
        }

        try {
            $stmt = $this->db->prepare(
                'UPDATE fees SET student_id = ?, amount = ?, month = ?, status = ?, payment_date = ? WHERE id = ?'
            );
            $stmt->execute([
                $validated['studentId'],
                $validated['amount'],
                $validated['formattedMonth'],
                $validated['status'],
                $validated['paymentDate'],
                $feeId,
            ]);
            if (session_status() === PHP_SESSION_ACTIVE) {
                unset($_SESSION['deleted_fee_months'][$validated['studentId'] . '|' . $validated['formattedMonth']]);
            }
            $result['message'] = 'Fee record updated successfully.';
            $result['messageType'] = 'success';
            $_POST = [];
        } catch (PDOException $e) {
            $errMsg = 'Unable to update fee: ' . $e->getMessage();
            $result['message'] = $errMsg;
            $result['errors'] = [$errMsg];
            $result['messageType'] = 'error';
        }

        return $result;
    }

    /**
     * Delete a fee invoice record.
     */
    public function handleDeleteFee(): array {
        $result = ['message' => '', 'messageType' => 'success'];

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || ($_POST['action'] ?? '') !== 'delete_fee') {
            return $result;
        }

        $feeId = (int) ($_POST['fee_id'] ?? 0);
        if ($feeId < 1) {
            return ['message' => 'Invalid fee invoice selected.', 'messageType' => 'error'];
        }

        try {
            $findStmt = $this->db->prepare('SELECT id, student_id, month FROM fees WHERE id = ? LIMIT 1');
            $findStmt->execute([$feeId]);
            $feeRow = $findStmt->fetch();

            if (!$feeRow) {
                return ['message' => 'Fee record not found.', 'messageType' => 'error'];
            }

            $delStmt = $this->db->prepare('DELETE FROM fees WHERE id = ?');
            $delStmt->execute([$feeId]);

            if (session_status() === PHP_SESSION_ACTIVE) {
                $_SESSION['deleted_fee_months'][(int) $feeRow['student_id'] . '|' . $feeRow['month']] = true;
            }

            $result['message'] = 'Fee record deleted successfully.';
            $result['messageType'] = 'success';
        } catch (PDOException $e) {
            $result['message'] = 'Unable to delete fee record: ' . $e->getMessage();
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
