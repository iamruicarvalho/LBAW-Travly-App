<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Redirect;
use App\Models\FriendRequest;
use App\Models\User;
use App\Models\Follow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FriendRequestController extends Controller{

    public function acceptFriendRequest(Request $request) {
        
        if (Auth::check()) {
            
            $this->validate($request, [
                 'notifType' => 'required', 
                 'to' => 'required|exists:user_,id',
            ]);

            $userid = Auth::user()->id;

            $this->createFriends($userid, $request->input('to')); //Make me follow target and target follow me
            $notifCheck = app('App\Http\Controllers\NotificationController')->addNotif($request); //send notification accepting friend request
            
            return redirect()->back();
        }

        //redirect to error page (still none) with error user not logged in
        return redirect()->action('login');
    }

    public function rejectFriendRequest(Request $request) {
        
        if (Auth::check()) {
            
            $this->validate($request, [
                 'notifType' => 'required', 
                 'to' => 'required|exists:user_,id',
            ]);

            $userid = Auth::user()->id;

            FriendRequest::where('senderid', $request->input('to'))->where('receiverid', $userid)->delete();

            $notifCheck = app('App\Http\Controllers\NotificationController')->addNotif($request); //send notification accepting friend request
            
            return redirect()->back();
        }

        //redirect to error page (still none) with error user not logged in
        return redirect()->action('login');
    }

    public function removeFollow(Request $request) { //used for public accounts (iFollowU)
        
        if (Auth::check()) {
            
            $this->validate($request, [
                 'notifType' => 'required', 
                 'toRemove' => 'required|exists:user_,id',
            ]);

            $userid = Auth::user()->removeFollow($request->input($toRemove));

            return redirect()->back();
        }

        //redirect to error page (still none) with error user not logged in
        return redirect()->action('login');
    }

    public function removeFriend(Request $request) { //used for friends (iFollowU, uFollowMe)
        
        if (Auth::check()) {
            
            $this->validate($request, [
                 'notifType' => 'required', 
                 'toRemove' => 'required|exists:user_,id',
            ]);

            Auth::user()->followers()->remove($request->input($toRemove));
            Auth::user()->following()->remove($request->input($toRemove));

            return redirect()->back();
        }

        //redirect to error page (still none) with error user not logged in
        return redirect()->action('login');
    }

    public function sendFriendRequest(Request $request){
        if (Auth::check()) {

            $this->validate($request, [
                'notifType' => 'required', 
                'to' => 'required|exists:user_,id',
            ]);
            
            $notifType = $request->input('notifType');

            Auth::user()->requestsSent()->attach($request->input('to'));

            $check = FriendRequest::where('senderid', $request->input('to'))->where('receiverid', Auth::user()->id)->exists(); //check if other person already sent request
            
            if($check){ //if they did, make them friends and change notif to accepted_follow
                $this->createFriends(Auth::user()->id, $request->input('to'));
                $notifType = 'accepted_follow';
            } 

            $notifCheck = app('App\Http\Controllers\NotificationController')->addNotif($request); //can be used to check if notification was created

            return redirect()->back();
        }
        
        //redirect to error page (still none) with error user not logged in
        return "Not logged in";
    }

    public function createFriends($friend1, $friend2){
        $friend1 = User::where('id', $friend1)->first();

        $friend1->followers()->attach($friend2);
        $friend1->following()->attach($friend2);
    }
}