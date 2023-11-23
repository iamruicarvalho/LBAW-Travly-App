<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class Request extends Model
{
    public $timestamps = false;
    protected $table = 'request_';
    protected $primaryKey = ['senderID', 'receiverID'];
    protected $fillable = [
        'senderID', 'receiverID'
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'senderID');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiverID');
    }
}