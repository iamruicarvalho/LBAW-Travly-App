<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PostNotification;
use App\Models\UserNotification;
use App\Models\Notification;
use App\Models\FriendRequest;
use App\Models\User;
use App\Models\Follow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class NotificationController extends Controller
{
    public function getAll(){
        if (Auth::check()) {
            $notifications = Notification::select('notification_.*')
                            ->fromRaw('notification_')
                            ->where('notification_.notifies', '=', Auth::user()->id)
                            ->orderBy('time_', 'desc')->get();
            return view('pages.notifications', ['notifications' => $notifications]);
        }

        //redirect to error page (still none) with error user not found
        return "User not found.";
    }
    
    public function removeNotif(Request $request)
    {
        // PostNotification::where('notificationid', '=', $request->input('notificationid'))->delete();
        // UserNotification::where('notificationid', '=', $request->input('notificationid'))->delete();
        // Notification::where('notificationid', '=', $request->input('notificationid'))->delete();
        // return $this->getAll();

        //para testar o addNotif
        $this->addNotif($request);
        return $this->getAll();
    }

    public function setSeen(Request $request){
        if (Auth::check()) {
            $this->validate($request, [
            'notificationid' => 'required',
            ]);
        

        $notif = Notification::find($request->input('notificationid'));
        $notif->seen = TRUE;

        $notif.save();
        }
    }

    public function addNotif(Request $request)
    {   
        if (Auth::check()) {

            $this->validate($request, [
                'notifType' => 'required', 
                'to' => 'required|exists:user_,id',
                'targetPost' => 'exists:post_,postid',
            ]);

            $uNotif = TRUE;

            $notifType = $request->input('notifType');

            $notification = new Notification();
            $notification->notificationid = Notification::orderBy('notificationid','desc')->first()->notificationid + 1;
            $notification->time_ = now();
            $notification->notifies = $request->input('to');
            $notification->sends_notif = Auth::user()->id;

            $username = Auth::user()->username;

            switch($notifType){
                case('started_following'):
                    $notification->description_ = $username . ' started following you!';
                    break;
                case('request_follow'):
                    $notification->description_ = $username . ' sent you a follow request!';
                    break;
                case('accepted_follow'):
                    $notification->description_ = $username . ' accepted your follow request!';
                    break;
                case('liked_post'):
                    $notification->description_ = $username . ' liked one of your posts!';
                    $uNotif = FALSE;
                    break;
                case('commented_post'):
                    $notification->description_ = $username . ' left a comment in one of your posts!';
                    $uNotif = FALSE;
                    break;
            }

            $notification->save();

            if($uNotif == TRUE){
                $notif_type_add = new UserNotification();
                $notif_type_add->notificationid = $notification->notificationid;
                $notif_type_add->id = Auth::user()->id;
                $notif_type_add->notification_type = $notifType;
            } else {
                $notif_type_add = new PostNotification();
                $notif_type_add->notificationid = $notification->notificationid;
                $notif_type_add->postid = $request->input('targetPost');
                $notif_type_add->notification_type = $notifType;
            }

            $notif_type_add->save();

            return 1;
        } else return 0;
    }
}