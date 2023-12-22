<?php

use App\Http\Controllers\MailController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StaticPageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\FriendRequestController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CommentLikeController;
use App\Http\Controllers\PostLikeController;
use App\Http\Controllers\AdminController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|

*/

Route::redirect('/', '/login');

// Home
Route::controller(HomeController::class)->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/guest', [HomeController::class, 'index'])->name('guest');
});

// User
Route::controller(UserController::class)->group(function () {
    Route::get('/profile/show/{id}', [UserController::class, 'showProfile'])->name('profile.show');
    Route::get('/profile/edit/{id}', [UserController::class, 'editProfile'])->name('profile.edit');
    Route::post('/profile/update/{id}', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::get('/profile/delete/{id}', [UserController::class, 'deleteAccount'])->name('profile.delete');

    //other users
    Route::get('/users/search', [UserController::class, 'searchUsers'])->name('users.searchh');
    Route::get('/{userid}/followers', [UserController::class, 'getFollowers'])->name('followers');
    Route::get('/{userid}/following', [UserController::class, 'getFollowing'])->name('following');
});

// Post
Route::controller(PostController::class)->group(function () {
    Route::get('/posts', [PostController::class, 'listPosts'])->name('posts.list');
    Route::post('/posts/create', [PostController::class, 'createPost'])->name('posts.create');
    Route::post('/posts/delete', [PostController::class, 'deletePost'])->name('posts.delete');
    Route::post('/posts/edit/{id}', [PostController::class, 'editPost'])->name('posts.edit');
    Route::post('/posts/like', [PostController::class, 'likePost'])->name('posts.like');
    Route::get('/posts/search', [PostController::class, 'searchPosts'])->name('posts.search');

});

Route::get('/create_post', [HomeController::class, 'create_post'])->middleware('auth');

Route::get('/my_post', [HomeController::class, 'my_post'])->middleware('auth');
Route::get('/my_posts_del/{postid}', [HomeController::class, 'my_posts_del'])->middleware('auth');
Route::get('/post_update_page/{postid}', [HomeController::class, 'post_update_page'])->middleware('auth');
Route::post('/update_post_data/{postid}', [HomeController::class, 'update_post_data'])->middleware('auth');

Route::get('/posts/{postid}/likes', [LikeController::class, 'showLikes']);
Route::post('/posts/{postid}/like', [LikeController::class, 'likePost'])->name('posts.likes');
Route::delete('/posts/{postid}/unlike', [LikeController::class, 'unlikePost'])->name('posts.unlike');


Route::post('/user_post', [HomeController::class, 'user_post']);

Route::post('/user_comment', [HomeController::class, 'addComment'])->middleware('auth');

Route::get('/posts/{postid}/comments', [HomeController::class, 'showPostComments']);
Route::get('/comments/{commentid}/edit', [HomeController::class, 'editComment'])->middleware('auth');
Route::put('/comments/{commentid}', [HomeController::class, 'updateComment'])->middleware('auth');
Route::delete('/comments/{commentid}', [HomeController::class, 'destroy'])->middleware('auth')->name('comments.destroy');



// Groups
Route::controller(GroupController::class)->group(function () {
    Route::get('/groups', [GroupController::class, 'showGroups'])->name('groups.showGroups');
    Route::get('/groups/create', [GroupController::class, 'showCreateForm'])->name('groups.showcreate');
    Route::post('/groups/create', [GroupController::class, 'createGroup'])->name('groups.create');
    Route::get('/groups/{groupid}', [GroupController::class, 'showGroup'])->name('groups.show');
    Route::get('/groups/{groupid}/details', [GroupController::class, 'groupDetails'])->name('group.details');
    Route::get('/groups/{groupid}/details/remove-user/{userid}', [GroupController::class, 'removeUser'])->name('group.removeuser');
    Route::get('/user/search', [GroupController::class, 'searchUsers'])->name('users.search');
    Route::post('/groups/{groupid}/details/add-user/{userid}', [GroupController::class, 'addUser'])->name('group.addUser');
    Route::post('/groups/{groupid}/details/editName', [GroupController::class, 'editName'])->name('group.editName');
    Route::post('/groups/{groupid}/details/editDescription', [GroupController::class, 'editDescription'])->name('group.editDescription');
    Route::post('/groups/{groupid}/details/deleteGroup', [GroupController::class, 'deleteGroup'])->name('group.delete');
    Route::post('/groups/{groupid}/leave', [GroupController::class, 'leaveGroup'])->name('groups.leave');
    Route::get('/groups/search', [GroupController::class, 'searchGroups'])->name('groups.search');
});

// Notifications
Route::controller(NotificationController::class)->group(function () {
    Route::get('/notifications', [NotificationController::class, 'getAll'])->name('notifications');
    Route::post('/notifications', [NotificationController::class, 'removeNotif'])->name('notifications.remove');
});

Route::controller(FriendRequestController::class)->group(function () {
    Route::post('/request/reject', [FriendRequestController::class, 'rejectFriendRequest'])->name('request.rejectFriend');
    Route::post('/request/accept', [FriendRequestController::class, 'acceptFriendRequest'])->name('request.acceptFriend');
    Route::post('/request/sendFollow', [FriendRequestController::class, 'sendFriendRequest'])->name('request.sendFollow');
    Route::post('/request/startFollow', [FriendRequestController::class, 'startFollowing'])->name('request.startFollow');
    Route::post('/request/removeFriend', [FriendRequestController::class, 'removeFriend'])->name('request.removeFriend');
    Route::post('/request/removeFollow', [FriendRequestController::class, 'removeFollow'])->name('request.removeFollow');
});

// Authentication
Route::middleware('web')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate']);
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
});

