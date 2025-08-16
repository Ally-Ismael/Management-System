<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Lib\Helpers\AuthHelper;
use App\Lib\Helpers\CsrfHelper;
use App\Models\Driver;
use App\Models\ActivityLog;

class DriversController extends BaseController {
    public function createAction(): void {
        AuthHelper::requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo 'Method Not Allowed'; return; }
        CsrfHelper::validateOrFail();
        $driverId = trim($_POST['driver_id'] ?? '');
        $fullName = trim($_POST['full_name'] ?? '');
        $gender = trim($_POST['gender'] ?? '');
        $carColor = trim($_POST['car_color'] ?? '');
        $ok = false;
        if ($driverId !== '' && $fullName !== '' && in_array($gender, ['male','female','other'], true) && $carColor !== '') {
            try {
                $id = (new Driver())->create($driverId, $fullName, $gender, $carColor);
                $ok = $id > 0;
            } catch (\Throwable $e) { $ok = false; }
        }
        (new ActivityLog())->log((int)($_SESSION['user']['id'] ?? 0), 'create_driver', 'driver', $ok ? ($id ?? null) : null, $ok ? null : 'failed');
        header('Location: /index.php?r=users/index&msg=' . ($ok ? 'Driver added' : 'Failed to add driver'));
    }
}