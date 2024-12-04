<?php
session_start(); // Start the session

$host = "localhost";
$dbname = "medico_shop";
$username = "root";
$password = "";
$uid = $_SESSION['uid']; 

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['searchQuery'])) {
    $searchQuery = $_POST['searchQuery'];

    // Prepare SQL query to fetch products based on search query
    $stmt = $conn->prepare("SELECT id, name AS product_name, image AS product_image, price AS original_price, discounted_price FROM products WHERE name LIKE ? AND discounted_price > 0");
    $likeQuery = '%' . $searchQuery . '%';
    $stmt->bind_param("s", $likeQuery);
    $stmt->execute();
    $result = $stmt->get_result();

    $searchResults = array();
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $searchResults[] = $row;
        }
    }

    header('Content-Type: application/json'); // Set header for JSON response
    echo json_encode($searchResults); // Send JSON data
    exit(); // Exit to prevent any further code from executing after the response
}

// Check if logout successful
if (isset($_GET['logout']) && $_GET['logout'] === 'success') {
    echo '<script>alert("Logout successful!");</script>';
}

// Prepare for displaying discounted products
$sql = "SELECT id, name AS product_name, image AS product_image, price AS original_price, discounted_price FROM products WHERE discounted_price > 0";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchForm = document.getElementById('searchForm');
            const searchQueryInput = document.getElementById('searchQuery');
            const searchResultsDiv = document.getElementById('searchResults');

            // Add event listener to handle search input submission
            searchForm.addEventListener('submit', function (e) {
                e.preventDefault();
                const searchQuery = searchQueryInput.value.trim();
                
                if (searchQuery !== "") {
                    fetchSearchResults(searchQuery);
                }
            });

            // Function to fetch search results using AJAX
            function fetchSearchResults(query) {
                fetch('index1.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'searchQuery=' + encodeURIComponent(query)
                })
                .then(response => response.json())
                .then(data => displaySearchResults(data))
                .catch(error => {
                    console.error('Error fetching search results:', error);
                });
            }

            // Function to display search results
            function displaySearchResults(results) {
                searchResultsDiv.innerHTML = ""; // Clear previous results
                if (results.length > 0) {
                    results.forEach(product => {
                        const productElement = document.createElement('div');
                        productElement.classList.add('product-card');
                        productElement.innerHTML = `
                            <img src="../${product.product_image}" alt="${product.product_name}" />
                            <h3>${product.product_name}</h3>
                            <p><span class="original-price">₹${product.original_price}</span> ₹${product.original_price - product.discounted_price}</p>
                            <a href="product_details.php?id=${product.id}" class="btn view-details">View Details</a>
                        `;
                        searchResultsDiv.appendChild(productElement);
                    });
                } else {
                    searchResultsDiv.innerHTML = "<p>No results found.</p>";
                }
            }
        });
    </script>
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
                      <li><a href="login.php">Login</a></li>
                  <?php endif; ?>
              </ul>
          </nav>
      </div>
  </header>

  <!-- Search Section -->
  <section class="search-section-top">
      <form id="searchForm">
          <input type="text" id="searchQuery" placeholder="Search for a product..." required>
          <button type="submit" class="btn">Search</button>
      </form>
      <div id="searchResults"></div>
  </section>

  <!-- Home Section -->
  <section class="hero-section">
      <div class="hero-content">
          <h2>Your Health, Our Priority</h2>
          <p>Shop for all your medical needs at affordable prices</p>
          <a href="products.php" class="btn">Shop Now</a>
      </div>
  </section>

  <section class="discount-products-section">
      <h2>Discounted Products</h2>
      <div class="products-grid">
          <?php
          // Output data for each discounted product
          if ($result && $result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                  // Calculate the final price after discount
                  $finalPrice = number_format($row['original_price'] - $row['discounted_price'], 2);
                  echo "<div class='product-card'>
                          <img src='../" . htmlspecialchars($row['product_image'], ENT_QUOTES) . "' alt='" . htmlspecialchars($row['product_name'], ENT_QUOTES) . "'>
                          <h3>" . htmlspecialchars($row['product_name'], ENT_QUOTES) . "</h3>
                          <p><span class='original-price'>₹" . number_format($row['original_price'], 2) . "</span> ₹" . htmlspecialchars($finalPrice, ENT_QUOTES) . "</p>
                          <a href='product_details.php?id=" . urlencode($row['id']) . "' class='btn view-details'>View Details</a>
                        </div>";
              }
          } else {
              echo "<p>No discounted products available.</p>";
          }

          // Close the connection
          $conn->close();
          ?>
      </div>
  </section>
</body>
<footer>
    <p>&copy; 2024 MediCare. All rights reserved.</p>
</footer>
</html>
