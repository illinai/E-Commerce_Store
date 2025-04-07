document.addEventListener("DOMContentLoaded", () => {
    const sidebar = document.getElementById("sidebar");

    // Function to handle logout confirmation
    window.confirmLogout = () => {
        alert("You have been logged out successfully.");
        localStorage.clear(); // Clear stored session data
        window.location.href = "login.html"; // Redirect to login page
    };

    // Toggle sidebar menu
    window.toggleMenu = () => {
        if (sidebar.style.left === "0px") {
            sidebar.style.left = "-250px"; // Hide sidebar
        } else {
            sidebar.style.left = "0px"; // Show sidebar
        }
    };
});