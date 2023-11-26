<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class Admin extends Model
{
    public $timestamps = false;
    protected $table = 'admin_';
    protected $primaryKey = 'id';

    // protected $fillable = [
    //     'id'
    // ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }
}