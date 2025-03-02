document.addEventListener("DOMContentLoaded", () => {
    const orderContainer = document.getElementById("order-container");
    const sidebar = document.getElementById("sidebar");

    // Sample order data
    let orders = JSON.parse(localStorage.getItem("orders")) || [
        { id: 101, name: "Handmade Earrings", price: 25, date: "2025-02-15", status: "Shipped", image: "images/earrings.jpg" },
        { id: 102, name: "Custom Art Print", price: 30, date: "2025-02-12", status: "Delivered", image: "images/art.jpg" },
        { id: 103, name: "Wooden Home Decor", price: 40, date: "2025-02-10", status: "Pending", image: "images/home-decor.jpg" }
    ];

    // Function to display orders
    function renderOrders() {
        orderContainer.innerHTML = "";
        if (orders.length === 0) {
            orderContainer.innerHTML = `<p>No past orders yet.</p>`;
            return;
        }
        orders.forEach(order => {
            const orderCard = document.createElement("div");
            orderCard.classList.add("order-card");
            orderCard.innerHTML = `
                <img src="${order.image}" alt="${order.name}">
                <h3>${order.name}</h3>
                <p>Order ID: ${order.id}</p>
                <p class="order-date">Order Date: ${order.date}</p>
                <p class="order-status">Status: ${order.status}</p>
                <button class="track-btn" onclick="trackOrder(${order.id})">Track Order</button>
            `;
            orderContainer.appendChild(orderCard);
        });
    }

    // Simulate order tracking
    window.trackOrder = (orderId) => {
        const order = orders.find(o => o.id === orderId);
        if (order) {
            alert(`Tracking Order #${orderId}\nStatus: ${order.status}`);
        }
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
    renderOrders();
});