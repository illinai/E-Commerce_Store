document.addEventListener("DOMContentLoaded", () => {
    const orderList = document.getElementById("order-list");

    // Sample order data
    let orders = [
        {
            id: 1,
            name: "Handmade Earrings",
            price: 25,
            date: "March 1, 2025",
            status: "Shipped",
            tracking: "Track Order"
        },
        {
            id: 2,
            name: "Wooden Home Decor",
            price: 40,
            date: "February 28, 2025",
            status: "Delivered",
            tracking: "View Details"
        }
    ];

    function updateOrders() {
        orderList.innerHTML = "";

        if (orders.length === 0) {
            orderList.innerHTML = "<p>No past orders yet.</p>";
            return;
        }

        orders.forEach(order => {
            const orderCard = document.createElement("div");
            orderCard.classList.add("order-card");
            orderCard.innerHTML = `
                <h3>${order.name}</h3>
                <p><strong>Price:</strong> $${order.price.toFixed(2)}</p>
                <p class="order-date"><strong>Date:</strong> ${order.date}</p>
                <p class="order-status"><strong>Status:</strong> ${order.status}</p>
                <button class="track-btn">${order.tracking}</button>
            `;
            orderList.appendChild(orderCard);
        });
    }

    updateOrders();
});