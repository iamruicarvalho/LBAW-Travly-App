<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostLike extends Model
{
    protected $table = 'post_likes';
    public $incrementing = false;
    protected $primaryKey = ['id', 'postid']; 
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }



    // Relacionamento com o modelo Post
    public function post()
    {
        return $this->belongsTo(Post::class, 'postid');
    }
}
