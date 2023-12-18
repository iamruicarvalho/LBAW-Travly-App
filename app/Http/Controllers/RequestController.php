<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller{
    public function canSendRequestTo($target){
        if (Auth::check()) {
            $check = Request::where('senderid', Auth::user()->id)->where('receiverid', $target)->exists();
            if($check){
                return 1;
            }
            return 0;
        }

        return "User not logged in."; //maybe error 2
    }
}