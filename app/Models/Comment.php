<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Post;
use App\Models\User;
use App\Models\CommentLike;

class Comment extends Model
{
    public $timestamps = false;
    protected $table = 'comment_';
    protected $primaryKey = 'commentid'; 
    protected $fillable = [
        'description_', 'likes_', 'time_', 'id', 'postid', 'comment_replies'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'postid');
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'comment_replies');
    }

    public function likes()
    {
        return $this->hasMany(CommentLike::class)->count();
    }


}