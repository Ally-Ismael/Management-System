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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $serial_number = sanitize_input($_POST['serial_number']);
	 $laptop_number = sanitize_input($_POST['laptop_number']);
    $model = sanitize_input($_POST['model']);
    $owner = sanitize_input($_POST['owner']);
    $department = sanitize_input($_POST['department']);
    
    // Prepare and bind parameters
    $stmt = $db->prepare("INSERT INTO laptops (serial_number, laptop_number, model, owner, department) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $serial_number, $laptop_number, $model, $owner, $department);

    // Execute statement
    if ($stmt->execute()) {
        $_SESSION['success'] = "New laptop added successfully!";
    } else {
        if ($stmt->errno == 1062) { // 1062 is the error code for duplicate entry
            $_SESSION['error'] = "Error: The laptop number is already registered. This laptop number is not for your company.";
        } else {
            $_SESSION['error'] = "Error: " . $stmt->error;
        }
    }

    // Close statement
    $stmt->close();

    // Redirect back to the form page
    header("Location: laptops.php"); // Changed to .php extension
    exit();
}

// Close database connection
$db->close();
?>
