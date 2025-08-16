<?php
declare(strict_types=1);

namespace App\Models;

class ActivityLog extends BaseModel {
    public function log(?int $userId, string $action, ?string $entity = null, ?int $entityId = null, ?string $details = null): int {
        $stmt = $this->db->prepare('INSERT INTO activity_logs (user_id, action, entity, entity_id, details) VALUES (?, ?, ?, ?, ?)');
        $stmt->bind_param('issis', $userId, $action, $entity, $entityId, $details);
        $stmt->execute();
        return $stmt->insert_id;
    }
}