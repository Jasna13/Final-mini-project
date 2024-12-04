<?php
// Database connection
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'medical_shop';

$conn = new mysqli($host, $user, $pass, $db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $productId = $_POST['id'];
    $productName = $_POST['productName'];
    $productPrice = $_POST['productPrice'];
    $productStock = $_POST['productStock'];
    $productDescription = $_POST['productDescription'];

    // Check if a new image was uploaded
    if (isset($_FILES['productImage']) && $_FILES['productImage']['error'] == 0) {
        // Handle new image upload
        $image = $_FILES['productImage'];
        $imageName = $image['name'];
        $imageTmpName = $image['tmp_name'];
        $imageSize = $image['size'];
        $imageExt = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
        $allowedExt = array('jpg', 'jpeg', 'png', 'gif');

        if (in_array($imageExt, $allowedExt) && $imageSize < 5000000) {
            $newImageName = uniqid('', true) . "." . $imageExt;
            $imageDestination = 'images/' . $newImageName;
            move_uploaded_file($imageTmpName, $imageDestination);

            // Delete old image
            $sql = "SELECT image FROM products WHERE id='$productId'";
            $result = $conn->query($sql);
            $oldImage = $result->fetch_assoc()['image'];
            unlink('images/' . $oldImage);

            // Update database with new image
            $sql = "UPDATE products SET name='$productName', price='$productPrice', stock='$productStock', 
                    description='$productDescription', image='$newImageName' WHERE id='$productId'";
        }
    } else {
        // If no new image, update other fields only
        $sql = "UPDATE products SET name='$productName', price='$productPrice', stock='$productStock', 
                description='$productDescription' WHERE id='$productId'";
    }

    if ($conn->query($sql) === TRUE) {
        echo "Product updated successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
