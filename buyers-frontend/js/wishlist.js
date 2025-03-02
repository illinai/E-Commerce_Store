document.addEventListener("DOMContentLoaded", () => {
    const wishlistContainer = document.getElementById("wishlist-container");

    // Sample wishlist data
    const wishlistItems = [
        { id: 1, name: "Handmade Earrings", price: 25, image: "imgs/earrings.jpg" },
        { id: 2, name: "Wooden Home Decor", price: 40, image: "imgs/home-decor.jpg" },
        { id: 3, name: "Custom Art Print", price: 30, image: "imgs/art.jpg" }
    ];

    // Display wishlist items
    wishlistContainer.innerHTML = ""; // Clear default message
    wishlistItems.forEach(item => {
        const wishlistCard = document.createElement("div");
        wishlistCard.classList.add("wishlist-card");
        wishlistCard.innerHTML = `
            <img src="${item.image}" alt="${item.name}">
            <h3>${item.name}</h3>
            <p>$${item.price}</p>
            <button onclick="removeFromWishlist(${item.id})">Remove</button>
        `;
        wishlistContainer.appendChild(wishlistCard);
    });

    // Remove from wishlist function
    window.removeFromWishlist = (itemId) => {
        const updatedWishlist = wishlistItems.filter(item => item.id !== itemId);
        wishlistContainer.innerHTML = "<p>No items in wishlist.</p>"; // Reset if empty
        updatedWishlist.forEach(item => {
            const wishlistCard = document.createElement("div");
            wishlistCard.classList.add("wishlist-card");
            wishlistCard.innerHTML = `
                <img src="${item.image}" alt="${item.name}">
                <h3>${item.name}</h3>
                <p>$${item.price}</p>
                <button onclick="removeFromWishlist(${item.id})">Remove</button>
            `;
            wishlistContainer.appendChild(wishlistCard);
        });
    };
});