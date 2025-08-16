<?php
declare(strict_types=1);

namespace App\Models;

class Car extends BaseModel {
    public function list(int $page = 1, int $perPage = 20): array {
        $offset = ($page - 1) * $perPage;
        $stmt = $this->db->prepare('SELECT id, registration_number, make, model, assigned_to, created_at FROM cars ORDER BY id DESC LIMIT ? OFFSET ?');
        $stmt->bind_param('ii', $perPage, $offset);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    public function count(): int {
        $res = $this->db->query('SELECT COUNT(*) AS c FROM cars');
        return (int)$res->fetch_assoc()['c'];
    }
    public function create(string $registrationNumber, string $make, string $model, ?string $assignedTo): int {
        $stmt = $this->db->prepare('INSERT INTO cars (registration_number, make, model, assigned_to) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('ssss', $registrationNumber, $make, $model, $assignedTo);
        $stmt->execute();
        return $stmt->insert_id;
    }
    public function findById(int $id): ?array {
        $stmt = $this->db->prepare('SELECT * FROM cars WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return $row ?: null;
    }
    public function update(int $id, string $registrationNumber, string $make, string $model, ?string $assignedTo): bool {
        $stmt = $this->db->prepare('UPDATE cars SET registration_number=?, make=?, model=?, assigned_to=? WHERE id=?');
        $stmt->bind_param('ssssi', $registrationNumber, $make, $model, $assignedTo, $id);
        return $stmt->execute();
    }
    public function delete(int $id): bool {
        $stmt = $this->db->prepare('DELETE FROM cars WHERE id = ?');
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
}