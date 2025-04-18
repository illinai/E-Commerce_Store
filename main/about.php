<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>About Us</title>
  <link rel="stylesheet" href="css/homePage.css" />
  <style>
    .content {
      max-width: 900px;
      margin: 2rem auto;
      padding: 2rem;
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
      font-family: 'Segoe UI', sans-serif;
    }

    .content section {
      margin-bottom: 2rem;
    }

    .content h2 {
      color: #333;
      border-left: 5px solid #ff8c42;
      padding-left: 10px;
      font-size: 1.5rem;
      margin-bottom: 0.5rem;
    }

    .content p {
      color: #555;
      line-height: 1.6;
      font-size: 1rem;
    }

    body {
      background-color: #f6f6f6;
    }
  </style>
</head>
<body>
  <!-- Title Header Section -->
  <header class="header1">
    <div id="container">
      <div id="shopName">
        <h1>The Maker's Market</h1>
      </div>
    </div>
  </header>

  <!-- Navigation Header Section -->
  <header class="header2">
    <div class="menuTab">
      <button class="menu-button" onclick="toggleMenu()">
        <img src="icons/menu.png" alt="Menu" />
      </button>
      <div id="sidebar" class="menu-content">
        <button class="close-menu" onclick="toggleMenu()">×</button>
        <?php if ($isLoggedIn): ?>
          <a href="homePage.php">Home</a>
          <a href="wishlist.html">Wishlist</a>
          <a href="cart.html">Cart</a>
          <a href="orders.html">Orders</a>
          <a href="reviews.html">Reviews</a>
          <a href="profile.html">Profile</a>
          <a href="logout.php">Logout</a>
        <?php else: ?>
          <a href="public_home.php">Home</a>
          <a href="index.html">Sign In</a>
          <a href="register.html">Register</a>
        <?php endif; ?>
        <a href="about.php" class="active">About</a>
        <a href="contact.php">Contact</a>
      </div>
    </div>
  </header>

  <!-- About Page Content -->
  <main class="content">
    <section>
      <h2>Who We Are</h2>
      <p>
        The Maker's Market is a platform designed to connect creative sellers with buyers who value handmade goods, personalized items, and one-of-a-kind creations.
      </p>
    </section>
    <section>
      <h2>Our Mission</h2>
      <p>
        We aim to empower artisans, small businesses, and creative entrepreneurs to reach a wide audience through a supportive and customizable online storefront.
        We believe in celebrating creativity, passion, and craftsmanship in every product we help bring to life.
      </p>
    </section>
  </main>

  <script>
    function toggleMenu() {
      const sidebar = document.getElementById("sidebar");
      sidebar.style.left = sidebar.style.left === "0px" ? "-250px" : "0px";
    }
  </script>
</body>
</html>