<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'medico_shop');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$productToEdit = null;

// Function to convert image to Base64 (Not used here, but left for reference)
function imageToBase64($imageFile) {
    $imageData = file_get_contents($imageFile);
    return 'data:' . mime_content_type($imageFile) . ';base64,' . base64_encode($imageData);
}

// Handle Add Product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category = $_POST['category'];
    $discount = $_POST['discount'];
    
    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = $_FILES['image'];
        $imagePath = 'images/' . basename($image['name']);  // Set the path where the image will be saved
        move_uploaded_file($image['tmp_name'], $imagePath);  // Move the uploaded file to the server
    } else {
        $imagePath = '';  // If no image, set empty
    }

    // Check if requires_priscription is checked
    $requires_priscription = isset($_POST['requires_priscription']) ? 1 : 0;
    
    // Insert into the database
    $stmt = $conn->prepare("INSERT INTO products (name, price, stock, category, discounted_price, image, requires_priscription) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('sdisssi', $name, $price, $stock, $category, $discount, $imagePath, $requires_priscription);
    
    if ($stmt->execute()) {
        echo "Product added successfully!";
    } else {
        echo "Error adding product: " . $stmt->error;
    }
}

// Handle Delete Product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $productId = $_POST['id'];
    
    // Delete the product from the database
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param('i', $productId);
    if ($stmt->execute()) {
        echo "Product deleted successfully!";
    } else {
        echo "Error deleting product: " . $stmt->error;
    }
}

// Handle Edit Product (Retrieve the product data to pre-fill the form)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
    $productId = $_POST['id'];
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param('i', $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $productToEdit = $result->fetch_assoc();
    }
}

// Handle Update Product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category = $_POST['category'];
    $discount = $_POST['discount'];

    // Handle image upload (if new image is uploaded)
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = $_FILES['image'];
        $imagePath = 'images/' . basename($image['name']);
        move_uploaded_file($image['tmp_name'], $imagePath);
    } else {
        // If no new image uploaded, keep the old image path
        $imagePath = $productToEdit['image'];
    }
    
    // Check if requires_priscription is checked
    $requires_priscription = isset($_POST['requires_priscription']) ? 1 : 0;

    // Update the product in the database
    $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, stock = ?, category = ?, discounted_price = ?, image = ?, requires_priscription = ? WHERE id = ?");
    $stmt->bind_param('sdisssii', $name, $price, $stock, $category, $discount, $imagePath, $requires_priscription, $id);
    
    if ($stmt->execute()) {
        echo "Product updated successfully!";
        header('Location: products.php');  // Redirect after update to avoid form resubmission
        exit;
    } else {
        echo "Error updating product: " . $stmt->error;
    }
}

