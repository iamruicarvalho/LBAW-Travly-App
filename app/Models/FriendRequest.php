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

    public function sender()
    {
        return $this->belongsTo(User::class, 'senderid');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiverid');
    }

    public function createRequest($sender, $receiver){
        $this->senderid = Auth::user()->id;
        $this->receiverid = $request->input('to');

        $this->save();
    }
}