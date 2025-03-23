document.addEventListener("DOMContentLoaded", () => {
  // Sidebar toggle
  window.toggleMenu = () => {
    const sidebar = document.getElementById("sidebar");
    sidebar.style.left = (sidebar.style.left === "0px") ? "-250px" : "0px";
  };

  // Product list
  const products = [
    { id: 1, name: "Handmade Earrings", price: 25, image: "imgs/earrings.jpg" },
    { id: 2, name: "Wooden Home Decor", price: 40, image: "imgs/home-decor.jpg" },
    { id: 3, name: "Custom Art Print", price: 30, image: "imgs/art.jpg" }
  ];

  const container = document.getElementById("products-container");

  // Render function
  function renderProducts(productList) {
    container.innerHTML = "";
    productList.forEach(product => {
      const card = document.createElement("div");
      card.className = "product-card";
      card.innerHTML = `
        <img src="${product.image}" alt="${product.name}">
        <h3 class="product-name">${product.name}</h3>
        <p class="product-price">$${product.price}</p>
        <button onclick="addToCart(${product.id})">Add to Cart</button>
      `;
      container.appendChild(card);
    });
  }

  // Initial render
  renderProducts(products);

  // Add to cart
  window.addToCart = (productId) => {
    const product = products.find(p => p.id === productId);
    if (!product) return;

    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    const existing = cart.find(item => item.id === productId);

    if (existing) {
      existing.quantity += 1;
    } else {
      cart.push({ ...product, quantity: 1 });
    }

    localStorage.setItem("cart", JSON.stringify(cart));
    alert(`${product.name} added to cart!`);
  };

  // Profile dropdown
  window.toggleProfile = () => {
    const dropdown = document.getElementById("profileOpt");
    dropdown.style.display = dropdown.style.display === "flex" ? "none" : "flex";
  };

  // Close profile dropdown on outside click
  document.addEventListener("click", (event) => {
    const dropdown = document.getElementById("profileOpt");
    const profileBtn = document.getElementById("profileButton");
    if (!profileBtn.contains(event.target) && !dropdown.contains(event.target)) {
      dropdown.style.display = "none";
    }
  });

  // ✅ SEARCH FUNCTIONALITY
  const searchInput = document.getElementById("searchInput");
  searchInput.addEventListener("input", (e) => {
    const keyword = e.target.value.toLowerCase();
    const filtered = products.filter(product =>
      product.name.toLowerCase().includes(keyword)
    );
    renderProducts(filtered);
  });
});