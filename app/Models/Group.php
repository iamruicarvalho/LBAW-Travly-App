<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Post;

class Group extends Model
{
    public $timestamps = false;
    protected $table = 'group_';
    protected $primaryKey = 'groupID';
    protected $fillable = [
        'name_', 'description_'
    ];

    public function posts()
    {
        return $this->hasMany(Post::class, 'groupID');
    }
}
