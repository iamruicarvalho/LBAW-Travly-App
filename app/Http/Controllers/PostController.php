<?php

// app/Http/Controllers/PostController.php

// app/Http/Controllers/PostController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Group;
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
        $content = $request->input('media');
        $description = $request->input('description');
        $group_id = $request->input('group_id');

        if (!isset($content) && $_FILES["image"]["error"] && !isset($description)) {
          return redirect()->back()->with('error', 'You can not create an empty post');
        }
        if(!isset($content) && !in_array(pathinfo($_FILES["image"]["name"],PATHINFO_EXTENSION),['jpg','jpeg','png','gif','mp4','mov'])) {
          return redirect()->back()->with('error', 'File format not supported');
        }

        // Validate data
        $request->validate([
            'description' => 'required|max:1500',
            'content' => 'nullable|file|mimes:jpeg,png,gif,mp4,avi,wmv|max:2048',
            'group_id' => 'required|exists:group_,groupid',
        ]);

        $user = Auth()->user();
        $user->posts()->create([
            'content_'=> $content,
            'description_'=> $description,
            'group_id' => $group_id,
        ]);

        // return redirect()->route('home')
        //     ->with('success', 'Post created successfully!');
        return view('pages.home');
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

    public function showLikes($postId, $id)
    {
        $post = Post::find($postId);

        if (!$post) {
            return redirect()->route('profile.show', $id);
        }

        $likes = $post->likes; 

        return view('posts.likes', compact('post', 'likes'));
    }

    public function getPostsByHashtag($hashtag)
    {
        $post = Post::where('description_', 'like', "%#$hashtag%")
                ->orderBy('time_', 'desc') // Ordena por data de criação descendente
                ->get();

        return view('pages.by_hashtag', compact('post', 'hashtag'));
    }

    public function getPostsByCity($city)
    {
        $post = Post::where('description_', 'like', "%$city%")->get();

        return view('pages.posts_by_city', compact('post', 'city'));
    }

    public function searchPosts(Request $request)
    {
        $query = $request->input('query');
    
        $posts = Post::whereRaw('LOWER(content_) LIKE ?', ['%' . strtolower($query) . '%'])
                     ->orWhereRaw('LOWER(description_) LIKE ?', ['%' . strtolower($query) . '%'])
                     ->with('created_by') 
                     ->get();
    
        return response()->json($posts);
    }
    
    
    

}


