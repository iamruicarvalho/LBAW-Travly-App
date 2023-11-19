<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Post;
use App\Models\Admin;
use App\Models\Comment;
use App\Models\Follow;
use App\Models\Request;
use App\Models\UserNotification;

class User extends Model
{
    protected $table = 'user_';

    protected $fillable = [
        'username', 'name_', 'email', 'password_', 'private_'
    ];

    protected $hidden = [
        'password_', 
    ];

    public function posts()
    {
        return $this->hasMany(Post::class, 'created_by');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'userID');
    }

    public function admin()
    {
        return $this->hasOne(Admin::class, 'userID');
    }

    public function requestsSent()
    {
        return $this->hasMany(Request::class, 'senderID');
    }

    public function requestsReceived()
    {
        return $this->hasMany(Request::class, 'receiverID');
    }

    public function follows()
    {
        return $this->hasMany(Follow::class, 'followerID');
    }

    public function notifications()
    {
        return $this->hasMany(UserNotification::class, 'userID');
    }
}