<!-- UNREGISTERED USERS -->
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
    </style>
</head>
<body>
    <header class="header1">
        <div id="container">
            <div id="shopName">
                <h1>The Maker's Market</h1>
            </div>
        </div>
    </header>
    <header class="header2">
        <div class="menuTab">
            <button class="menu-button" onclick="toggleMenu()">
                <img src="icons/menu.png" alt="Menu" />
            </button>
            <div id="sidebar" class="menu-content">
                <button class="close-menu" onclick="toggleMenu()">×</button>
                <a href="public_home.php">Home</a>
                <a href="about.html">About</a>
                <a href="contact.html">Contact</a>
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
    <div class="main">
        <section id="banner">
            <div class="banner">
                <img src="imgs/banner3.png" alt="Banner" />
            </div>
        </section>
        <section class="product-list">
            <h2>Featured Products</h2>
            <div class="products" id="products-container">
                </div>
        </section>
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
                    `;
                    container.appendChild(card);
                });
            }

            function applyFilters() {
                const search = searchInput.value.toLowerCase();
                const filtered = products.filter(p => p.name.toLowerCase().includes(search));
                renderProducts(filtered);
            }

            renderProducts(products);
            searchInput.addEventListener("input", applyFilters);

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