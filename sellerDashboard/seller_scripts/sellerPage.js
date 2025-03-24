document.getElementById('productForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    const feedbackElement = document.getElementById('productFormFeedback');
    feedbackElement.textContent = ''; // Clear previous messages

    const formData = new FormData();
    formData.append('name', document.getElementById('productName').value);
    formData.append('description', document.getElementById('productDescription').value);
    formData.append('price', document.getElementById('productPrice').value);
    const imageFile = document.getElementById('productImage').files[0];
    formData.append('image', imageFile);

    try {
        const response = await fetch('../backend/add_product.php', {
            method: 'POST',
            body: formData
        });
        const responseText = await response.text();

        let result;
        try {
            result = JSON.parse(responseText);
        } catch (e) {
            feedbackElement.textContent = 'Invalid response from server: ' + responseText;
            feedbackElement.style.color = 'red';
            return;
        }

        if (result.success) {
            feedbackElement.textContent = 'Product added successfully!';
            feedbackElement.style.color = 'green';
            document.getElementById('productForm').reset();
            loadProducts();
        } else {
            feedbackElement.textContent = 'Error: ' + result.message;
            feedbackElement.style.color = 'red';
        }
    } catch (error) {
        feedbackElement.textContent = 'Failed to add product: ' + error.message;
        feedbackElement.style.color = 'red';
    }
});