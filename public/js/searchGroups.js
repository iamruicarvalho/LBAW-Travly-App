const searchGroupForm = document.getElementById('groups-search-bar');
const searchGroups = document.getElementById('search-groups');
const groupsList = document.getElementById('groups-list');

searchGroupForm.addEventListener('submit', (event) => {
    event.preventDefault();
});

searchGroups.addEventListener('keyup', () => {
    const groupQuery = searchGroups.value.toLowerCase();
    searchGroup(groupQuery);
})

let searchGroup = (groupQuery) => {
    const url = `/groups/search?query=${groupQuery}`;
    fetch(url)
        .then(response => response.json())
        .then(data => {
            displayGroups(data); 
        })
        .catch(error => {
            console.log('Error: ', error);
        });
}

function displayGroups(groups) {
    groupsList.innerHTML = '';

    let searchInput = searchGroups.value.trim().toLowerCase();
    if (searchInput === '') {
        groupsList.innerHTML = '';
        return;
    }

    if (groups.length === 0) {
        const groupsNotFound = document.createElement('li');
        groupsNotFound.id = 'groups-not-found';
        groupsNotFound.textContent = "No groups found";

        groupsList.appendChild(groupsNotFound);
        return;
    }

    groups.forEach(group => {
        const groupInfoListItem = document.createElement('li');
        const groupName = document.createElement('p');
        const groupDescription = document.createElement('p');
    
        groupName.className = 'group-name';
        groupDescription.className = 'group-description';
    
        groupName.textContent = group.name_;
        groupDescription.textContent = group.description_ || 'No description';

        groupsList.appendChild(groupInfoListItem);
        groupInfoListItem.appendChild(groupName);
        groupInfoListItem.appendChild(groupDescription);

        groupName.style.fontSize = '16px';
        groupDescription.style.fontSize = '13px';
    });

    
}