<?php
session_start();
$db = mysqli_connect('localhost', 'root', '', 'iyaloo');
if (!$db) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Define all functions

if (!function_exists('isLoggedIn')) {
    function isLoggedIn() {
        return isset($_SESSION['user']);
    }
}

if (!function_exists('checkLogin')) {
    function checkLogin() {
        if (!isLoggedIn()) {
            header('location: login.php');
            exit();
        }
    }
}

if (!function_exists('register')) {
    function register() {
        global $db, $errors;

        $username = e($_POST['username'] ?? '');
        $surname = e($_POST['surname'] ?? '');
        $firstname = e($_POST['firstname'] ?? '');
        $employee_no = e($_POST['employee_no'] ?? '');
        $gender = e($_POST['gender'] ?? '');
        $phonenumber = e($_POST['phonenumber'] ?? '');
        $email = e($_POST['email'] ?? ''); // New email field
        $password_1 = e($_POST['password_1'] ?? '');
        $confirm_password = e($_POST['confirm_password'] ?? '');

        $errors = [];

        // Validation for email
        if (empty($email)) {
            array_push($errors, "Email is required");
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($errors, "Invalid email format");
        }

        // Other validations (similar to existing code)
        if (empty($username)) { 
            array_push($errors, "Username is required"); 
        }
        if (empty($surname)) { 
            array_push($errors, "Surname is required"); 
        }
        if (empty($firstname)) { 
            array_push($errors, "Firstname name is required"); 
        }
        if (empty($employee_no)) { 
            array_push($errors, "Employee no is required"); 
        }
        if (empty($gender)) { 
            array_push($errors, "Gender is required"); 
        }
        if (empty($phonenumber)) { 
            array_push($errors, "Phonenumber is required"); 
        }
        if (empty($password_1)) { 
            array_push($errors, "Password is required"); 
        }
        if ($password_1 !== $confirm_password) {
            array_push($errors, "The two passwords do not match");
        }

        // If no errors, proceed with registration
        if (count($errors) == 0) {
            $password = password_hash($password_1, PASSWORD_BCRYPT);

            $user_type = e($_POST['user_type'] ?? 'user');
            $query = "INSERT INTO users (username, surname, firstname, employee_no, gender, user_type, phonenumber, email, password) 
                      VALUES('$username', '$surname', '$firstname', '$employee_no', '$gender', '$user_type', '$phonenumber', '$email', '$password')";
            if (mysqli_query($db, $query)) {
                $_SESSION['success'] = "You have successfully registered!";
                header('location: login.php');
                exit();
            } else {
                array_push($errors, "Registration failed: " . mysqli_error($db));
            }
        }
    }
}

if (!function_exists('getUserById')) {
    function getUserById($id) {
        global $db;
        $query = "SELECT * FROM users WHERE id=" . intval($id);
        $result = mysqli_query($db, $query);

        return mysqli_fetch_assoc($result);
    }
}

if (!function_exists('e')) {
    function e($val) {
        global $db;
        return mysqli_real_escape_string($db, trim($val));
    }
}

if (!function_exists('display_error')) {
    function display_error() {
        global $errors;

        if (count($errors) > 0) {
            echo '<div class="error">';
            foreach ($errors as $error) {
                echo htmlspecialchars($error) . '<br>';
            }
            echo '</div>';
        }
    }
}

if (!function_exists('login')) {
    function login() {
        global $db, $errors;

        $username = e($_POST['username'] ?? '');
        $password = e($_POST['password'] ?? '');

        if (empty($username)) {
            array_push($errors, "Username is required");
        }
        if (empty($password)) {
            array_push($errors, "Password is required");
        }

        // Debugging: Check if the form fields are being correctly received
        if (!empty($username) && !empty($password)) {
            echo "Username and password received: $username, $password<br>";
        }

        if (count($errors) == 0) {
            $query = "SELECT * FROM users WHERE username='$username' LIMIT 1";
            $results = mysqli_query($db, $query);

            // Debugging: Check if the query returned any results
            if (mysqli_num_rows($results) == 1) {
                echo "User found in the database.<br>";
                $logged_in_user = mysqli_fetch_assoc($results);

                // Debugging: Check if the password verification is working
                if (password_verify($password, $logged_in_user['password'])) {
                    echo "Password verified.<br>";
                    $_SESSION['user'] = $logged_in_user;
                    $_SESSION['success'] = "Welcome, You are now logged in";

                    if ($logged_in_user['user_type'] == 'admin') {
                        header('location: admin.php');
                    } else {
                        header('location: home.php');
                    }
                    exit(); // Ensure no further code is executed after the redirect
                } else {
                    array_push($errors, "Sorry, wrong username/password combination!");
                }
            } else {
                array_push($errors, "Sorry, wrong username/password combination!");
            }
        }
    }
}

if (!function_exists('isAdmin')) {
    function isAdmin() {
        return isset($_SESSION['user']) && $_SESSION['user']['user_type'] == 'admin';
    }
}

// Handle form submissions and other actions

$username = "";
$surname = "";
$firstname = "";
$employee_no = "";
$gender = "";
$phonenumber = "";
$errors = [];

if (isset($_POST['register_btn'])) {
    register();
}

if (isset($_POST['login_btn'])) {
    login();
}

if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['user']);
    header("location: login.html");
}
?>
