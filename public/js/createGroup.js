document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('users-search-bar');
    const searchUsers = document.getElementById('search-users');
    const usersList = document.getElementById('users-list');
    const selectedMembersInput = document.getElementById('selectedMembers');

    searchForm.addEventListener('submit', (event) => {
        event.preventDefault();
    });

    searchUsers.addEventListener('keyup', () => {
        const user = searchUsers.value.toLowerCase();
        search(user);
    });

    let search = (user) => {
        const url = `/users/search?query=${user}`;
        fetch(url)
            .then(response => response.json())
            .then(data => {
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
            const usersNotFound = document.createElement('li');
            usersNotFound.id = 'users-not-found';
            usersNotFound.textContent = "No users found";

            usersList.appendChild(usersNotFound);
            return;
        }

        users.forEach(user => {
            const userInfoListItem = document.createElement('li');
            userInfoListItem.id = 'user-info';

            const addUserButton = document.createElement('button');
            addUserButton.textContent = 'Add';
            addUserButton.addEventListener('click', () => {
                addUserToSelectedList(user);
            });

            const userUsername = document.createElement('p');
            userUsername.id = 'user-username';
            const userName = document.createElement('p');
            userName.id = 'user-name';

            userUsername.textContent = user.username;
            userName.textContent = user.name_;

            usersList.appendChild(userInfoListItem);
            userInfoListItem.appendChild(addUserButton);
            userInfoListItem.appendChild(userUsername);
            userUsername.insertAdjacentElement('afterend', userName);
            userUsername.style.fontSize = '16px';
            userName.style.fontSize = '13px';
        });
    }

    function addUserToSelectedList(user) {
        const selectedMembersList = document.getElementById('selectedMembersList');

        const isUserAlreadySelected = [...selectedMembersList.children].some(li => li.dataset.userId === user.id.toString());

        if (!isUserAlreadySelected) {
            const selectedUserItem = document.createElement('li');
            selectedUserItem.textContent = user.username;
            selectedUserItem.dataset.userId = user.id;

            selectedMembersList.appendChild(selectedUserItem);
        }
    }

    // Hook into the form submission event
    const createGroupForm = document.querySelector('.group-create-container form');
    createGroupForm.addEventListener('submit', function(event) {
        // Update the form's hidden input with the selected users
        const selectedUserIds = [...selectedMembersList.children].map(li => li.dataset.userId);
        selectedMembersInput.value = JSON.stringify(selectedUserIds);

        // Submit the form programmatically
        createGroupForm.submit();
    });

    function getSelectedUserIds() {
        const selectedMembersList = document.getElementById('selectedMembersList');
        return [...selectedMembersList.children].map(li => li.dataset.userId);
    }
});



