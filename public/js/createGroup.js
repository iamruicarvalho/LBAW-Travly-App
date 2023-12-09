$(function() {
    var selectedUsers = []; 

    function initializeAutocomplete() {
        $("#userSearch").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "/user/search", 
                    method: "GET",
                    data: { term: request.term },
                    success: function (data) {
                        var usernames = data.map(function (user) {
                            return { label: user.username, value: user.username, id: user.id };
                        });
                        response(usernames);
                    }
                });
            },
            minLength: 1,
            select: function(event, ui) {
                console.log("ui.item:", ui.item);
                selectedUsers.push({ id: ui.item.id, username: ui.item.label }); // Add selected user to the array
                console.log("Selected users:", selectedUsers);
            }
        });
    }

    initializeAutocomplete();

    $("#addUserBtn").click(function() {
        // Fetch CSRF token from meta tags
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        // Use a loop to handle multiple selected users
        if (selectedUsers.length > 0) { // Check if at least one user is selected
            selectedUsers.forEach(function(user) {
                $.ajax({
                    url: "/groups/create", // Update the URL to match your actual route for adding users
                    method: "POST",
                    data: {
                        userId: user.id,
                        name_: user.username,
                        _token: csrfToken
                    },
                    success: function(response) {
                        console.log("Server response:", response);

                        if (response.groupId && response.users && response.users.length > 0 && response.users[0].username) {
                            console.log("User added to the group successfully");

                            // Update the user list on the client side
                            updateUserList(response.users[0]);

                            // Assuming you have a container for the group ID, update it
                            updateGroupId(response.groupId);
                        } else {
                            console.error("Invalid server response. Missing group ID or user information.");
                        }
                    },
                    error: function(error) {
                        console.error("Error adding user to the group:", error);

                        // Log validation errors to the console
                        if (error.responseJSON && error.responseJSON.errors) {
                            console.error("Validation Errors:", error.responseJSON.errors);
                        }
                    }
                });
            });

            // Clear the selectedUsers array after adding users
            selectedUsers = [];
        } else {
            console.warn("No user selected. Please select a user before adding to the group.");
        }
    });

    function updateGroupId(groupId) {
        // Update the group ID in your UI or perform any necessary actions
        console.log("Group ID:", groupId);
    }

    function updateUserList(user) {
        // Assuming #selectedMembersList is the container for the user list
        let userList = $("#selectedMembersList");
    
        // Log the user information for debugging
        console.log("Adding user to the list:", user);
    
        // Use a unique identifier for each user list item
        let userListItemId = 'user_' + user.id;
    
        // Check if the user is already in the list
        if (!$("#" + userListItemId).length) {
            // Append the new user to the user list
            userList.append('<li id="' + userListItemId + '">' + user.username + '</li>');
    
            // Log a message after appending
            console.log("User added to the list successfully.");
    
            // Update the hidden input value with selected user IDs
            updateSelectedMembersInput(user.id);
        } else {
            console.warn("User is already in the list.");
        }
    }    
    
    
    function updateSelectedMembersInput(userId) {
        // Assuming #selectedMembers is the hidden input for selected member IDs
        let selectedMembersInput = $("#selectedMembers");
    
        // Get the current value and append the new user ID
        let currentValue = selectedMembersInput.val();
        let newValue = currentValue ? currentValue + ',' + userId : userId;
    
        // Update the hidden input value
        selectedMembersInput.val(newValue);
    
        // Log the updated value for debugging
        console.log("Selected Members Input Updated:", newValue);
    }
});




