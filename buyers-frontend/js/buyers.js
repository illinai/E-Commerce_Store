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

    // Replacing your placeholder
window.addToCart = (productId) => {
    fetch("../backend/add_to_cart.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ product_id: productId })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert("Product added to cart!");
        } else {
            alert("Failed to add to cart.");
        }
    })
    .catch(err => {
        console.error("Error adding to cart:", err);
    });
};
});