<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Comment;

class CommentLike extends Model
{
    protected $table = 'comment_likes';

    protected $fillable = [
        'id', 'commentID'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }

    public function comment()
    {
        return $this->belongsTo(Comment::class, 'commentID');
    }
}