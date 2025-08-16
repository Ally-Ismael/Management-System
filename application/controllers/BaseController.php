<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\ActivityLog;

// BASE_URL is a constant in App\Config; referenced directly in views

abstract class BaseController {
    protected function render(string $view, array $params = []): void {
        extract($params);
        ob_start();
        require __DIR__ . '/../views/' . $view . '.php';
        $content = ob_get_clean();
        require __DIR__ . '/../views/layouts/main.php';
    }

    protected function log(?int $userId, string $action, ?string $entity = null, ?int $entityId = null, ?string $details = null): void {
        try { (new ActivityLog())->log($userId, $action, $entity, $entityId, $details); } catch (\Throwable $e) { /* ignore logging errors */ }
    }
}