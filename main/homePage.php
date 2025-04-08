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
<style>
.product-card { cursor: pointer; }
.modal {
display: none;
position: fixed;
z-index: 1000;
left: 0; top: 0;
width: 100%; height: 100%;
background-color: rgba(0, 0, 0, 0.6);
 }
.modal-content {
background: #fff;
color: black;
margin: 5% auto;
padding: 20px;
max-width: 600px;
border-radius: 10px;
text-align: center;
 }
.close-modal {
float: right;
font-size: 1.5rem;
cursor: pointer;
 }
#modalProductImage {
max-width: 100%;
height: auto;
margin-bottom: 15px;
border-radius: 8px;
 }
#modalShopName {
font-size: 1.3rem;
font-weight: bold;
color: black;
margin-bottom: 0.3rem;
 }
#modalPrice {
font-size: 1.2rem;
font-weight: bold;
margin: 0.5rem 0;
 }
#modalAddToCart {
margin-top: 15px;
padding: 10px 20px;
background-color: #f25f5c;
color: white;
border: none;
border-radius: 5px;
cursor: pointer;
 }
#modalAddToCart:hover {
background-color: #d94a47;
 }
#modalShopDesc {
font-size: 1rem;
color: #333;
 }
</style>
</head>
<body>
<!-- Header -->
<header class="header1">
<div id="container">
<div id="shopName"><h1>The Maker's Market</h1></div>
</div>
<a href="sellerDashboard.html" class="back-button">Switch to Seller's Page</a>
</header>
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
<a href="logout.php">Logout</a>
<a href="about.php">About</a>
<a href="contact.php">Contact</a>
</div>
</div>
<div class="search-bar">
<input type="text" id="searchInput" placeholder="Search by name..." />
</div>
<div class="right-buttons">
<button class="liked-button"><a href="wishlist.html"><img src="icons/liked.png" alt="Likes" /></a></button>
<button class="cart-button"><a href="cart.html"><img src="icons/cart.png" alt="Cart" /></a></button>
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
<!-- Main Content -->
<div class="main">
<section id="banner">
<div class="banner"><img src="imgs/banner3.png" alt="Banner" /></div>
</section>
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
<section class="product-list">
<h2>Available Products</h2>
<div class="products" id="products-container"></div>
</section>
</div>
<!-- Modal -->
<div id="productModal" class="modal">
<div class="modal-content">
<span class="close-modal" onclick="closeModal()">×</span>
<h2>Seller Shop Info</h2>
<h3 id="modalShopName"></h3>
<img id="modalProductImage" src="" alt="Product Image" />
<p id="modalPrice"></p>
<p id="modalShopDesc"></p>
<button id="modalAddToCart">Add to Cart</button>
</div>
</div>
<footer>
<nav>
<a href="#">Stuff</a>
<a href="#">Stuff</a>
<a href="#">Stuff</a>
<a href="#">Stuff</a>
</nav>
</footer>
<script>
// Define products as a global variable
let products = [];
let currentProduct = null;
const isLoggedIn = <?php echo json_encode($isLoggedIn); ?>;

// Add to Cart Function
function addToCart(id) {
    if (!isLoggedIn) {
        alert("Please log in to add items to your cart.");
        window.location.href = "index.html";
        return;
    }
    
    const product = products.find(p => parseInt(p.id) === parseInt(id));
    if (!product) {
        alert("Product not found! ID: " + id);
        console.error("Product not found with ID:", id);
        console.log("Available products:", products);
        return;
    }
    
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    const existing = cart.find(item => parseInt(item.id) === parseInt(id));
    if (existing) {
        existing.quantity++;
    } else {
        cart.push({ ...product, quantity: 1 });
    }
    localStorage.setItem("cart", JSON.stringify(cart));
    alert(`${product.name} added to cart!`);
}

// Add to Wishlist Function
function addToWishlist(id) {
    if (!isLoggedIn) {
        alert("Please log in to save items to your wishlist.");
        window.location.href = "index.html";
        return;
    }
    
    const product = products.find(p => parseInt(p.id) === parseInt(id));
    if (!product) {
        alert("Product not found! ID: " + id);
        console.error("Product not found with ID:", id);
        console.log("Available products:", products);
        return;
    }
    
    let wishlist = JSON.parse(localStorage.getItem("wishlist")) || [];
    const existing = wishlist.find(item => parseInt(item.id) === parseInt(id));
    if (!existing) {
        wishlist.push(product);
        localStorage.setItem("wishlist", JSON.stringify(wishlist));
        alert(`${product.name} added to wishlist!`);
    } else {
        alert(`${product.name} is already in your wishlist.`);
    }
}

// Toggle Menu Function
function toggleMenu() {
    const sidebar = document.getElementById("sidebar");
    sidebar.style.left = (sidebar.style.left === "0px") ? "-250px" : "0px";
}

// Toggle Profile Function
function toggleProfile() {
    const dropdown = document.getElementById("profileOpt");
    dropdown.style.display = dropdown.style.display === "flex" ? "none" : "flex";
}

// Show Product Modal Function
function showProductModal(product) {
    currentProduct = product;
    document.getElementById("modalShopName").textContent = product.shop_name || "Unknown Shop";
    document.getElementById("modalShopDesc").textContent = product.shop_description || "No description available.";
    document.getElementById("modalProductImage").src = product.image || "imgs/default.png";
    document.getElementById("modalPrice").textContent = `$${product.price}`;
    document.getElementById("productModal").style.display = "block";
}

// Close Modal Function
function closeModal() {
    document.getElementById("productModal").style.display = "none";
}

document.addEventListener("DOMContentLoaded", () => {
    const container = document.getElementById("products-container");
    
    // Fetch products
    fetch("../backend/get_products.php")
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Store products in the global variable
                products = data.products.map(p => ({
                    ...p,
                    tags: Array.isArray(p.tags) ? p.tags : (p.tags || "").split(",").map(t => t.trim()),
                    image: p.image_url || "imgs/default.png" // Note: changed from image to image_url based on your DB schema
                }));
                console.log("Products loaded:", products);
                renderProducts(products);
            } else {
                container.innerHTML = `<p>Error loading products: ${data.error}</p>`;
            }
        })
        .catch(err => {
            container.innerHTML = `<p>Failed to fetch products: ${err.message}</p>`;
            console.error("Failed to fetch products:", err);
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
                <button class="add-to-cart-btn" onclick="addToCart(${product.id})">Add to Cart</button>
                <button class="add-to-wishlist-btn" onclick="addToWishlist(${product.id})">♡ Add to Wishlist</button>
            `;
            card.addEventListener("click", (e) => {
                if (!e.target.matches("button")) {
                    showProductModal(product);
                }
            });
            container.appendChild(card);
        });
    }

    // Setup filter event listeners
    ["searchInput", "categoryFilter", "tagFilter", "minPrice", "maxPrice"].forEach(id =>
        document.getElementById(id)?.addEventListener("input", applyFilters)
    );

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

    // Add click event to modal add to cart button
    document.getElementById("modalAddToCart")?.addEventListener("click", () => {
        if (currentProduct) {
            addToCart(currentProduct.id);
            closeModal();
        }
    });

    // Close dropdown when clicking outside
    document.addEventListener("click", (e) => {
        const dropdown = document.getElementById("profileOpt");
        const profileBtn = document.getElementById("profileButton");
        if (dropdown && profileBtn && !dropdown.contains(e.target) && !profileBtn.contains(e.target)) {
            dropdown.style.display = "none";
        }
    });
});
</script>
</body>
</html>