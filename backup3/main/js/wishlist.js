document.addEventListener("DOMContentLoaded", () => {
    const wishlistContainer = document.getElementById("wishlist-container");
    const sidebar = document.getElementById("sidebar");

    // Sample wishlist data (Load from localStorage or default)
    let wishlistItems = JSON.parse(localStorage.getItem("wishlist")) || [
        { id: 1, name: "Handmade Earrings", price: 25, image: "imgs/earrings.jpg" },
        { id: 2, name: "Wooden Home Decor", price: 40, image: "imgs/home-decor.jpg" },
        { id: 3, name: "Custom Art Print", price: 30, image: "imgs/art.jpg" }
    ];

    // Function to update the wishlist display
    function renderWishlist() {
        wishlistContainer.innerHTML = "";
        if (wishlistItems.length === 0) {
            wishlistContainer.innerHTML = `<p>No items in wishlist.</p>`;
            return;
        }
        wishlistItems.forEach(item => {
            const itemCard = document.createElement("div");
            itemCard.classList.add("wishlist-card");
            itemCard.innerHTML = `
                <img src="${item.image}" alt="${item.name}">
                <h3>${item.name}</h3>
                <p>$${item.price}</p>
                <button onclick="removeFromWishlist(${item.id})">Remove</button>
            `;
            wishlistContainer.appendChild(itemCard);
        });
    }

    // Remove item function
    window.removeFromWishlist = (itemId) => {
        wishlistItems = wishlistItems.filter(item => item.id !== itemId);
        localStorage.setItem("wishlist", JSON.stringify(wishlistItems)); // Save updated wishlist
        renderWishlist();
    };

    // Toggle sidebar menu function
    window.toggleMenu = () => {
        if (sidebar.style.left === "0px") {
            sidebar.style.left = "-250px"; // Hide sidebar
        } else {
            sidebar.style.left = "0px"; // Show sidebar
        }
    };

    // Initial render
    renderWishlist();
});