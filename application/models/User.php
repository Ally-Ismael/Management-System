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

    public function list(int $page = 1, int $perPage = 25, ?bool $onlyPending = null): array {
        $offset = ($page - 1) * $perPage;
        if ($onlyPending === true) {
            $stmt = $this->db->prepare('SELECT id, name, email, role, verified, created_at FROM users WHERE verified = 0 ORDER BY id DESC LIMIT ? OFFSET ?');
        } elseif ($onlyPending === false) {
            $stmt = $this->db->prepare('SELECT id, name, email, role, verified, created_at FROM users WHERE verified = 1 ORDER BY id DESC LIMIT ? OFFSET ?');
        } else {
            $stmt = $this->db->prepare('SELECT id, name, email, role, verified, created_at FROM users ORDER BY id DESC LIMIT ? OFFSET ?');
        }
        $stmt->bind_param('ii', $perPage, $offset);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function count(?bool $onlyPending = null): int {
        if ($onlyPending === true) {
            $res = $this->db->query('SELECT COUNT(*) AS c FROM users WHERE verified = 0');
        } elseif ($onlyPending === false) {
            $res = $this->db->query('SELECT COUNT(*) AS c FROM users WHERE verified = 1');
        } else {
            $res = $this->db->query('SELECT COUNT(*) AS c FROM users');
        }
        $row = $res->fetch_assoc();
        return (int)$row['c'];
    }

    public function setVerified(int $id, bool $verified): bool {
        $v = $verified ? 1 : 0;
        $stmt = $this->db->prepare('UPDATE users SET verified = ? WHERE id = ?');
        $stmt->bind_param('ii', $v, $id);
        return $stmt->execute();
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare('DELETE FROM users WHERE id = ?');
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
}