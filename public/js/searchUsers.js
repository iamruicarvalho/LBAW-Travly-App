// const token = document.querySelector('meta[name="csrf-token"').content;
const searchUsers = document.getElementById('search-users');
const usersList = document.getElementById('users-list');

searchUsers.addEventListener('keyup', () => {
    const user = searchUsers.value.toLowerCase();      // convert input search to lower case
    console.log(user);
    search(user);
})

// AJAX request
let search = (user) => {
    const url = `/users/search?query=${user}`;
    fetch(url)
        .then(response => response.json())
        .then(data => {
            // console.log(data);
            displayUsers(data);
        })
        .catch(error => {
            console.log('Error: ', error);
        })
}

function displayUsers(users) {
    usersList.innerHTML = '';

    let searchInput = searchUsers.value.trim().toLowerCase()
    if (searchInput === '') {
        usersList.innerHTML = '';
        return;
    }

    if (users.length === 0) {
        // usersList.innerHTML = '<li id="users-not-found"> No users found </li>';
        const usersNotFound = document.createElement('li');
        usersNotFound.id = 'users-not-found';
        usersNotFound.textContent = "No users found";

        usersList.appendChild(usersNotFound);
        return;
    }

    users.forEach(user => {
        const userInfoListItem = document.createElement('li');
        userInfoListItem.id = 'user-info';
        const userProfileLink = document.createElement('a');
        userProfileLink.id = 'user-profile-link';
        const userUsername = document.createElement('p');
        userUsername.id = 'user-username';
        const userName = document.createElement('p');
        userName.id = 'user-name';

        userProfileLink.href = `/profile/show/${user.id}`;
        userUsername.textContent = user.username;
        userName.textContent = user.name_;

        usersList.appendChild(userInfoListItem);
        userInfoListItem.appendChild(userProfileLink);
        userProfileLink.appendChild(userUsername);
        userUsername.insertAdjacentElement('afterend', userName);
        userUsername.style.fontSize = '16px'
        userName.style.fontSize = '13px';
    })
}

// we can also try to do by adding a classname = "hide" to all the users
// when the username or name includes the input, we remove the class
// from that user