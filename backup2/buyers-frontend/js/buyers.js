document.addEventListener("DOMContentLoaded", () => {
    const productsContainer = document.getElementById("products-container");

    // Sample product data
    const products = [
        { id: 1, name: "Handmade Earrings", price: 25, image: "imgs/earrings.jpg" },
        { id: 2, name: "Wooden Home Decor", price: 40, image: "imgs/home-decor.jpg" },
        { id: 3, name: "Custom Art Print", price: 30, image: "imgs/art.jpg" }
    ];

    // Display products
    products.forEach(product => {
        const productCard = document.createElement("div");
        productCard.classList.add("product-card");
        productCard.innerHTML = `
            <img src="${product.image}" alt="${product.name}">
            <h3>${product.name}</h3>
            <p>$${product.price}</p>
            <button onclick="addToCart(${product.id})">Add to Cart</button>
        `;
        productsContainer.appendChild(productCard);
    });

    // Sidebar toggle function
    window.toggleMenu = () => {
        const sidebar = document.getElementById("sidebar");
        if (sidebar.style.left === "0px") {
            sidebar.style.left = "-250px"; // Hide menu
        } else {
            sidebar.style.left = "0px"; // Show menu
        }
    };

    // Add to cart function (for now, just a placeholder)
    window.addToCart = (productId) => {
        alert(`Added product ID: ${productId} to cart.`);
    };
});