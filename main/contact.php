<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Contact Us</title>
  <link rel="stylesheet" href="css/homePage.css" />
  <style>
    body {
      background-color: #f6f6f6;
      font-family: 'Segoe UI', sans-serif;
    }

    .contact-container {
      max-width: 600px;
      margin: 2rem auto;
      padding: 2rem;
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }

    .contact-container h2 {
      font-size: 1.75rem;
      color: #333;
      border-left: 5px solid #ff8c42;
      padding-left: 10px;
      margin-bottom: 1.5rem;
    }

    .contact-container label {
      display: block;
      font-weight: bold;
      margin-bottom: 0.5rem;
    }

    .contact-container input,
    .contact-container textarea {
      width: 100%;
      padding: 0.75rem;
      margin-bottom: 1.5rem;
      border: 1px solid #ccc;
      border-radius: 4px;
      font-size: 1rem;
    }

    .contact-container button {
      background-color: #ff8c42;
      color: white;
      border: none;
      padding: 0.75rem 1.5rem;
      font-size: 1rem;
      border-radius: 4px;
      cursor: pointer;
    }

    .contact-container button:hover {
      background-color: #e67e34;
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
          <a href="profile.html">Profile</a>
          <a href="logout.php">Logout</a>
          <a href="reviews.html">Reviews</a>
        <?php else: ?>
          <a href="public_home.php">Home</a>
          <a href="about.php">About</a>
          <a href="contact.php" class="active">Contact</a>
          <a href="index.html">Sign In</a>
          <a href="register.html">Register</a>
        <?php endif; ?>
      </div>
    </div>
  </header>

  <!-- Contact Section -->
  <main class="contact-container">
    <h2>Contact Us</h2>
    <form onsubmit="handleSubmit(event)">
      <label for="name">Name:</label>
      <input type="text" id="name" required />

      <label for="email">Email:</label>
      <input type="email" id="email" required />

      <label for="message">Message:</label>
      <textarea id="message" rows="5" required></textarea>

      <button type="submit">Send Message</button>
    </form>
  </main>

  <script>
    function toggleMenu() {
      const sidebar = document.getElementById("sidebar");
      sidebar.style.left = sidebar.style.left === "0px" ? "-250px" : "0px";
    }

    function handleSubmit(event) {
      event.preventDefault();
      alert("Thank you! Your message has been sent.");
      event.target.reset();
    }
  </script>
</body>
</html>