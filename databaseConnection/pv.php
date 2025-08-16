<?php
session_start();
// Database connection parameters
$servername = "localhost";
$username = "root"; // Default MySQL username
$password = ""; // No password set
$dbname = "iyaloo"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
