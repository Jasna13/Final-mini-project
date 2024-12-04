<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Medico</title>
    <link rel="stylesheet" href="login.css">
</head>
<script>
        // Function to show alert if logged out
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('logout')) {
                alert('You have been logged out successfully.');
            }
        };
    </script>
<body>
    <div class="login-container">
        <h2>Login to Your Account</h2>
        <form method="post">
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="login-button" name="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="signup.php">Sign up here</a></p>
    </div>
</body>
</html>
<?php
session_start(); // Start the session
include("connection.php");
//Check if there is a message in the URL query string
if (isset($_GET['message']) && $_GET['message'] == 'logout_successful') {
    echo "<script>alert('Logout successful');</script>";
}

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the email exists and the password matches
    $sql = "SELECT * FROM user WHERE email='$email' AND password='$password'";
    $result = mysqli_query($con, $sql);
    $num = mysqli_num_rows($result);

    if ($num > 0) {
        $row = mysqli_fetch_assoc($result);  // Fetch user data
        $utype = $row['utype'];  // Get the user type
        $uid = $row['uid']; // Get the user ID
        $_SESSION['uid'] = $uid;
        if ($utype == 'admin') {
            error_log("Redirecting to admin dashboard");
            $_SESSION['admin_logged_in'] = true;
            header("Location: http://localhost/Project/medicare/medicare-main/Dashboard/index2.php");
            exit();
        }
        elseif ($utype == 'User') {
            error_log("Redirecting to user dashboard");
            header("Location: http://localhost/Project/medicare/medicare-main/userdashboard/index1.php");
            exit();
        } 
        elseif ($utype == 'staff') {
            error_log("Redirecting to staff dashboard");
            header("Location: http://localhost/MINI%20PROJECT/StaffDashboard/staffindex.html");
            exit();
        }
    } else {
        error_log("Login failed: incorrect email or password");
        echo '<script>alert("Email ID or password is incorrect")</script>';
    }
}
?>
