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

  // Display products
  const container = document.getElementById("products-container");
  products.forEach(product => {
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

  window.addToCart = (productId) => {
    const product = products.find(p => p.id === productId);
    if (!product) return;
  
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
  
    const existingItem = cart.find(item => item.id === productId);
    if (existingItem) {
      existingItem.quantity += 1;
    } else {
      cart.push({ ...product, quantity: 1 }); // Includes name, image, price
    }
  
    localStorage.setItem("cart", JSON.stringify(cart));
    alert(`${product.name} added to cart!`);
  };

  // Profile dropdown toggle
  window.toggleProfile = () => {
    const dropdown = document.getElementById("profileOpt");
    dropdown.style.display = dropdown.style.display === "flex" ? "none" : "flex";
  };

  // Close profile dropdown when clicked outside
  document.addEventListener("click", (event) => {
    const dropdown = document.getElementById("profileOpt");
    const profileBtn = document.getElementById("profileButton");
    if (!profileBtn.contains(event.target) && !dropdown.contains(event.target)) {
      dropdown.style.display = "none";
    }
  });
});