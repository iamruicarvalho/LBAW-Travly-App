<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Group;

class Belongs extends Model
{
    protected $table = 'belongs_';

    protected $primaryKey = null; 

    public $incrementing = false; 

    protected $fillable = [
        'userID',
        'groupID',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userID');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'groupID');
    }
}