<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Lib\Helpers\AuthHelper;
use App\Lib\Helpers\CsrfHelper;
use App\Models\Laptop;
use App\Models\Car;
use App\Models\ActivityLog;

class AssetController extends BaseController {
    public function laptopsAction(): void {
        AuthHelper::requireLogin();
        $page = max(1, (int)($_GET['page'] ?? 1));
        $model = new Laptop();
        $msg = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            CsrfHelper::validateOrFail();
            $ln = trim($_POST['laptop_number'] ?? '');
            $brand = trim($_POST['brand'] ?? '');
            $modelName = trim($_POST['model'] ?? '');
            $assigned = trim($_POST['assigned_to'] ?? '') ?: null;
            if ($ln && $brand && $modelName) { $model->create($ln, $brand, $modelName, $assigned); (new ActivityLog())->log((int)($_SESSION['user']['id'] ?? 0), 'create_laptop', 'laptop', null, $ln); $msg = 'Laptop added.'; }
        }
        $items = $model->list($page, 20);
        $count = $model->count();
        $this->render('assets/laptops', ['items' => $items, 'count' => $count, 'page' => $page, 'msg' => $msg]);
    }

    public function carsAction(): void {
        AuthHelper::requireLogin();
        $page = max(1, (int)($_GET['page'] ?? 1));
        $model = new Car();
        $msg = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            CsrfHelper::validateOrFail();
            $reg = trim($_POST['registration_number'] ?? '');
            $make = trim($_POST['make'] ?? '');
            $modelName = trim($_POST['model'] ?? '');
            $assigned = trim($_POST['assigned_to'] ?? '') ?: null;
            if ($reg && $make && $modelName) { $model->create($reg, $make, $modelName, $assigned); (new ActivityLog())->log((int)($_SESSION['user']['id'] ?? 0), 'create_car', 'car', null, $reg); $msg = 'Car added.'; }
        }
        $items = $model->list($page, 20);
        $count = $model->count();
        $this->render('assets/cars', ['items' => $items, 'count' => $count, 'page' => $page, 'msg' => $msg]);
    }

    public function deleteLaptopAction(): void {
        AuthHelper::requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo 'Method Not Allowed'; return; }
        CsrfHelper::validateOrFail();
        $id = (int)($_POST['id'] ?? 0);
        $ok = false;
        if ($id > 0) { $ok = (new Laptop())->delete($id); }
        (new ActivityLog())->log((int)($_SESSION['user']['id'] ?? 0), 'delete_laptop', 'laptop', $id, $ok ? null : 'failed');
        header('Location: /index.php?r=assets/laptops&msg=' . ($ok ? 'Deleted' : 'Delete failed'));
    }

    public function deleteCarAction(): void {
        AuthHelper::requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo 'Method Not Allowed'; return; }
        CsrfHelper::validateOrFail();
        $id = (int)($_POST['id'] ?? 0);
        $ok = false;
        if ($id > 0) { $ok = (new Car())->delete($id); }
        (new ActivityLog())->log((int)($_SESSION['user']['id'] ?? 0), 'delete_car', 'car', $id, $ok ? null : 'failed');
        header('Location: /index.php?r=assets/cars&msg=' . ($ok ? 'Deleted' : 'Delete failed'));
    }
}