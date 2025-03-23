document.addEventListener("DOMContentLoaded", () => {
    const loginForm = document.getElementById("loginForm");
    if (loginForm) {
        loginForm.addEventListener("submit", function (event) {
            event.preventDefault();

            // Collect form values
            const email = document.getElementById("email").value;
            const password = document.getElementById("password").value;

            // Perform basic client-side validation
            if (!email || !password) {
                alert("Please enter both email and password.");
                return;
            }

            // Perform a POST request to the server for authentication
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
});


    // Handle Registration
    const registerForm = document.getElementById("registerForm");
    if (registerForm) {
        registerForm.addEventListener("submit", function (event) {
            event.preventDefault(); // Prevent default form submission

            const firstName = document.getElementById("first_name").value;
            const lastName = document.getElementById("last_name").value;
            const email = document.getElementById("email").value;
            const password = document.getElementById("password").value;

            alert(`Account created successfully!\nFirst Name: ${firstName}\nLast Name: ${lastName}\nEmail: ${email}`);
            window.location.href = "dashboard.html"; // Redirect to dashboard
        });
    }
});

document.addEventListener("DOMContentLoaded", () => {
    const registerForm = document.getElementById("registerForm");

    if (registerForm) {
        registerForm.addEventListener("submit", function(event) {
            event.preventDefault(); // Prevent form submission

            // Get form field values
            const firstName = document.getElementById("first_name").value;
            const lastName = document.getElementById("last_name").value;
            const email = document.getElementById("email").value;
            const password = document.getElementById("password").value;

            // Basic client-side validation
            if (!firstName || !lastName || !email || !password) {
                document.getElementById("error-message").innerText = "Please fill out all required fields.";
                return;
            }

            const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            if (!email.match(emailPattern)) {
                document.getElementById("error-message").innerText = "Please enter a valid email address.";
                return;
            }

            // You can add more complex validation here (e.g., password strength)

            // If everything is valid, submit the form
            this.submit();  // This will submit the form
        });
    }
});
