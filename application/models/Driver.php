<?php
declare(strict_types=1);

namespace App\Models;

class Driver extends BaseModel {
    public function create(string $driverId, string $fullName, string $gender, string $carColor): int {
        $stmt = $this->db->prepare('INSERT INTO drivers (driver_id, full_name, gender, car_color) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('ssss', $driverId, $fullName, $gender, $carColor);
        $stmt->execute();
        return $stmt->insert_id;
    }
    public function list(int $page = 1, int $perPage = 25): array {
        $offset = ($page - 1) * $perPage;
        $stmt = $this->db->prepare('SELECT id, driver_id, full_name, gender, car_color, created_at FROM drivers ORDER BY id DESC LIMIT ? OFFSET ?');
        $stmt->bind_param('ii', $perPage, $offset);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    public function count(): int {
        $res = $this->db->query('SELECT COUNT(*) AS c FROM drivers');
        $row = $res->fetch_assoc();
        return (int)$row['c'];
    }
}