<?php
include('functions.php');

// Handle password reset form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reset_password_btn'])) {
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate inputs
    if ($new_password !== $confirm_password) {
        $_SESSION['msg'] = "New password and confirm password do not match";
        header('location: resetpassword.php?token=' . urlencode($token));
        exit();
    }

    // Sanitize inputs
    $new_password = e($new_password);

    // Verify if token exists in the password_reset table
    $token = e($token);
    $query = "SELECT * FROM password_resets WHERE token = '$token'";
    $result = mysqli_query($db, $query);

    if (mysqli_num_rows($result) > 0) {
        // Token exists, update user's password
        $row = mysqli_fetch_assoc($result);
        $user_id = $row['user_id'];

        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $update_password_query = "UPDATE users SET password = '$hashed_password' WHERE id = $user_id";

        if (mysqli_query($db, $update_password_query)) {
            // Password updated successfully, delete token from password_reset table
            $delete_token_query = "DELETE FROM password_resets WHERE token = '$token'";
            mysqli_query($db, $delete_token_query);

            $_SESSION['msg'] = "Password reset successful";
            header('location: login.php'); // Redirect to login page
            exit();
        } else {
            $_SESSION['msg'] = "Failed to reset password";
        }
    } else {
        // Token does not exist or is invalid
        $_SESSION['msg'] = "Invalid or expired token";
    }

    // Redirect back to reset password page
    header('location: resetpassword.php?token=' . urlencode($token));
    exit();
} elseif (!isset($_GET['token'])) {
    // If no token is provided in the URL
    $_SESSION['msg'] = "Token not provided";
    header('location: forgotpassword.php');
    exit();
}
?>
