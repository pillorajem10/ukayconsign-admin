// Function to hide the success and error messages after 2 seconds
window.onload = function() {
    var successMessage = document.getElementById('success-message');
    var errorMessage = document.getElementById('error-message');

    if (successMessage) {
        setTimeout(function() {
            successMessage.style.display = 'none';
        }, 2000);
    }

    if (errorMessage) {
        setTimeout(function() {
            errorMessage.style.display = 'none';
        }, 2000);
    }
}

// Edit and Save button click event
document.querySelectorAll('.edit-button').forEach(button => {
    button.addEventListener('click', function() {
        const supplierId = this.dataset.id;
        const supplierCell = this.closest('tr').querySelector('.supplier-name-cell');
        const supplierName = supplierCell.querySelector('.supplier-name');
        const supplierInput = supplierCell.querySelector('.supplier-name-input');
        const saveButton = this.closest('tr').querySelector('.save-button');

        // Toggle visibility
        supplierName.style.display = 'none';
        supplierInput.style.display = 'block';
        this.style.display = 'none'; // Hide Edit button
        saveButton.style.display = 'inline-block'; // Show Save button
    });
});

document.querySelectorAll('.save-button').forEach(button => {
    button.addEventListener('click', function() {
        const supplierId = this.dataset.id;
        const supplierInput = this.closest('tr').querySelector('.supplier-name-input');
        const supplierName = this.closest('tr').querySelector('.supplier-name');
        
        const updatedName = supplierInput.value;

        fetch(`/suppliers/update/${supplierId}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.Laravel.csrfToken // Use the global CSRF token variable
            },
            body: JSON.stringify({ supplier_name: updatedName })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the supplier name display and toggle buttons
                supplierName.textContent = updatedName;
                supplierName.style.display = 'block';
                supplierInput.style.display = 'none';
                this.style.display = 'none'; // Hide Save button
                this.closest('tr').querySelector('.edit-button').style.display = 'inline-block'; // Show Edit button
            } else {
                alert('Failed to update supplier name.');
            }
        })
        .catch(error => console.error('Error:', error));
    });
});
