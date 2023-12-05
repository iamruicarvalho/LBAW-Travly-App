// function showError(error, errorMessage) {
//     let errorElement = document.getElementsByClassName(error)[0];
//     errorElement.classList.add("display-error");
//     errorElement.innerHTML = errorMessage;
// }

// let updateProfileForm = document.getElementById("update-profile-form");

// updateProfileForm.addEventListener('submit', (e) => {
//     let username = updateProfileForm.username.value;
//     let isUsernameUnique = !allUsers.some(function(user) {
//         return user.username === username;
//     })

//     if (!isUsernameUnique) {
//         // display error message 
//         let error = "repeated-username-error";       
//         let errorMessage = "This username was already taken by another user. Choose a different one."
//         showError(error, errorMessage);
        
//         // prevent form submission
//         e.preventDefault(); 
//     }

// })

// -------------------------------------------------
// const updateProfileForm = document.getElementById("update-profile-form");
// const username = document.getElementById('username');
// const errorDisplay = updateProfileForm.querySelector('.repeated-username-error');


// updateProfileForm.addEventListener('submit', (e) => {
//     validateInputs();
//     e.preventDefault();
// });

// const setError = (message) => {
//     errorDisplay.innerHTML = message;
//     errorDisplay.classList.add('error');
//     errorDisplay.classList.remove('success');
// }

// const setSuccess = () => {
//     errorDisplay.innerHTML = '';
//     errorDisplay.classList.add('success');
//     errorDisplay.classList.remove('error');
// };

// const validateInputs = () => {
//     // const isUsernameUnique = !allUsers.some(function(user) {
//     //     return user.username === username.value;
//     // })

//     if (username.value === "") {
//         setError('This username was already taken by another user. Choose another one.');
//     } else {
//         setSuccess();
//     }
// };

// const isValidEmail = email => {
//     const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
//     return re.test(String(email).toLowerCase());
// }


const updateProfileForm = document.getElementById('update-profile-form');
const username = document.getElementById('username');

updateProfileForm.addEventListener('submit', (e) => {
    validateInputs();
    
    e.preventDefault();
});

const setError = (element, message) => {
    const usernameInputControl = element.parentElement;
    const errorDisplay = inputControl.querySelector('.repeated-username-error');

    errorDisplay.innerHTML = message;
    usernameInputControl.classList.add('error');
    usernameInputControl.classList.remove('success')
}

const setSuccess = (element) => {
    const usernameInputControl = element.parentElement;
    const errorDisplay = usernameInputControl.querySelector('.repeated-username-error');

    errorDisplay.innerHTML = '';
    usernameInputControl.classList.add('success');
    usernameInputControl.classList.remove('error');
};

const validateInputs = () => {
    const usernameValue = username.value.trim();

    if (usernameValue === 'hello') {
        // setError(username, 'Username is required');
        alert("hello");
    } 
    else {
        const isUsernameUnique = !otherUsers.some(user => user.username === usernameValue);
        
        if (!isUsernameUnique) {
            // setError(username, 'This username is already taken. Choose a different one.');
            alert("This username was already taken by another user.\nChoose a different one.");
        }
        else {
            setSuccess(username);
        }
    }
};
