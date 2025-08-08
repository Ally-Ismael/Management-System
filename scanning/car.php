<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database connection
$db = new mysqli('localhost', 'root', '', 'iyaloo');
if ($db->connect_error) {
    die("Database connection failed: " . $db->connect_error);
}

// Function to escape input data
function e($val) {
    global $db;
    return $db->real_escape_string(trim($val));
}

// Function to sanitize input data
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Function to get all transactions
function getAllTransactions($conn) {
    $transactions = [];
    $sql = "SELECT * FROM transactions ORDER BY transaction_id DESC";
    $result = $conn->query($sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $transactions[] = $row;
        }
    }
    return $transactions;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $registration_number = sanitize_input($_POST['registration_number']);
    $department = sanitize_input($_POST['department']);
    $make = sanitize_input($_POST['make']);
    $model = sanitize_input($_POST['model']);
    $color = sanitize_input($_POST['color']);
    $year = sanitize_input($_POST['year']);
    
    // Prepare and bind parameters
    $stmt = $db->prepare("INSERT INTO cars (registration_number, department, make, model, color, year) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $registration_number, $department, $make, $model, $color, $year);

    // Execute statement
    if ($stmt->execute()) {
        $_SESSION['success'] = "Company car added successfully!";
    } else {
        if ($stmt->errno == 1062) { // 1062 is the error code for duplicate entry
            $_SESSION['error'] = "Error: The registration number of this car is already registered with another car.";
        } else {
            $_SESSION['error'] = "Error: " . $stmt->error;
        }
    }

    // Close statement
    $stmt->close();

    // Redirect back to the form page
    header("Location: addcar.php"); // Ensure this page exists
    exit();
}

// Close database connection
$db->close();
?>
