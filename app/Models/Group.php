<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Post;
use App\Models\User;
class Group extends Model
{
    public $timestamps = false;
    protected $table = 'group_';
    protected $primaryKey = 'groupid';
    protected $fillable = [
        'name_', 'description_'
    ];


    public function owners()
    {
        return $this->belongsToMany(User::class, 'owner_', 'groupid', 'id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'belongs_', 'groupid', 'id');
    }
    
    public function posts()
    {
        return $this->hasMany(Post::class, 'groupid');
    }
}
