const updateProfileForm = document.getElementById('update-profile-form');
const username = document.getElementById('username');



const validateInputs = () => {
    const usernameValue = username.value.trim();
    console.log("hello");

    const isUsernameUnique = !otherUsers.some(user => user.username === usernameValue);
    
    if (!isUsernameUnique) {
        setError(username, 'This username is already taken. Choose a different one.');
        // alert("This username was already taken by another user.\nChoose a different one.");
        // const usernameInputControl = element.parentElement;
        // const errorDisplay = inputControl.querySelector('.error');

        // errorDisplay.innerHTML = message;
        // usernameInputControl.classList.add('error');
        // usernameInputControl.classList.remove('success');
        // errorDisplay.style.display = "block";
        // errorDisplay.style.backgroundColor = "red";

    }
    else {
        setSuccess(username);
    }
};

const setError = (element, message) => {

    const usernameInputControl = element.parentElement;
    const errorDisplay = inputControl.querySelector('.repeated-username-error');

    errorDisplay.innerText = message;
    usernameInputControl.classList.add('error');
    usernameInputControl.classList.remove('success')
}

const setSuccess = (element) => {
    const usernameInputControl = element.parentElement;
    const errorDisplay = usernameInputControl.querySelector('.repeated-username-error');

    errorDisplay.innerText = '';
    usernameInputControl.classList.add('success');
    usernameInputControl.classList.remove('error');
};

updateProfileForm.addEventListener('submit', (e) => {
    validateInputs();
    e.preventDefault();
    
});



