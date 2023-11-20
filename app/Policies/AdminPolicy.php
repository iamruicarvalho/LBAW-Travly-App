<?php

namespace App\Policies;

use app\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminPolicy
{
    use HandlesAuthorization;

    public function show() {
        return Auth::check() && Auth::user()->admin;
    }   

    public function block_user() {
        return Auth::check() && Auth::user()->admin;
    } 

    public function unblock_user() {
        return Auth::check() && Auth::user()->admin;
    }

    public function delete_post() {
        return Auth::check() && Auth::user()->admin;
    }

    public function delete_comment() {
        return Auth::check() && Auth::user()->admin;
    }
}