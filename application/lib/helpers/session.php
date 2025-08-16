<?php
declare(strict_types=1);

namespace App\Lib\Helpers;

class SessionHelper {
    public static function applySecurityHeaders(): void {
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: DENY');
        header('X-XSS-Protection: 1; mode=block');
        header('Referrer-Policy: no-referrer-when-downgrade');
        header('Content-Security-Policy: default-src \"self\"; img-src \"self\" data:; style-src \"self\" \"unsafe-inline\"; script-src \"self\" \"unsafe-inline\"');
        if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
            header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
        }
    }

    public static function startSecureSession(): void {
        if (session_status() === PHP_SESSION_ACTIVE) return;
        $cookieParams = session_get_cookie_params();
        session_set_cookie_params([
            'lifetime' => 0,
            'path' => $cookieParams['path'] ?: '/',
            'domain' => $cookieParams['domain'] ?: '',
            'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        session_name('mgmt_session');
        session_start();
    }

    public static function regenerate(): void {
        if (session_status() !== PHP_SESSION_ACTIVE) return;
        session_regenerate_id(true);
    }
}