<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    public function delete(User $user, Comment $comment) {
        return Auth::check() && (Auth::user()->admin || Auth::user()->id == $comment->userID);
    }

    public function create() {
        return Auth::check();
    }

    public function like() {
        return Auth::check();
    }
  
}