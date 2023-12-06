<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\PostNotification;
use App\Models\UserNotification;

class Notification extends Model
{
    public $timestamps = false;
    protected $table = 'notification_';
    protected $primaryKey = 'notificationid';
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

    public function get_type()
    {
        $postNotification = PostNotification::where('notificationid', $this->notificationid)->first();
        if ($postNotification) {
            return $postNotification->notification_type;
        }

        $userNotification = UserNotification::where('notificationid', $this->notificationid)->first();
        if($userNotification) {
            return $userNotification->notification_type;
        }

        return "error";
    }

}