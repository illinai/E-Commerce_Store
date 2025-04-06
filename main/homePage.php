<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Home Page</title>
  <link rel="stylesheet" href="css/homePage.css" />
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
        <a href="homePage.php">Home</a>
        <a href="wishlist.html">Wishlist</a>
        <a href="cart.html">Cart</a>
        <a href="orders.html">Orders</a>
        <a href="reviews.html">Reviews</a>
        <a href="profile.html">Profile</a>
        <a href="logout.html">Logout</a>
      </div>
    </div>

    <div class="search-bar">
      <input type="text" id="searchInput" placeholder="Search by name..." />
    </div>

    <div class="right-buttons">
      <button class="liked-button">
        <a href="wishlist.html"><img src="icons/liked.png" alt="Likes" /></a>
      </button>
      <button class="cart-button">
        <a href="cart.html"><img src="icons/cart.png" alt="Cart" /></a>
      </button>
      <button id="profileButton" class="profile-button" onclick="toggleProfile()">
        <img src="icons/profile.png" alt="Profile" />
      </button>
      <div id="profileOpt" class="profileTab">
        <div class="profile-content">
          <?php if ($isLoggedIn): ?>
            <a href="profile.html">My Profile</a>
            <a href="logout.php">Logout</a>
          <?php else: ?>
            <a href="index.html">Sign In</a>
            <a href="register.html">Register</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </header>

  <!-- Main Banner -->
  <div class="main">
    <section id="banner">
      <div class="banner">
        <img src="imgs/banner3.png" alt="Banner" />
      </div>
    </section>

    <!-- Filter Bar -->
    <section class="filter-bar">
      <select id="categoryFilter">
        <option value="">All Categories</option>
        <option value="Jewelry">Jewelry</option>
        <option value="Home">Home</option>
        <option value="Art">Art</option>
      </select>
      <input type="text" id="tagFilter" placeholder="Search by tag" />
      <input type="number" id="minPrice" placeholder="Min Price" />
      <input type="number" id="maxPrice" placeholder="Max Price" />
    </section>

    <!-- Product Listings -->
    <section class="product-list">
      <h2>Available Products</h2>
      <div class="products" id="products-container"></div>
    </section>
  </div>

  <!-- Footer -->
  <footer>
    <nav>
      <a href="#">Stuff</a>
      <a href="#">Stuff</a>
      <a href="#">Stuff</a>
      <a href="#">Stuff</a>
    </nav>
  </footer>

  <!-- JavaScript -->
  <script>
    const isLoggedIn = <?php echo json_encode($isLoggedIn); ?>;

    const products = [
      { id: 1, name: "Handmade Earrings", price: 25, category: "Jewelry", tags: ["handmade", "accessory"], image: "imgs/earrings.jpg" },
      { id: 2, name: "Wooden Home Decor", price: 40, category: "Home", tags: ["wood", "decor"], image: "imgs/home-decor.jpg" },
      { id: 3, name: "Custom Art Print", price: 30, category: "Art", tags: ["print", "custom"], image: "imgs/art.jpg" }
    ];

    document.addEventListener("DOMContentLoaded", () => {
      const container = document.getElementById("products-container");
      const searchInput = document.getElementById("searchInput");

      function renderProducts(filtered) {
        container.innerHTML = filtered.length ? '' : "<p>No products found.</p>";
        filtered.forEach(product => {
          const card = document.createElement("div");
          card.className = "product-card";
          card.innerHTML = `
            <img src="${product.image}" alt="${product.name}">
            <h3 class="product-name">${product.name}</h3>
            <p class="product-price">$${product.price}</p>
            <button onclick="addToCart(${product.id})">Add to Cart</button>
            <button onclick="addToWishlist(${product.id})">♡ Add to Wishlist</button>
          `;
          container.appendChild(card);
        });
      }

      function applyFilters() {
        const search = searchInput.value.toLowerCase();
        const category = document.getElementById("categoryFilter").value;
        const tag = document.getElementById("tagFilter").value.toLowerCase();
        const min = parseFloat(document.getElementById("minPrice").value) || 0;
        const max = parseFloat(document.getElementById("maxPrice").value) || Infinity;

        const filtered = products.filter(p =>
          p.name.toLowerCase().includes(search) &&
          (!category || p.category === category) &&
          (!tag || p.tags.some(t => t.toLowerCase().includes(tag))) &&
          p.price >= min && p.price <= max
        );

        renderProducts(filtered);
      }

      renderProducts(products);
      searchInput.addEventListener("input", applyFilters);
      document.getElementById("categoryFilter").addEventListener("change", applyFilters);
      document.getElementById("tagFilter").addEventListener("input", applyFilters);
      document.getElementById("minPrice").addEventListener("input", applyFilters);
      document.getElementById("maxPrice").addEventListener("input", applyFilters);

      // Add to Cart
      window.addToCart = (id) => {
        if (!isLoggedIn) {
          alert("Please log in to add items to your cart.");
          window.location.href = "index.html";
          return;
        }

        const product = products.find(p => p.id === id);
        let cart = JSON.parse(localStorage.getItem("cart")) || [];
        const existing = cart.find(item => item.id === id);
        existing ? existing.quantity++ : cart.push({ ...product, quantity: 1 });
        localStorage.setItem("cart", JSON.stringify(cart));
        alert(`${product.name} added to cart!`);
      };

      // Add to Wishlist
      window.addToWishlist = (id) => {
        if (!isLoggedIn) {
          alert("Please log in to save items to your wishlist.");
          window.location.href = "index.html";
          return;
        }

        const product = products.find(p => p.id === id);
        let wishlist = JSON.parse(localStorage.getItem("wishlist")) || [];
        if (!wishlist.find(item => item.id === id)) {
          wishlist.push(product);
          localStorage.setItem("wishlist", JSON.stringify(wishlist));
          alert(`${product.name} added to wishlist!`);
        } else {
          alert(`${product.name} is already in your wishlist.`);
        }
      };

      window.toggleMenu = () => {
        const sidebar = document.getElementById("sidebar");
        sidebar.style.left = (sidebar.style.left === "0px") ? "-250px" : "0px";
      };

      window.toggleProfile = () => {
        const dropdown = document.getElementById("profileOpt");
        dropdown.style.display = dropdown.style.display === "flex" ? "none" : "flex";
      };

      document.addEventListener("click", (e) => {
        const dropdown = document.getElementById("profileOpt");
        const profileBtn = document.getElementById("profileButton");
        if (!dropdown.contains(e.target) && !profileBtn.contains(e.target)) {
          dropdown.style.display = "none";
        }
      });
    });
  </script>
</body>
</html>