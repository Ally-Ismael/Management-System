<?php
include('db.php');

if (isset($_GET['asset_tag'])) {
    $asset_tag = $_GET['asset_tag'];

    $sql = "SELECT * FROM assets WHERE asset_tag='$asset_tag'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $asset = $result->fetch_assoc();
        echo "<h1>Asset Details</h1>";
        echo "<p>ID: {$asset['id']}</p>";
        echo "<p>Asset Tag: {$asset['asset_tag']}</p>";
        echo "<p>Owner: {$asset['owner']}</p>";
        echo "<p>Product Description: {$asset['product_description']}</p>";
        echo "<p>Status: {$asset['status']}</p>";
        echo "<p>Location: {$asset['location']}</p>";
        echo "<p>Cost Centre: {$asset['cost_centre']}</p>";
    } else {
        echo "<p>Asset not found.</p>";
    }

    $conn->close();
}
?>
