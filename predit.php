<?php
// Include the priva.php file for session management and database connection
include 'pv.php';

// Check if the ID parameter is provided in the URL
if (!isset($_GET['id'])) {
    // Redirect back to the report page if ID is not provided
    header("Location: pvreport.php");
    exit();
}

// Get the ID from the URL
$id = $_GET['id'];

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $serial_number = $_POST['serial_number'];
    $model = $_POST['model'];
    $owner = $_POST['owner'];
    $department = $_POST['department'];

    // Update the record in the database
    $sql = "UPDATE private SET serial_number='$serial_number', model='$model', owner='$owner', department='$department' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        // Redirect to the report page after successful update
        $_SESSION['success'] = "Record updated successfully";
        header("Location: pvreport.php");
        exit();
    } else {
        // Error handling if the update fails
        $_SESSION['error'] = "Error updating record: " . $conn->error;
    }
}

// Retrieve the existing details of the laptop from the database
$sql = "SELECT * FROM private WHERE id=$id";
$result = $conn->query($sql);

// Check if the record exists
if ($result->num_rows > 0) {
    // Fetch the data
    $row = $result->fetch_assoc();
} else {
    // Redirect back to the report page if the record does not exist
    header("Location: pvreport.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Private Laptop</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
        }

        .container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 600px;
            margin: 0 auto;
        }

        h2 {
            margin-top: 0;
            margin-bottom: 20px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button[type="submit"] {
            padding: 10px 20px;
            background-color: #154360;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            display: block;
            margin: 0 auto;
        }

        button[type="submit"]:hover {
            background-color: red;
        }

        .notification {
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 5px;
        }

        .error {
            background-color:  #154360;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Private Laptop</h2>
        <?php
        if (isset($_SESSION['error'])) {
            echo '<div class="notification error">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']);
        }
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $id; ?>" method="post">
            <div class="form-group">
                <label for="serial_number">Serial Number:</label>
                <input type="text" id="serial_number" name="serial_number" value="<?php echo $row['serial_number']; ?>" required>
            </div>
            <div class="form-group">
                <label for="model">Model:</label>
                <input type="text" id="model" name="model" value="<?php echo $row['model']; ?>" required>
            </div>
            <div class="form-group">
                <label for="owner">Owner:</label>
                <input type="text" id="owner" name="owner" value="<?php echo $row['owner']; ?>" required>
            </div>
            <div class="form-group">
                <label for="department">Department:</label>
                <input type="text" id="department" name="department" value="<?php echo $row['department']; ?>" required>
            </div>
            <button type="submit">Update</button>
        </form>
    </div>
</body>
</html>
