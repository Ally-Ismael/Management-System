<?php
declare(strict_types=1);

namespace App\Models;

use mysqli_stmt;

class User extends BaseModel {
    public function create(string $name, string $email, string $passwordHash, string $role = 'user'): int {
        $sql = 'INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ssss', $name, $email, $passwordHash, $role);
        if (!$stmt->execute()) {
            throw new \RuntimeException('Create user failed: ' . $stmt->error);
        }
        return $stmt->insert_id;
    }

    public function findByEmail(string $email): ?array {
        $sql = 'SELECT id, name, email, password_hash, role, created_at FROM users WHERE email = ? LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        return $row ?: null;
    }

    public function findById(int $id): ?array {
        $sql = 'SELECT id, name, email, role, created_at FROM users WHERE id = ? LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        return $row ?: null;
    }

    public function countUsers(): int {
        $res = $this->db->query('SELECT COUNT(*) AS c FROM users');
        $row = $res->fetch_assoc();
        return (int)$row['c'];
    }
}