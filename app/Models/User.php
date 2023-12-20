<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable; 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;

use App\Models\Post;
use App\Models\Belongs;
use App\Models\Group;
use App\Models\Admin;
use App\Models\Comment;
use App\Models\Follow;
use App\Models\FriendRequest;
use App\Models\UserNotification;

class User extends Model implements Authenticatable 
{   
    use AuthenticatableTrait; 

    public $timestamps = false;
    protected $table = 'user_';
    protected $primaryKey = 'id';
    protected $fillable = [
        'username', 
        'name_', 
        'email', 
        'password_', 
        'private_', 
        'description_', 
        'location', 
        'countries_visited',
        'header_picture',
        'profile_picture'
    ];
    
    protected $hidden = [
        'password_', 
    ];

    public function getAuthPassword()
    {
        return $this->password_;
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'created_by');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'id');
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'belongs_', 'id', 'groupid');
    }

    public function admin()
    {
        return $this->hasOne(Admin::class, 'id');
    }

    public function requestsSent()
    {
        return $this->belongsToMany(User::class, 'request_', 'senderid', 'receiverid');
    }

    public function requestsReceived()
    {
        return $this->belongsToMany(User::class, 'request_', 'receiverid', 'senderid');
    }

    public function notifications()
    {
        return $this->hasMany(UserNotification::class, 'id');
    }

    public function postLikes()
    {
        return $this->hasMany(PostLike::class, 'id');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows_', 'followedid', 'followerid');
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'follows_', 'followerid', 'followedid');
    }

    public function isFriend($friend)
    {
        $iFollowU = Follow::where('followerid', $this->id)->where('followedid', $friend)->exists();
        $uFollowMe = Follow::where('followerid', $friend)->where('followedid', $this->id)->exists();
        return ($iFollowU && $uFollowMe);       
    }

    public function isFollowing($friend)
    {
        return Follow::where('followerid', $this->id)->where('followedid', $friend)->exists();    
    }

    public function canSendRequestTo($target){
        $check = FriendRequest::where('senderid', $this->id)->where('receiverid', $target)->exists();
        return !($check || $this->isFriend($target));
    }

    public function canAcceptRequestFrom($target){
        $check = FriendRequest::where('senderid', $target)->where('receiverid', $this->id)->exists();
        return $check && !$this->isFriend($target);
    }

    public function follows($target){
        return Follow::where('followerid', $this->id)->where('followedid', $friend)->exists();
    }
}
