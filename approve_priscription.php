<?php
// approve_prescription.php
if (isset($_GET['id'])) {
    $prescription_id = $_GET['id'];

    // Database connection
    $conn = new mysqli('localhost', 'root', 'root', 'medico_shop');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update prescription status to approved
    $stmt = $conn->prepare("UPDATE prescriptions SET status = 'approved' WHERE id = ?");
    $stmt->bind_param('i', $prescription_id);
    
    if ($stmt->execute()) {
        echo "Prescription approved successfully!";
    } else {
        echo "Error approving prescription: " . $stmt->error;
    }

    // Redirect back to the prescription list
    header('Location: priscription.php');
    exit;
}
?>