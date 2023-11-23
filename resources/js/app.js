import './bootstrap';

function redirectToHome() {
    // Perform any form validation if needed

    // Redirect to the home page
    window.location.href = '/home'; // Update the path as needed

    // Cancel the form submission to prevent it from being submitted normally
    return true;
}

