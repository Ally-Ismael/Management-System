<?php
include('car.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Company Car</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        .container h2 {
            margin-top: 0;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .form-group button {
            padding: 10px 20px;
            background-color: #154360;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .form-group button:hover {
            background-color: red;
        }
        .notification {
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 5px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
	 .button {
        display: inline-block;
        margin-top: 10px; /* Adjust as needed */
    }

    .button a {
        display: inline-block;
        padding: 10px 20px;
        background-color: #154360;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .button a:hover {
        background-color: red;
    }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add Company Car</h2>
        <?php
        if (isset($_SESSION['success'])) {
            echo '<div class="notification success">' . $_SESSION['success'] . '</div>';
            unset($_SESSION['success']);
        }
        if (isset($_SESSION['error'])) {
            echo '<div class="notification error">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']);
        }
        ?>
        <form action="addcar.php" method="post">
            <div class="form-group">
                <label for="registration_number">Registration Number:</label>
                <input type="text" id="registration_number" name="registration_number" required>
            </div>
			   <div class="form-group">
                <label for="driver">Driver Name:</label>
                <input type="text" id="driver" name="driver" required>
            </div>
			 <div class="form-group">
                <label for="department">Department:</label>
                <input type="text" id="department" name="department" required>
            </div>
            <div class="form-group">
                <label for="make">Make:</label>
                <input type="text" id="make" name="make" required>
            </div>
            <div class="form-group">
                <label for="model">Model:</label>
                <input type="text" id="model" name="model" required>
            </div>
            <div class="form-group">
                <label for="color">color:</label>
                <input type="text" id="color" name="color" required>
            </div>
			<div class="form-group">
                <label for="year">Year:</label>
                <input type="number" id="year" name="year" required>
            </div>
            <div class="form-group">
                <button type="submit">Add Company Car</button>
			</div>
			<div class ="button">
			<a href="admin.php" target="_self">Back</a>
			</div>
        </form>
    </div>
</body>
</html>
