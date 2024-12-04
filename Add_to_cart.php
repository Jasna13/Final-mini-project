<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "medico_shop");
// Get user ID from session
$uid = $_SESSION['uid'];

// Fetch cart items for this user
$query = "SELECT cart.cid, products.name, products.price, products.image, cart.quantity, (products.price * cart.quantity) AS total
          FROM cart
          INNER JOIN products ON cart.id = products.id
          WHERE cart.uid = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $uid);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link rel="stylesheet" href="styles.css">
</head>
<header>
    <div class="header-container">
        <span class="brand-name">MediCare</span>
    </div>
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
            background-color: #06782c;
            padding: 27px;
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
            margin-top: 200px;
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
/* Table styling */
table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

table, th, td {
    border: 1px solid #ddd;
}

th, td {
    padding: 10px;
    text-align: center;
}

th {
    background-color: #43A047; /* Brighter green for table headers */
    color: white;
}

tr:nth-child(even) {
    background-color: #f2f2f2;
}

tr:hover {
    background-color: #e2e2e2;
}

/* Input fields in forms
input[type="number"] {
    width: 60px;
    padding: 5px;
    text-align: center;
}

input[type="submit"] {
    padding: 6px 12px;
    margin-top: 5px;
    cursor: pointer;
    background-color: #43A047; /* Matching green for buttons */
    /* color: white;
    border: none;
    border-radius: 5px;
}

input[type="submit"]:hover {
    background-color: #388E3C;
}

 */
.checkout{
    display: inline-block;
    padding: 10px 20px;
    background-color: #43A047;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    text-align: center;
    margin-top: 20px;
}

.checkout:hover {
    background-color: #388E3C;
}

</style>
<body>

<h2>Your Cart</h2>

<table>
    <tr>
        <th>Product Image</th>
        <th>Product Name</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Total</th>
        <th>Action</th>
    </tr>

    <?php while ($row = $result->fetch_assoc()): ?>
<tr>
    <td class="product-image"><img src="../<?php echo $row['image']; ?>" alt="<?php echo htmlspecialchars($row['name']); ?>"></td>
    <td><?php echo htmlspecialchars($row['name']); ?></td>
    <td><?php echo htmlspecialchars($row['price']); ?></td>
    <td><?php echo htmlspecialchars($row['quantity']); ?></td>
    <td><?php echo htmlspecialchars($row['total']); ?></td>
    <td>
        <!-- Form to update quantity or remove from cart -->
        <form action="update_cart.php" method="POST" style="display:inline;">
            <input type="hidden" name="cart_id" value="<?php echo htmlspecialchars($row['cid']); ?>">
            <input type="number" name="quantity" value="<?php echo htmlspecialchars($row['quantity']); ?>" min="1">
            <input type="submit" value="Update Quantity">
        </form>
        <form action="remove_from_cart.php" method="POST" style="display:inline;">
            <input type="hidden" name="cart_id" value="<?php echo htmlspecialchars($row['cid']); ?>">
            <input type="submit" value="Remove">
        </form>
        <!-- Redirect to buynow_cart.php with cart_id in the URL -->
        <a href="buynow_cart.php?cart_id=<?php echo htmlspecialchars($row['cid']); ?>" style="display:inline;">
            <button>Proceed to Checkout</button>
        </a>
    </td>
</tr>
<?php endwhile; ?>


</table>
</body>
<footer>
    <p>&copy; 2024 MediCare. All rights reserved.</p>
</footer>

</html>
