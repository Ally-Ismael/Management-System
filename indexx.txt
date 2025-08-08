<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laptop Scan System</title>
</head>
<body>
    <h2>Laptop Scan System</h2>
    <?php
    if (isset($_SESSION['success'])) {
        echo '<div style="color: green;">'.$_SESSION['success'].'</div>';
        unset($_SESSION['success']);
    }
    if (isset($_SESSION['error'])) {
        echo '<div style="color: red;">'.$_SESSION['error'].'</div>';
        unset($_SESSION['error']);
    }
    ?>
    <form action="index.php" method="POST">
        <label for="serial_number">Laptop Serial Number:</label><br>
        <input type="text" id="serial_number" name="serial_number" required><br><br>
        <input type="radio" id="scan_in" name="scan_type" value="scan-in" checked>
        <label for="scan_in">Scan In</label><br>
        <input type="radio" id="scan_out" name="scan_type" value="scan-out">
        <label for="scan_out">Scan Out</label><br><br>
        <input type="submit" name="scan_btn" value="Scan">
    </form>
</body>
</html>
