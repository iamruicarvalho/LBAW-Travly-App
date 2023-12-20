// const token = document.querySelector('meta[name="csrf-token"').content;
const searchForm = document.getElementById('posts-search-bar');
const searchPosts = document.getElementById('search-posts');
const postsList = document.getElementById('posts-list');

searchForm.addEventListener('submit', (event) => {
    event.preventDefault();
});

searchPosts.addEventListener('keyup', () => {
    const post = searchPosts.value.toLowerCase();
    search(post);
})

let search = (post) => {
    const url = `/posts/search?query=${post}`;
    fetch(url)
        .then(response => response.json())
        .then(data => {
            displayPosts(data); 
        })
        .catch(error => {
            console.log('Error: ', error);
        });
}

function displayPosts(posts) {
    postsList.innerHTML = '';

    let searchInput = searchPosts.value.trim().toLowerCase();
    if (searchInput === '') {
        postsList.innerHTML = '';
        return;
    }

    if (posts.length === 0) {
        const postsNotFound = document.createElement('li');
        postsNotFound.id = 'posts-not-found';
        postsNotFound.textContent = "No posts found";

        postsList.appendChild(postsNotFound);
        return;
    }

    posts.forEach(post => {
        const postInfoListItem = document.createElement('li');
        const postContent = document.createElement('p');
        const postDescription = document.createElement('p');
        const postCreatedBy = document.createElement('p');

        postContent.textContent = post.content_;
        postDescription.textContent = post.description_;
        postCreatedBy.textContent = `Created by: ${post.created_by.username}`;

        postsList.appendChild(postInfoListItem);
        postInfoListItem.appendChild(postContent);
        postInfoListItem.appendChild(postDescription);
        postInfoListItem.appendChild(postCreatedBy);

        postContent.style.fontSize = '16px';
        postDescription.style.fontSize = '13px';
        postCreatedBy.style.fontSize = '13px';
    });
}
