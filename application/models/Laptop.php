<?php
declare(strict_types=1);

namespace App\Models;

class Laptop extends BaseModel {
    public function list(int $page = 1, int $perPage = 20): array {
        $offset = ($page - 1) * $perPage;
        $stmt = $this->db->prepare('SELECT id, laptop_number, brand, model, assigned_to, created_at FROM laptops ORDER BY id DESC LIMIT ? OFFSET ?');
        $stmt->bind_param('ii', $perPage, $offset);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    public function count(): int {
        $res = $this->db->query('SELECT COUNT(*) AS c FROM laptops');
        return (int)$res->fetch_assoc()['c'];
    }
    public function create(string $laptopNumber, string $brand, string $model, ?string $assignedTo): int {
        $stmt = $this->db->prepare('INSERT INTO laptops (laptop_number, brand, model, assigned_to) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('ssss', $laptopNumber, $brand, $model, $assignedTo);
        $stmt->execute();
        return $stmt->insert_id;
    }
    public function findById(int $id): ?array {
        $stmt = $this->db->prepare('SELECT * FROM laptops WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return $row ?: null;
    }
    public function update(int $id, string $laptopNumber, string $brand, string $model, ?string $assignedTo): bool {
        $stmt = $this->db->prepare('UPDATE laptops SET laptop_number=?, brand=?, model=?, assigned_to=? WHERE id=?');
        $stmt->bind_param('ssssi', $laptopNumber, $brand, $model, $assignedTo, $id);
        return $stmt->execute();
    }
    public function delete(int $id): bool {
        $stmt = $this->db->prepare('DELETE FROM laptops WHERE id = ?');
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
}