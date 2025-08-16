<?php
include('us.php');
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Reports</title>
    <style>
           body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 20px;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-top: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        .button {
            margin-top: 10px;
        }

        .button button, .button a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #154360;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
        }

        .button button:hover, .button a:hover {
            background-color: red;
        }
		  @media print {
            .button, {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>User Reports</h2>
              <?php
            // Include the database connection file
            include 'function.php';
  // Fetch data from the database
        $sql = "SELECT * FROM users";
        $result = $conn->query($sql);

            // Output table headers
            echo '<table>';
            echo '<tr><th>Id</th><th>Username</th><th>Surname</th><th>Firstname</th><th>Employee Number</th><th>Gender</th><th>User Type</th><th>phonenumber</th><th>Email</th><th>Reg. Date</th></tr>';
            if ($result->num_rows > 0) {
                // Output data of each row
                while($row = $result->fetch_assoc()) {
					echo '<td>' . $row["id"] . '</td>';
                    echo '<td>' . $row["username"] . '</td>';
					echo '<td>' . $row["surname"] . '</td>';
				    echo  '<td>' . $row["firstname"] . '</td>';
					 echo  '<td>' . $row["employee_no"] . '</td>';
					 echo '<td>' . $row["gender"] . '</td>';
                     echo '<td>' . $row["user_type"] . '</td>';
					echo '<td>' . $row["phonenumber"] . '</td>';
					echo '<td>' . $row["email"] . '</td>';
					echo '<td>' . $row["reg_date"] . '</td>';
                    echo '</tr>';
             }
            
            echo '</table>';
        } else {
            echo "No users found.";
        }
        $conn->close();
        ?>
        <div class="button">
            <!-- Add a print button -->
            <button onclick="window.print()">Print Report</button>
            <a href="admin.php">Back</a>
        </div>
    </div>
</body>
</html>