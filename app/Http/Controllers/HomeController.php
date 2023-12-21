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
use App\Models\Comment;


class HomeController extends Controller
{
    public function index()
    {
        if (Auth::check()) {

            $user = Auth::user();
            $userid = $user->id;
            
            // Recupera os IDs dos usuários que o usuário autenticado segue
            $followedUsers = DB::table('follows_')
            ->where('followerid', $userid)
            ->pluck('followedid');
            
            // Inclui o próprio ID do usuário na lista
            $followedUsers[] = $userid;
            
            // Recupera os posts do usuário autenticado e dos usuários seguidos
            $data = Post::with(['comments' => function ($query) {
                        $query->orderBy('time_', 'desc')->take(50);
                    }])
                    ->whereIn('created_by', $followedUsers)
                    ->orderBy('time_', 'desc') // Ordena os posts por data
                    ->get();

            return view('pages.home', compact('data'));
        } 
        else {
            $posts = Post::with('created_by')
            ->whereHas('created_by', function ($query) {
                $query->where('private_', false);
            })
            ->orderBy('time_', 'desc')  
            ->get();

            return view('pages.guest', compact('posts'));
        }
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
        $user = Auth::user();
        $userid = $user->id;
    
        // Recupera os IDs dos usuários que o usuário autenticado segue
        $followedUsers = DB::table('follows_')
                           ->where('followerid', $userid)
                           ->pluck('followedid');
    
        // Inclui o próprio ID do usuário na lista
        $followedUsers[] = $userid;
    
        // Recupera os posts do usuário autenticado e dos usuários seguidos
        $data = Post::whereIn('created_by', $followedUsers)
                    ->orderBy('time_', 'desc') // Ordena os posts por data
                    ->get();
    
        return view('home.my_post', compact('data'));
    }
    

    public function user_post(Request $request)
    {
        $user = Auth()->user();
        $userid = $user->id;
        $username = $user->name_;

        $post = new Post;

        $post->description_ = $request->description;
        $post->created_by = $userid;

        if ($request->has('group_id')) {
            $post->groupid = $request->group_id;
        }

        $image = $request->image;
        if ($image) {
            $imagename = time() . '.' . $image->getClientOriginalExtension();
            $request->image->move(public_path('postimage'), $imagename);
            $post->content_ = $imagename;
        }

        $post->time_ = now();
        $post->save();

        return redirect()->back();
    }


    public function addComment(Request $request)
    {
        $user = Auth()->user();

        $comment = new Comment();
        $comment->description_ = $request->input('comment');
        $comment->time_ = now();
        $comment->id = $user->id; 
        $comment->postid = $request->input('postid');

        $comment->save();

        return redirect()->back()->with('message', 'Comment added successfully!');
    }

    public function showPostComments($postid)
    {
        $post = Post::find($postid);

        if (!$post) {
            return redirect()->route('home')->with('error', 'Post not found');
        }

        $comments = Comment::where('postid', $postid)->get();

        return view('comments.show', compact('post', 'comments'));
    }

    public function editComment($commentid)
    {
        $comment = Comment::find($commentid);
    
        // Verificar se o comentário existe
        if (!$comment) {
            return redirect()->route('home')->with('error', 'Comentário não encontrado.');
        }
    
        // Verificar se o usuário autenticado é o autor do comentário
        if (Auth::check() && $comment->user_id != Auth::id()) {
            return redirect()->route('home')->with('error', 'Você não tem permissão para editar este comentário.');
        }
    
        return view('comments.edit', compact('comment'));
    }
    

    public function updateComment(Request $request, $commentid)
    {
        $comment = Comment::find($commentid);

        $comment->description_ = $request->input('comment');
        $comment->save();

        return redirect()->back()->with('message', 'Comment update successfully!');
    }

    public function destroy($commentid)
    {
        $comment = Comment::find($commentid);
    
        if (!$comment) {
            return redirect()->back()->with('error', 'Comment not found');
        }
    
        $comment->delete();
    
        return redirect()->back()->with('message', 'Comment deleted successfully!');
    }
    


}
