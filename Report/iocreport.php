<?php
// Include the database connection and function definitions file
include_once 'function.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Company Laptop Report</title>
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
            .button {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Company Laptops Report</h2>
        <table>
            <tr>
                <th>Id</th>
                <th>Laptop Number</th>
                <th>Owner</th>
                <th>Department</th>
                <th>Scanned time</th>
                <th>Action</th>
            </tr>
            <?php
            // Check if $db is set and not null
            if ($db) {
                // Fetch data from the database with owner's name, laptop number and department
                $sql = "SELECT ls.id, ls.laptop_number, l.owner, l.department, ls.scanned_at, ls.action 
                        FROM laptop_scans ls 
                        LEFT JOIN laptops l ON ls.laptop_number = l.laptop_number";
                $result = $db->query($sql);

                if ($result && $result->num_rows > 0) {
                    // Output data of each row
                    while($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . $row["id"] . '</td>';
                        echo '<td>' . $row["laptop_number"] . '</td>';
                        echo '<td>' . $row["owner"] . '</td>';
                        echo '<td>' . $row["department"] . '</td>';
                        echo '<td>' . $row["scanned_at"] . '</td>';
                        echo '<td>' . $row["action"] . '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo "<tr><td colspan='6'>No scanned Company laptops found.</td></tr>";
                }
            } else {
                echo "<tr><td colspan='6'>Database connection error.</td></tr>";
            }
            ?>
        </table>
        <div class="button">
            <!-- Add a print button -->
            <button onclick="window.print()">Print Report</button>
            <a href="admin.php">Back</a>
        </div>
    </div>
</body>
</html>
