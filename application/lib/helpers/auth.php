<?php
declare(strict_types=1);

namespace App\Lib\Helpers;

class AuthHelper {
    public static function requireLogin(): void {
        if (empty($_SESSION['user'])) {
            header('Location: /index.php?r=auth/login');
            exit;
        }
    }

    public static function requireAdmin(): void {
        self::requireLogin();
        if (($_SESSION['user']['role'] ?? 'user') !== 'admin') {
            http_response_code(403);
            echo 'Forbidden';
            exit;
        }
    }
}