<?php
session_start();
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "medico_shop"; 

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the product and user IDs
    $productId = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $userId = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;

    // Check if the user is logged in
    if (!isset($_SESSION['uid'])) {
        echo "You need to log in first.";
        exit;
    }

    // Check if file was uploaded
    if (isset($_FILES['prescription_image']) && $_FILES['prescription_image']['error'] == 0) {
        $file = $_FILES['prescription_image'];
        
        // Validate file type (only images)
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file['type'], $allowedTypes)) {
            echo "Only JPG, PNG, and GIF images are allowed.";
            exit;
        }

        // Validate file size (max 2MB)
        $maxSize = 2 * 1024 * 1024; // 2MB
        if ($file['size'] > $maxSize) {
            echo "File size must be less than 2MB.";
            exit;
        }

        // Define a unique name for the file and save it
        $targetDir = "uploads/prescriptions/";
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $targetFile = $targetDir . uniqid() . "_" . basename($file["name"]);
        
        // Move uploaded file to target directory
        if (move_uploaded_file($file["tmp_name"], $targetFile)) {
            // Prepare the SQL query to insert prescription details
            $sql = "INSERT INTO prescriptions (user_id, product_id, image, status) VALUES (?, ?, ?, 'pending')";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iis", $userId, $productId, $targetFile);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                // Redirect back to product details page with a success message
                $stmt->close();
                $conn->close();
                header("Location: product_details.php?id=$productId&prescription_uploaded=success");
                exit;
            } else {
                echo "Failed to save prescription details in the database.";
            }
            $stmt->close();
        } else {
            echo "Failed to upload the file.";
        }
    } else {
        echo "No file uploaded or there was an error uploading the file.";
    }
}

$conn->close();
?>
