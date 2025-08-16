<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Lib\Helpers\AuthHelper;
use App\Lib\Helpers\CsrfHelper;
use App\Models\User;
use App\Models\ActivityLog;

class UsersController extends BaseController {
    public function indexAction(): void {
        AuthHelper::requireAdmin();
        $page = max(1, (int)($_GET['page'] ?? 1));
        $userModel = new User();
        $items = $userModel->list($page, 25, null);
        $count = $userModel->count(null);
        $this->render('users/index', [
            'items' => $items,
            'count' => $count,
            'page' => $page,
            'filter' => 'all',
            'msg' => $_GET['msg'] ?? ''
        ]);
    }

    public function pendingAction(): void {
        AuthHelper::requireAdmin();
        $page = max(1, (int)($_GET['page'] ?? 1));
        $userModel = new User();
        $items = $userModel->list($page, 25, true);
        $count = $userModel->count(true);
        $this->render('users/index', [
            'items' => $items,
            'count' => $count,
            'page' => $page,
            'filter' => 'pending',
            'msg' => $_GET['msg'] ?? ''
        ]);
    }

    public function verifyAction(): void {
        AuthHelper::requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo 'Method Not Allowed'; return; }
        CsrfHelper::validateOrFail();
        $id = (int)($_POST['id'] ?? 0);
        $verified = (int)($_POST['verified'] ?? 1) === 1;
        $userModel = new User();
        $ok = $id > 0 ? $userModel->setVerified($id, $verified) : false;
        (new ActivityLog())->log((int)($_SESSION['user']['id'] ?? 0), 'verify_user', 'user', $id, $verified ? 'verified' : 'unverified');
        header('Location: /index.php?r=users/' . ($verified ? 'index' : 'pending') . '&msg=' . ($ok ? 'Updated' : 'Failed'));
    }

    public function deleteAction(): void {
        AuthHelper::requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo 'Method Not Allowed'; return; }
        CsrfHelper::validateOrFail();
        $id = (int)($_POST['id'] ?? 0);
        if ($id === (int)($_SESSION['user']['id'] ?? 0)) { header('Location: /index.php?r=users/index&msg=Cannot delete self'); return; }
        $userModel = new User();
        $ok = $id > 0 ? $userModel->delete($id) : false;
        (new ActivityLog())->log((int)($_SESSION['user']['id'] ?? 0), 'delete_user', 'user', $id, $ok ? null : 'failed');
        header('Location: /index.php?r=users/index&msg=' . ($ok ? 'Deleted' : 'Delete failed'));
    }

    public function exportCsvAction(): void {
        AuthHelper::requireAdmin();
        $userModel = new User();
        $items = $userModel->list(1, 100000, null);
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="users.csv"');
        $out = fopen('php://output', 'w');
        fputcsv($out, ['id','name','email','role','verified','created_at']);
        foreach ($items as $u) {
            fputcsv($out, [$u['id'], $u['name'], $u['email'], $u['role'], $u['verified'], $u['created_at']]);
        }
        fclose($out);
        (new ActivityLog())->log((int)($_SESSION['user']['id'] ?? 0), 'export_users_csv', 'user', null, null);
    }
}