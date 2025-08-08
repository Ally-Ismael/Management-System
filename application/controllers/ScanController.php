<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Lib\Helpers\AuthHelper;
use App\Lib\Helpers\CsrfHelper;
use App\Models\LaptopScan;
use App\Models\CarScan;

class ScanController extends BaseController {
    public function laptopsAction(): void {
        AuthHelper::requireLogin();
        $model = new LaptopScan();
        $msg = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            CsrfHelper::validateOrFail();
            $ln = trim($_POST['laptop_number'] ?? '');
            $dir = $_POST['direction'] ?? 'in';
            if ($ln && in_array($dir, ['in','out'], true)) {
                $model->log($ln, $dir, (int)$_SESSION['user']['id']);
                $msg = 'Logged.';
            } else {
                $msg = 'Invalid input.';
            }
        }
        $recent = $model->recent(50);
        $this->render('scans/laptops', ['recent' => $recent, 'msg' => $msg]);
    }

    public function carsAction(): void {
        AuthHelper::requireLogin();
        $model = new CarScan();
        $msg = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            CsrfHelper::validateOrFail();
            $reg = trim($_POST['registration_number'] ?? '');
            $dir = $_POST['direction'] ?? 'in';
            if ($reg && in_array($dir, ['in','out'], true)) {
                $model->log($reg, $dir, (int)$_SESSION['user']['id']);
                $msg = 'Logged.';
            } else {
                $msg = 'Invalid input.';
            }
        }
        $recent = $model->recent(50);
        $this->render('scans/cars', ['recent' => $recent, 'msg' => $msg]);
    }
}