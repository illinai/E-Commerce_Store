// adminDash.js

document.getElementById('searchInput').addEventListener('input', function() {
    const searchTerm = this.value.trim();

    if (searchTerm.length > 2) { 
        fetch(`search.php?term=${encodeURIComponent(searchTerm)}`)
            .then(response => response.json())
            .then(data => {
                displayResults(data);
            })
            .catch(error => {
                console.error('Error:', error);
            });
    } else {
        document.getElementById('resultsContainer').innerHTML = ''; 
    }
});

function displayResults(data) {
    const resultsContainer = document.getElementById('resultsContainer');
    resultsContainer.innerHTML = ''; // Clear previous results

    if (data.products.length > 0 || data.users.length > 0) {
        if (data.products.length > 0) {
            resultsContainer.innerHTML += '<h3>Products</h3>';
            data.products.forEach(product => {
                resultsContainer.innerHTML += `<div class="result-item">${product.name} - $${product.price}</div>`;
            });
        }

        if (data.users.length > 0) {
            resultsContainer.innerHTML += '<h3>Users</h3>';
            data.users.forEach(user => {
                resultsContainer.innerHTML += `<div class="result-item">${user.username} - ${user.email}</div>`;
            });
        }
    } else {
        resultsContainer.innerHTML = '<div class="result-item">No results found.</div>';
    }
}


document.getElementById('searchInput').addEventListener('input', function() {
        const searchTerm = this.value.trim();
    
        if (searchTerm.length > 2) { 
            fetch(`search.php?term=${encodeURIComponent(searchTerm)}`)
                .then(response => response.json())
                .then(data => {
            displayResults(data);
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        } else {
            document.getElementById('resultsContainer').innerHTML = ''; 
        }
    });
    
    function displayResults(data) {
        const resultsContainer = document.getElementById('resultsContainer');
        resultsContainer.innerHTML = ''; 
    
        if (data.products.length > 0 || data.users.length > 0) {
            if (data.products.length > 0) {
                resultsContainer.innerHTML += '<h3>Products</h3>';
                data.products.forEach(product => {
                    resultsContainer.innerHTML += `
                        <div class="result-item">
                            ${product.name} - $${product.price}
                            <button class="delete-button" data-product-id="${product.id}">Delete</button>
                        </div>`;
                });
            }
    
            if (data.users.length > 0) {
                resultsContainer.innerHTML += '<h3>Users</h3>';
                data.users.forEach(user => {
                    resultsContainer.innerHTML += `<div class="result-item">${user.username} - ${user.email}</div>`;
                });
            }
            } else {
            resultsContainer.innerHTML = '<div class="result-item">No results found.</div>';
        }

        document.querySelectorAll('.delete-button').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                deleteProduct(productId);
            });
        });
    }
    
    function deleteProduct(productId) {
        if (confirm('Are you sure you want to delete this product?')) {
            fetch('search.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `delete_product=true&product_id=${productId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert(data.message);
                    // Refresh the search results after deletion
                document.getElementById('searchInput').dispatchEvent(new Event('input'));
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    }


