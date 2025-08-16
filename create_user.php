<?php
include('add_user.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Laptop</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        .container h2 {
            margin-top: 0;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .form-group button {
            padding: 10px 20px;
            background-color: #154360;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .form-group button:hover {
            background-color: red;
        }
        .notification {
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 5px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
	   .button-container {
        display: flex;
        align-items: center; /* Align items vertically */
        margin-top: 10px; /* Adjust as needed */
    }

    /* Style for button */
    .button-container button,
    .button-container a {
        margin-right: 10px; /* Adjust spacing between button and link */
        padding: 10px 20px;
        background-color: #154360;
        color: white;
        text-decoration: none;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .button-container button:hover,
    .button-container a:hover {
        background-color: red;
    }
 .toggle-password {
            position: relative;
            cursor: pointer;
        }

        .toggle-password .eye {
            width: 20px;
            height: 20px;
            background-color: transparent;
            border: none;
            outline: none;
            cursor: pointer;
            position: absolute;
            top: 50%;
            right: 10px; /* Adjust as needed */
            transform: translateY(-50%);
        }

        .toggle-password .eye::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 8px;
            height: 4px;
            background-color: #333;
            border-radius: 50%;
        }

        .toggle-password .eye::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(45deg);
            width: 8px;
            height: 8px;
            border-left: 2px solid #333;
            border-bottom: 2px solid #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add new user</h2>
        <?php
        if (isset($_SESSION['success'])) {
            echo '<div class="notification success">' . $_SESSION['success'] . '</div>';
            unset($_SESSION['success']);
        }
        if (isset($_SESSION['error'])) {
            echo '<div class="notification error">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']);
        }
// Your PHP code here...

// Function to toggle password visibility
function togglePasswordVisibilityScript($inputId) {
    return <<<HTML
    <script>
    function togglePasswordVisibility(inputId) {
        var x = document.getElementById(inputId);
        var icon = document.querySelector('#' + inputId + ' + .toggle-password');

        if (x.type === "password") {
            x.type = "text";
            icon.textContent = "Hide";
        } else {
            x.type = "password";
            icon.textContent = "Show";
        }
    }
    </script>
    HTML;
}

?>

        <form action="create_user.php" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="Surname">Surname:</label>
                <input type="text" id="surname" name="surname" required>
            </div>
            <div class="form-group">
                <label for="firstname">Firstname:</label>
                <input type="text" id="firstname" name="firstname" required>
            </div>
			<div class="form-group">
                <label for="employee_no">Employee no:</label>
                <input type="number" id="employee_no" name="employee_no" required>
            </div>
			<div class="form-group">
                <label for="gender">Gender:</label>
                <select name="gender" style="width: 340px;margin: 2px;text-align: right;display:block;
                text-align:left;
                margin: 3px;height:30px;
                width:93%;
                padding:5px 10px;
                font-size:16px;
                border-radius:5px;
                border:1px solid blue;">
                    <option value=""></option>
                    <option value="male" <?php echo (isset($gender) && $gender == 'male') ? 'selected' : ''; ?>>Male</option>
                    <option value="female" <?php echo (isset($gender) && $gender == 'female') ? 'selected' : ''; ?>>Female</option>
                    <option value="other" <?php echo (isset($gender) && $gender == 'other') ? 'selected' : ''; ?>>Others</option>
                </select>
            <div class="form-group">
                <label for="Phonenumber">Phonenumber:</label>
                <input type="number" id="Phonenumber" name="Phonenumber" required>
            </div>
			<div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
		
 <div class="form-group">
        <label for="password_1">Password</label>
        <div class="toggle-password">
            <input type="password" name="password_1" id="password_1">
            <div class="eye" onclick="togglePasswordVisibility('password_1')"></div>
        </div>
    </div>

    <div class="form-group">
        <label for="confirm_password">Confirm Password</label>
        <div class="toggle-password">
            <input type="password" name="confirm_password" id="confirm_password">
            <div class="eye" onclick="togglePasswordVisibility('confirm_password')"></div>
        </div>
    </div>

            <div class="button-container">
                <button type="submit">Add User</button>
			<a href="admin.php" target="_self">Back</a>
			</div>
        </form>
    </div>
 <script>
        function togglePasswordVisibility(inputId) {
            var input = document.getElementById(inputId);
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>
</html>
