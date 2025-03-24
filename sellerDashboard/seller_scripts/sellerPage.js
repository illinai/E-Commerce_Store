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

    // Initial load
    loadProducts();
});