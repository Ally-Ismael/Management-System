<?php
declare(strict_types=1);

namespace App\Models;

use App\Config\Database;
use mysqli;

abstract class BaseModel {
    protected mysqli $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }
}