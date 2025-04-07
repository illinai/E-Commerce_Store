document.addEventListener('DOMContentLoaded', function() {
    // Menu toggle functionality
    /*const menuButton = document.querySelector('.menu-button');
    if (menuButton) {
        menuButton.addEventListener('click', function() {
            document.querySelector('.menu-content').classList.toggle('show');
        });
    }*/
    
    // Search functionality
    const searchButton = document.getElementById('searchButton');
    const searchInput = document.getElementById('searchInput');
    
    if (searchButton && searchInput) {
        searchButton.addEventListener('click', function() {
            performSearch();
        });
        
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                performSearch();
            }
        });
    }
    
    // Close menu when clicking outside
    /*document.addEventListener('click', function(event) {
        const menuContent = document.querySelector('.menu-content');
        if (menuContent && !menuButton.contains(event.target) && !menuContent.contains(event.target)) {
            menuContent.classList.remove('show');
        }
    });*/
    
});

function performSearch() {
    const searchInput = document.getElementById('searchInput');
    const searchTerm = searchInput.value.trim();
    const resultsContainer = document.getElementById('resultsContainer');
    
    if (searchTerm === '') {
        resultsContainer.innerHTML = '<p>Please enter a search term</p>';
        return;
    }
    
    resultsContainer.innerHTML = '<p>Searching...</p>';
    
    // Send AJAX request to search_users.php
    fetch(`search_users.php?term=${encodeURIComponent(searchTerm)}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                resultsContainer.innerHTML = `<p class="error">${data.error}</p>`;
                return;
            }
            
            if (data.users && data.users.length > 0) {
                displaySearchResults(data.users);
            } else {
                resultsContainer.innerHTML = '<p>No users found</p>';
            }
        })
        .catch(error => {
            console.error('Error searching users:', error);
            resultsContainer.innerHTML = '<p class="error">An error occurred while searching</p>';
        });
}

function displaySearchResults(users) {
    const resultsContainer = document.getElementById('resultsContainer');
    resultsContainer.innerHTML = '';
    
    users.forEach(user => {
        const userCard = document.createElement('div');
        userCard.className = 'user-card';
        
        const statusClass = user.ability === 'enabled' ? 'status-enabled' : 'status-disabled';
        
        userCard.innerHTML = `
            <h3>${user.first_name} ${user.last_name}</h3>
            <p><strong>Email:</strong> ${user.email}</p>
            <p><strong>Status:</strong> <span class="${statusClass}">${user.ability}</span></p>
            <form class="user-action-form" onsubmit="updateUser(event, ${user.id})">
                <div class="radio-group">
                    <label>
                        <input type="radio" name="action_${user.id}" value="enable" ${user.ability === 'enabled' ? 'checked' : ''}>
                        Enable
                    </label>
                    <label>
                        <input type="radio" name="action_${user.id}" value="disable" ${user.ability === 'disabled' ? 'checked' : ''}>
                        Disable
                    </label>
                    <label>
                        <input type="radio" name="action_${user.id}" value="delete">
                        Delete User
                    </label>
                </div>
                <input type="hidden" name="user_id" value="${user.id}">
                <button type="submit" class="action-button">Submit</button>
            </form>
        `;
        
        resultsContainer.appendChild(userCard);
    });
}

function updateUser(event, userId) {
    event.preventDefault();
    
    const form = event.target;
    const radioName = `action_${userId}`;
    const selectedAction = document.querySelector(`input[name="${radioName}"]:checked`).value;
    
    if (selectedAction === 'delete') {
        if (!confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
            return;
        }
    }
    
    const formData = new FormData();
    formData.append('user_id', userId);
    formData.append('action', selectedAction);
    
    fetch('update_user.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            alert(data.error);
            return;
        }
        
        if (data.success) {
            alert(data.message);
            // Refresh the search results
            performSearch();
        }
    })
    .catch(error => {
        console.error('Error updating user:', error);
        alert('An error occurred while updating the user');
    });
}