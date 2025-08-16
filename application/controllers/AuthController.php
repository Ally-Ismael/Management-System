<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Lib\Helpers\AuthHelper;
use App\Lib\Helpers\CsrfHelper;
use App\Lib\Helpers\SessionHelper;
use App\Models\User;
use App\Models\ActivityLog;
use const App\Config\ALLOW_SELF_REGISTRATION;

class AuthController extends BaseController {
    public function indexAction(): void { $this->loginAction(); }

    public function loginAction(): void {
        if (!empty($_SESSION['user'])) {
            header('Location: /index.php?r=dashboard/index');
            return;
        }
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            CsrfHelper::validateOrFail();
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            if ($email === '' || $password === '') {
                $error = 'Email and password are required.';
            } else {
                $userModel = new User();
                $user = $userModel->findByEmail($email);
                if ($user && password_verify($password, $user['password_hash'])) {
                    SessionHelper::regenerate();
                    $_SESSION['user'] = [
                        'id' => (int)$user['id'],
                        'name' => $user['name'],
                        'email' => $user['email'],
                        'role' => $user['role'],
                    ];
                    (new ActivityLog())->log((int)$user['id'], 'login', 'user', (int)$user['id'], null);
                    header('Location: /index.php?r=dashboard/index');
                    return;
                } else {
                    (new ActivityLog())->log(null, 'login_failed', 'user', null, $email);
                    $error = 'Invalid credentials.';
                }
            }
        }
        $this->render('auth/login', ['error' => $error, 'allowRegister' => ALLOW_SELF_REGISTRATION]);
    }

    public function registerAction(): void {
        if (!ALLOW_SELF_REGISTRATION) {
            http_response_code(404);
            echo 'Not found';
            return;
        }
        $userModel = new User();
        $error = '';
        $success = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            CsrfHelper::validateOrFail();
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $password2 = $_POST['password2'] ?? '';
            if ($name === '' || $email === '' || $password === '') {
                $error = 'All fields are required.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Invalid email format.';
            } elseif ($password !== $password2) {
                $error = 'Passwords do not match.';
            } elseif (!preg_match('/^(?=.*[A-Za-z])(?=.*\d).{8,}$/', $password)) {
                $error = 'Password must be at least 8 characters with letters and numbers.';
            } else {
                $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
                $role = $userModel->countUsers() === 0 ? 'admin' : 'user';
                try {
                    $userId = $userModel->create($name, $email, $hash, $role);
                    (new ActivityLog())->log((int)$userId, 'register', 'user', (int)$userId, null);
                    $success = 'Registration successful. You can now login.';
                } catch (\Throwable $e) {
                    (new ActivityLog())->log(null, 'register_failed', 'user', null, $email);
                    $error = 'Registration failed. Email may already be in use.';
                }
            }
        }
        $this->render('auth/register', ['error' => $error, 'success' => $success]);
    }

    public function logoutAction(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            CsrfHelper::validateOrFail();
            (new ActivityLog())->log((int)($_SESSION['user']['id'] ?? 0), 'logout', 'user', (int)($_SESSION['user']['id'] ?? 0), null);
            $_SESSION = [];
            if (ini_get('session.use_cookies')) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
            }
            session_destroy();
        }
        header('Location: /index.php?r=auth/login');
    }

    public function indexDashboard(): void { $this->dashboardAction(); }

    public function dashboardAction(): void {
        AuthHelper::requireLogin();
        $this->render('dashboard/index', []);
    }
}