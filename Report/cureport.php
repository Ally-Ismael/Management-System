<?php
include('cu.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Laptops</title>
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
            .button{
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Company Laptops Report</h2>
        <?php
        // Include the database connection file
        include 'laptop.php';

        // Fetch data from the database
        $sql = "SELECT * FROM laptops";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Output table headers
            echo '<table>';
            echo '<tr><th>Serial Number</th><th>Laptop number</th><th>Model</th><th>Owner</th><th>Department</th></tr>';
            
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row["serial_number"] . '</td>';
				 echo '<td>' . $row["laptop_number"] . '</td>';
                echo '<td>' . $row["model"] . '</td>';
                echo '<td>' . $row["owner"] . '</td>';
                echo '<td>' . $row["department"] . '</td>';
                // Add an edit button
                echo '</tr>';
            }
            
            echo '</table>';
        } else {
            echo "No Company laptops found.";
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
