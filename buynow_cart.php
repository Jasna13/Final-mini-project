<?php
session_start();

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

// Access the cart_id passed in the URL
$cartId = isset($_GET['cart_id']) ? $_GET['cart_id'] : null;
if (!$cartId || !is_numeric($cartId)) {
    echo "Invalid or missing cart ID.";
    exit;
}

if ($cartId > 0) {
    // Fetch cart details and join with the products table
    $sql = "SELECT cart.quantity, cart.image, products.id, products.name, products.price
            FROM cart 
            JOIN products ON cart.id = products.id 
            WHERE cart.cid = ?";
            
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die('MySQL prepare error: ' . $conn->error);
    }

    $stmt->bind_param("i", $cartId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result && $result->num_rows > 0) {
        // Fetch the cart item data
        $cartItem = $result->fetch_assoc();

        // Check if $cartItem is populated
        if ($cartItem) {
            ?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Placing Order | MediCare</title>
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
                        background-color: #f4f4f4;
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

                    .logo h1 {
                        font-size: 24px;
                    }

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

                    /* Main Content Styling */
                    main {
                        max-width: 900px;
                        margin: 20px auto;
                        padding: 20px;
                        background-color: #ffffff;
                        border-radius: 10px;
                        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                    }

                    /* Flex container for product details and image */
                    .product-details {
                        display: flex;
                        align-items: flex-start;
                        gap: 20px;
                        margin-bottom: 20px;
                    }

                    .product-info{
                        display : grid;
                        grid-template-columns : repeat(2,1fr);
                        
                        .right{
                            display : flex;
                            flex-direction : column;
                            justify-content : center;
                        }
                    }

                    /* Product image styling */
                    .product-image img {
                        width: 150px;
                        height: auto;
                        border-radius: 5px;
                        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                    }

                    /* Textarea and Input Styling */
                    textarea, input[type="tel"], select {
                        width: 100%;
                        padding: 10px;
                        margin-top: 5px;
                        border: 1px solid #ccc;
                        border-radius: 4px;
                    }

                    textarea {
                        height: 100px;
                        resize: vertical;
                    }

                    /* Button Styling */
                    button {
                        padding: 10px 20px;
                        border: none;
                        border-radius: 5px;
                        font-size: 16px;
                        cursor: pointer;
                        background-color: #45a049;
                        color: white;
                        transition: background-color 0.3s ease;
                    }

                    button:hover {
                        background-color: #388e3c;
                        color: #f0f0f0;
                    }

                    /* Footer Styling */
                    footer {
                        text-align: center;
                        margin-top: 20px;
                        padding: 10px;
                        background-color: #06782c;
                        color: white;
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
                    <h2>Placing Order</h2>
                    <form action="place_order.php" method="POST">
                        <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($cartItem['id'], ENT_QUOTES); ?>">
                        <input type="hidden" name="quantity" value="<?php echo htmlspecialchars($cartItem['quantity'], ENT_QUOTES); ?>">


                            <!-- Product details on the right side -->
                            <div class="product-info">
                        
                                <div class="right">
                                    <p><strong>Product:</strong> <?php echo htmlspecialchars($cartItem['name'], ENT_QUOTES); ?></p>
                                    <p><strong>Quantity:</strong> <?php echo htmlspecialchars($cartItem['quantity'], ENT_QUOTES); ?></p>
                                    <p><strong>Total Price:</strong> ₹<?php echo number_format($cartItem['price'] * $cartItem['quantity'], 2); ?></p>
                                </div>

                                <div class="left">
                                    <p class="prod-img"><img src=<?php echo "../".$cartItem['image'] ?> alt=<?php echo $cartItem['name'] ?>  /></p>
                                </div>
                                
                            </div>
                        </div>

                        <div>
                            <label for="shipping_address">Shipping Address:</label>
                            <textarea id="shipping_address" name="shipping_address" required></textarea>
                        </div>
                        <div>
                            <label for="contact_number">Contact Number:</label>
                            <input type="tel" id="contact_number" name="contact_number" required>
                        </div>
                        <div>
                            <label for="payment_method">Payment Method:</label>
                            <select name="payment_method" id="payment_method" required>
                                <option value="Credit Card">Credit Card</option>
                                <option value="PayPal">PayPal</option>
                                <option value="Cash On Delivery">Cash on Delivery</option>
                            </select>
                        </div>

                        <button type="submit" class="btn place-order">Place Order</button>
                    </form>
                </main>

                <footer>
                    <p>&copy; 2024 MediCare. All rights reserved.</p>
                </footer>
            </body>
            </html>
            <?php
        } else {
            echo "Product not found!";
        }
    } else {
        echo "No items found in your cart with this ID.";
    }

    $stmt->close();
} else {
    echo "Invalid cart ID.";
}

$conn->close();
?>
