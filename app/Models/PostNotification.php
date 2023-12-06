<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Post;
use App\Models\Notification;

class PostNotification extends Model
{
    public $timestamps = false;
    protected $table = 'post_notification';
    protected $primaryKey = 'notificationid';
    protected $fillable = [
        'notificationid', 'postID', 'notification_type'
    ];

    public function notification()
    {
        return $this->belongsTo(Notification::class, 'notificationid');
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'postID');
    }
}