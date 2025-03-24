document.addEventListener("DOMContentLoaded", () => {
    const nameInput = document.getElementById("name");
    const emailInput = document.getElementById("email");
    const phoneInput = document.getElementById("phone");
    const addressInput = document.getElementById("address");
    const saveBtn = document.querySelector(".save-btn");
    const profileUpload = document.getElementById("profile-upload");
    const profilePic = document.getElementById("profile-pic");
    const sidebar = document.getElementById("sidebar");

    // Load saved profile data from localStorage
    function loadProfile() {
        const profile = JSON.parse(localStorage.getItem("profile")) || {
            name: "John Doe",
            email: "johndoe@example.com",
            phone: "+1 234 567 8901",
            address: "123 Handmade St, Artisan City, CA",
            picture: "images/default-profile.png"
        };

        nameInput.value = profile.name;
        emailInput.value = profile.email;
        phoneInput.value = profile.phone;
        addressInput.value = profile.address;
        profilePic.src = profile.picture;
    }

    // Enable edit mode
    window.editProfile = () => {
        nameInput.disabled = false;
        emailInput.disabled = false;
        phoneInput.disabled = false;
        addressInput.disabled = false;
        saveBtn.disabled = false;
    };

    // Save profile changes
    window.saveProfile = () => {
        const profile = {
            name: nameInput.value,
            email: emailInput.value,
            phone: phoneInput.value,
            address: addressInput.value,
            picture: profilePic.src
        };

        localStorage.setItem("profile", JSON.stringify(profile));

        nameInput.disabled = true;
        emailInput.disabled = true;
        phoneInput.disabled = true;
        addressInput.disabled = true;
        saveBtn.disabled = true;

        alert("Profile updated successfully!");
    };

    // Handle profile picture upload
    profileUpload.addEventListener("change", (event) => {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                profilePic.src = e.target.result;
                localStorage.setItem("profile", JSON.stringify({ ...JSON.parse(localStorage.getItem("profile")), picture: e.target.result }));
            };
            reader.readAsDataURL(file);
        }
    });

    // Toggle sidebar menu
    window.toggleMenu = () => {
        if (sidebar.style.left === "0px") {
            sidebar.style.left = "-250px"; // Hide sidebar
        } else {
            sidebar.style.left = "0px"; // Show sidebar
        }
    };

    // Initial render
    loadProfile();
});