<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Group;

class Message extends Model
{
    public $timestamps = false;
    protected $table = 'message_';
    protected $primaryKey = 'messageID';
    protected $fillable = [
        'description_', 'time_', 'sender', 'receiver', 'sent_to', 'message_replies'
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver');
    }

    public function sentToGroup()
    {
        return $this->belongsTo(Group::class, 'sent_to');
    }

    public function replies()
    {
        return $this->hasMany(Message::class, 'message_replies');
    }
}