<?php
include('functions.php');
if (!isAdmin()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
}

if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['user']);
    header("location: login.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="style.css">
    <style>
        a:link, a:visited {
            background-color: #154360;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
        }

        a:hover, a:active {
            background-color: #e74c3c;
        }
        
        ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }
        
        li a, .dropbtn {
            display: inline-block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        li a:hover, .dropdown:hover .dropbtn {
            background-color: #e74c3c;
        }

        li.dropdown {
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #154360;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: white;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            text-align: left;
        }

        .dropdown-content a:hover {
            background-color: #e74c3c;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

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
            width: 400px;
            height: auto;
            border: 1px solid #ddd;
            border-radius: 5px;
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
            background-color: #e74c3c;
        }

        .content {
            text-align: center;
            margin-top: 20px;
        }

        .error.success {
            color: #154360;
        }

        .nam {
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
          <img src="logo.png" alt="Company Logo" class="top-left">
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

        setInterval(updateTimeAndDate, 1000);
        updateTimeAndDate();
    </script><br><br>
    <ul>
      <li class="dropdown">
        <a href="javascript:void(0)" class="dropbtn">Reports</a>
        <div class="dropdown-content">
          <a href="assetreport.php">Assets Report</a>
          <a href="userreport.php">Users Report</a>
          <a href="cureport.php">Company Laptop Report</a>
          <a href="pvreport.php">Private Laptop Report</a>
          <a href="corep.php">Company Car Report</a>
          <a href="prepo.php">Private Car Report</a>
          <a href="iocreport.php">In/Out company laptop Report</a>
          <a href="iopreport.php">In/Out private laptop Report</a>
          <a href="crp.php">In/Out company Car Report</a>
          <a href="prep.php">In/Out private Car Report</a>
        </div>
      </li>
    </ul><br><br><br>
    <a href="create_asset.php" target="_self">Add Asset</a><br><br>
    <a href="create_user.php" target="_self">Add user</a><br><br>
    <a href="laptops.php" target="_self">Add Company Laptop</a><br><br>
    <a href="addcar.php" target="_self">Add Company Car</a><br><br>
    <a href="homep.php?logout='1'">Logout</a>
    <div class="content">
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
