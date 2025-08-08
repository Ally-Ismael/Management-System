<?php
// Include database connection file
include('funct.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Scan Laptop Number</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f0;
            margin: 0;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
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
        <h2>Company Assets</h2>
        <?php if (isset($_SESSION['message'])) : ?>
            <div class="notification success">
                <?php
                    echo $_SESSION['message'];
                    unset($_SESSION['message']);
                ?>
            </div>
        <?php endif ?>
        <?php if (isset($_SESSION['msg'])) : ?>
            <div class="notification error">
                <?php
                    echo $_SESSION['msg'];
                    unset($_SESSION['msg']);
                ?>
            </div>
        <?php endif ?>
        <form method="post" action="scaninout.php">
              <div class="form-group">
                <label for="asset_tag">Asset Tag:</label>
                <input type="text" id="asset_tag" name="asset_tag" required>
            </div>
			 <div class="form-group">
                <label for="product_description">Product Description:</label>
                <input type="text" id="product_description" name="product_description" required>
            </div>
            <div class="form-group">
                <label for="status">Status:</label>
                <select id="status" name="status" required>
                    <option value="in">Scan In</option>
                    <option value="out">Scan Out</option>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" name="scan_btn">Scan</button>
            </div>
            <div class ="button">
                <a href="admin.php" target="_self">Back</a>
            </div>
        </form>
    </div>
</body>
</html>
