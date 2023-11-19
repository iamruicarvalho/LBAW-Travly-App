<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class Admin extends Model
{
    protected $table = 'admin_';

    protected $fillable = [
        'userID'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userID');
    }
}