<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Group;

class Belongs extends Model
{
    public $timestamps = false;
    protected $table = 'belongs_';
    protected $primaryKey = ['id', 'groupid'];   

    public $incrementing = false; 

    protected $fillable = [
        'id',
        'groupid',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'groupid');
    }
}