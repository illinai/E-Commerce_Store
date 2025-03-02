document.addEventListener("DOMContentLoaded", () => {
    const cartContainer = document.getElementById("cart-container");
    const cartTotal = document.getElementById("cart-total");
    const sidebar = document.getElementById("sidebar");

    // Load cart items from localStorage or default
    let cartItems = JSON.parse(localStorage.getItem("cart")) || [
        { id: 1, name: "Handmade Earrings", price: 25, image: "images/earrings.jpg" },
        { id: 2, name: "Wooden Home Decor", price: 40, image: "images/home-decor.jpg" },
    ];

    // Function to update cart display
    function renderCart() {
        cartContainer.innerHTML = "";
        if (cartItems.length === 0) {
            cartContainer.innerHTML = `<p>No items in cart.</p>`;
            cartTotal.textContent = "$0.00";
            return;
        }
        let total = 0;
        cartItems.forEach(item => {
            total += item.price;
            const itemCard = document.createElement("div");
            itemCard.classList.add("cart-card");
            itemCard.innerHTML = `
                <img src="${item.image}" alt="${item.name}">
                <h3>${item.name}</h3>
                <p>$${item.price}</p>
                <button onclick="removeFromCart(${item.id})">Remove</button>
            `;
            cartContainer.appendChild(itemCard);
        });
        cartTotal.textContent = `$${total.toFixed(2)}`;
    }

    // Remove item from cart
    window.removeFromCart = (itemId) => {
        cartItems = cartItems.filter(item => item.id !== itemId);
        localStorage.setItem("cart", JSON.stringify(cartItems)); // Save updated cart
        renderCart();
    };

    // Toggle sidebar menu
    window.toggleMenu = () => {
        if (sidebar.style.left === "0px") {
            sidebar.style.left = "-250px"; // Hide sidebar
        } else {
            sidebar.style.left = "0px"; // Show sidebar
        }
    };

    // Initial render
    renderCart();
});