<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$db = mysqli_connect('localhost', 'root', '', 'iyaloo');
if (!$db) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Function to escape input data
function e($val) {
    global $db;
    return mysqli_real_escape_string($db, trim($val));
}

// Function to check if a serial number is registered
function isRegistered($serial_number) {
    global $db;

    $serial_number = e($serial_number); // Sanitize input

    // Query to check if the serial_number exists in the 'registered_laptops' table
    $query = "SELECT COUNT(*) as count FROM private WHERE serial_number = '$serial_number'";
    $result = mysqli_query($db, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['count'] > 0; // Returns true if serial_number exists, false otherwise
    } else {
        return false; // Query execution failed
    }
}

// Function to get the current status of a serial number
function getCurrentStatus($serial_number) {
    global $db;

    $serial_number = e($serial_number); // Sanitize input

    // Query to get the latest action for the given serial number
    $query = "SELECT action FROM scan_event WHERE serial_number = '$serial_number' ORDER BY id DESC LIMIT 1";
    $result = mysqli_query($db, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['action'] ?? null; // Return the last action or null if no records
    } else {
        return null; // Query execution failed
    }
}

// Function to scan serial number and store it in the database
function scanSerial($serial_number, $action) {
    global $db;

    // Validate the serial number
    if (empty($serial_number)) {
        return "Serial number is required";
    }

    // Check if the serial number is registered
    if (!isRegistered($serial_number)) {
        return "This serial number is not registered.";
    }

    // Check current status
    $currentStatus = getCurrentStatus($serial_number);

    if ($action === "in") {
        if ($currentStatus === "in") {
            return "This laptop is already in.";
        }
    } elseif ($action === "out") {
        if ($currentStatus === "out") {
            return "This laptop is already out.";
        }
    } else {
        return "Invalid action. Use 'in' or 'out'.";
    }

    // Insert the scanned serial number and action into the database
    $query = "INSERT INTO scan_event (serial_number, action) VALUES ('$serial_number', '$action')";
    if (mysqli_query($db, $query)) {
        return "Laptop serial number scanned successfully!";
    } else {
        return "Failed to scan laptop serial number: " . mysqli_error($db);
    }
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user']);
}

// Function to handle login check
function checkLogin() {
    if (!isLoggedIn()) {
        $_SESSION['msg'] = "You must log in first";
        header('Location: login.php');
        exit();
    }
}

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['scan_btn'])) {
    checkLogin(); // Ensure user is logged in

    // Retrieve and sanitize form data
    $serial_number = e($_POST['serial_number']);
    $action = e($_POST['action']);

    // Process the scanning action
    $_SESSION['message'] = scanSerial($serial_number, $action);

    // Redirect to the same page to display the result
    header('Location: inde.php');
    exit();
}
?>
