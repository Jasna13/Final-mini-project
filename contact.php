<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "medico_shop";

// Establish database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch contact queries from the database
$sql = "SELECT c.id, c.name, c.email, c.message, c.submitted_at, u.username AS user_name
        FROM contact c
        LEFT JOIN user u ON c.user_id = u.uid
        ORDER BY c.submitted_at DESC"; // Fetch queries ordered by submission date
$result = $conn->query($sql);

// Check if the query is successful
if (!$result) {
    die("Query failed: " . $conn->error);
}

$queries = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $queries[] = $row;
    }
} else {
    $queries = []; // No queries found
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Queries | Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #333;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .message {
            font-size: 14px;
            word-wrap: break-word;
            white-space: pre-wrap;
        }

        .actions {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 8px 12px;
            background-color: #06782c;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .btn.delete {
            background-color: #dc3545;
        }

        .btn.delete:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

    <header>
        <h1>Contact Management</h1>
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

    <main>
        <h2>Contact Queries</h2>

        <?php if (count($queries) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Message</th>
                        <th>Submitted At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($queries as $query): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($query['id']); ?></td>
                            <td><?php echo htmlspecialchars($query['user_name'] ?: $query['name']); ?></td>
                            <td><?php echo htmlspecialchars($query['email']); ?></td>
                            <td class="message"><?php echo nl2br(htmlspecialchars($query['message'])); ?></td>
                            <td><?php echo date("Y-m-d H:i:s", strtotime($query['submitted_at'])); ?></td>
                            <td class="actions">
                                <!-- Action buttons (e.g., delete, mark as read) -->
                                <a href="delete_query.php?id=<?php echo $query['id']; ?>" class="btn delete">Delete</a>
                                <!-- You can add more actions if needed, like "Mark as Read" -->
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No queries submitted yet.</p>
        <?php endif; ?>

    </main>

    <footer>
        <p>&copy; 2024 MediCare. All rights reserved.</p>
    </footer>

</body>
</html>
