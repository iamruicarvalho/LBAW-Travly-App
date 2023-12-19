<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class FriendRequest extends Model
{
    public $timestamps = false;
    protected $table = 'request_';
    protected $primaryKey = ['senderid', 'receiverid'];
    protected $fillable = [
        'senderid', 'receiverid'
    ];

    public $incrementing = false;

    public function sender()
    {
        return $this->belongsTo(User::class, 'senderid');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiverid');
    }
}