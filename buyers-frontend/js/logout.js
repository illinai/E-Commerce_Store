document.addEventListener("DOMContentLoaded", () => {
    const logoutButton = document.querySelector(".confirm-logout");

    logoutButton.addEventListener("click", () => {
        // Simulate logging out process
        alert("You have been logged out.");
        
        // Redirect to login page
        window.location.href = "login.html";
    });
});