document.addEventListener('DOMContentLoaded', function() {
    // Initialize Chart.js
    let userChart = null;
    
    // Set up event listeners
    const dateRangeSelect = document.getElementById('dateRange');
    if (dateRangeSelect) {
        dateRangeSelect.addEventListener('change', function() {
            fetchUserStats(this.value);
        });
    }
    
    // Fetch initial data
    fetchUserStats(dateRangeSelect ? dateRangeSelect.value : '7days');
});

function fetchUserStats(range) {
    fetch(`get_user_stats.php?range=${range}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                renderUserChart(data.data);
            } else {
                console.error('Error fetching user stats:', data.error);
                document.getElementById('userChart').innerHTML = 
                    `<p class="error-message">Error loading chart data: ${data.error}</p>`;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('userChart').innerHTML = 
                `<p class="error-message">Error loading chart data: ${error.message}</p>`;
        });
}

function renderUserChart(data) {
    const ctx = document.getElementById('userChart').getContext('2d');
    
    // Format dates for display
    const formattedDates = data.map(item => {
        const date = new Date(item.date);
        return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
    });
    
    const counts = data.map(item => item.count);
    
    // Destroy existing chart if it exists
    if (window.userChart) {
        window.userChart.destroy();
    }
    
    // Create gradient fill
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(75, 192, 192, 0.6)');
    gradient.addColorStop(1, 'rgba(75, 192, 192, 0.1)');
    
    // Create new chart
    window.userChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: formattedDates,
            datasets: [{
                label: 'New User Registrations',
                data: counts,
                backgroundColor: gradient,
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                pointBackgroundColor: 'rgba(75, 192, 192, 1)',
                pointBorderColor: '#fff',
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.7)',
                    titleFont: {
                        size: 14
                    },
                    bodyFont: {
                        size: 14
                    },
                    callbacks: {
                        label: function(context) {
                            return `Registrations: ${context.raw}`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 12
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0,
                        stepSize: 1,
                        font: {
                            size: 12
                        }
                    },
                    grid: {
                        borderDash: [2, 4]
                    }
                }
            }
        }
    });
    
    // Update summary statistics
    updateSummaryStats(data);
}

function updateSummaryStats(data) {
    // Calculate total registrations in the period
    const totalRegistrations = data.reduce((sum, item) => sum + item.count, 0);
    
    // Find the day with most registrations
    let maxDay = { date: '', count: 0 };
    data.forEach(item => {
        if (item.count > maxDay.count) {
            maxDay = item;
        }
    });
    
    // Format the peak date
    let peakDate = 'N/A';
    if (maxDay.date) {
        const date = new Date(maxDay.date);
        peakDate = date.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
    }
    
    // Calculate average daily registrations
    const avgRegistrations = totalRegistrations / data.length;
    
    // Find or create summary container
    let summaryContainer = document.querySelector('.stats-summary');
    if (!summaryContainer) {
        summaryContainer = document.createElement('div');
        summaryContainer.className = 'stats-summary';
        document.querySelector('.card').appendChild(summaryContainer);
    }
    
    // Update summary
    summaryContainer.innerHTML = `
        <div class="summary-item">
            <div class="summary-value">${totalRegistrations}</div>
            <div class="summary-label">Total Registrations</div>
        </div>
        <div class="summary-item">
            <div class="summary-value">${avgRegistrations.toFixed(1)}</div>
            <div class="summary-label">Avg. Daily</div>
        </div>
        <div class="summary-item">
            <div class="summary-value">${maxDay.count}</div>
            <div class="summary-label">Peak (${peakDate})</div>
        </div>
    `;
}

// Search functionality - maintain compatibility with adminDash.js
function performSearch() {
    const searchInput = document.getElementById('searchInput');
    const searchTerm = searchInput.value.trim();
    const searchType = document.getElementById('searchType').value;
    const resultsContainer = document.getElementById('resultsContainer');
    
    if (searchTerm === '') {
        resultsContainer.innerHTML = '<p>Please enter a search term</p>';
        return;
    }
    
    resultsContainer.innerHTML = '<p>Searching...</p>';
    
    // Determine which search endpoint to use based on search type
    const searchEndpoint = searchType === 'users' ? 'search_users.php' : 'search_products.php';
    
    // Send AJAX request
    fetch(`${searchEndpoint}?term=${encodeURIComponent(searchTerm)}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                resultsContainer.innerHTML = `<p class="error">${data.error}</p>`;
                return;
            }
            
            if (searchType === 'users') {
                if (data.users && data.users.length > 0) {
                    displayUserResults(data.users);
                } else {
                    resultsContainer.innerHTML = '<p>No users found</p>';
                }
            } else {
                if (data.products && data.products.length > 0) {
                    displayProductResults(data.products);
                } else {
                    resultsContainer.innerHTML = '<p>No products found</p>';
                }
            }
        })
        .catch(error => {
            console.error('Error searching:', error);
            resultsContainer.innerHTML = '<p class="error">An error occurred while searching</p>';
        });
}

// Hook up search button
document.addEventListener('DOMContentLoaded', function() {
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
});

// Toggle menu function
function toggleMenu() {
    document.querySelector('.menu-content').classList.toggle('show');
}