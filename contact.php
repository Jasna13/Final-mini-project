<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "medico_shop";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Insert form data into the database
    $sql = "INSERT INTO contact (user_id, name, email, message) VALUES (NULL, '$name', '$email', '$message')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Message sent successfully!');</script>";
    } else {
        echo "<p class='error'>Error: " . $sql . "<br>" . $conn->error . "</p>";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - MediCare</title>
    <link rel="stylesheet" href="styles.css">
    <style>

        .contact-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 2em;
            color: #4CAF50;
            text-align: center;
            margin-bottom: 20px;
        }

        p {
            text-align: center;
            font-size: 1.1em;
            margin-bottom: 30px;
            color: #777;
        }

        .contact-form {
            display: flex;
            flex-direction: column;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        .form-group input, 
        .form-group textarea {
            width: 100%;
            padding: 10px;
            font-size: 1em;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .form-group input:focus, 
        .form-group textarea:focus {
            border-color: #4CAF50;
            outline: none;
            box-shadow: 0 0 5px rgba(76, 175, 80, 0.3);
        }

        .submit-btn {
            padding: 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1.2em;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .submit-btn:hover {
            background-color: #45a049;
        }

        .success {
            color: #4CAF50;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .error {
            color: red;
            font-weight: bold;
            margin-bottom: 20px;
        }
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
    text-align: center;
    padding: 20px;
    background-color: #06782c;
    color: white;
    position: relative;
    bottom: 0;
    width: 100%;
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

    <div class="contact-container">
        <h1>Contact Us</h1>
        <p>If you have any questions or concerns, please feel free to reach out to us using the form below.</p>

        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="contact-form">
            <div class="form-group">
                <label for="name">Your Name</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="email">Your Email</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="message">Your Message</label>
                <textarea id="message" name="message" rows="5" required></textarea>
            </div>

            <button type="submit" class="submit-btn">Send Message</button>
        </form>
    </div>
    <footer>
        <p>&copy; 2024 MediCare. All rights reserved.</p>
    </footer>
</body>
</html>
