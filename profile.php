<?php
// Start the session and check if the user is logged in
session_start();
if (!isset($_SESSION['uid'])) {
    header("Location: http://localhost/Project/medicare/medicare-main/Login/login.php"); // Redirect to login if not logged in
    exit();
}

// Get user ID from session
$userId = $_SESSION['uid']; 

// Database connection
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "medico_shop"; 

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connectiona
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user details from the user table
$userSql = "SELECT username, email, phone_number FROM user WHERE uid = ?";
$stmt = $conn->prepare($userSql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($username, $email, $phone_number);
$stmt->fetch();
$stmt->close();

// Fetch user orders from the orders table, including product name, total price, and image
$orderSql = "SELECT oid, product_name, price, quantity, shipping_address, contact_number, order_date, status, payment, image FROM orders WHERE uid = ?";
$orderStmt = $conn->prepare($orderSql);
$orderStmt->bind_param("i", $userId);  // Use $userId to fetch orders for the logged-in user
$orderStmt->execute();
$orderStmt->bind_result($oid, $product_name, $total_price, $quantity, $shipping_address, $contact_number, $order_date, $status, $payment, $image);

// HTML structure for the profile page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - Medical Shop</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        td {
            background-color: #f9f9f9;
        }
        img {
            max-width: 100px;
            height: auto;
        }
        .cancel-btn {
            background-color: red;
            color: white;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
        }
        .cancel-btn:disabled {
            background-color: grey;
        }

        /* Header Styling */
        header {
    width: 100%;
    background-color: #008000; /* Adjust the color if needed */
    display: flex;
    justify-content: space-between;
    padding: 10px 20px;
    box-sizing: border-box;
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
        footer {
    text-align: center;
    padding: 20px;
    background-color: #06782c;
    color: white;
    position: relative;
    bottom: 0;
    width: 100%;
}
    </style>
</head>
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
<body>

<div class="container">
    <h1>User Profile</h1>
    
    <h3>Personal Information</h3>
    <p><strong>Username:</strong> <?php echo htmlspecialchars($username, ENT_QUOTES); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($email, ENT_QUOTES); ?></p>
    <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($phone_number, ENT_QUOTES); ?></p>

    <h3>Order History</h3>
    <table>
        <tr>
            <th>Order ID</th>
            <th>Product Name</th>
            <th>Total Price</th>
            <th>Quantity</th>
            <th>Shipping Address</th>
            <th>Contact Number</th>
            <th>Order Date</th>
            <th>Status</th>
            <th>Payment</th>
            <th>Image</th>
            <th>Action</th>
        </tr>
        
        <?php
        // Loop through and display user orders
        while ($orderStmt->fetch()) {
            echo "<tr>
                    <td>" . htmlspecialchars($oid, ENT_QUOTES) . "</td>
                    <td>" . htmlspecialchars($product_name, ENT_QUOTES) . "</td>
                    <td>₹" . htmlspecialchars($total_price, ENT_QUOTES) . "</td>
                    <td>" . htmlspecialchars($quantity, ENT_QUOTES) . "</td>
                    <td>" . htmlspecialchars($shipping_address, ENT_QUOTES) . "</td>
                    <td>" . htmlspecialchars($contact_number, ENT_QUOTES) . "</td>
                    <td>" . htmlspecialchars($order_date, ENT_QUOTES) . "</td>
                    <td>" . htmlspecialchars($status, ENT_QUOTES) . "</td>
                    <td>" . htmlspecialchars($payment, ENT_QUOTES) . "</td>
                    <td><img src='" . htmlspecialchars($image, ENT_QUOTES) . "' alt='Product Image'></td>
                    <td>";
            
            // Cancel Order Button Form
            if ($status != 'Cancelled') { // Check if the order isn't already cancelled
                echo "<form action='cancel_order.php' method='POST'>
                        <input type='hidden' name='oid' value='" . htmlspecialchars($oid, ENT_QUOTES) . "'>
                        <button type='submit' class='cancel-btn'>Cancel Order</button>
                      </form>";
            } else {
                echo "<button disabled class='cancel-btn'>Cancelled</button>";
            }

            echo "</td></tr>";
        }
        ?>
    </table>

</div>
<footer>
        <p>&copy; 2024 MediCare. All rights reserved.</p>
    </footer>
</body>
</html>

<?php
// Close the database connection
$orderStmt->close();
$conn->close();
?> 