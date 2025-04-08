<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Welcome to The Maker's Market</title>
  <link rel="stylesheet" href="css/homePage.css" />
  <style>
    .right-buttons {
      display: flex;
      gap: 10px;
    }
    .right-buttons button a {
      text-decoration: none;
      color: inherit;
    }
    .product-card p.guest-msg {
      font-style: italic;
      color: #555;
      margin-top: 10px;
    }
    .filter-bar {
      display: flex;
      gap: 10px;
      padding: 20px;
      justify-content: center;
    }
    .filter-bar input, .filter-bar select {
      padding: 6px 10px;
      border-radius: 6px;
      border: 1px solid #ccc;
    }
  </style>
</head>
<body>
  <!-- Title Header -->
  <header class="header1">
    <div id="container">
      <div id="shopName">
        <h1>The Maker's Market</h1>
      </div>
    </div>
  </header>

  <!-- Navigation -->
  <header class="header2">
    <div class="menuTab">
      <button class="menu-button" onclick="toggleMenu()">
        <img src="icons/menu.png" alt="Menu" />
      </button>
      <div id="sidebar" class="menu-content">
        <button class="close-menu" onclick="toggleMenu()">×</button>
        <a href="public_home.php">Home</a>
        <a href="about.php">About</a>
        <a href="contact.php">Contact</a>
        <a href="index.html">Sign In</a>
        <a href="register.html">Register</a>
      </div>
    </div>

    <div class="search-bar">
      <input type="text" id="searchInput" placeholder="Search by name..." />
    </div>

    <div class="right-buttons">
      <button class="profile-button" onclick="toggleProfile()">
        <img src="icons/profile.png" alt="Profile" />
      </button>
      <div id="profileOpt" class="profileTab">
        <div class="profile-content">
          <a href="index.html">Sign In</a>
          <a href="register.html">Register</a>
        </div>
      </div>
    </div>
  </header>

  <!-- Main Content -->
  <div class="main">
    <!-- Banner -->
    <section id="banner">
      <div class="banner">
        <img src="imgs/banner3.png" alt="Banner" />
      </div>
    </section>

    <!-- Filters -->
    <section class="filter-bar">
      <select id="categoryFilter">
        <option value="">All Categories</option>
        <option value="Jewelry">Jewelry</option>
        <option value="Home">Home</option>
        <option value="Art">Art</option>
      </select>
      <input type="text" id="tagFilter" placeholder="Tag..." />
      <input type="number" id="minPrice" placeholder="Min Price" />
      <input type="number" id="maxPrice" placeholder="Max Price" />
    </section>

    <!-- Products -->
    <section class="product-list">
      <h2>Featured Products</h2>
      <div class="products" id="products-container">
        <p>Loading products...</p>
      </div>
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

  <!-- Script -->
  <script>
    let products = [];

    document.addEventListener("DOMContentLoaded", () => {
      const container = document.getElementById("products-container");

      fetch("../backend/get_products_public.php")
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            products = data.products.map(p => ({
              ...p,
              tags: p.tags ? p.tags.split(",").map(t => t.trim()) : [],
              image: p.image || "imgs/default.png"
            }));
            renderProducts(products);
          } else {
            container.innerHTML = `<p>Error loading products: ${data.error}</p>`;
          }
        })
        .catch(err => {
          container.innerHTML = `<p>Failed to fetch products: ${err.message}</p>`;
        });

      function renderProducts(filtered) {
        container.innerHTML = filtered.length ? '' : "<p>No products found.</p>";
        filtered.forEach(product => {
          const card = document.createElement("div");
          card.className = "product-card";
          card.innerHTML = `
            <img src="${product.image}" alt="${product.name}">
            <h3 class="product-name">${product.name}</h3>
            <p class="product-price">$${product.price}</p>
            <p class="guest-msg">Login to add to cart or wishlist</p>
          `;
          container.appendChild(card);
        });
      }

      function applyFilters() {
        const search = document.getElementById("searchInput").value.toLowerCase();
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

      document.getElementById("searchInput").addEventListener("input", applyFilters);
      document.getElementById("categoryFilter").addEventListener("change", applyFilters);
      document.getElementById("tagFilter").addEventListener("input", applyFilters);
      document.getElementById("minPrice").addEventListener("input", applyFilters);
      document.getElementById("maxPrice").addEventListener("input", applyFilters);

      // Sidebar/profile toggle
      window.toggleMenu = () => {
        const sidebar = document.getElementById("sidebar");
        sidebar.style.left = sidebar.style.left === "0px" ? "-250px" : "0px";
      };

      window.toggleProfile = () => {
        const dropdown = document.getElementById("profileOpt");
        dropdown.style.display = dropdown.style.display === "flex" ? "none" : "flex";
      };

      document.addEventListener("click", e => {
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