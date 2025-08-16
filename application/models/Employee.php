<?php
declare(strict_types=1);

namespace App\Models;

class Employee extends BaseModel {
    public function create(string $employeeNumber, string $fullName, string $department): int {
        $stmt = $this->db->prepare('INSERT INTO employees (employee_number, full_name, department) VALUES (?, ?, ?)');
        $stmt->bind_param('sss', $employeeNumber, $fullName, $department);
        $stmt->execute();
        return $stmt->insert_id;
    }
    public function list(int $page = 1, int $perPage = 20): array {
        $offset = ($page - 1) * $perPage;
        $stmt = $this->db->prepare('SELECT id, employee_number, full_name, department, created_at FROM employees ORDER BY id DESC LIMIT ? OFFSET ?');
        $stmt->bind_param('ii', $perPage, $offset);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    public function count(): int {
        $res = $this->db->query('SELECT COUNT(*) AS c FROM employees');
        return (int)$res->fetch_assoc()['c'];
    }
}