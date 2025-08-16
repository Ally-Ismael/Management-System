<?php
// Include database connection file
include('functions.php');

// Handle password change form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password_btn'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate inputs
    if ($new_password !== $confirm_password) {
        $_SESSION['msg'] = "New password and confirm password do not match";
        header('location: changepassword.php');
        exit();
    }

    // Sanitize inputs
    $current_password = e($current_password);
    $new_password = e($new_password);

    // Verify current password
    $user_id = $_SESSION['user']['id'];
    $query = "SELECT password FROM users WHERE id = $user_id";
    $result = mysqli_query($db, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $stored_password = $row['password'];

        if (password_verify($current_password, $stored_password)) {
            // Current password matches, update the password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_query = "UPDATE users SET password = '$hashed_password' WHERE id = $user_id";

            if (mysqli_query($db, $update_query)) {
                $_SESSION['msg'] = "Password changed successfully";
            } else {
                $_SESSION['msg'] = "Failed to change password";
            }
        } else {
            $_SESSION['msg'] = "Incorrect current password";
        }
    } else {
        $_SESSION['msg'] = "Failed to verify current password";
    }

    // Redirect back to change password page
    header('location: changepassword.php');
    exit();
}
?>
