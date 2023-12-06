<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


use App\Models\Post;
use App\Models\User;
use App\Models\Group;

class HomeController extends Controller
{
    public function index()
    {
        $this->middleware('auth');
        $user = Auth()->user();
        $userid = $user->id;
        $data = Post::where('created_by', '=', $userid)->get();
        
        return view('pages.home', compact('data'));
    }

    public function my_posts_del($postid)
    {
        $data = Post::find($postid);
        $data->delete();
        return redirect()->back()->with('message', 'Post deleted successfully');
    }
    

    public function post_update_page($postid)
    {
        $data = Post::find($postid);
        return view('home.post_page', compact('data'));
    }

    
    public function update_post_data(Request $request, $postid)
    {
        $data = Post::find($postid);
        $data->description_ = $request->description;
    
        $image = $request->image;
        if ($image) {
            $imagename = time() . '.' . $image->getClientOriginalExtension();
            $image->move('postimage', $imagename);
            $data->content_ = $imagename;
        }
    
        $data->time_ = now();
        $data->save();
    
        return Redirect::route('home')->with('message', 'Post updated successfully');
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

    public function create_post()
    {
        return view('home.create_post');
    }

    public function my_post()
    {
        $user = Auth()->user();
        $userid = $user->id;
        $data = Post::where('created_by', '=', $userid)->get();

        return view('home.my_post', compact('data'));
    }

    public function user_post(Request $request)
    {
        
        $user = Auth()->user();

        $userid = $user->id;

        $username = $user->name_;

        $post = new Post;

        $post->description_ = $request->description;

        $post->created_by=$userid;
        
        $image = $request->image;
        if ($image) {
            $imagename = time() . '.' . $image->getClientOriginalExtension();
            $request->image->move('postimage', $imagename);
            $post->content_ = $imagename;
        }

        $post->time_ = now(); 

        $post->save();

        return redirect()->back();
    }


}
