<?php
include_once 'funct.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Assets Report</title>
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
        <h2>Assets Report</h2>
        <table>
            <tr>
                <th>ID</th>
        <th>Asset Tag</th>
        <th>Owner</th>
        <th>Product Description</th>
        <th>Status</th>
        <th>Location</th>
        <th>Cost Centre</th>
		<th>scanned_at</th>
            </tr>
            <?php
            
            if ($db) {
               
                $sql = "SELECT ls.id, ls.asset_tag, 1s.product_description, ls.owner, 1s.status, 1s.location, ls.cost_centre, ls.scanned_at 
                        FROM asset_scans ls 
                        LEFT JOIN assets l ON ls.asset_tag = l.asset_tag";
                $result = $db->query($sql);

                if ($result && $result->num_rows > 0) {
                 
                    while($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . $row["id"] . '</td>';
                        echo '<td>' . $row["asset_tag"] . '</td>';
						echo '<td>' . $row["product_description"] . '</td>';
                        echo '<td>' . $row["owner"] . '</td>';
						echo '<td>' . $row["status"] . '</td>';
						echo '<td>' . $row["location"] . '</td>';
                        echo '<td>' . $row["cost_centre"] . '</td>';
                        echo '<td>' . $row["scanned_at"] . '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo "<tr><td colspan='6'>No scanned assets found.</td></tr>";
                }
            } else {
                echo "<tr><td colspan='6'>Database connection error.</td></tr>";
            }
            ?>
        </table>
        <div class="button">
            <button onclick="window.print()">Print Report</button>
            <a href="admin.php">Back</a>
        </div>
    </div>
</body>
</html>
