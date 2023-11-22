<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    protected $table = 'owner_';

    protected $primaryKey = null; 

    public $incrementing = false; 

    protected $fillable = [
        'id',
        'groupID',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'groupID');
    }
}