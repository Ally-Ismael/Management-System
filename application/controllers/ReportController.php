<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Lib\Helpers\AuthHelper;
use App\Models\LaptopScan;
use App\Models\CarScan;
use App\Models\Attendance;

class ReportController extends BaseController {
    public function indexAction(): void {
        AuthHelper::requireLogin();
        $ls = (new LaptopScan())->recent(100);
        $cs = (new CarScan())->recent(100);
        $as = (new Attendance())->recent(100);
        $this->render('reports/index', ['laptopScans' => $ls, 'carScans' => $cs, 'attendance' => $as]);
    }

    public function exportLaptopCsvAction(): void {
        AuthHelper::requireLogin();
        $rows = (new LaptopScan())->recent(1000);
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="laptop_scans.csv"');
        $out = fopen('php://output', 'w');
        fputcsv($out, ['id','laptop_number','direction','scanned_at','scanned_by']);
        foreach ($rows as $r) fputcsv($out, $r);
        fclose($out);
    }

    public function exportCarCsvAction(): void {
        AuthHelper::requireLogin();
        $rows = (new CarScan())->recent(1000);
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="car_scans.csv"');
        $out = fopen('php://output', 'w');
        fputcsv($out, ['id','registration_number','direction','scanned_at','scanned_by']);
        foreach ($rows as $r) fputcsv($out, $r);
        fclose($out);
    }

    public function exportAttendanceCsvAction(): void {
        AuthHelper::requireLogin();
        $rows = (new Attendance())->recent(1000);
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="attendance.csv"');
        $out = fopen('php://output', 'w');
        fputcsv($out, ['id','employee_number','full_name','status','occurred_at']);
        foreach ($rows as $r) fputcsv($out, $r);
        fclose($out);
    }
}