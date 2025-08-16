<?php
include('db.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $date_installed = $_POST['date_installed'];

    $sql = "UPDATE assets SET date installed='$date_installed' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "Asset date installation updated successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
