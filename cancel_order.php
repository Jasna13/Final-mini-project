<?php
// Start the session and check if the user is logged in
session_start();
if (!isset($_SESSION['uid'])) {
    header("Location: http://localhost/Project/medicare/medicare-main/Login/login.php"); // Redirect to login if not logged in
    exit();
}

// Get the order ID from the POST request
if (isset($_POST['oid'])) {
    $oid = $_POST['oid'];

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "medico_shop";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update the order status to 'Cancelled'
    $sql = "UPDATE orders SET status = 'Cancelled' WHERE oid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $oid);

    if ($stmt->execute()) {
        echo "<script>alert('Order has been cancelled.'); window.location.href = 'profile.php';</script>";
    } else {
        echo "<script>alert('Error cancelling order.'); window.location.href = 'profile.php';</script>";
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
} else {
    echo "<script>alert('Invalid order.'); window.location.href = 'profile.php';</script>";
}
?>