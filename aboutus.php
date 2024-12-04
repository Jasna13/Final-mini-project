<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | MediCare</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS stylesheet -->
    <style>
        /* General Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Arial', sans-serif;
    line-height: 1.6;
    background-color: #f4f4f4; /* Slightly lighter background for better contrast */
    color: #333;
}

/* Header Styling */
header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #06782c;
    padding: 15px 20px; /* Added padding for better spacing */
    color: white;
}

.logo h1 {
    font-size: 26px; /* Slightly larger logo font */
    font-weight: bold; /* Make the logo bold */
}

nav {
    display: flex;
}

nav a {
    color: white;
    text-decoration: none;
    margin-left: 20px;
    font-size: 16px;
    transition: color 0.3s; /* Smooth transition for hover effect */
}

nav a:hover {
    text-decoration: underline;
    color: #e0e0e0; /* Light color on hover */
}

/* Main Content Styling */
main {
    max-width: 800px;
    margin: 40px auto; /* More spacing from top */
    padding: 30px; /* Increased padding */
    background: white;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); /* Softer shadow */
}

/* Headings Styling */
h2 {
    color: #06782c;
    margin-bottom: 15px;
    font-size: 28px; /* Increased font size for headings */
    border-bottom: 2px solid #06782c; /* Underline effect */
    padding-bottom: 5px; /* Spacing below heading */
}

h3 {
    color: #333;
    margin: 20px 0 10px; /* Margin for spacing */
    font-size: 22px; /* Slightly larger sub-headings */
}

/* Paragraph Styling */
p {
    margin-bottom: 15px; /* Space between paragraphs */
    line-height: 1.8; /* Increased line height for readability */
}

/* List Styling */
ul {
    list-style-type: disc; /* Bullet points for lists */
    margin-left: 20px; /* Space to left for bullet points */
    padding-bottom: 15px; /* Space below the list */
}

ul li {
    margin-bottom: 10px; /* Space between list items */
}

/* Footer Styling */
footer {
    text-align: center;
    padding: 20px;
    background-color: #06782c;
    color: white;
    position: relative;
    bottom: 0;
    width: 100%;
}

footer p {
    margin: 0; /* No margin for footer text */
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
        <h2>About Us</h2>
        <p>Welcome to MediCare, your trusted online pharmacy where health meets convenience. Our mission is to provide high-quality medical products and services to our valued customers, ensuring their health and well-being.</p>
        
        <h3>Our Vision</h3>
        <p>At MediCare, we envision a world where everyone has access to essential healthcare products. We strive to bridge the gap between healthcare providers and patients by offering a wide range of medications and wellness products at competitive prices.</p>
        
        <h3>Our Values</h3>
        <ul>
            <li><strong>Integrity:</strong> We believe in honesty and transparency in all our operations.</li>
            <li><strong>Quality:</strong> We source our products from trusted manufacturers to ensure safety and effectiveness.</li>
            <li><strong>Customer Focus:</strong> Our customers are at the heart of everything we do.</li>
            <li><strong>Innovation:</strong> We are committed to continuously improving our services and adapting to the needs of our customers.</li>
        </ul>
        
        <h3>Meet Our Team</h3>
        <p>Our team consists of experienced professionals in pharmacy, healthcare, and customer service who are dedicated to providing you with the best possible experience.</p>
        
        <h3>Contact Us</h3>
        <p>If you have any questions or need further information, feel free to reach out to us through our <a href="contact.php">Contact Page</a>.</p>
    </main>

    <footer>
        <p>&copy; 2024 MediCare. All rights reserved.</p>
    </footer>
</body>
</html>
