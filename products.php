<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Products | MediCare</title>
  <link rel="stylesheet" href="styles.css">
  <style>
    /* Modal styles */
    .modal {
      display: none; 
      position: fixed; 
      z-index: 1; 
      left: 0;
      top: 0;
      width: 100%; 
      height: 100%; 
      overflow: auto; 
      background-color: rgb(0,0,0);
      background-color: rgba(0,0,0,0.9); 
      padding-top: 60px;
    }
    .modal-content {
      margin: auto;
      display: block;
      width: 80%; 
      max-width: 700px; 
    }
    .close {
      position: absolute;
      top: 15px;
      right: 35px;
      color: #fff;
      font-size: 40px;
      font-weight: bold;
    }
    .close:hover,
    .close:focus {
      color: #bbb;
      text-decoration: none;
      cursor: pointer;
    }

    /* Style for the strikethrough original price */
    .original-price {
      text-decoration: line-through; 
      color: red; 
      margin-right: 10px;
      font-size: 14px;
    }

    /* Style for the final discounted price */
    .final-price {
      font-weight: bold;
      color: green;
      font-size: 16px;
    }

    /* Style for the discount amount text */
    .final-price-discount {
      font-size: 14px;
      color: #ff5733;  
    }

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

    .product-image img {
      max-width: 150px;
      height: 150px;
      border-radius: 10px;
      object-fit: cover;
    }

    .product-info {
      flex-grow: 1;
      margin-left: 20px;
    }

    .product-info h3 {
      font-size: 22px;
      color: #333;
      margin-bottom: 10px;
    }

    footer {
      background-color: #06782c;
      margin-top: 20px;
      text-align: center;
      padding: 10px;
      color: white;
    }
    
    /* Product Card Styles */
    .product-card {
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      align-items: center;
      background-color: #fff;
      border-radius: 15px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      padding: 20px;
      max-width: 280px;
      margin: 20px;
      overflow: hidden;
      transition: transform 0.3s ease-in-out;
    }

    .product-card:hover {
      transform: scale(1.05);
    }

    .product-image img {
      max-width: 100%;
      height: auto;
      border-radius: 10px;
      object-fit: cover;
    }

    .product-info {
      text-align: center;
      padding: 10px;
    }

    .product-info h3 {
      font-size: 18px;
      color: #333;
      margin-bottom: 10px;
    }

    .product-info p {
      font-size: 14px;
      color: #555;
      margin-bottom: 20px;
    }

    button.view-details {
      padding: 10px 20px;
      background-color: #06782c;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    button.view-details:hover {
      background-color: #005d1d;
    }

    /* Section Styling */
    .category-section {
      text-align: center;
      margin: 30px 0;
    }

    .category-section h2 {
      font-size: 24px;
      margin-bottom: 15px;
    }

    #categorySelect {
      padding: 10px;
      font-size: 16px;
      border-radius: 5px;
      border: 1px solid #ccc;
    }

    /* Products grid layout */
    .products-grid {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
    }

    .products-grid .product-card {
      flex: 1 0 250px; /* Grow and shrink, but take up a minimum of 250px width */
      margin: 15px;
      box-sizing: border-box;
    }

  </style>
</head>
<body>
  <?php
  session_start(); 

  $isLoggedIn = isset($_SESSION['uid']); // Check if user is logged in


  // Navbar
  echo '<header><div class="navbar"><h1 class="logo">MediCare</h1><nav><ul>
    <li><a href="index1.php">Home</a></li>
    <li><a href="products.php">Products</a></li>
    <li><a href="aboutus.php">About Us</a></li>
    <li><a href="contact.php">Contact</a></li>
    <li><a href="Add_to_cart.php">Add to Cart</a></li>
    <li><a href="profile.php">Profile</a></li>';

    if ($isLoggedIn) {
        echo '<li><a href="logout.php">Logout</a></li>';
    } else {
        echo '<li><a href="http://localhost/Project/medicare/medicare-main/Login/login.php">Login</a></li>';
    }

    echo '</ul></nav></div></header>';

  // Database connection
  $servername = "localhost"; 
  $username = "root"; 
  $password = ""; 
  $dbname = "medico_shop"; 

  $conn = new mysqli($servername, $username, $password, $dbname);

  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }

  // Get category from query parameter
  $category = isset($_GET['category']) ? $conn->real_escape_string($_GET['category']) : 'all';
  if ($category === 'all') {
      $sql = "SELECT * FROM products";
  } else {
      $sql = "SELECT * FROM products WHERE category='$category'";
  }

  $result = $conn->query($sql);

  echo '<section class="category-section">
          <h2>Select Category</h2>
          <select id="categorySelect">
            <option value="all">All Products</option>
            <option value="Skin Care">Skin Care</option>
            <option value="Tabletes">Tabletes</option>
            <option value="Syrup">Syrup</option>
            <option value="Baby Products">Baby Products</option>
          </select>
        </section>';

  echo '<section class="products-section"><h2>Products</h2><div class="products-grid">';

  if ($result && $result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
          $actualPrice = $row['price'];
          $discountedPrice = isset($row['discounted_price']) && $row['discounted_price'] < $row['price'] ? $row['discounted_price'] : null;
          $finalPrice = $discountedPrice ? number_format($actualPrice - $discountedPrice, 2) : null;

          echo "<div class='product-card'>
                  <img src='../" . htmlspecialchars($row['image'], ENT_QUOTES) . "' alt='" . htmlspecialchars($row['name'], ENT_QUOTES) . "' class='product-image' />
                  <div class='product-info'>
                    <h3>" . htmlspecialchars($row['name'], ENT_QUOTES) . "</h3>
                    <p>";

          if ($finalPrice) {
              echo "<span class='original-price'>₹" . number_format($actualPrice, 2) . "</span>";
              echo "<span class='final-price'>₹" . $finalPrice . "</span>";
          } else {
              echo "<span class='final-price'>₹" . number_format($actualPrice, 2) . "</span>";
          }

          echo "<button class='btn view-details' onclick='viewDetails(\"" . htmlspecialchars($row['id'], ENT_QUOTES) . "\")'>View Details</button>
                </div>
              </div>";
      }
  } else {
      echo "<p>No products available.</p>";
  }

  $conn->close();

  echo '</div></section>';

  // Modal for image viewing
  echo '<div id="myModal" class="modal">
          <span class="close" onclick="closeModal()">&times;</span>
          <img class="modal-content" id="img01">
        </div>';

  // JavaScript for modal
  echo '<script>
          function viewDetails(productId) {
            window.location.href = "product_details.php?id=" + productId;
          }
          const categorySelect = document.getElementById("categorySelect");
          categorySelect.addEventListener("change", function() {
            window.location.href = "?category=" + categorySelect.value;
          });
        </script>';
  ?>

  <footer>
    <p>&copy; 2024 MediCare. All rights reserved.</p>
  </footer>
</body>
</html>
