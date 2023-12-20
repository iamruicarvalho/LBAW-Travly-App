// const token = document.querySelector('meta[name="csrf-token"]').content;
const searchForm = document.getElementById('posts-search-bar');
const searchPosts = document.getElementById('search-posts');
const postsList = document.getElementById('posts-list');

searchForm.addEventListener('submit', (event) => {
    event.preventDefault();
});

searchPosts.addEventListener('keyup', () => {
    const postQuery = searchPosts.value.trim();
    if (postQuery) {
        search(postQuery);
    } else {
        postsList.innerHTML = '';
    }
});

let search = (postQuery) => {
    const url = `/posts/search?query=${postQuery}`;
    fetch(url)
        .then(response => response.json())
        .then(data => displayPosts(data))
        .catch(error => {
            console.error('Search Error:', error);
            postsList.textContent = 'An error occurred while searching.';
        });
}

function displayPosts(posts) {
    postsList.innerHTML = '';

    if (posts.length === 0) {
        postsList.textContent = "No posts found";
        return;
    }

    posts.forEach(post => {
        const postInfoListItem = document.createElement('li');
        appendElement(postInfoListItem, 'p', post.content_, 'post-content');
        appendElement(postInfoListItem, 'p', post.description_, 'post-description');
        appendElement(postInfoListItem, 'p', `Created by: ${post.created_by.username}`, 'post-created-by');

        postsList.appendChild(postInfoListItem);
    });
}

function appendElement(parent, elementType, text, className) {
    const element = document.createElement(elementType);
    element.textContent = text;
    if (className) element.className = className;
    parent.appendChild(element);
}
