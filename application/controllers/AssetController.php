<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Lib\Helpers\AuthHelper;
use App\Lib\Helpers\CsrfHelper;
use App\Models\Laptop;
use App\Models\Car;

class AssetController extends BaseController {
    public function laptopsAction(): void {
        AuthHelper::requireLogin();
        $page = max(1, (int)($_GET['page'] ?? 1));
        $model = new Laptop();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            CsrfHelper::validateOrFail();
            $ln = trim($_POST['laptop_number'] ?? '');
            $brand = trim($_POST['brand'] ?? '');
            $modelName = trim($_POST['model'] ?? '');
            $assigned = trim($_POST['assigned_to'] ?? '') ?: null;
            if ($ln && $brand && $modelName) { $model->create($ln, $brand, $modelName, $assigned); }
        }
        $items = $model->list($page, 20);
        $count = $model->count();
        $this->render('assets/laptops', ['items' => $items, 'count' => $count, 'page' => $page]);
    }

    public function carsAction(): void {
        AuthHelper::requireLogin();
        $page = max(1, (int)($_GET['page'] ?? 1));
        $model = new Car();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            CsrfHelper::validateOrFail();
            $reg = trim($_POST['registration_number'] ?? '');
            $make = trim($_POST['make'] ?? '');
            $modelName = trim($_POST['model'] ?? '');
            $assigned = trim($_POST['assigned_to'] ?? '') ?: null;
            if ($reg && $make && $modelName) { $model->create($reg, $make, $modelName, $assigned); }
        }
        $items = $model->list($page, 20);
        $count = $model->count();
        $this->render('assets/cars', ['items' => $items, 'count' => $count, 'page' => $page]);
    }
}