<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Lib\Helpers\AuthHelper;
use App\Lib\Helpers\CsrfHelper;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\ActivityLog;

class AttendanceController extends BaseController {
    public function indexAction(): void {
        AuthHelper::requireLogin();
        $msg = '';
        $employeeModel = new Employee();
        $attendanceModel = new Attendance();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            CsrfHelper::validateOrFail();
            if (isset($_POST['create_employee'])) {
                $num = trim($_POST['employee_number'] ?? '');
                $name = trim($_POST['full_name'] ?? '');
                $dept = trim($_POST['department'] ?? '');
                if ($num && $name && $dept) { $employeeModel->create($num, $name, $dept); $msg = 'Employee added.'; (new ActivityLog())->log((int)($_SESSION['user']['id'] ?? 0), 'create_employee', 'employee', null, $num); }
            } else {
                $empId = (int)($_POST['employee_id'] ?? 0);
                $status = $_POST['status'] ?? 'in';
                if ($empId > 0 && in_array($status, ['in','out'], true)) { $attendanceModel->log($empId, $status); $msg = 'Attendance logged.'; (new ActivityLog())->log((int)($_SESSION['user']['id'] ?? 0), 'attendance', 'attendance', $empId, $status); }
            }
        }
        $page = max(1, (int)($_GET['page'] ?? 1));
        $employees = $employeeModel->list($page, 50);
        $count = $employeeModel->count();
        $recent = $attendanceModel->recent(50);
        $this->render('attendance/index', ['employees' => $employees, 'count' => $count, 'recent' => $recent, 'msg' => $msg]);
    }
}