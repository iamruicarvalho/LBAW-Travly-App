import './bootstrap';

function redirectToHome() {
    // Perform any form validation if needed

    // Redirect to the home page
    window.location.href = '/home'; // Update the path as needed

    // Cancel the form submission to prevent it from being submitted normally
    return true;
}

document.querySelector('form').addEventListener('submit', function () {
    document.querySelector('[name="name"]').value = document.getElementById('name').innerText;
    document.querySelector('[name="description"]').value = document.getElementById('description').innerText;
    document.querySelector('[name="location"]').value = document.getElementById('location').innerText;
});