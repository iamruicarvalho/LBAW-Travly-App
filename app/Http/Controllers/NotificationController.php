<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
    
    public function show($notificationID)
    {
        $notification = Notification::find("notificationID");
        return view('partials.showNotification', ['notification'=> $notification]);
    }
}