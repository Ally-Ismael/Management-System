<?php
session_start();
include_once 'car.php';

header("Content-Type: application/json");

// Scan a car
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $license_plate = $data['license_plate'];
    $scan_type = $data['scan_type'];
    $location = isset($data['location']) ? mysqli_real_escape_string($conn, $data['location']) : '';

    // Fetch car_id from cars table based on license plate
    $sql_car_id = "SELECT car_id FROM cars WHERE license_plate = '$license_plate'";
    $result_car_id = $conn->query($sql_car_id);

    if ($result_car_id->num_rows > 0) {
        $row = $result_car_id->fetch_assoc();
        $car_id = $row['car_id'];

        // Insert scan record into transactions table
        $sql_insert = "INSERT INTO transactions (car_id, scan_type, location)
                       VALUES ($car_id, '$scan_type', '$location')";

        if ($conn->query($sql_insert) === TRUE) {
            $response = array('success' => true, 'message' => 'Car scanned successfully');
        } else {
            $response = array('success' => false, 'message' => 'Error scanning car: ' . $conn->error);
        }
    } else {
        $response = array('success' => false, 'message' => 'Car not found');
    }

    echo json_encode($response);
    exit; // Ensure script stops after sending response
}

// Retrieve all transactions
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Call getAllTransactions function from car.php
    $transactions = getAllTransactions($conn);

    echo json_encode($transactions);
    exit; // Ensure script stops after sending response
}

$conn->close(); // Close database connection
?>
