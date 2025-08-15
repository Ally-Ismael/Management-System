<?php
declare(strict_types=1);

// Front Controller
$rootPath = dirname(__DIR__);
$appPath = $rootPath . '/application';

require_once $appPath . '/config/constants.php';
require_once $appPath . '/lib/helpers/session.php';
require_once $appPath . '/lib/helpers/csrf.php';
require_once $appPath . '/lib/helpers/auth.php';
require_once $appPath . '/controllers/BaseController.php';

// Security headers on every request
\App\Lib\Helpers\SessionHelper::applySecurityHeaders();
\App\Lib\Helpers\SessionHelper::startSecureSession();

// Autoload minimal (PSR-4-like) for our App namespace
spl_autoload_register(function ($class) use ($appPath) {
    $prefix = 'App\\';
    if (strpos($class, $prefix) !== 0) return;
    $relative = str_replace('App\\', '', $class);
    $relative = str_replace('\\', '/', $relative);
    $parts = explode('/', $relative);
    $fileBase = array_pop($parts);
    $dirOriginal = implode('/', $parts);
    $dirLower = strtolower($dirOriginal);
    $candidates = [
        $appPath . '/' . $dirOriginal . '/' . $fileBase . '.php',
        $appPath . '/' . $dirLower . '/' . $fileBase . '.php',
        $appPath . '/' . $dirLower . '/' . strtolower($fileBase) . '.php',
        $appPath . '/' . $dirOriginal . '/' . strtolower($fileBase) . '.php',
    ];
    foreach ($candidates as $file) {
        if (is_file($file)) { require_once $file; return; }
    }
});

use App\Controllers\AuthController;
use App\Controllers\AssetController;
use App\Controllers\ScanController;
use App\Controllers\ReportController;
use App\Controllers\AttendanceController;

$route = $_GET['r'] ?? '';

function isLoggedIn(): bool { return !empty($_SESSION['user']); }

if ($route === '') {
    $route = isLoggedIn() ? 'auth/dashboard' : 'auth/login';
}

[$controllerId, $actionId] = array_pad(explode('/', $route, 2), 2, 'index');

$controllerMap = [
    'auth' => AuthController::class,
    'assets' => AssetController::class,
    'scans' => ScanController::class,
    'reports' => ReportController::class,
    'attendance' => AttendanceController::class,
    'dashboard' => AuthController::class, // dashboard in AuthController for simplicity
];

$controllerClass = $controllerMap[$controllerId] ?? null;
if ($controllerClass === null) {
    http_response_code(404);
    echo 'Not Found';
    exit;
}

$controller = new $controllerClass();
$method = $actionId . 'Action';
if (!method_exists($controller, $method)) {
    http_response_code(404);
    echo 'Not Found';
    exit;
}

// Dispatch
$controller->$method();