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
    protected $primaryKey = 'postid';
    public $timestamps = false; 
    protected $fillable = [
        'content_', 'description_', 'likes_', 'comments_', 'time_', 'created_by', 'content_type'
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
        return $this->hasMany(PostLike::class, 'postid');
    }



    public function comments()
    {
        return $this->hasMany(Comment::class, 'postid');
    }

    public function media()
    {
        $files = DB::files("images/post/{$this->postid}.{png,jpg,jpeg,gif,mp4}");
        return count($files) > 0 ? $files[0] : null;
    }

    public static function publicPosts()
    {
        // returns all the posts from the public users (not necessarily users I follow)
        return Post::select('post.*')
            ->join('user_', 'user_.id', '=', 'post.created_by')
            ->where('user_.is_private', false)
            ->orderBy('time_', 'desc');
    }
    
}
