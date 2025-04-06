<?php
session_start();

// If user confirmed logout, destroy session and redirect
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_logout'])) {
    session_unset();
    session_destroy();
    header("Location: index.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Logout</title>
  <link rel="stylesheet" href="css/homePage.css" />
  <style>
    .logout-section {
      max-width: 500px;
      margin: 100px auto;
      padding: 30px;
      background: white;
      border-radius: 12px;
      text-align: center;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.15);
    }

    .logout-section h2 {
      font-size: 1.8em;
      margin-bottom: 10px;
    }

    .logout-section p {
      font-size: 1em;
      margin-bottom: 20px;
      color: #555;
    }

    .logout-buttons {
      display: flex;
      justify-content: center;
      gap: 15px;
    }

    .confirm-logout,
    .cancel-logout {
      padding: 10px 25px;
      font-size: 1em;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      transition: 0.3s ease;
    }

    .confirm-logout {
      background-color: #ff6b6b;
      color: white;
    }

    .confirm-logout:hover {
      background-color: #ff4f4f;
    }

    .cancel-logout {
      background-color: #ccc;
      color: #333;
      text-decoration: none;
      display: inline-block;
      line-height: 2.5em;
    }

    .cancel-logout:hover {
      background-color: #bbb;
    }
  </style>
</head>
<body>
  <!-- Title Header Section -->
  <header class="header1">
    <h1>The Maker's Market</h1>
  </header>

  <!-- Navigation Header Section -->
  <header class="header2">
    <div class="menuTab">
      <button class="menu-button" onclick="toggleMenu()">
        <img src="icons/menu.png" alt="Menu" />
      </button>
      <div id="sidebar" class="menu-content">
        <button class="close-menu" onclick="toggleMenu()">×</button>
        <a href="homePage.php">Home</a>
        <a href="about.html">About Us</a>
        <a href="contact.html">Contact Us</a>
        <a href="wishlist.html">Wishlist</a>
        <a href="cart.html">Cart</a>
        <a href="orders.html">Orders</a>
        <a href="reviews.html">Reviews</a>
        <a href="profile.html">Profile</a>
        <a href="logout.php" class="active">Logout</a>
      </div>
    </div>

    <!-- Search Bar -->
    <div class="search-bar">
      <input type="text" placeholder="Search..." />
    </div>

    <div class="right-buttons">
      <button class="liked-button">
        <a href="wishlist.html"><img src="icons/liked.png" alt="Likes" /></a>
      </button>
      <button class="cart-button">
        <a href="cart.html"><img src="icons/cart.png" alt="Cart" /></a>
      </button>
      <button class="profile-button">
        <a href="profile.html"><img src="icons/profile.png" alt="Profile" /></a>
      </button>
    </div>
  </header>

  <!-- Logout Confirmation Section -->
  <section class="logout-section">
    <h2>Are you sure you want to log out?</h2>
    <p>You will be redirected to the login page after logging out.</p>
    <form method="POST">
      <div class="logout-buttons">
        <button type="submit" name="confirm_logout" class="confirm-logout">Yes, Logout</button>
        <a href="profile.html" class="cancel-logout">Cancel</a>
      </div>
    </form>
  </section>

  <script>
    function toggleMenu() {
      const sidebar = document.getElementById("sidebar");
      sidebar.style.left = (sidebar.style.left === "0px") ? "-250px" : "0px";
    }
  </script>
</body>
</html>