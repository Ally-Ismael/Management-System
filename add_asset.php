<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$db = new mysqli('localhost', 'root', '', 'iyaloo');
if ($db->connect_error) {
    die("Database connection failed: " . $db->connect_error);
}

function e($val) {
    global $db;
    return $db->real_escape_string(trim($val));
}

function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $asset_tag = sanitize_input($_POST['asset_tag']);
	 $product_description = sanitize_input($_POST['product_description']);
    $owner = sanitize_input($_POST['owner']);
	 $date_installed = sanitize_input($_POST['date_installed']);
    $location = sanitize_input($_POST['location']);
     $cost_centre = sanitize_input($_POST['cost_centre']);
	
    $stmt = $db->prepare("INSERT INTO assets (asset_tag, product_description, owner, date_installed, location, cost_centre) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $asset_tag, $product_description, $owner, $date_installed, $location, $cost_centre);

    if ($stmt->execute()) {
        $_SESSION['success'] = "New asset added successfully!";
    } else {
        if ($stmt->errno == 1062) { // 1062 is the error code for duplicate entry
            $_SESSION['error'] = "Error: The asset is already registered.";
        } else {
            $_SESSION['error'] = "Error: " . $stmt->error;
        }
    }

    $stmt->close();

    header("Location: create_asset.php"); 
    exit();
}

$db->close();
?>
