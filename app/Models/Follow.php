<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class Follow extends Model
{
    protected $table = 'follows_';
    protected $primaryKey = ['followerid', 'followedid'];

    public $incrementing = false; 

    protected $fillable = [
        'followerid', 'followedid'
    ];

    public function follower()
    {
        return $this->belongsTo(User::class, 'followerid');
    }

    public function followed()
    {
        return $this->belongsTo(User::class, 'followedid');
    }
}