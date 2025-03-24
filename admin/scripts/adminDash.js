// adminDash.js

document.getElementById('searchInput').addEventListener('input', function() {
    const searchTerm = this.value.trim();

    if (searchTerm.length > 2) { // Only search if the term is longer than 2 characters
        fetch(`search.php?term=${encodeURIComponent(searchTerm)}`)
            .then(response => response.json())
            .then(data => {
                displayResults(data);
            })
            .catch(error => {
                console.error('Error:', error);
            });
    } else {
        document.getElementById('resultsContainer').innerHTML = ''; // Clear results if the search term is too short
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


