document.addEventListener("DOMContentLoaded", () => {
    // LOGIN HANDLER
    const loginForm = document.getElementById("loginForm");
    if (loginForm) {
        loginForm.addEventListener("submit", function (event) {
            event.preventDefault();

            const email = document.getElementById("email").value;
            const password = document.getElementById("password").value;

            if (!email || !password) {
                alert("Please enter both email and password.");
                return;
            }

            fetch("authenticate.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: `email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`,
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Login successful!");
                        window.location.href = "dashboard.html";
                    } else {
                        alert("Invalid email or password.");
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("An error occurred. Please try again later.");
                });
        });
    }

    // REGISTER HANDLER
    const registerForm = document.getElementById("registerForm");
    if (registerForm) {
        registerForm.addEventListener("submit", function (event) {
            event.preventDefault();

            const firstName = document.getElementById("first_name").value.trim();
            const lastName = document.getElementById("last_name").value.trim();
            const email = document.getElementById("email").value.trim();
            const password = document.getElementById("password").value;

            const errorDiv = document.getElementById("error-message");

            if (!firstName || !lastName || !email || !password) {
                errorDiv.innerText = "Please fill out all required fields.";
                return;
            }

            const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            if (!email.match(emailPattern)) {
                errorDiv.innerText = "Please enter a valid email address.";
                return;
            }

            // Success handling (AJAX or normal POST)
            alert(`Account created successfully!\nFirst Name: ${firstName}\nLast Name: ${lastName}\nEmail: ${email}`);
            registerForm.submit(); 
        });
    }
});