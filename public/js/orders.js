function showLoading() {
    document.getElementById('orderPageloadingOverlay').style.display = 'flex';
}

// Function to toggle the quantity field to editable mode
// Function to toggle the quantity field to editable mode
function editQuantity(index, orderId, cartId) {
    // Hide the current quantity display and show the input
    document.getElementById('quantity-display-' + orderId + '-' + cartId).style.display = 'none';
    document.getElementById('quantity-input-' + orderId + '-' + cartId).style.display = 'inline-block';
    
    // Hide the Edit button and show the Save and Cancel buttons
    document.getElementById('edit-button-' + orderId + '-' + cartId).style.display = 'none';
    document.getElementById('save-button-' + orderId + '-' + cartId).style.display = 'inline-block';
    document.getElementById('cancel-button-' + orderId + '-' + cartId).style.display = 'inline-block';
}

// Function to cancel the edit and revert to the original quantity
function cancelEdit(index, orderId, cartId) {
    // Get the original quantity value from the display
    var originalQuantity = document.getElementById('quantity-display-' + orderId + '-' + cartId).textContent;

    // Revert the input to the original quantity and hide the input field
    document.getElementById('quantity-input-' + orderId + '-' + cartId).value = originalQuantity;
    
    // Show the display span and hide the input field
    document.getElementById('quantity-display-' + orderId + '-' + cartId).style.display = 'inline-block';
    document.getElementById('quantity-input-' + orderId + '-' + cartId).style.display = 'none';
    
    // Hide the Cancel and Save buttons, and show the Edit button again
    document.getElementById('cancel-button-' + orderId + '-' + cartId).style.display = 'none';
    document.getElementById('save-button-' + orderId + '-' + cartId).style.display = 'none';
    document.getElementById('edit-button-' + orderId + '-' + cartId).style.display = 'inline-block';
}



// Function to save the edited quantity
function saveQuantity(orderId, cartId) {
    var quantity = document.getElementById('quantity-input-' + orderId + '-' + cartId).value;
    
    // Basic validation
    if (quantity <= -1 || isNaN(quantity)) {
        alert("Please enter a valid quantity.");
        return;
    }

    // Prepare data to send to the backend
    var updatedProduct = {
        cart_id: cartId,  // Pass the cart_id
        quantity: quantity,
        order_id: orderId
    };

    // Create FormData to send to backend
    var formData = new FormData();
    formData.append('updated_product', JSON.stringify(updatedProduct));
    formData.append('_token', window.Laravel.csrfToken); // Access the CSRF token from window.Laravel

    // Perform the AJAX request to update the order quantity
    fetch("/orders/update-quantity", {
        method: 'POST',
        body: formData
    })
    .then(() => {
        // On success, update the UI with the new quantity
        document.getElementById('quantity-display-' + orderId + '-' + cartId).textContent = quantity;
        document.getElementById('quantity-display-' + orderId + '-' + cartId).style.display = 'inline-block';
        document.getElementById('quantity-input-' + orderId + '-' + cartId).style.display = 'none';
        document.getElementById('edit-button-' + orderId + '-' + cartId).style.display = 'inline-block';
        document.getElementById('save-button-' + orderId + '-' + cartId).style.display = 'none';
        document.getElementById('cancel-button-' + orderId + '-' + cartId).style.display = 'none';
        
        // Optionally, you can reload the page or handle the response from Laravel (e.g., a success message)
        window.location.reload(); // This reloads the page to reflect any changes from the backend (e.g., updated price)
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Something went wrong!');
    });
}



