<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact | MediCare</title>
  <link rel="stylesheet" href="styles.css">
  <script src="script.js" defer></script>
</head>
<body>
  <!-- Navbar -->
  <header>
    <div class="navbar">
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
    </div>
  </header>

  <!-- Contact Section -->
  <section class="contact-section">
    <h2>Contact Us</h2>
    <form id="contactForm">
      <label for="name">Name</label>
      <input type="text" id="name" name="name" required>
      <label for="email">Email</label>
      <input type="email" id="email" name="email" required>
      <label for="message">Message</label>
      <textarea id="message" name="message" required></textarea>
      <button type="submit" class="btn">Send Message</button>
    </form>
    <div id="messageStatus"></div>
  </section>

  <!-- Footer -->
  <footer>
    <p>&copy; 2024 MediCare. All rights reserved.</p>
  </footer>
</body>
</html>
