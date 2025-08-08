<?php
declare(strict_types=1);

namespace App\Models;

class Attendance extends BaseModel {
    public function log(int $employeeId, string $status): int {
        $stmt = $this->db->prepare('INSERT INTO attendance_logs (employee_id, status) VALUES (?, ?)');
        $stmt->bind_param('is', $employeeId, $status);
        $stmt->execute();
        return $stmt->insert_id;
    }
    public function recent(int $limit = 50): array {
        $stmt = $this->db->prepare('SELECT a.id, e.employee_number, e.full_name, a.status, a.occurred_at FROM attendance_logs a JOIN employees e ON e.id = a.employee_id ORDER BY a.id DESC LIMIT ?');
        $stmt->bind_param('i', $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}