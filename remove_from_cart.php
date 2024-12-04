<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "medico_shop");

// Check if the user is logged in
if (!isset($_SESSION['uid'])) {
    header("Location: login.php");
    exit();
}

// Get the cart ID from the POST request
if (isset($_POST['cart_id'])) {
    $cart_id = $_POST['cart_id'];

    // Prepare and execute the deletion query
    $query = "DELETE FROM cart WHERE cid = ? AND uid = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ii", $cart_id, $_SESSION['uid']);

    if ($stmt->execute()) {
        // Redirect to cart page with a success message
        header("Location: Add_to_cart.php?message=Product removed successfully.");
    } else {
        // Redirect to cart page with an error message
        header("Location: Add_to_cart.php?message=Failed to remove product.");
    }
} else {
    // Redirect to cart page if no cart ID is set
    header("Location: cart.php");
}

$stmt->close();
$con->close();
?>
