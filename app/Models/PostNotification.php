<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Post;
use App\Models\Notification;

class PostNotification extends Model
{
    protected $table = 'post_notification';

    protected $fillable = [
        'notificationID', 'postID', 'notification_type'
    ];

    public function notification()
    {
        return $this->belongsTo(Notification::class, 'notificationID');
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'postID');
    }
}