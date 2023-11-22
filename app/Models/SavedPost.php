<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Post;

class SavedPost extends Model
{
    protected $table = 'saved_post';

    protected $primaryKey = null; 

    public $incrementing = false; 

    protected $fillable = [
        'id',
        'postID',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'postID');
    }
}