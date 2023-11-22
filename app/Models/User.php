<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable; # <-----
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;

use App\Models\Post;
use App\Models\Admin;
use App\Models\Comment;
use App\Models\Follow;
use App\Models\Request;
use App\Models\UserNotification;

class User extends Model implements Authenticatable # <-----
{   
    use AuthenticatableTrait; # <-----

    public $timestamps = false;
    protected $table = 'user_';
    protected $primaryKey = 'id';
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
        return $this->hasMany(Comment::class, 'id');
    }

    public function admin()
    {
        return $this->hasOne(Admin::class, 'id');
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

    public function following()
    {
        return $this->hasMany(Follow::class, 'followedID');
    }

    public function notifications()
    {
        return $this->hasMany(UserNotification::class, 'id');
    }
}
