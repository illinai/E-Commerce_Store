document.addEventListener("DOMContentLoaded", () => {
    const productsContainer = document.getElementById("products-container");
    const cartItems = document.getElementById("cart-items");
    const orderHistory = document.getElementById("order-history");

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

    // Add to cart function
    window.addToCart = (productId) => {
        const product = products.find(p => p.id === productId);
        cartItems.innerHTML = `<p>${product.name} - $${product.price}</p>`;
    };
});