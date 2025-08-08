<?php
declare(strict_types=1);

namespace App\Lib\Helpers;

class CsrfHelper {
    public static function token(): string {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function inputField(): string {
        $t = self::token();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($t, ENT_QUOTES, 'UTF-8') . '">';
    }

    public static function validateOrFail(): void {
        $sent = $_POST['csrf_token'] ?? '';
        $valid = hash_equals($_SESSION['csrf_token'] ?? '', $sent);
        if (!$valid) {
            http_response_code(400);
            echo 'Invalid CSRF token';
            exit;
        }
    }
}