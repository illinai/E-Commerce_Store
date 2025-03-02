document.addEventListener("DOMContentLoaded", () => {
    // Handle Login
    const loginForm = document.getElementById("loginForm");
    if (loginForm) {
        loginForm.addEventListener("submit", function (event) {
            event.preventDefault(); // Prevent default form submission

            // Simulated authentication (replace with actual backend authentication)
            const username = document.getElementById("username").value;
            const password = document.getElementById("password").value;

            if (username === "user" && password === "1234") {
                alert("Login successful! Redirecting...");
                window.location.href = "dashboard.html";
            } else {
                alert("Invalid username or password.");
            }
        });
    }

    // Handle Registration
    const registerForm = document.getElementById("registerForm");
    if (registerForm) {
        registerForm.addEventListener("submit", function (event) {
            event.preventDefault(); // Prevent default form submission

            const newUsername = document.getElementById("newUsername").value;
            const newPassword = document.getElementById("newPassword").value;

            alert(`Account created successfully!\nUsername: ${newUsername}`);
            window.location.href = "dashboard.html"; // Redirect to dashboard
        });
    }
});