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
    $username = sanitize_input($_POST['username']);
    $surname = sanitize_input($_POST['surname']);
    $firstname = sanitize_input($_POST['firstname']);
    $employee_no = sanitize_input($_POST['employee_no']);
	$gender = sanitize_input($_POST['gender']);
	$phonenumber = sanitize_input($_POST['phonenumber']);
	$email = sanitize_input($_POST['email']);
	$password = sanitize_input($_POST['password']);
    
    // Prepare and bind parameters
    $stmt = $db->prepare("INSERT INTO users (username, surname, firstname, employee_no, gender, phonenumber, email, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $username, $surname, $firstname, $employee_no, $gender, $phonenumber, $email, $password);

    // Execute statement
    if ($stmt->execute()) {
        $_SESSION['success'] = "New User added successfully!";
    } else {
        if ($stmt->errno == 1062) { // 1062 is the error code for duplicate entry
            $_SESSION['error'] = "Error: The username is already registered. please enter a different username.";
        } else {
            $_SESSION['error'] = "Error: " . $stmt->error;
        }
    }

    // Close statement
    $stmt->close();

    // Redirect back to the form page
    header("Location: create_user.php"); // Changed to .php extension
    exit();
}

// Close database connection
$db->close();
?>
