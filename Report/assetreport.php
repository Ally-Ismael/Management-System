<?php
include('assetlogic.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Assets</title>
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
        <h2>Company Assets Report</h2>
        <?php
        include 'add_asset.php';

        $sql = "SELECT * FROM assets";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo '<table>';
            echo '<tr><th>Asset tag</th><th>Product description</th><th>Owner</th><th>Date installed</th><th>Location</th><th>Cost centre</th></tr>';

            while($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row["asset_tag"] . '</td>';
				 echo '<td>' . $row["product_description"] . '</td>';
                echo '<td>' . $row["owner"] . '</td>';
				  echo '<td>' . $row["date_installed"] . '</td>';
				    echo '<td>' . $row["location"] . '</td>';
                echo '<td>' . $row["cost_centre"] . '</td>';
                echo '</tr>';
            }
            
            echo '</table>';
        } else {
            echo "No assets found.";
        }
        $conn->close();
        ?>
        <div class="button">
            <button onclick="window.print()">Print Report</button>
            <a href="admin.php">Back</a>
        </div>
    </div>
</body>
</html>
