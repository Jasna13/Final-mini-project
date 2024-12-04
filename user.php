<?php
// Database connection
$servername = "localhost";
$username = "root"; // Change this to your database username
$password = ""; // Change this to your database password
$dbname = "medico_shop"; // The name of your database

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle blocking and unblocking users
if (isset($_GET['id']) && isset($_GET['action'])) {
    $user_id = $_GET['id'];
    $action = $_GET['action'];

    if ($action == 'block') {
        $sql = "UPDATE user SET is_blocked = 1 WHERE uid = $user_id";
    } elseif ($action == 'unblock') {
        $sql = "UPDATE user SET is_blocked = 0 WHERE uid = $user_id";
    }

    if ($conn->query($sql) === TRUE) {
        header("Location: user.php");
    } else {
        echo "Error: " . $conn->error;
    }
}

// Fetch all users
$sql = "SELECT * FROM user";
$result = $conn->query($sql);
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
        <h1>User Management</h1>
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
    <style>
    body {
        font-family: 'Helvetica Neue', Arial, sans-serif;
        background-color: #f9f9f9;
        margin: 0;
        padding: 0;
    }
    header {
        background-color: #333;
        color: white;
        padding: 20px;
        text-align: center;
        height:130px;
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
        /* display: inline; */
        /* margin-right: 20px; */
    }
    nav ul li a {
        color: white;
        text-decoration: none;
    }
    section {
        padding: 20px;
        max-width: 1200px;
        margin: 0 auto;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        background-color: #fff;
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
        background-color: #444;
        color: white;
    }
    tr:hover {
        background-color: #f1f1f1;
    }
    button {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    button:hover {
        background-color: #0056b3;
    }
    button.delete {
        background-color: #dc3545;
    }
    button.delete:hover {
        background-color: #c82333;
    }
    img {
        max-width: 80px;
        height: auto;
        border-radius: 5px;
    }
    form {
        margin-bottom: 20px;
        padding: 20px;
        background-color: #fff;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
    }
    .form-group {
        margin-bottom: 20px;
    }
    footer {
        text-align: center;
        padding: 10px;
        background-color: #444;
        color: white;
        position: relative;
        bottom: 0;
        width: 100%;
        margin-top: 20px;
    }

</style>
</head>
<body>

    <div class="container">
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>UID</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
    <?php while ($user = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $user['username']; ?></td>
            <td><?php echo $user['uid']; ?></td>
            <td><?php echo $user['email']; ?></td>
            <td><?php echo $user['phone_number']; ?></td>
            <td><?php echo $user['is_blocked'] ? 'Blocked' : 'Active'; ?></td>
            <td>
                <?php if ($user['is_blocked']): ?>
                    <a href="user.php?id=<?php echo $user['uid']; ?>&action=unblock" class="unblock">Unblock</a>
                <?php else: ?>
                    <a href="user.php?id=<?php echo $user['uid']; ?>&action=block" class="block">Block</a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endwhile; ?>
</tbody>
        </table>

        <?php
        // View user details if a user is selected
        if (isset($_GET['id'])) {
            $user_id = $_GET['id'];
            $sql = "SELECT * FROM users WHERE uid = $user_id";
            $result = $conn->query($sql);
            $user = $result->fetch_assoc();
            if ($user):
        ?>
        <h2>User Details</h2>
        <p><strong>Username:</strong> <?php echo $user['username']; ?></p>
        <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
        <p><strong>Phone Number:</strong> <?php echo $user['phone_number']; ?></p>
        <p><strong>Status:</strong> <?php echo $user['is_blocked'] ? 'Blocked' : 'Active'; ?></p>
        <a href="user_management.php" class="back">Back to Dashboard</a>
        <?php endif; } ?>

    </div>

    <?php $conn->close(); ?>

</body>
</html>
