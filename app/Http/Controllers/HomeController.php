<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Group;

class HomeController extends Controller
{
    public function index()
    {
        $this->middleware('auth');

        return view('pages.home');
    }

    public function settings()
    {
        $this->middleware('auth');

        return view('user.settings');
    }

    public function explore()
    {
        $popularPosts = Post::orderBy('likes_', 'desc')->take(10)->get();
        $popularUsers = User::orderBy('followers_count', 'desc')->take(10)->get();
        $popularGroups = Group::orderBy('members_count', 'desc')->take(10)->get();

        return view('explore.index', [
            'popularPosts' => $popularPosts,
            'popularUsers' => $popularUsers,
            'popularGroups' => $popularGroups,
        ]);
    }

    // Outros métodos relacionados à página inicial, se necessário

}
