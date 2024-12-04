<?php
// reject_prescription.php
if (isset($_GET['id'])) {
    $prescription_id = $_GET['id'];

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'medico_shop');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update prescription status to rejected
    $stmt = $conn->prepare("UPDATE prescriptions SET status = 'rejected' WHERE id = ?");
    $stmt->bind_param('i', $prescription_id);
    
    if ($stmt->execute()) {
        echo "Prescription rejected successfully!";
    } else {
        echo "Error rejecting prescription: " . $stmt->error;
    }

    // Redirect back to the prescription list
    header('Location: priscription.php');
    exit;
}
?>
