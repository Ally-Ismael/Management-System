<?php
declare(strict_types=1);

namespace App\Config;

// Base URL (for building links). Set APP_BASE_URL env for production.
const BASE_URL = (
    getenv('APP_BASE_URL') !== false && getenv('APP_BASE_URL') !== ''
) ? getenv('APP_BASE_URL') : '/';

// DB Config (use env vars in production)
const DB_HOST = __ENV__('DB_HOST', '127.0.0.1');
const DB_USER = __ENV__('DB_USER', 'root');
const DB_PASS = __ENV__('DB_PASS', '');
const DB_NAME = __ENV__('DB_NAME', 'iyaloo');

// Self-registration toggle
const ALLOW_SELF_REGISTRATION = __ENV__('ALLOW_SELF_REGISTRATION', '1') === '1';

// Helper: read env
function __ENV__(string $key, string $default = ''): string {
    $val = getenv($key);
    return ($val === false) ? $default : $val;
}