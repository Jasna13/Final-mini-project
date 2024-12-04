<?php
// Start the session
session_start();

// Database connection
$host = 'localhost';
$db = 'medico_shop';
$user = 'root'; // Change to your DB username
$pass = ''; // Change to your DB password

// Create a connection to the database
$conn = new mysqli($host, $user, $pass, $db);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in (assuming 'admin_logged_in' session variable)
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // Redirect to the login page if not logged in
    header('Location: http://localhost/Project/medicare/medicare-main/Login/login.php');
    exit();
}

// Query for total products
$product_query = "SELECT COUNT(*) AS total_products FROM products";
$product_result = $conn->query($product_query);
$total_products = $product_result ? $product_result->fetch_assoc()['total_products'] : 0;

// Query for stock status (low stock example: products with less than 20 items)
$low_stock_query = "SELECT COUNT(*) AS low_stock_products FROM products WHERE stock < 20";
$low_stock_result = $conn->query($low_stock_query);
$low_stock_products = $low_stock_result ? $low_stock_result->fetch_assoc()['low_stock_products'] : 0;

// Query for active staff
$staff_query = "SELECT COUNT(*) AS active_staff FROM staff WHERE status = 'active'";
$staff_result = $conn->query($staff_query);
$active_staff = $staff_result ? $staff_result->fetch_assoc()['active_staff'] : 0;

// Close the connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        header {
            background: #333; /* Changed color */
            color: #ffffff;
            padding: 10px 0;
            text-align: center;
        }
        nav{
            background-color:#333;
        }
        nav ul {
            list-style: none;
            padding: 0;
        }

        nav ul li {
            display: inline;
            margin: 0 15px;
        }

        nav ul li a {
            color: #ffffff;
            text-decoration: none;
        }

        .panel {
            padding: 20px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .grid-item {
            background: #e4e4e4;
            border-radius: 5px;
            padding: 15px;
            text-align: center;
            transition: background 0.3s;
        }

        .grid-item:hover {
            background: #d1d1d1;
        }

        h3 {
            margin: 0;
            color: #333; /* Changed color */
        }

        .count {
            font-size: 24px;
            font-weight: bold;
            color: #333; /* Changed color */
        }

        footer {
            text-align: center;
            padding: 10px;
            background: #333; /* Changed color */
            color: #ffffff;
            bottom: 0;
            width: 100%;
            margin-top: 20px;
            position:fixed;
        }
    </style>
    <nav>
    <header>
        <h1>Admin Dashboard</h1>
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

    <section id="dashboard" class="panel">
        <h2>Welcome Admin,</h2>
        <div class="grid-container">
            <div class="grid-item">
                <h3>Total Products</h3>
                <p class="count"><?php echo htmlspecialchars($total_products); ?></p>
            </div>
            <div class="grid-item">
                <h3>Stock Status</h3>
                <p class="count">Low in <?php echo htmlspecialchars($low_stock_products); ?> products</p>
            </div>
            <div class="grid-item">
                <h3>Staff</h3>
                <p class="count"><?php echo htmlspecialchars($active_staff); ?> active staff</p>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; 2024 Admin Dashboard</p>
    </footer>
</body>
</html>
