<?php
declare(strict_types=1);

namespace App\Config;

// Helper: read env
function __ENV__(string $key, string $default = ''): string {
	$val = getenv($key);
	return ($val === false) ? $default : $val;
}

// Base URL (for building links). Set APP_BASE_URL env for production.
$__baseUrl = getenv('APP_BASE_URL');
if ($__baseUrl === false || $__baseUrl === '') { $__baseUrl = '/'; }
if (!\defined(__NAMESPACE__ . '\\BASE_URL')) {
	\define(__NAMESPACE__ . '\\BASE_URL', $__baseUrl);
}

// DB Config (use env vars in production)
if (!\defined(__NAMESPACE__ . '\\DB_HOST')) {
	\define(__NAMESPACE__ . '\\DB_HOST', __ENV__('DB_HOST', '127.0.0.1'));
}
if (!\defined(__NAMESPACE__ . '\\DB_USER')) {
	\define(__NAMESPACE__ . '\\DB_USER', __ENV__('DB_USER', 'root'));
}
if (!\defined(__NAMESPACE__ . '\\DB_PASS')) {
	\define(__NAMESPACE__ . '\\DB_PASS', __ENV__('DB_PASS', ''));
}
if (!\defined(__NAMESPACE__ . '\\DB_NAME')) {
	\define(__NAMESPACE__ . '\\DB_NAME', __ENV__('DB_NAME', 'iyaloo'));
}

// Self-registration toggle
if (!\defined(__NAMESPACE__ . '\\ALLOW_SELF_REGISTRATION')) {
	\define(__NAMESPACE__ . '\\ALLOW_SELF_REGISTRATION', __ENV__('ALLOW_SELF_REGISTRATION', '1') === '1');
}