<?php
include('functions.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['forgot_password_btn'])) {
    $email = $_POST['email'];

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['msg'] = "Invalid email format";
        header('location: forgotpassword.php');
        exit();
    }

    // Check if email exists in the database
    $email = e($email);
    $query = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($db, $query);

    if (mysqli_num_rows($result) > 0) {
        // Email exists, generate password reset token and insert into database
        $user = mysqli_fetch_assoc($result);
        $user_id = $user['id'];

        // Generate a random token
        $token = bin2hex(random_bytes(50));

        // Insert token into database
        $insert_token_query = "INSERT INTO password_resets (user_id, token) VALUES ($user_id, '$token')";
        mysqli_query($db, $insert_token_query);

        // Send password reset link to user's email
        $reset_link = "http://localhost/laptop/forgotpassword.php?token=$token"; // Replace with your actual domain
        $to = $email;
        $subject = "Password Reset";
        $message = "Hello,\n\nYou have requested to reset your password. Click the link below to reset your password:\n\n$reset_link\n\nIf you did not request this, please ignore this email.";
        $headers = "From: shihepov@namwater.com.na"; // Replace with your email address

        // Send email
        if (mail($to, $subject, $message, $headers)) {
            $_SESSION['msg'] = "Password reset link sent to your email";
        } else {
            $_SESSION['msg'] = "Failed to send password reset link. Please try again later.";
        }
    } else {
        // Email does not exist
        $_SESSION['msg'] = "No account found with that email address";
    }

    // Redirect back to forgot password page
    header('location: forgotpassword.php');
    exit();
}
?>
