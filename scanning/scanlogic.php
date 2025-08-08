<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection file
$db = mysqli_connect('localhost', 'root', '', 'iyaloo');
if (!$db) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Function to escape input data
function e($val) {
    global $db;
    return mysqli_real_escape_string($db, trim($val));
}

// Function to check if a plate number is registered
function isRegisteredLaptop($registration_number) {
    global $db;

    $registration_number = e($registration_number); // Sanitize input

    // Query to check if the plate number exists in the 'cars' table
    $query = "SELECT COUNT(*) as count FROM cars WHERE registration_number = '$registration_number'";
    $result = mysqli_query($db, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $count = $row['count'];
        return ($count > 0); // Returns true if plate number exists, false otherwise
    } else {
        return false; // Query execution failed
    }
}

// Function to get the current status of a plate number
function getCurrentStatus($registration_number) {
    global $db;

    $registration_number = e($registration_number); // Sanitize input

    // Query to get the latest action for the given plate number
    $query = "SELECT action FROM transactions WHERE registration_number = '$registration_number' ORDER BY id DESC LIMIT 1";
    $result = mysqli_query($db, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['action'] ?? null; // Return the last action or null if no records
    } else {
        return null; // Query execution failed
    }
}

// Function to scan plate number and store it in the database
function scanLaptop($registration_number, $action) {
    global $db;

    // Validate the plate number
    if (empty($registration_number)) {
        return "Registration number is required";
    }

    // Check current status
    $currentStatus = getCurrentStatus($registration_number);

    if ($action === "in") {
        if ($currentStatus === "in") {
            return "This car is already in the lot.";
        }
    } elseif ($action === "out") {
        if ($currentStatus === "out") {
            return "This car is already out of the lot.";
        }
    }

    // Insert the scanned plate number and action into the database
    $registration_number = e($registration_number); // Sanitize input
    $action = e($action); // Sanitize input
    
    $query = "INSERT INTO transactions (registration_number, action) VALUES ('$registration_number', '$action')";
    if (mysqli_query($db, $query)) {
        return "Registration number scanned successfully!";
    } else {
        return "Failed to scan registration number: " . mysqli_error($db);
    }
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user']);
}

// Check if session is started, start if not
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in, redirect to login page if not
if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
    exit();
}

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['scan_btn'])) {
    $registration_number = $_POST['registration_number'];
    $action = $_POST['action'];

    // Check if the plate number is registered
    if (isRegisteredLaptop($registration_number)) {
        // Registration number is registered, proceed with scanning
        $_SESSION['message'] = scanLaptop($registration_number, $action);
    } else {
        // Registration number is not registered
        $_SESSION['msg'] = "This registration number is not registered.";
    }
    
    // Redirect to scancar.php after processing
    header('Location: scancar.php');
    exit();
}
?>
