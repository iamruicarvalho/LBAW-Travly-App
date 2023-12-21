document.addEventListener('DOMContentLoaded', function () {
    const addUserBtn = document.getElementById('addUserBtn');

    if (addUserBtn) {
        let selectedUserId;
        let userSearch;
        let groupid;

        function initializeAutocomplete() {
            userSearch = document.getElementById('search-users');
            const datalist = document.getElementById('usernames-list');

            userSearch.addEventListener('input', function () {
                const searchTerm = this.value;
                if (searchTerm.length >= 1) {
                    searchUsers(searchTerm);
                }
            });

            userSearch.addEventListener('change', function () {
                const selectedOption = datalist.querySelector(`option[value="${userSearch.value}"]`);
                if (selectedOption) {
                    selectedUserId = selectedOption.getAttribute('data-user-id');
                }
            });
        }

        function searchUsers(searchTerm) {
            fetch(`/user/search?term=${searchTerm}`)
                .then(response => response.json())
                .then(data => {
                    updateUserAutocomplete(data);
                })
                .catch(error => {
                    console.error('Error fetching user data:', error);
                });
        }

        function updateUserAutocomplete(usernames) {
            const datalist = document.getElementById('usernames-list');
            datalist.innerHTML = "";

            usernames.forEach(user => {
                const option = document.createElement('option');
                option.value = user.username;
                option.setAttribute('data-user-id', user.id);
                datalist.appendChild(option);
            });

            // Set the user ID as a property of the userSearch input
            userSearch.dataset.userId = usernames.length > 0 ? usernames[0].id : null;
        }

        function updateUsersList(users) {
            const userList = document.getElementById('users-list');
        
            // Clear the existing list
            userList.innerHTML = "";
        
            // Add the updated users to the list
            users.forEach(user => {
                const li = document.createElement('li');
                li.className = 'user-item';
                li.setAttribute('data-user-id', user.id);
                li.textContent = user.username;
        
                const removeLink = document.createElement('a');
                removeLink.href = `/groups/${groupid}/remove-user/${user.id}`;
                removeLink.className = 'remove-user';
                removeLink.textContent = 'Remove';
        
                // Append the new user item to the list
                userList.appendChild(li);
        
                // If the logged-in user is an owner and the user is not an owner, add the remove link
                if (user.is_owner !== true) {
                    li.appendChild(removeLink);
                }
            });
        }

        function addUserToGroup() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const dynamicUserId = userSearch.dataset.userId;
            console.log('dynamicUserId', dynamicUserId);

            if (dynamicUserId) {
                fetch(`/groups/${groupid}/details/add-user/${dynamicUserId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ userid: dynamicUserId })
                })
                    .then(response => response.json())
                    .then(response => {
                        if (response.error) {
                            console.error('Error adding user to the group:', response.error);
                        } else if (response.user && response.user.username) {
                            console.log('User added to the group successfully');
                            updateUsersList(response.users);
                        } else {
                            console.error('Invalid server response. Missing user information.');
                        }
                    })
                    .catch(error => {
                        console.error('Error adding user to the group:', error);
                    });
            } else {
                console.warn('No user selected. Please select a user before adding to the group.');
            }
        }

        function leaveGroup() {
            const userid = getUserId();
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            fetch(`/groups/${groupid}/leave`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    groupid: groupid,
                    userid: userid
                })
            })
                .then(response => response.json())
                .then(response => {
                    if (response.success) {
                        console.log('User left the group successfully');
                    } else {
                        console.error('Error leaving the group:', response.error);
                        alert(`Error: ${response.error}`);
                    }
                })
                .catch(error => {
                    console.error('Error leaving the group:', error);
                });
        }

        document.getElementById('addUserBtn').addEventListener('click', function () {
            groupid = document.getElementById('users-search-bar').dataset.groupid;
            addUserToGroup();
        });

        initializeAutocomplete();

        const leaveGroupBtn = document.getElementById('leaveGroupBtn');
        if (leaveGroupBtn) {
            leaveGroupBtn.addEventListener('click', leaveGroup);
        }
    }
});








