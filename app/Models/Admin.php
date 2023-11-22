<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class Admin extends Model
{
    protected $table = 'admin_';

    protected $fillable = [
        'id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }
}