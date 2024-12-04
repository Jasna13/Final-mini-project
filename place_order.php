<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Summary | MediCare</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
        }

        /* Header Styling */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color:#06782c;
            padding: 15px;
            color: white;
        }

        /* Logo Styling */
        .logo h1 {
            font-size: 24px;
        }

        /* Navigation Styling */
        nav {
            display: flex;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-size: 16px;
        }

        nav a:hover {
            text-decoration: underline;
        }

        /* Order Summary Styling */
        .order-summary {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            gap: 20px;
        }

        /* Flex Container Styling */
        .order-details, .product-image {
            flex: 1;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        p {
            margin-bottom: 10px;
            color: #06782c;
        }

        /* Footer Styling */
        footer {
            text-align: center;
            padding: 10px;
            background-color:#06782c;
            color: white;
            margin-top: 50px;
        }
        
        footer p {
            color: white;
        }
        
        /* Product Image Styling */
        .product-image img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <header>
        <h1 class="logo">MediCare</h1>
        <nav>
        <ul>
                  <li><a href="index1.php">Home</a></li>
                  <li><a href="products.php">Products</a></li>
                  <li><a href="aboutus.php">About Us</a></li>
                  <li><a href="contact.php">Contact</a></li>
                  <li><a href="Add_to_cart.php">Add to Cart</a></li>
                  <li><a href="profile.php">Profile</a></li>
                  <?php if (isset($_SESSION['uid'])): ?>
                      <li><a href="logout.php">Logout</a></li>
                  <?php else: ?>
                      <li><a href="http://localhost/Project/medicare/medicare-main/Login/login.php">Login</a></li>
                  <?php endif; ?>
              </ul>
        </nav>
    </header>

    <div class="order-summary">
        <div class="order-details">
        <?php
session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "medico_shop"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure session contains user ID
if (!isset($_SESSION['uid'])) {
    echo "<p>You must be logged in to place an order.</p>";
    exit;
}

$userId = $_SESSION['uid'];

// Check if user ID exists in user table
$result = $conn->query("SELECT uid FROM user WHERE uid = $userId");
if ($result->num_rows == 0) {
    echo "<p>Error: User ID not found. Please log in again.</p>";
    exit;
}

// Get product ID and other details from the POST request
$productId = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
$shippingAddress = isset($_POST['shipping_address']) ? htmlspecialchars($_POST['shipping_address'], ENT_QUOTES) : '';
$contactNumber = isset($_POST['contact_number']) ? htmlspecialchars($_POST['contact_number'], ENT_QUOTES) : '';
$paymentMethod = isset($_POST['payment_method']) ? $_POST['payment_method'] : null;

// Validate inputs
if ($productId > 0 && $quantity > 0 && !empty($shippingAddress) && !empty($contactNumber) && !empty($paymentMethod)) {
    // Retrieve product details from the products table
    $productQuery = $conn->prepare("SELECT name, price, image FROM products WHERE id = ?");
    if ($productQuery === false) {
        // Output error if prepare() fails
        die("Error preparing product query: " . $conn->error);
    }
    $productQuery->bind_param("i", $productId);
    $productQuery->execute();
    $productQuery->bind_result($productName, $productPrice, $productImage);
    $productQuery->fetch();
    $productQuery->close();

    // Check if product is found
    if (!empty($productName)) {
        // Calculate total price
        $totalPrice = $productPrice * $quantity;

        // Prepare SQL to insert the order
        $stmt = $conn->prepare("INSERT INTO orders (uid, id, product_name, quantity, price, image, shipping_address, contact_number, payment) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt === false) {
            // Output error if prepare() fails
            die("Error preparing insert query: " . $conn->error);
        }

        // Bind the parameters to the prepared statement
        $stmt->bind_param("iisiissss", $userId, $productId, $productName, $quantity, $totalPrice, $productImage, $shippingAddress, $contactNumber, $paymentMethod);

        // Execute the statement and check for success
        if ($stmt->execute()) {
            echo "<h2>Order Placed Successfully!</h2>";
            echo "<p>Product Name: " . htmlspecialchars($productName, ENT_QUOTES) . "</p>";
            echo "<p>Quantity: " . htmlspecialchars($quantity, ENT_QUOTES) . "</p>";
            echo "<p>Total Price: ₹" . htmlspecialchars($totalPrice, ENT_QUOTES) . "</p>";
            echo "<p>Shipping Address: " . htmlspecialchars($shippingAddress, ENT_QUOTES) . "</p>";
            echo "<p>Contact Number: " . htmlspecialchars($contactNumber, ENT_QUOTES) . "</p>";
            echo "<p>Payment Method: " . htmlspecialchars($paymentMethod, ENT_QUOTES) . "</p>";
        } else {
            echo "<p>Error placing order: " . htmlspecialchars($stmt->error, ENT_QUOTES) . "</p>";
        }

        // Close the prepared statement
        $stmt->close();
    } else {
        echo "<p>Error: Product not found.</p>";
    }
} else {
    echo "<p>Please fill in all the fields correctly.</p>";
}

$conn->close();
?>

        </div>

        <div class="product-image">
            <?php if (!empty($productImage)): ?>
                <img src="../<?php echo htmlspecialchars($productImage, ENT_QUOTES); ?>" alt="Product Image">
            <?php endif; ?>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 MediCare. All rights reserved.</p>
    </footer>
</body>
</html>
