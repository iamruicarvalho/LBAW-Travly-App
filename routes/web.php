<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StaticPageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

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
});

// User
Route::controller(UserController::class)->group(function () {
    Route::get('/profile/show/{id}', [UserController::class, 'showProfile'])->name('profile.show');
    Route::get('/profile/edit/{id}', [UserController::class, 'editProfile'])->name('profile.edit');
    Route::post('/profile/update/{id}', [UserController::class, 'updateProfile'])->name('profile.update');
});

// Post
Route::controller(PostController::class)->group(function () {
    Route::get('/posts', [PostController::class, 'listPosts'])->name('posts.list');
    Route::post('/posts/create', [PostController::class, 'createPost'])->name('posts.create');
    Route::post('/posts/delete', [PostController::class, 'deletePost'])->name('posts.delete');
    Route::post('/posts/edit/{id}', [PostController::class, 'editPost'])->name('posts.edit');
    Route::post('/posts/like', [PostController::class, 'likePost'])->name('posts.like');
});

// Groups
Route::controller(GroupController::class)->group(function () {
    Route::get('/groups', [GroupController::class, 'list'])->name('groups');
    Route::get('/groups/{groupName}', [GroupController::class, 'showGroup'])->name('groups.show');

});

// Notifications
Route::controller(NotificationController::class)->group(function () {
    Route::get('/notifications', [NotificationController::class, 'getAll'])->name('notifications');
    Route::post('/notifications', [NotificationController::class, 'removeNotif'])->name('notifications.remove');
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

// Static Pages
Route::controller(StaticPageController::class)->group(function () {
    Route::get('/faq', [StaticPageController::class, 'faq'])->name('static.faq');
    Route::get('/about', [StaticPageController::class, 'about'])->name('static.about');
    Route::get('/privacy-policy', [StaticPageController::class, 'privacy_policy'])->name('static.privacy_policy');
    Route::get('/help', [StaticPageController::class, 'help'])->name('static.help');
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