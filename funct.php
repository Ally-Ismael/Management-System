<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$db = mysqli_connect('localhost', 'root', '', 'iyaloo');
if (!$db) {
    die("Database connection failed: " . mysqli_connect_error());
}

function e($val) {
    global $db;
    return mysqli_real_escape_string($db, trim($val));
}

function isRegisteredAsset($asset_tag) {
    global $db;

    $asset_tag = e($asset_tag); 
    $query = "SELECT COUNT(*) as count FROM assets WHERE asset_tag = '$asset_tag'";
    $result = mysqli_query($db, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $count = $row['count'];
        return ($count > 0); 
    } else {
        return false; 
    }
}

function getCurrentStatus($asset_tag) {
    global $db;

    $asset_tag = e($asset_tag); 

    $query = "SELECT action FROM asset_scans WHERE asset_tag = '$asset_tag' ORDER BY id DESC LIMIT 1";
    $result = mysqli_query($db, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['status'] ?? null; 
    } else {
        return null; 
    }
}

function scanAsset($asset_tag, $status) {
    global $db;

    if (empty($asset_tag)) {
        return "Asset tag is required";
    }

    $currentStatus = getCurrentStatus($asset_tag);

    if ($action === "in") {
        if ($currentStatus === "in") {
            return "This asset is already checked in.";
        }
    } elseif ($action === "out") {
        if ($currentStatus === "out") {
            return "This asset is already checked out.";
        }
    }

    $asset_tag = e($asset_tag); 
    $status = e($status); 
    
    $query = "INSERT INTO asset_scans (asset_tag, status) VALUES ('$asset_tag', '$status')";
    if (mysqli_query($db, $query)) {
        return "asset scanned successfully!";
    } else {
        return "Failed to scan asset: " . mysqli_error($db);
    }
}

function isLoggedIn() {
    return isset($_SESSION['user']);
}

function checkLogin() {
    if (!isLoggedIn()) {
        $_SESSION['msg'] = "You must log in first";
        header('location: login.php');
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['scan_btn'])) {
    $asset_tag= $_POST['asset_tag'];
    $status = $_POST['status'];

    if (isRegisteredAsset($asset_tag)) {
        $_SESSION['message'] = scanAsset($asset_tag, $status);
    } else {
        $_SESSION['msg'] = "This asset is not registered.";
    }
    header('Location: scaninout.php');
    exit();
}
?>