Route::middleware('web')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Forgot Password
Route::get('/send-email', [UserController::class, 'showEmailLinkRequestForm'])->name('send-email');
Route::get('/password/reset', [UserController::class, 'showUpdatePasswordForm'])->name('password.reset');
Route::post('/password/reset', [UserController::class, 'updatePassword'])->name('password.update');
Route::post('/send', [MailController::class, 'send'])->name('send');

// Static Pages
Route::controller(StaticPageController::class)->group(function () {
    Route::get('/faq', [StaticPageController::class, 'faq'])->name('static.faq');
    Route::get('/about', [StaticPageController::class, 'about'])->name('static.about');
    Route::get('/privacy-policy', [StaticPageController::class, 'privacy_policy'])->name('static.privacy_policy');
    Route::get('/help', [StaticPageController::class, 'help'])->name('static.help');
});


Route::get('/messages', [MessageController::class, 'showAllConversations'])->name('messages.showAllConversations');
Route::get('/messages/{id}', [MessagesController::class, 'show'])->name('messages.show');

Route::get('/explore', [ExploreController::class, 'index'])->name('explore');
Route::get('/explore', [ExploreController::class, 'explore'])->name('explore');

Route::get('/posts/by-city/{city}', [PostController::class, 'getPostsByCity'])->name('posts.by.city');
Route::get('/posts/{hashtag}', [PostController::class, 'getPostsByHashtag'])->name('posts.by.hashtag');


Route::post('/posts/{post}/likes', [PostLikeController::class, 'store'])->name('posts.likes.store');
Route::delete('/posts/{post}/likes', [PostLikeController::class, 'destroy'])->name('posts.likes.destroy');



Route::group(['prefix' => 'admin', 'middleware' => ['auth']], function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    Route::delete('/comments/{comment}', [AdminController::class, 'removeComment'])->name('admin.removeComment');

    Route::post('/users/{user}/approve', [AdminController::class, 'approveUser'])->name('admin.approveUser');

    Route::post('/users/{user}/ban', [AdminController::class, 'deleteAccount'])->name('admin.banUser');

    Route::delete('/delete-post/{postid}', [AdminController::class, 'deletePost'])->name('admin.deletePost');


});

/*
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StaticPageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\MessageController;



// AdminController
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
});

// CommentController
Route::middleware(['auth'])->group(function () {
    Route::post('/comments/create/{postId}', [CommentController::class, 'create'])->name('comments.create');
    Route::post('/comments/edit/{commentId}', [CommentController::class, 'edit'])->name('comments.edit');
    Route::post('/comments/delete/{commentId}', [CommentController::class, 'delete'])->name('comments.delete');
});


Route::get('/comments/list/{postId}', [CommentController::class, 'list'])->name('comments.list');

// HomeController
Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/settings', [HomeController::class, 'settings'])->name('settings');

Route::get('/explore', [HomeController::class, 'explore'])->name('explore');
*/


/*
// MessageController
Route::post('/sendMessage', [MessageController::class, 'sendMessage'])->name('sendMessage');

Route::get('/getMessages/{id}', [MessageController::class, 'getMessages'])->name('getMessages');

Route::get('/getConversations', [MessageController::class, 'getConversations'])->name('getConversations');
*/

/*
// UserController
Route::middleware(['auth'])->group(function () {
    Route::get('/profile/{id}', [UserController::class, 'showProfile'])->name('profile.show');

    Route::get('/profile/{id}/edit', [UserController::class, 'editProfile'])->name('profile.edit');

    Route::put('/profile/{id}', [UserController::class, 'updateProfile'])->name('profile.update');
});
*/