document.addEventListener("DOMContentLoaded", () => {
    const editModal = document.getElementById("editModal");
    const closeModal = document.querySelector(".close");
    const editName = document.getElementById("edit-name");
    const editEmail = document.getElementById("edit-email");

    const profileName = document.getElementById("profile-name");
    const profileEmail = document.getElementById("profile-email");

    // Open modal
    window.editProfile = () => {
        editName.value = profileName.textContent;
        editEmail.value = profileEmail.textContent;
        editModal.style.display = "flex";
    };

    // Close modal
    closeModal.addEventListener("click", () => {
        editModal.style.display = "none";
    });

    // Save changes
    window.saveProfile = () => {
        profileName.textContent = editName.value;
        profileEmail.textContent = editEmail.value;
        editModal.style.display = "none";
    };

    // Close modal when clicking outside content
    window.onclick = (event) => {
        if (event.target === editModal) {
            editModal.style.display = "none";
        }
    };
});