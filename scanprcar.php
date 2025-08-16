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

// Function to check if a registration number is registered
function isRegisteredLaptop($registration_number) {
    global $db;

    $registration_number = e($registration_number); // Sanitize input

    // Query to check if the registration_number exists in the 'pcars' table
    $query = "SELECT COUNT(*) as count FROM pcars WHERE registration_number = '$registration_number'";
    $result = mysqli_query($db, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return ($row['count'] > 0); // Returns true if registration_number exists, false otherwise
    } else {
        return false; // Query execution failed
    }
}

// Function to get the current status of a registration number
function getCurrentStatus($registration_number) {
    global $db;

    $registration_number = e($registration_number); // Sanitize input

    // Query to get the latest action for the given registration_number
    $query = "SELECT action FROM ptransactions WHERE registration_number = '$registration_number' ORDER BY scanned_at DESC LIMIT 1";
    $result = mysqli_query($db, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['action'] ?? null; // Return the last action or null if no records
    } else {
        return null; // Query execution failed
    }
}

// Function to scan registration_number and store it in the database
function scanLaptop($registration_number, $action) {
    global $db;

    // Validate the registration_number
    if (empty($registration_number)) {
        return "Registration number is required";
    }

    // Check current status
    $currentStatus = getCurrentStatus($registration_number);

    // Handle the scanning logic based on the current status
    if ($action === "in") {
        if ($currentStatus === "in") {
            return "This car is already in the lot.";
        }
    } elseif ($action === "out") {
        if ($currentStatus === "out") {
            return "This car is already out of the lot.";
        }
    } else {
        return "Invalid action. Use 'in' or 'out'.";
    }

    // Insert the scanned registration_number and action into the database
    $registration_number = e($registration_number); // Sanitize input
    $action = e($action); // Sanitize input
    
    $query = "INSERT INTO ptransactions (registration_number, action, scanned_at) VALUES ('$registration_number', '$action', NOW())";
    if (mysqli_query($db, $query)) {
        return "Registration number scanned successfully!";
    } else {
        return "Failed to scan registration number: " . mysqli_error($db);
    }
}

// Function to check if the user is logged in
function isLoggedIn() {
    return isset($_SESSION['user']);
}

// Check if user is logged in, redirect to login page if not
if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('Location: login.php');
    exit();
}

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['scan_btn'])) {
    $registration_number = $_POST['registration_number'];
    $action = $_POST['action'];

    // Check if the registration_number is registered
    if (isRegisteredLaptop($registration_number)) {
        // Registration_number is registered, proceed with scanning
        $_SESSION['message'] = scanLaptop($registration_number, $action);
    } else {
        // Registration number is not registered
        $_SESSION['msg'] = "This registration number is not registered.";
    }
    
    // Redirect to the same page after processing
    header('Location: pricr.php');
    exit();
}
?>
