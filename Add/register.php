<?php include('functions.php') ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
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
        .form-group button,
        .button-container a {
            padding: 10px 20px;
            background-color: #154360;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .form-group button:hover,
        .button-container a:hover {
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
            align-items: center;
            margin-top: 10px;
        }
        .button-container button,
        .button-container a {
            margin-right: 10px;
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
            right: 10px;
            transform: translateY(-50%);
        }
        .toggle-password .eye::before {
            content: '👁';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <?php
        if (isset($_SESSION['success'])) {
            echo '<div class="notification success">' . $_SESSION['success'] . '</div>';
            unset($_SESSION['success']);
        }
        if (isset($_SESSION['error'])) {
            echo '<div class="notification error">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']);
        }
        ?>
		
        <form action="register.php" method="post">
    <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
    </div>
    <div class="form-group">
        <label for="surname">Surname:</label>
        <input type="text" id="surname" name="surname" required>
    </div>
    <div class="form-group">
        <label for="firstname">Firstname:</label>
        <input type="text" id="firstname" name="firstname" required>
    </div>
    <div class="form-group">
        <label for="employee_no">Employee No:</label>
        <input type="number" id="employee_no" name="employee_no" required>
    </div>
    <div class="form-group">
        <label for="gender">Gender:</label>
        <select name="gender" id="gender" required>
            <option value=""></option>
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="other">Other</option>
        </select>
    </div>
    <div class="form-group">
        <label for="phonenumber">Phone Number:</label>
        <input type="number" id="phonenumber" name="phonenumber" required>
    </div>
	<div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
    </div>
    <div class="form-group">
        <label for="password_1">Password</label>
        <div class="toggle-password">
            <input type="password" name="password_1" id="password_1" required>
            <div class="eye" onclick="togglePasswordVisibility('password_1')"></div>
        </div>
    </div>
    <div class="form-group">
        <label for="confirm_password">Confirm Password</label>
        <div class="toggle-password">
            <input type="password" name="confirm_password" id="confirm_password" required>
            <div class="eye" onclick="togglePasswordVisibility('confirm_password')"></div>
        </div>
    </div>
    <div class="button-container">
        <button type="submit" class="btn" name="register_btn">Register</button>
        <a href="homep.php" class="btn">Back</a>
    </div>
</form>

    </div>
    <script>
        function togglePasswordVisibility(inputId) {
            var input = document.getElementById(inputId);
            var eye = input.nextElementSibling;
            if (input.type === 'password') {
                input.type = 'text';
                eye.textContent = '🙈';
            } else {
                input.type = 'password';
                eye.textContent = '👁';
            }
        }
    </script>
</body>
</html>
