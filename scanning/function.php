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

// Function to check if a laptop number is registered
function isRegisteredLaptop($laptop_number) {
    global $db;

    $laptop_number = e($laptop_number); // Sanitize input

    // Query to check if the laptop number exists in the 'laptops' table
    $query = "SELECT COUNT(*) as count FROM laptops WHERE laptop_number = '$laptop_number'";
    $result = mysqli_query($db, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $count = $row['count'];
        return ($count > 0); // Returns true if laptop number exists, false otherwise
    } else {
        return false; // Query execution failed
    }
}

// Function to get the current status of a laptop number
function getCurrentStatus($laptop_number) {
    global $db;

    $laptop_number = e($laptop_number); // Sanitize input

    // Query to get the latest action for the given laptop number
    $query = "SELECT action FROM laptop_scans WHERE laptop_number = '$laptop_number' ORDER BY id DESC LIMIT 1";
    $result = mysqli_query($db, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['action'] ?? null; // Return the last action or null if no records
    } else {
        return null; // Query execution failed
    }
}

// Function to scan laptop number and store it in the database
function scanLaptop($laptop_number, $action) {
    global $db;

    // Validate the laptop number
    if (empty($laptop_number)) {
        return "Laptop number is required";
    }

    // Check current status
    $currentStatus = getCurrentStatus($laptop_number);

    if ($action === "in") {
        if ($currentStatus === "in") {
            return "This laptop is already checked in.";
        }
    } elseif ($action === "out") {
        if ($currentStatus === "out") {
            return "This laptop is already checked out.";
        }
    }

    // Insert the scanned laptop number and action into the database
    $laptop_number = e($laptop_number); // Sanitize input
    $action = e($action); // Sanitize input
    
    $query = "INSERT INTO laptop_scans (laptop_number, action) VALUES ('$laptop_number', '$action')";
    if (mysqli_query($db, $query)) {
        return "Laptop number scanned successfully!";
    } else {
        return "Failed to scan laptop number: " . mysqli_error($db);
    }
}

// Check if user is logged in
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

// Call the scanLaptop function if the scan button is clicked
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['scan_btn'])) {
    $laptop_number = $_POST['laptop_number'];
    $action = $_POST['action'];

    // Check if the laptop number is registered
    if (isRegisteredLaptop($laptop_number)) {
        // Laptop is registered, proceed with scanning
        $_SESSION['message'] = scanLaptop($laptop_number, $action);
    } else {
        // Laptop is not registered
        $_SESSION['msg'] = "This laptop number is not registered.";
    }
    
    // Redirect to index.php after processing
    header('Location: index.php');
    exit();
}
?>
