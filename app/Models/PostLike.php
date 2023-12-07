<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostLike extends Model
{
    public $timestamps = false;
    protected $table = 'post_likes';
    protected $primaryKey = null; // Para indicar que não há uma chave primária simples
    public $incrementing = false; // Para indicar que não há auto-incremento

    protected $fillable = [
        'id', 'postid'
    ];

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
