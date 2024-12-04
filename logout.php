<?php
session_start(); // Start the session
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session

// Redirect to the login page with a logout status
header("Location: http://localhost/Project/medicare/medicare-main/Login/login.php?logout=1");
exit();
?>
