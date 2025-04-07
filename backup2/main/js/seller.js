document.addEventListener("DOMContentLoaded", function () {
    initNavigation();
    loadDashboardStats();
    loadProducts();
    setupProductForm();
    loadOrders();
});

function initNavigation() {
    const navLinks = document.querySelectorAll(".top-header nav ul li a");
    navLinks.forEach(link => {
        link.addEventListener("click", function (e) {
            e.preventDefault();
            navLinks.forEach(l => l.classList.remove("active"));
            this.classList.add("active");

            const targetId = this.getAttribute("href").substring(1);
            document.querySelectorAll("main section").forEach(section => {
                section.classList.add("hidden-section");
                if (section.id === targetId) {
                    section.classList.remove("hidden-section");
                }
            });

            if (targetId === 'orders') {
                loadOrders();
            }
        });
    });
}

async function loadDashboardStats() {
    try {
        const response = await fetch("../backend/get_seller_stats.php");
        const data = await response.json();
        if (!data.success) throw new Error(data.error || "Failed to load stats");

        document.getElementById("totalProducts").textContent = data.product_count || 0;
        document.getElementById("totalRevenue").textContent = data.total_sales?.toFixed(2) || "0.00";
        document.getElementById("totalOrders").textContent = data.order_count || 0;
    } catch (error) {
        console.error("Error loading dashboard stats:", error);
    }
}

async function loadProducts() {
    try {
        const response = await fetch("../../backend/get_products.php");
        const data = await response.json();
        if (!data.success) throw new Error(data.error || "Failed to load products");

        const products = data.products;
        const container = document.getElementById("productsGrid");
        container.innerHTML = "";

        if (products.length === 0) {
            container.innerHTML = '<div class="empty-state">No products added yet</div>';
            return;
        }

        products.forEach(product => {
            const card = document.createElement("div");
            card.className = "product-card";
            card.innerHTML = `
    <div class="product-info">
    <h3>${product.name}</h3>
    <p class="product-price">$${parseFloat(product.price).toFixed(2)}</p>
    <p>${product.description.substring(0, 60)}${product.description.length > 60 ? '...' : ''}</p>
    <p>Quantity: ${product.quantity}</p>
    <p>Tags: ${product.tags}</p>
    <div class="product-actions">
    <button class="btn-edit" onclick="editProduct(${product.id})">Edit</button>
    <button class="btn-delete" onclick="deleteProduct(${product.id})">Delete</button>
    </div>
    </div>`;
            container.appendChild(card);
        });
    } catch (error) {
        console.error("Error loading products:", error);
        document.getElementById("productsGrid").innerHTML = `<div class="empty-state">Error loading products: ${error.message}</div>`;
    }
}

function setupProductForm() {
    const form = document.getElementById("productForm");
    form.addEventListener("submit", async function (e) {
        e.preventDefault();

        const formData = new FormData(form);
        const feedback = document.getElementById("formFeedback");
        const submitBtn = form.querySelector("button[type='submit']");

        feedback.textContent = "";
        feedback.className = "feedback";
        submitBtn.disabled = true;
        submitBtn.textContent = "Processing...";

        try {
            const response = await fetch("../backend/add_product.php", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(Object.fromEntries(formData))
            });

            const result = await response.json();
            if (!result.success) {
                throw new Error(result.error || "Failed to add product");
            }

            feedback.textContent = "Product added successfully!";
            feedback.className = "feedback success";
            form.reset();
            loadProducts();
            loadDashboardStats();
        } catch (error) {
            feedback.textContent = error.message;
            feedback.className = "feedback error";
            console.error("Error:", error);
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = "Add Product";
        }
    });
}

window.deleteProduct = async function (productId) {
    if (!confirm("Are you sure you want to delete this product?")) return;

    try {
        const response = await fetch(`../backend/delete_product.php?id=${productId}`);
        const result = await response.json();
        if (!result.success) throw new Error(result.error || "Failed to delete product");

        alert("Product deleted successfully");
        loadProducts();
        loadDashboardStats();
    } catch (error) {
        alert(`Error: ${error.message}`);
        console.error("Error deleting product:", error);
    }
};

window.editProduct = function (productId) {
    alert("Edit functionality will be implemented soon for product #" + productId);
};

async function loadOrders() {
    try {
        const response = await fetch('../backend/get_products.php');
        const data = await response.json();
        const orders = data.orders;
        const ordersList = document.getElementById('ordersList');
        ordersList.innerHTML = '';

        if (!data.success) throw new Error(data.error || "Failed to load orders");

        if (orders.length === 0) {
            ordersList.innerHTML = '<div class="empty-state">No orders yet.</div>';
            return;
        }

        orders.forEach(order => {
            const orderDiv = document.createElement('div');
            orderDiv.className = 'order-item';
            orderDiv.innerHTML = `
    <h3>Order #${order.id}</h3>
    <p>User ID: ${order.user_id}</p>
    <p>Total: $${order.total.toFixed(2)}</p>
    <p>Status: ${order.order_status}</p>
    <p>Date: ${order.created_at}</p>
    <button onclick="updateOrderStatus(${order.id}, 'shipped')">Mark as Shipped</button>
    <button onclick="updateOrderStatus(${order.id}, 'returned')">Mark as Returned</button>
    `;
            ordersList.appendChild(orderDiv);
        });
    } catch (error) {
        console.error('Error loading orders:', error);
        document.getElementById('ordersList').innerHTML = `<div class="empty-state">Error loading orders: ${error.message}</div>`;
    }
}

async function updateOrderStatus(orderId, status) {
    try {
        const response = await fetch('../backend/update_order_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ order_id: orderId, status: status }),
        });

        const result = await response.json();
        if (result.success) {
            alert('Order status updated successfully');
            loadOrders();
        } else {
            alert('Failed to update order status');
        }
    } catch (error) {
        console.error('Error updating order status:', error);
        alert(`Error: ${error.message}`);
    }
}