<?php

// app/Http/Controllers/PostController.php

// app/Http/Controllers/PostController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function listPosts()
    {
        if (Auth::check()) {
            $posts = Auth::user()->visiblePosts()->get();
            return view('pages.home', ['posts' => $posts]);
        }

        $posts = Post::publicPosts()->get();
        return view('pages.home', ['posts' => $posts]);
    }

    public function createPost(Request $request)
    {
        // error handling
        $content = $request->input('content');
        $description = $request->input('description');

        if (!isset($content) && $_FILES["image"]["error"] && !isset($description)) {
          return redirect()->back()->with('error', 'You can not create an empty post');
        }
        if(!isset($content) && !in_array(pathinfo($_FILES["image"]["name"],PATHINFO_EXTENSION),['jpg','jpeg','png','gif','mp4','mov'])) {
          return redirect()->back()->with('error', 'File format not supported');
        }

        // Validate data
        $request->validate([
            'description' => 'required|max:1500',
            'content' => 'nullable|file|mimes:jpeg,png,gif,mp4,avi,wmv|max:2048'
        ]);

        $user = Auth()->user();
        $user->posts()->create([
            'content_'=> $content,
            'description_'=> $description,
        ]);

        return redirect()->route('home')
            ->with('success', 'Post created successfully!');
    }

    public function show($postID)
    {
        $post = Post::find($postID);
        return view('partials.showPost', ['post'=> $post]);
    }

    public function editPost(Request $request)
    {
        # code...
    }

    public function deletePost(Request $request)
    {
        # code...
    }
}


