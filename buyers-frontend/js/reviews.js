
document.addEventListener("DOMContentLoaded", () => {
    const reviewForm = document.getElementById("review-form");
    const productSelect = document.getElementById("product");
    const reviewList = document.getElementById("review-list");
    const menuButton = document.querySelector(".menu-button");
    const closeButton = document.querySelector(".close-menu");

    // Ensure Menu Button Works (Now Works on Reviews Page)
    if (menuButton) {
        menuButton.addEventListener("click", toggleMenu);
    }
    if (closeButton) {
        closeButton.addEventListener("click", toggleMenu);
    }

    // Sample Purchased Products
    const purchasedProducts = [
        { id: 1, name: "Handmade Earrings" },
        { id: 2, name: "Wooden Home Decor" },
        { id: 3, name: "Custom Art Print" }
    ];

    // Populate Select Dropdown
    purchasedProducts.forEach(product => {
        const option = document.createElement("option");
        option.value = product.id;
        option.textContent = product.name;
        productSelect.appendChild(option);
    });

    // Handle Review Submission
    reviewForm.addEventListener("submit", (event) => {
        event.preventDefault();

        const selectedProduct = productSelect.value;
        const rating = document.querySelector("input[name='stars']:checked");
        const reviewText = document.getElementById("review-text").value;

        if (!selectedProduct || !rating || !reviewText.trim()) {
            alert("Please complete all fields.");
            return;
        }

        // Create Review Element
        const reviewItem = document.createElement("div");
        reviewItem.classList.add("review-item");
        reviewItem.innerHTML = `
            <h3>${purchasedProducts.find(p => p.id == selectedProduct).name}</h3>
            <p>Rating: ${rating.value} ⭐</p>
            <p>${reviewText}</p>
            <hr>
        `;

        // Add to Review List
        if (reviewList.textContent === "No reviews yet.") {
            reviewList.textContent = "";
        }
        reviewList.appendChild(reviewItem);

        // Clear Form
        reviewForm.reset();


    });
    
});

window.toggleMenu = () => {
    const sidebar = document.getElementById("sidebar");
    if (sidebar.style.left === "0px") {
        sidebar.style.left = "-250px"; // Hide menu
    } else {
        sidebar.style.left = "0px"; // Show menu
    }
};