<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Asset Status</title>
</head>
<body>

<h1>Update Asset Status</h1>

<form action="update_asset_logic.php" method="POST">
    Asset ID: <input type="number" name="id" required><br>
    New Status: <input type="text" name="status" required><br>
    <input type="submit" value="Update Status">
</form>

</body>
</html>
