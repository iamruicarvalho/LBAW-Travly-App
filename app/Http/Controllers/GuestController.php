<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class GuestController extends Controller
{
    public function showGuestPosts()
    {
        // get posts from users with public account
        $posts = Post::with('created_by')
            ->whereHas('created_by', function ($query) {
                $query->where('private_', false);
            })
            ->orderBy('time_', 'desc')  
            ->get();
    
        return view('pages.guest', ['posts' => $posts]);
    }
}