// Fetch products for display
$products = [];
$result = $conn->query("SELECT * FROM products");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <link rel="stylesheet" href="styles.css">
    <style>
    /* General Body Styles */
    body {
        font-family: 'Helvetica Neue', Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }

    /* Header Styles */
    header {
        background-color: #333;
        color: white;
        padding: 20px 0;
        text-align: center;
        font-size: 24px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    header h1 {
        margin: 0;
    }

    /* Navigation Styles */
    nav {
        background-color: #333;
        padding: 10px 0;
    }

    nav ul {
        list-style: none;
        padding: 0;
        margin: 0;
        text-align: center;
    }

    nav ul li {
        display: inline-block;
        margin: 0 15px;
    }

    nav ul li a {
        color: white;
        text-decoration: none;
        font-size: 16px;
        padding: 8px 12px;
        transition: background-color 0.3s;
    }

    nav ul li a:hover {
        background-color: #333;
        border-radius: 5px;
    }

    /* Section Styles */
    section {
        padding: 20px;
        max-width: 1200px;
        margin: 20px auto;
    }

    /* Table Styles */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        background-color: white;
        border-radius: 5px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    th, td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #333;
        color: white;
    }

    tr:hover {
        background-color: #f1f1f1;
    }

    /* Button Styles */
    button {
        background-color: #333;
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
        font-size: 14px;
    }

    button:hover {
        background-color: #555;
    }

    /* Edit Button Styles */
    button.edit {
        background-color: #28a745;
    }

    button.edit:hover {
        background-color: #218838;
    }

    /* Delete Button Styles */
    button.delete {
        background-color: #dc3545;
    }

    button.delete:hover {
        background-color: #c82333;
    }

    /* Image Styles */
    img {
        max-width: 80px;
        height: auto;
        border-radius: 5px;
    }

    /* Form Styles */
    form {
        margin-bottom: 20px;
        padding: 20px;
        background-color: white;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
    }

    input, select {
        width: calc(100% - 16px);
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    /* Footer Styles */
    footer {
        text-align: center;
        padding: 10px;
        background-color: #333;
        color: white;
        position: relative;
        bottom: 0;
        width: 100%;
        margin-top: 20px;
    }
</style>

</head>
<body>
<nav>
<header>
    <h1>Product Management</h1>
    <ul>
        <li><a href="index2.php">Dashboard</a></li>
        <li><a href="products.php">Product Management</a></li>
        <li><a href="stock.php">Stock Management</a></li>
        <li><a href="order.php">Order Mangement</a></li>   
        <li><a href="user.php">User Mangement</a></li> 
        <li><a href="priscription.php">Prescription Mangement</a></li>
        <li><a href="contact.php">Contact Mangement</a></li>   
        <li><a href="logout.php">Logout</a></li>
    </ul>
</header>
</nav>

<section>
    <h2>Manage Products</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Product Name</label>
            <input type="text" id="name" name="name" value="<?= isset($productToEdit) ? $productToEdit['name'] : '' ?>" required>
        </div>
        <div class="form-group">
            <label for="price">Price</label>
            <input type="number" id="price" name="price" value="<?= isset($productToEdit) ? $productToEdit['price'] : '' ?>" required>
        </div>
        <div class="form-group">
            <label for="stock">Stock</label>
            <input type="number" id="stock" name="stock" value="<?= isset($productToEdit) ? $productToEdit['stock'] : '' ?>" required>
        </div>
        <div class="form-group">
            <label for="category">Category</label>
            <select id="category" name="category">
                <option value="Electronics" <?= isset($productToEdit) && $productToEdit['category'] == 'Electronics' ? 'selected' : '' ?>>Electronics</option>
                <option value="Clothing" <?= isset($productToEdit) && $productToEdit['category'] == 'Clothing' ? 'selected' : '' ?>>Clothing</option>
                <option value="Accessories" <?= isset($productToEdit) && $productToEdit['category'] == 'Accessories' ? 'selected' : '' ?>>Accessories</option>
            </select>
        </div>
        <div class="form-group">
            <label for="discount">Discounted Price</label>
            <input type="number" id="discount" name="discount" value="<?= isset($productToEdit) ? $productToEdit['discounted_price'] : '' ?>">
        </div>
        <div class="form-group">
            <label for="image">Product Image</label>
            <input type="file" id="image" name="image" accept="image/*">
        </div>
        <div class="form-group">
            <label for="requires_priscription">Requires Prescription</label>
            <input type="checkbox" id="requires_priscription" name="requires_priscription" <?= isset($productToEdit) && $productToEdit['requires_priscription'] == 1 ? 'checked' : '' ?>>
        </div>

        <input type="hidden" name="action" value="<?= isset($productToEdit) ? 'update' : 'add' ?>">
        <?= isset($productToEdit) ? '<input type="hidden" name="id" value="' . $productToEdit['id'] . '">' : '' ?>

        <button type="submit"><?= isset($productToEdit) ? 'Update Product' : 'Add Product' ?></button>
    </form>

    <h3>Product List</h3>
    <table>
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Category</th>
                <th>Discount</th>
                <th>Image</th>
                <th>Requires Prescription</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td><?= htmlspecialchars($product['price']) ?></td>
                    <td><?= htmlspecialchars($product['stock']) ?></td>
                    <td><?= htmlspecialchars($product['category']) ?></td>
                    <td><?= htmlspecialchars($product['discounted_price']) ?></td>
                    <td><img src="<?= "../".$product['image'] ?>" alt="Image"></td>
                    <td><?= $product['requires_priscription'] == 1 ? 'Yes' : 'No' ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $product['id'] ?>">
                            <input type="hidden" name="action" value="edit">
                            <button type="submit">Edit</button>
                        </form>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $product['id'] ?>">
                            <input type="hidden" name="action" value="delete">
                            <button type="submit" class="delete">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

<footer>
    <p>&copy; 2024 Medico Shop. All Rights Reserved.</p>
</footer>

</body>
</html>
