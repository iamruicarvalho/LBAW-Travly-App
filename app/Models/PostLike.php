<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Post;

class PostLike extends Model
{
    protected $table = 'post_likes';

    protected $fillable = [
        'userID', 'postID'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userID');
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'postID');
    }
}