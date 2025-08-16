<?php
declare(strict_types=1);

namespace App\Models;

class CarScan extends BaseModel {
    public function log(string $registrationNumber, string $direction, int $userId): int {
        $stmt = $this->db->prepare('INSERT INTO transactions (registration_number, direction, scanned_by) VALUES (?, ?, ?)');
        $stmt->bind_param('ssi', $registrationNumber, $direction, $userId);
        $stmt->execute();
        return $stmt->insert_id;
    }
    public function recent(int $limit = 50): array {
        $stmt = $this->db->prepare('SELECT id, registration_number, direction, scanned_at, scanned_by FROM transactions ORDER BY id DESC LIMIT ?');
        $stmt->bind_param('i', $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}