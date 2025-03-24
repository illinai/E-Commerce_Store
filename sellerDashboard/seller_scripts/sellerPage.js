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
        try {
            console.log('Loading products...');
            const response = await fetch('../backend/get_products.php');
            const responseText = await response.text();
            console.log('Raw product response:', responseText);
            
            let products;
            try {
                products = JSON.parse(responseText);
            } catch (e) {
                console.error('Failed to parse JSON:', e);
                return;
            }
            
            const tbody = document.querySelector('#productTable tbody');
            if (products.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5">No products found</td></tr>';
                return;
            }
            
            tbody.innerHTML = products.map(product => {
                const imageHtml = product.image 
                    ? `<img src="data:image/jpeg;base64,${product.image}" alt="${product.name}" width="100">`
                    : 'No image';
                    
                return `
                    <tr>
                        <td>${product.name}</td>
                        <td>${product.description}</td>
                        <td>$${product.price}</td>
                        <td>${imageHtml}</td>
                        <td><button onclick="deleteProduct(${product.id})">Delete</button></td>
                    </tr>
                `;
            }).join('');
            
            // Update dashboard counts
            document.getElementById('totalProducts').textContent = products.length;
        } catch (error) {
            console.error('Error loading products:', error);
        }
    }

    // Add product
    document.getElementById('productForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        console.log('Form submitted');
        
        const formData = new FormData();
        formData.append('name', document.getElementById('productName').value);
        formData.append('description', document.getElementById('productDescription').value);
        formData.append('price', document.getElementById('productPrice').value);
        
        // Log file information
        const imageFile = document.getElementById('productImage').files[0];
        console.log('Image file:', imageFile);
        formData.append('image', imageFile);
        
        formData.append('category_id', 1); // Default category ID
        
        try {
            console.log('Sending request to add_product.php...');
            const response = await fetch('../backend/add_product.php', {
                method: 'POST',
                body: formData
            });
            
            const responseText = await response.text();
            console.log('Raw response:', responseText);
            
            let result;
            try {
                result = JSON.parse(responseText);
            } catch (e) {
                console.error('Failed to parse JSON:', e);
                alert('Server returned invalid response: ' + responseText);
                return;
            }
            
            if (result.success) {
                alert('Product added successfully! ID: ' + result.product_id);
                document.getElementById('productForm').reset();
                loadProducts(); // Refresh product list
            } else {
                alert('Error: ' + (result.message || 'Unknown error'));
            }
        } catch (error) {
            console.error('Network or fetch error:', error);
            alert('Failed to add product: ' + error.message);
        }
    });

    // Delete product - Define this globally so it can be called from onclick
    window.deleteProduct = async function(productId) {
        if (!confirm('Are you sure you want to delete this product?')) {
            return;
        }
        
        try {
            const response = await fetch('../backend/delete_product.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ product_id: productId })
            });
            
            const result = await response.json();
            if (result.success) {
                alert('Product deleted successfully!');
                loadProducts(); // Refresh product list
            } else {
                alert('Error: ' + result.message);
            }
        } catch (error) {
            console.error('Error deleting product:', error);
            alert('Failed to delete product: ' + error.message);
        }
    };

    // Load orders
    async function loadOrders() {
        try {
            console.log('Loading orders...');
            const response = await fetch('../backend/get_orders.php');
            const responseText = await response.text();
            console.log('Orders raw response:', responseText);
            
            let orders;
            try {
                orders = JSON.parse(responseText);
            } catch (e) {
                console.error('Failed to parse orders JSON:', e);
                return;
            }
            
            const tbody = document.querySelector('#orderTable tbody');
            if (!orders || orders.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5">No orders found</td></tr>';
                return;
            }
            
            tbody.innerHTML = orders.map(order => `
                <tr>
                    <td>${order.id}</td>
                    <td>${order.name}</td>
                    <td>${order.quantity}</td>
                    <td>${order.status}</td>
                    <td><button onclick="updateOrderStatus(${order.id}, 'Shipped')">Mark as Shipped</button></td>
                </tr>
            `).join('');
            
            // Update dashboard counts
            document.getElementById('totalOrders').textContent = orders.length;
            
            // Calculate total revenue
            let revenue = 0;
            for (const order of orders) {
                revenue += parseFloat(order.quantity) * parseFloat(order.price || 0);
            }
            document.getElementById('totalRevenue').textContent = revenue.toFixed(2);
        } catch (error) {
            console.error('Error loading orders:', error);
        }
    }

    // Update order status
    window.updateOrderStatus = async function(orderId, status) {
        try {
            const response = await fetch('../backend/update_order.php', {
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
        } catch (error) {
            console.error('Error updating order status:', error);
            alert('Failed to update order: ' + error.message);
        }
    };

    // Load profile
    async function loadProfile() {
        try {
            console.log('Loading profile...');
            const response = await fetch('../backend/get_profile_seller.php');
            const responseText = await response.text();
            console.log('Profile raw response:', responseText);
            
            let profile;
            try {
                profile = JSON.parse(responseText);
            } catch (e) {
                console.error('Failed to parse profile JSON:', e);
                return;
            }
            
            if (profile && !profile.error) {
                document.getElementById('sellerName').value = profile.first_name || '';
                document.getElementById('sellerLastName').value = profile.last_name || '';

                // Display profile image
                if (profile.profile_img) {
                    document.getElementById('profileImagePreview').src = `data:image/jpeg;base64,${profile.profile_img}`;
                    document.getElementById('profileImagePreview').style.display = 'block';
                }
            } else {
                console.log('No profile data available or error occurred');
            }
        } catch (error) {
            console.error('Error loading profile:', error);
        }
    }

    // Edit profile
    document.getElementById('profileForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const formData = new FormData();
        formData.append('first_name', document.getElementById('sellerName').value);
        formData.append('last_name', document.getElementById('sellerLastName').value);
        
        const profileImageFile = document.getElementById('sellerImage').files[0];
        if (profileImageFile) {
            formData.append('profile_img', profileImageFile);
        }
        
        try {
            const response = await fetch('../backend/edit_profile.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            if (result.success) {
                alert('Profile updated successfully!');
                loadProfile(); // Refresh profile data
            } else {
                alert('Error: ' + (result.message || 'Unknown error'));
            }
        } catch (error) {
            console.error('Error updating profile:', error);
            alert('Failed to update profile: ' + error.message);
        }
    });

    // Initial load
    loadProducts();
    loadOrders();
    loadProfile();
});