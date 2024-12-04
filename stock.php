<?php
// Database connection
$host = 'localhost';  // Change to your host
$dbname = 'medico_shop';  // Change to your database name
$username = 'root';  // Change to your username
$password = '';  // Change to your password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Fetch all products from the database
$query = "SELECT * FROM products";  // Replace 'products' with your actual table name
$stmt = $pdo->prepare($query);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle stock updates
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id']) && isset($_POST['new_stock'])) {
    $productId = $_POST['product_id'];
    $newStock = (int) $_POST['new_stock'];

    // Update stock in the database
    $updateQuery = "UPDATE products SET stock = :new_stock WHERE id = :product_id";
    $updateStmt = $pdo->prepare($updateQuery);
    $updateStmt->execute(['new_stock' => $newStock, 'product_id' => $productId]);

    // Redirect to prevent form resubmission
    header("Location: stock.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Management</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Basic styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        header {
            background-color: #333;
            color: white;
            padding: 15px;
            text-align: center;
        }
        nav{
            background-color:#333;
        }
        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        nav ul li {
            display: inline;
            margin-right: 15px;
        }
        nav ul li a {
            color: white;
            text-decoration: none;
        }
        section {
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        button {
            background-color: #5cb85c;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
        }
        button.update {
            background-color: #337ab7;
        }
        .low-stock, .out-of-stock {
            font-weight: bold;
        }
        .low-stock {
            color: red;
        }
        .out-of-stock {
            color: grey;
        }
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
    </style>
</head>
<body>
    <nav>
    <header>
        <h1>Stock Management</h1>
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
    <section id="stock" class="panel">
        <h2>Manage Stock</h2>

        <!-- Stock table -->
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <!-- <th>Supplier Name</th> -->
                    <th>Current Stock</th>
                    <th>Edit Stock</th>
                    <th>Stock Status</th>
                    <th>Update</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <!-- <td><?= htmlspecialchars($product['supplier']) ?></td> -->
                    <td><?= htmlspecialchars($product['stock']) ?></td>
                    <td>
                        <!-- Stock update form for each product -->
                        <form method="POST" action="stock.php">
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            <input type="number" name="new_stock" min="0" value="<?= htmlspecialchars($product['stock']) ?>" required>
                    </td>
                    <td>
                        <?php if ($product['stock'] == 0): ?>
                            <span class="out-of-stock">Out of Stock</span>
                        <?php elseif ($product['stock'] < 20): ?>
                            <span class="low-stock">Low Stock</span>
                        <?php else: ?>
                            <span>In Stock</span>
                        <?php endif; ?>
                    </td>
                    <td><button class="update" type="submit">Update Stock</button></form></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <footer>
        <p>&copy; 2024 Admin Dashboard</p>
    </footer>
</body>
</html>
