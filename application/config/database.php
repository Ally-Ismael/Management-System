<?php
declare(strict_types=1);

namespace App\Config;

use mysqli;
use RuntimeException;

class Database {
    private static ?mysqli $conn = null;

    public static function getConnection(): mysqli {
        if (self::$conn instanceof mysqli) {
            return self::$conn;
        }
        $conn = @new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($conn->connect_errno) {
            throw new RuntimeException('Database connection failed: ' . $conn->connect_error);
        }
        // Strict mode, utf8mb4
        $conn->set_charset('utf8mb4');
        self::$conn = $conn;
        return self::$conn;
    }
}