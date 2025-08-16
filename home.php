<?php
include('functions.php');
if (!isLoggedIn()) {
	$_SESSION['msg'] = "You must log in first";
	header('location: login.php');
}


?>
<!DOCTYPE html>
<html>
<head>
    <title>User page</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }

        .container {
            width: 80%;
            max-width: 600px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            position: relative;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .image-container {
            position: relative;
        }

        .top-left {
            position: absolute;
            top: 10px;
            left: 0px;
            width: 400px; /* Adjust the width as needed */
            height: auto;
            border: 1px solid #ddd; /* Adds a border around the image */
            border-radius: 5px; /* Rounds the corners of the image */
        }
        .time-calendar {
            text-align: right;
            margin-bottom: 20px;
        }

        .links {
            text-align: left;
            margin: 20px 0;
        }

        .links a {
            background-color: #154360;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            display: inline-block;
            margin: 10px 5px;
            border-radius: 5px;
        }

        .links a:hover {
            background-color: red;
        }

        .content {
            text-align: center;
            margin-top: 20px;
        }

        .error.success {
            color: #154360;
        }
        .nam{
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
       <div class="nam">
        <h2>Laptop Gate Scan</h2>
       </div>
        <div class="image-container">
            <img src="logo.png" alt="Logo" class="top-left">
        </div><br><br>

        <div class="time-calendar">
            <p id="time"></p>
            <p id="date"></p>
        </div>

        <script>
            function updateTimeAndDate() {
                const now = new Date();
                const time = now.toLocaleTimeString();
                const date = now.toLocaleDateString();
                
                document.getElementById('time').textContent = time;
                document.getElementById('date').textContent = date;
            }

            // Update time and date every second
            setInterval(updateTimeAndDate, 1000);
            // Initialize on page load
            updateTimeAndDate();
        </script>
        <br><br>
        <div class="links">
		<a href="index.php" target="_self">Scan Company Laptop</a><br><br>
		<a href="inde.php" target="_self">Scan Private Laptop</a><br><br>
		<a href="pricr.php" target="_self">Scan Private Car</a><br><br>
		 <a href="scancar.php" target="_self">Scan Company Car</a><br><br>
		 <a href="private.php" target="_self">Add Private Laptop</a><br><br>
		 <a href="addpcar.php" target="_self">Add private Car</a><br><br>
            <a href="homep.php?logout='1'">Logout</a><br>
        </div>
        <div class="content">
            <!-- notification message -->
            <?php if (isset($_SESSION['success'])) : ?>
                <div class="error success">
                    <h3>
                        <?php 
                            echo htmlspecialchars($_SESSION['success']); 
                            unset($_SESSION['success']);
                        ?>
                    </h3>
                </div>
            <?php endif ?>

            <!-- logged in user information -->
            <div class="small">
                <?php if (isset($_SESSION['user'])) : ?>
                    <strong><?php echo htmlspecialchars($_SESSION['user']['username']); ?></strong>
                    <i style="color: #154360;">(<?php echo htmlspecialchars(ucfirst($_SESSION['user']['user_type'])); ?>)</i> 
                    <br>
                <?php endif ?>
            </div>
        </div>
    </div>
</body>
</html>
