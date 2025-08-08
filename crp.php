<?php
// Include the database connection and function definitions file
include_once 'crepo.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Company Cars Report</title>
    <!-- Your CSS styling -->
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
        <h2>In and Out Company Cars Report</h2>
        <table>
            <tr>
                <th>Id</th>
                <th>Registration Number</th>
                <th>Driver</th>
                <th>Department</th>
                <th>Scanned Time</th>
                <th>Action</th>
            </tr>
            <?php
            // Check if $db is set and not null
            if (isset($db) && $db) {
                // Fetch data from the database
                $sql = "SELECT ls.id, ls.registration_number, 1.driver, l.department, ls.scanned_at, ls.action 
                        FROM transactions ls 
                        LEFT JOIN cars l ON ls.registration_number = l.registration_number";
                $result = $db->query($sql);

                if ($result) {
                    if ($result->num_rows > 0) {
                        // Output data of each row
                        while($row = $result->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($row["id"]) . '</td>';
                            echo '<td>' . htmlspecialchars($row["registration_number"]) . '</td>';
                            echo '<td>' . htmlspecialchars($row["driver"]) . '</td>';
                            echo '<td>' . htmlspecialchars($row["department"]) . '</td>';
                            echo '<td>' . htmlspecialchars($row["scanned_at"]) . '</td>';
                            echo '<td>' . htmlspecialchars($row["action"]) . '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo "<tr><td colspan='6'>No scanned company cars found.</td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>Query error: " . $db->error . "</td></tr>";
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

        
