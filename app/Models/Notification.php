<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class Notification extends Model
{
    public $timestamps = false;
    protected $table = 'notification_';
    protected $primaryKey = 'notificationID';
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