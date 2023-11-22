<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Group;
use App\Models\PostLike;
use App\Models\Comment;

class Post extends Model
{
    protected $table = 'post_';

    protected $fillable = [
        'content', 'description_', 'likes_', 'comments', 'time_', 'created_by', 'content_type'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function likes()
    {
        return $this->hasMany(PostLike::class)->count();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->whereNull('previous')->get();
    }

    public function media()
    {
        $files = DB::files("images/post/{$this->postID}.{png,jpg,jpeg,gif,mp4}");
        return count($files) > 0 ? $files[0] : null;
    }

    public static function publicPosts()
    {
        return static::select('post.*')
            ->join('users', 'users.id', '=', 'post.created_by')
            ->where('users.is_public', true)
            ->where('post.is_public', true)
            ->orderBy('time_', 'desc');
    }
}
