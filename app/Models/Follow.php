<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class Follow extends Model
{
    public $timestamps = false;
    protected $table = 'follows_';
    protected $primaryKey = ['followerID', 'followedID'];

    protected $fillable = [
        'followerID', 'followedID'
    ];

    public function follower()
    {
        return $this->belongsTo(User::class, 'followerID');
    }

    public function followed()
    {
        return $this->belongsTo(User::class, 'followedID');
    }
}