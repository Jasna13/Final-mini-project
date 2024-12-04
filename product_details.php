<?php
session_start(); // Start the session

// Database connection
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "medico_shop"; 

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get product ID from the URL and fetch product details
$productId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $productId);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    echo "<h2>Product not found!</h2>";
    exit;
}

// Check for added success message
$addedSuccess = isset($_GET['added']) ? true : false;

// Get the current date
$currentDate = date("Y-m-d");

// Check if the product is expired
$expiryDate = isset($product['expiry_date']) ? $product['expiry_date'] : null;
if ($expiryDate && $expiryDate < $currentDate) {
    echo "<h2>This product has expired and is no longer available for purchase.</h2>";
    exit;
}

if ($product['stock'] <= 0) {
    echo "<h2>This product is currently out of stock.</h2>";
    exit;
} elseif ($product['stock'] <= 5) {
    // Low stock condition
    echo "<h2>Warning: Only " . $product['stock'] . " items left in stock. Hurry, get yours now!</h2>";
}

// Fetch user's prescription status if logged in
$prescriptionStatus = 'not uploaded'; // Default status
$canPurchase = false; // Flag to indicate whether user can purchase
$isPrescriptionPending = false; // Flag to indicate if prescription is pending
$prescriptionUploaded = false; // Flag to check if prescription has been uploaded

if (isset($_SESSION['uid'])) {
    $userId = $_SESSION['uid'];
    $sqlPrescription = "SELECT status FROM prescriptions WHERE user_id = ? AND product_id = ?";
    $stmtPrescription = $conn->prepare($sqlPrescription);
    $stmtPrescription->bind_param("ii", $userId, $productId);
    $stmtPrescription->execute();
    $resultPrescription = $stmtPrescription->get_result();
    
    if ($resultPrescription->num_rows > 0) {
        $prescriptionData = $resultPrescription->fetch_assoc();
        $prescriptionStatus = $prescriptionData['status'];
        // Check if the prescription is approved
        if ($prescriptionStatus == 'approved') {
            $canPurchase = true;
            $prescriptionUploaded = true; // Prescription is uploaded and approved
        } elseif ($prescriptionStatus == 'pending') {
            $isPrescriptionPending = true;
        }
    }
    $stmtPrescription->close();
}

// Final price calculation
$finalPrice = ($product['discounted_price'] > 0) ? $product['price'] - $product['discounted_price'] : $product['price'];

// Close the database connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name'], ENT_QUOTES); ?> | MediCare</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Styling for prescription image and upload */
        .prescription-required {
            margin-top: 20px;
            color: red;
        }

        .prescription-image img {
            max-width: 200px;
            margin-top: 10px;
        }

        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        /* Stock message container */
.stock-message {
    padding: 20px;
    margin: 20px 0;
    border-radius: 10px;
    font-size: 18px;
    text-align: center;
    font-weight: bold;
}

/* Out of stock message styling */
.out-of-stock {
    background-color: #dc3545; /* Red background */
    color: white;
    border: 2px solid #c82333;
}

/* Low stock message styling */
.low-stock {
    background-color: #ffc107; /* Yellow background */
    color: #333;
    border: 2px solid #e0a800;
}

