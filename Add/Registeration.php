<?php include('functions.php') ?>
<!DOCTYPE html>
<html>
<head>
	<title>Registration form</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="stylesheet" href="style.css">
</head>
<body>
    <form method="post" action="Registeration.php">
        <?php echo display_error(); ?>
        <div class="header">
            <h2>Register</h2>
        </div>
        <div class="input-group">
            <label>Username</label>
            <input type="text" name="username" value="<?php echo $username; ?>">
        </div>
        <div class="input-group">
            <label>Surname</label>
            <input type="text" name="surname" value="<?php echo $surname; ?>">
        </div>
        <div class="input-group">
            <label>Firstname</label>
            <input type="text" name="firstname" value="<?php echo $firstname; ?>">
        </div>
        <div class="input-group">
            <label>Employee no</label>
            <input type="number" name="employee_no" value="<?php echo $employee_no; ?>">
        </div>
        <div class="input-group">
            <label>Email</label>
            <input type="email" name="email" value="<?php echo $email; ?>">
        </div>
        <div class="input-group">
            <label>Gender</label>
            <select name="gender" style="width: 340px;margin: 2px;text-align: right;display:block;
            text-align:left;
            margin: 3px;height:30px;
            width:93%;
            padding:5px 10px;
            font-size:16px;
            border-radius:5px;
            border:1px solid blue;">
                <option value="">Select Gender</option>
                <option value="male" <?php echo ($gender == 'male') ? 'selected' : ''; ?>>Male</option>
                <option value="female" <?php echo ($gender == 'female') ? 'selected' : ''; ?>>Female</option>
                <option value="other" <?php echo ($gender == 'other') ? 'selected' : ''; ?>>Other</option>
            </select>
        </div>
        <div class="input-group">
            <label>Phonenumber</label>
            <input type="number" name="phonenumber" value="<?php echo $phonenumber; ?>">
        </div>  
        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password_1">
        </div>
        <div class="input-group">
            <label>Confirm password</label>
            <input type="password" name="confirm_password">
        </div>
        <button type="submit" class="btn" name="register_btn">Register</button>
        <button type="reset" class="btn" name="reset_btn">Reset</button>
        <p>
            Already registered? <a href="login.php" style="color: red;">Sign in</a>
        </p>
    </form>
</body>
</html>
