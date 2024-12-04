<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'medico_shop');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add staff if form is submitted
if (isset($_POST['add_staff'])) {
    // Sanitize input
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone_number = $conn->real_escape_string($_POST['phone_number']);
    $password = $conn->real_escape_string($_POST['password']); // No hashing
    $position = $conn->real_escape_string($_POST['position']);
    $utype = $conn->real_escape_string($_POST['utype']);

    // Prepare the SQL statement with placeholders
    $stmt = $conn->prepare("INSERT INTO user (username, email, phone_number, password, position, utype) VALUES (?, ?, ?, ?, ?, ?)");
    
    if ($stmt) {
        $stmt->bind_param("ssssss", $name, $email, $phone_number, $password, $position, $utype);
        if ($stmt->execute()) {
            // Redirect to refresh the staff list
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "Error executing statement: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}

// Edit staff if form is submitted
if (isset($_POST['edit_staff'])) {
    $edit_id = $_POST['edit_id'];
    // Fetch the existing data for the selected staff member
    $stmt = $conn->prepare("SELECT * FROM user WHERE uid = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $staff_member = $result->fetch_assoc();
    $stmt->close();
}

// Update staff if update form is submitted
if (isset($_POST['update_staff'])) {
    $update_id = $_POST['update_id'];
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone_number = $conn->real_escape_string($_POST['phone_number']);
    $position = $conn->real_escape_string($_POST['position']);
    $utype = $conn->real_escape_string($_POST['utype']);

    // Prepare the update statement
    $stmt = $conn->prepare("UPDATE user SET username = ?, email = ?, phone_number = ?, position = ?, utype = ? WHERE uid = ?");
    if ($stmt) {
        $stmt->bind_param("sssssi", $name, $email, $phone_number, $position, $utype, $update_id);
        if ($stmt->execute()) {
            // Redirect to refresh the staff list
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "Error updating record: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}

// Delete staff if form is submitted
if (isset($_POST['delete_staff'])) {
    $delete_id = $_POST['delete_id'];
    $stmt = $conn->prepare("DELETE FROM user WHERE uid = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
    // Redirect to refresh the staff list
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch current staff
$result = $conn->query("SELECT * FROM user WHERE utype = 'staff'");

// Close connection (if you want to keep it open for future queries, remove this line)
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Management</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding:0;
            margin:0;
        }
        header {
            background-color: #333;
            color: white;
            padding: 20px;
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
            background-color: #444;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            margin-right: 10px;
            cursor: pointer;
        }
        button:hover {
            background-color: #666;
        }
        button.update {
            background-color: #337ab7;
        }
        button.delete {
            background-color: #d9534f;
        }
        img {
            max-width: 100px;
            height: auto;
        }
        form {
            margin-bottom: 20px;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        label {
            display: block;
            margin-bottom: 10px;
        }
        input, select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-group {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<nav>
<header>
    <h1>Staff Management</h1>
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
<section id="staff" class="panel">
    <h2>Manage Staff</h2>
    
    <!-- Form to Add Staff -->
    <form method="post" action="">
        <h3>Add Staff</h3>
        <div class="form-group">
            <label for="name">Staff Name:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="email">Staff Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="phone_number">Phone Number:</label>
            <input type="text" id="phone_number" name="phone_number" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="position">Position:</label>
            <input type="text" id="position" name="position" required>
        </div>
        <div class="form-group">
            <label for="utype">User Type:</label>
            <input type="text" id="utype" name="utype" required>
        </div>
        <button type="submit" name="add_staff">Add Staff</button>
    </form>

    <!-- Display Staff Table -->
    <h3>Current Staff</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Position</th>
                <th>User Type</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $result = $conn->query("SELECT * FROM user WHERE utype = 'staff'");
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['uid']}</td>
                    <td>{$row['username']}</td>
                    <td>{$row['email']}</td>
                    <td>{$row['phone_number']}</td>
                    <td>{$row['position']}</td>
                    <td>{$row['utype']}</td>
                    <td>
                        <form method='post' style='display:inline;'>
                            <input type='hidden' name='edit_id' value='{$row['uid']}'>
                            <button type='submit' name='edit_staff' class='update'>Edit</button>
                        </form>
                        <form method='post' style='display:inline;'>
                            <input type='hidden' name='delete_id' value='{$row['uid']}'>
                            <button type='submit' name='delete_staff' class='delete'>Delete</button>
                        </form>
                    </td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No staff members found.</td></tr>";
        }
        ?>
        </tbody>
    </table>

    <!-- Display update form if editing -->
    <?php if (isset($staff_member)): ?>
        <h3>Edit Staff</h3>
        <form method="post" action="">
            <input type="hidden" name="update_id" value="<?php echo $staff_member['uid']; ?>">
            <div class="form-group">
                <label for="name">Staff Name:</label>
                <input type="text" id="name" name="name" value="<?php echo $staff_member['username']; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Staff Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $staff_member['email']; ?>" required>
            </div>
            <div class="form-group">
                <label for="phone_number">Phone Number:</label>
                <input type="text" id="phone_number" name="phone_number" value="<?php echo $staff_member['phone_number']; ?>" required>
            </div>
            <div class="form-group">
                <label for="position">Position:</label>
                <input type="text" id="position" name="position" value="<?php echo $staff_member['position']; ?>" required>
            </div>
            <div class="form-group">
                <label for="utype">User Type:</label>
                <input type="text" id="utype" name="utype" value="<?php echo $staff_member['utype']; ?>" required>
            </div>
            <button type="submit" name="update_staff">Update Staff</button>
        </form>
    <?php endif;
     ?>
</section>
<?php
// Close the database connection at the very end
$conn->close();
?>
</body>
<footer>
        <p>&copy; 2024 Admin Dashboard</p>
    </footer>
</html>
