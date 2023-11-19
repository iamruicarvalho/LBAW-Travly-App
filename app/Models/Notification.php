<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notification_';

    protected $fillable = [
        'description_', 'time_', 'notifies', 'sends_notif'
    ];

    public function notifiedUser()
    {
        return $this->belongsTo(User::class, 'notifies');
    }

    public function senderUser()
    {
        return $this->belongsTo(User::class, 'sends_notif');
    }
}