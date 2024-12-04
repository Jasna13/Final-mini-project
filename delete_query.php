<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "medico_shop";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    $query_id = $_GET['id'];

    // Prepare the DELETE SQL statement
    $sql = "DELETE FROM contact WHERE id = ?";

    // Prepare statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind the 'id' parameter to the statement
        $stmt->bind_param("i", $query_id);

        // Execute the statement
        if ($stmt->execute()) {
            // Successfully deleted, redirect to the contact queries page
            header("Location: contact.php");
            exit;
        } else {
            // Error occurred during deletion
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        // Error preparing statement
        echo "Error: " . $conn->error;
    }
} else {
    // If no 'id' is provided, redirect to the contact queries page
    header("Location: contact.php");
    exit;
}

// Close the database connection
$conn->close();
?>
