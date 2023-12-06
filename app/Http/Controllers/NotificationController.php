<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PostNotification;
use App\Models\UserNotification;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class NotificationController extends Controller
{
    public function getAll(){
        if (Auth::check()) {
            $notifications = Auth::user()->allNotifications()->get();
            return view('pages.notifications', ['notifications' => $notifications]);
        }

        //redirect to error page (still none) with error user not found
        return "User not found.";
    }
    
    //ainda não usado
    public function showNotif($notificationID)
    {
        $notification = Notification::find("notificationid");
        return view('partials.showNotification', ['notification'=> $notification]);
    }

    public function removeNotif(Request $request)
    {
        PostNotification::where('notificationid', '=', $request->input('notificationid'))->delete();
        UserNotification::where('notificationid', '=', $request->input('notificationid'))->delete();
        Notification::where('notificationid', '=', $request->input('notificationid'))->delete();
        return $this->getAll();
    }
}