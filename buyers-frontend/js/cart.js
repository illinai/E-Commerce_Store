document.addEventListener("DOMContentLoaded", () => {
    const cartContainer = document.getElementById("cart-container");
    const cartTotal = document.getElementById("cart-total");

    // Sample cart data
    let cartItems = [
        { id: 1, name: "Handmade Earrings", price: 25, image: "imgs/earrings.jpg" },
        { id: 2, name: "Wooden Home Decor", price: 40, image: "imgs/home-decor.jpg" }
    ];

    function updateCart() {
        cartContainer.innerHTML = ""; // Clear previous cart display
        let total = 0;

        if (cartItems.length === 0) {
            cartContainer.innerHTML = "<p>No items in cart.</p>";
            cartTotal.textContent = "0.00";
            return;
        }

        cartItems.forEach(item => {
            total += item.price;
            const cartCard = document.createElement("div");
            cartCard.classList.add("cart-card");
            cartCard.innerHTML = `
                <img src="${item.image}" alt="${item.name}">
                <h3>${item.name}</h3>
                <p>$${item.price.toFixed(2)}</p>
                <button onclick="removeFromCart(${item.id})">Remove</button>
            `;
            cartContainer.appendChild(cartCard);
        });

        cartTotal.textContent = total.toFixed(2);
    }

    window.removeFromCart = (itemId) => {
        cartItems = cartItems.filter(item => item.id !== itemId);
        updateCart();
    };

    window.checkout = () => {
        if (cartItems.length === 0) {
            alert("Your cart is empty!");
            return;
        }
        alert("Proceeding to checkout...");
        cartItems = [];
        updateCart();
    };

    updateCart();
});