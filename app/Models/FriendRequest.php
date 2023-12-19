<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class FriendRequest extends Model
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

    public function createRequest($sender, $receiver){
        $this->senderid = Auth::user()->id;
        $this->receiverid = $request->input('to');

        $this->save();
    }
}