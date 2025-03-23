document.addEventListener("DOMContentLoaded", function () {
    const navLinks = document.querySelectorAll(".top-header nav ul li a");
    const sections = document.querySelectorAll("main section");

    // Navigation link click handler
    navLinks.forEach(link => {
        link.addEventListener("click", function (event) {
            event.preventDefault(); // Prevent default anchor behavior

            // Remove 'active' class from all navigation links
            navLinks.forEach(nav => nav.classList.remove("active"));

            // Add 'active' class to the clicked link
            this.classList.add("active");

            // Get the target section ID from the link's href
            const targetSectionId = this.getAttribute("href").substring(1);

            // Hide all sections and show the target section
            sections.forEach(section => {
                if (section.id === targetSectionId) {
                    section.classList.add("active-section");
                    section.classList.remove("hidden-section");
                } else {
                    section.classList.remove("active-section");
                    section.classList.add("hidden-section");
                }
            });
        });
    });

    // Load products
    async function loadProducts() {
        const response = await fetch('backend/get_products.php'); // Fetch products
        const products = await response.json();
        const tbody = document.querySelector('#productTable tbody');
        tbody.innerHTML = products.map(product => `
            <tr>
                <td>${product.name}</td>
                <td>${product.description}</td>
                <td>$${product.price}</td>
                <td>${product.quantity}</td>
                <td><button onclick="deleteProduct(${product.id})">Delete</button></td>
            </tr>
        `).join('');
    }

    // Load orders
    async function loadOrders() {
        const response = await fetch('backend/get_orders.php'); // Fetch orders
        const orders = await response.json();
        const tbody = document.querySelector('#orderTable tbody');
        tbody.innerHTML = orders.map(order => `
            <tr>
                <td>${order.id}</td>
                <td>${order.name}</td>
                <td>${order.quantity}</td>
                <td>${order.status}</td>
                <td><button onclick="updateOrderStatus(${order.id}, 'Shipped')">Mark as Shipped</button></td>
            </tr>
        `).join('');
    }

    // Add product
    document.getElementById('productForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const productData = {
            name: document.getElementById('productName').value,
            description: document.getElementById('productDescription').value,
            price: document.getElementById('productPrice').value,
            image_url: document.getElementById('productImage').value,
            quantity: document.getElementById('productQuantity').value
        };
        const response = await fetch('backend/add_product.php', { // Add product
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(productData)
        });
        const result = await response.json();
        if (result.success) {
            alert('Product added successfully!');
            loadProducts(); // Refresh product list
        } else {
            alert('Error: ' + result.message);
        }
    });

    // Update order status
    async function updateOrderStatus(orderId, status) {
        const response = await fetch('backend/update_order.php', { // Update order
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ order_id: orderId, status: status })
        });
        const result = await response.json();
        if (result.success) {
            alert('Order status updated!');
            loadOrders(); // Refresh order list
        } else {
            alert('Error: ' + result.message);
        }
    }

    // Load profile
    async function loadProfile() {
        const response = await fetch('backend/get_profile_seller.php'); // Fetch profile
        const profile = await response.json();
        document.getElementById('sellerName').value = profile.first_name;
        document.getElementById('sellerLastName').value = profile.last_name; // Add this field to your HTML
        document.getElementById('sellerImage').value = profile.profile_image;
    }

    // Edit profile
    document.getElementById('profileForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const profileData = {
            first_name: document.getElementById('sellerName').value,
            last_name: document.getElementById('sellerLastName').value, // Add this field to your HTML
            profile_image: document.getElementById('sellerImage').value
        };
        const response = await fetch('backend/edit_profile.php', { // Update profile
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(profileData)
        });
        const result = await response.json();
        if (result.success) {
            alert('Profile updated successfully!');
        } else {
            alert('Error: ' + result.message);
        }
    });

    // Initial load
    loadProducts();
    loadOrders();
    loadProfile();
});