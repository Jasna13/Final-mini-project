<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "medico_shop"; // Replace with your actual database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch orders from the database
$sql = "SELECT 
            user.username AS Username, 
            orders.oid AS OrderID, 
            orders.product_name AS Medicine, 
            products.stock AS AvailableStock, 
            orders.quantity AS QuantityPurchased, 
            orders.price AS TotalPrice, 
            orders.status AS Status 
        FROM orders 
        JOIN user ON orders.uid = user.uid 
        JOIN products ON orders.product_name = products.name";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Order Management</h1>
        <nav>
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
        </nav>
    </header>

    <section id="orders" class="panel">
        <h2>View Orders</h2>
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Order ID</th>
                    <th>Medicine</th>
                    <th>Available Stock</th>
                    <th>Quantity Purchased</th>
                    <th>Total Price</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="order-list">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row["Username"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["OrderID"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["Medicine"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["AvailableStock"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["QuantityPurchased"]) . "</td>";
                        echo "<td>$" . htmlspecialchars($row["TotalPrice"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["Status"]) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No orders found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </section>

    <footer>
        <p>&copy; 2024 Admin Dashboard</p>
    </footer>
</body>
</html>

<?php
$conn->close();
?>
