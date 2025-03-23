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
                <td><button onclick="deleteProduct(${product.id})">Delete</button></td>
            </tr>
        `).join('');
    }

    // Add product
    document.getElementById('productForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        // Create FormData object to handle file uploads
        const formData = new FormData();
        formData.append('name', document.getElementById('productName').value);
        formData.append('description', document.getElementById('productDescription').value);
        formData.append('price', document.getElementById('productPrice').value);
        formData.append('image', document.getElementById('productImage').files[0]); // Handle file upload
        formData.append('category_id', 1); // Default category ID

        const response = await fetch('backend/add_product.php', {
            method: 'POST',
            body: formData // Send FormData instead of JSON
        });
        const result = await response.json();
        if (result.success) {
            alert('Product added successfully!');
            loadProducts(); // Refresh product list
        } else {
            alert('Error: ' + result.message);
        }
    });

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

    // Update order status
    async function updateOrderStatus(orderId, status) {
        const response = await fetch('backend/update_order.php', {
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
        document.getElementById('sellerLastName').value = profile.last_name;
        // Display profile image (if stored as binary data, you may need to convert it to a URL)
        if (profile.profile_img) {
            const imageUrl = URL.createObjectURL(new Blob([profile.profile_img]));
            document.getElementById('sellerImage').src = imageUrl;
        }
    }

    // Edit profile
    document.getElementById('profileForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        // Create FormData object to handle file uploads
        const formData = new FormData();
        formData.append('first_name', document.getElementById('sellerName').value);
        formData.append('last_name', document.getElementById('sellerLastName').value);
        formData.append('profile_image', document.getElementById('sellerImage').files[0]); // Handle file upload

        const response = await fetch('backend/edit_profile.php', {
            method: 'POST',
            body: formData // Send FormData instead of JSON
        });
        const result = await response.json();
        if (result.success) {
            alert('Profile updated successfully!');
            loadProfile(); // Refresh profile data
        } else {
            alert('Error: ' + result.message);
        }
    });

    // Initial load
    loadProducts();
    loadOrders();
    loadProfile();
});