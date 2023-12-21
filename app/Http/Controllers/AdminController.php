<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Comment;
use App\Models\Post;


class AdminController extends Controller
{
    public function index()
    {
        $users = User::all();
        $comments = Comment::all();
        $posts = Post::all();
    
        return view('admin.dashboard', compact('users', 'comments', 'posts'));
    }
    
    
    // Método para remover um comentário
    public function removeComment($commentid)
    {
        $comment = Comment::findOrFail($commentid);
        $comment->delete();

        return back()->with('success', 'Comment removed successfully.');
    }

    // Método para aprovar um usuário
    public function approveUser($id)
    {
        $user = User::findOrFail($id);
        $user->is_approved = true;
        $user->save();

        return back()->with('success', 'User approved successfully.');
    }


    public function deleteAccount($id) {
        $userToDelete = User::findOrFail($id);

        $userToDelete->username = "anonymous" . $userToDelete->id;
        $userToDelete->name_ = "Anonymous";
        $userToDelete->email = "anonymous" . $userToDelete->id . "@example.com";
        $userToDelete->password_ = Hash::make(Str::random(40));

        $userToDelete->save();

        if (Auth::id() == $id) {
            Auth::logout();
        }

        return back()->with('success', 'User banned successfully.');
    }

    public function deletePost($postid)
    {
        $postToDelete = Post::findOrFail($postid);

        $postToDelete->delete();

        return back()->with('success', 'Post deleted successfully.');
    }


}

