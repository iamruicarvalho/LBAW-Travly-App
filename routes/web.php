<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CardController;
use App\Http\Controllers\ItemController;

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
// Home
Route::redirect('/', '/login');

// Cards
Route::controller(CardController::class)->group(function () {
    Route::get('/cards', 'list')->name('cards');
    Route::get('/cards/{id}', 'show');
});


// API
Route::controller(CardController::class)->group(function () {
    Route::put('/api/cards', 'create');
    Route::delete('/api/cards/{card_id}', 'delete');
});

Route::controller(ItemController::class)->group(function () {
    Route::put('/api/cards/{card_id}', 'create');
    Route::post('/api/item/{id}', 'update');
    Route::delete('/api/item/{id}', 'delete');
});


// Authentication
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'authenticate');
    Route::get('/logout', 'logout')->name('logout');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
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

// PostController
Route::get('/posts', [PostController::class, 'list'])->name('posts.list');

Route::post('/posts/create', [PostController::class, 'create'])->name('posts.create');

Route::post('/posts/delete', [PostController::class, 'delete'])->name('posts.delete');

Route::post('/posts/edit/{id}', [PostController::class, 'edit'])->name('posts.edit');

Route::post('/posts/like', [PostController::class, 'like'])->name('posts.like');

// MessageController
Route::post('/sendMessage', [MessageController::class, 'sendMessage'])->name('sendMessage');

Route::get('/getMessages/{userId}', [MessageController::class, 'getMessages'])->name('getMessages');

Route::get('/getConversations', [MessageController::class, 'getConversations'])->name('getConversations');


// StaticPageController
Route::get('/faq', [StaticPageController::class, 'faq'])->name('static.faq');

Route::get('/about', [StaticPageController::class, 'about'])->name('static.about');

Route::get('/privacy', [StaticPageController::class, 'privacy'])->name('static.privacy');

// UserController
Route::middleware(['auth'])->group(function () {
    Route::get('/profile/{userId}', [UserController::class, 'showProfile'])->name('profile.show');

    Route::get('/profile/{userId}/edit', [UserController::class, 'editProfile'])->name('profile.edit');

    Route::put('/profile/{userId}', [UserController::class, 'updateProfile'])->name('profile.update');
});
*/