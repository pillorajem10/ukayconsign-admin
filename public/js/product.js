// PRODUCT LIST SNACK BAR
window.onload = function() {
    var successMessage = document.getElementById('success-message');
    var errorMessage = document.getElementById('error-message');

    if (successMessage) {
        setTimeout(function() {
            successMessage.style.display = 'none';
        }, 2000); // Hides after 2 seconds
    }

    if (errorMessage) {
        setTimeout(function() {
            errorMessage.style.display = 'none';
        }, 2000); // Hides after 2 seconds
    }
}


// ADD PRODUCT SNACK BAR
function showSnackbar(message) {
    const snackbar = document.getElementById("snackbar");
    snackbar.textContent = message;
    snackbar.className = "show";
    setTimeout(() => { snackbar.className = snackbar.className.replace("show", ""); }, 3000);
}

// Show snackbar messages if they exist
document.addEventListener('DOMContentLoaded', () => {
    if (window.messages.success) {
        showSnackbar(window.messages.success);
    }

    if (window.messages.error) {
        showSnackbar(window.messages.error);
    }
});
