<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Notification;

class UserNotification extends Model
{
    protected $table = 'user_notification';

    protected $fillable = [
        'notificationID', 'userID', 'notification_type'
    ];

    public function notification()
    {
        return $this->belongsTo(Notification::class, 'notificationID');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userID');
    }
}