/* Optional: Add hover effect for messages */
.stock-message:hover {
    opacity: 0.8;
    transition: opacity 0.3s ease;
}

        body {
            font-family: Arial, sans-serif;
        }

        /* Header Styling */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #06782c;
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

        /* Product Card Styling */
        .product-card {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 600px;
            margin: 20px auto;
            overflow: hidden;
        }

        /* Image Styling */
        .product-image img {
            max-width: 250px;
            border-radius: 10px;
            object-fit: cover;
        }

        /* Product Info Styling */
        .product-info {
            flex-grow: 1;
            margin-left: 20px;
        }

        .product-info h2 {
            font-size: 22px;
            color: #333;
            margin-bottom: 10px;
        }

        footer {
            background-color: #06782c;
            margin-top: 20px;
            text-align: center;
            padding: 10px;
            color: white;
        }

        .product-info .price {
            font-size: 20px;
            color: red;
            margin-bottom: 15px;
        }

        /* Button Styling */
        button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button.add-to-cart {
            background-color: #06782c;
            color: white;
        }

        button.add-to-cart:hover {
            background-color: #0056b3;
        }

        button.buy-now {
            background-color: #06782c;
            color: white;
        }

        button.buy-now:hover {
            background-color: #0056b3;
        }

        .login-required {
            color: red;
            margin-top: 15px;
        }

        .file-input-container {
            margin-top: 15px;
        }

        .file-input-container input[type="file"] {
            padding: 5px;
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

    <main>
        <div class="product-card">
            <div class="product-image">
                <img src="../<?php echo htmlspecialchars($product['image'], ENT_QUOTES); ?>" alt="<?php echo htmlspecialchars($product['name'], ENT_QUOTES); ?>" />
            </div>
            <div class="product-info">
                <h2><?php echo htmlspecialchars($product['name'], ENT_QUOTES); ?></h2>
                <p>Price: <?php if ($product['discounted_price'] > 0): ?>
                        <span class="original-price" style="text-decoration: line-through;">₹<?php echo number_format($product['price'], 2); ?></span>
                        ₹<?php echo number_format($finalPrice, 2); ?>
                    <?php else: ?>
                        ₹<?php echo number_format($product['price'], 2); ?>
                    <?php endif; ?></p>

                <?php if (isset($_SESSION['uid'])): ?>
                    <!-- Form to add product to cart -->
                    <form action="add_to_cart_actions.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id'], ENT_QUOTES); ?>">
                        <div>
                            <label for="quantity">Quantity:</label>
                            <input type="number" id="quantity" name="quantity" min="1" value="1" required>
                        </div>
                        <div class="buttons-container">
                            <button type="submit" class="btn add-to-cart">Add to Cart</button>

                            <?php if ($isPrescriptionPending): ?>
                                <p class="prescription-required">Admin has to verify the prescription.</p>
                            <?php elseif ($canPurchase): ?>
                                <button type="button" class="btn buy-now" onclick="buyNow(<?php echo htmlspecialchars($product['id'], ENT_QUOTES); ?>)">Buy Now</button>
                            <?php endif; ?>
                        </div>
                    </form>

                    <!-- Prescription Check -->
                    <?php if ($product['requires_priscription'] == 1): ?>
                        <?php if ($prescriptionUploaded): ?>
                            <p class="approved">Your prescription is uploaded and approved.</p>
                        <?php else: ?>
                            <!-- Form to upload prescription -->
                            <p class="prescription-required">This product requires a prescription.</p>
                            <form action="upload_prescription.php" method="POST" enctype="multipart/form-data">
                                <div class="file-input-container">
                                    <label for="prescription_image">Upload Prescription Image:</label>
                                    <input type="file" name="prescription_image" id="prescription_image" accept="image/*">
                                </div>
                                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id'], ENT_QUOTES); ?>">
                                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($userId, ENT_QUOTES); ?>">
                                <button type="submit" class="btn">Upload Prescription</button>
                            </form>
                        <?php endif; ?>
                    <?php endif; ?>

                <?php else: ?>
                    <p class="login-required">Please <a href="http://localhost/Project/medicare/medicare-main/Login/login.php">login</a> to add items to your cart.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 MediCare. All rights reserved.</p>
    </footer>

    <script>
        function buyNow(productId) {
            const quantity = document.getElementById('quantity').value;
            if (quantity <= 0) {
                alert("Please enter a valid quantity.");
                return;
            }
            window.location.href = "buy_now.php?id=" + productId + "&quantity=" + quantity;
        }
        
        <?php if ($addedSuccess): ?>
            alert("Product added to cart successfully!");
        <?php endif; ?>
    </script>
</body>
</html>
