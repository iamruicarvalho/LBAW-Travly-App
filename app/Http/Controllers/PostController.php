<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Post; 
use App\Models\PostLike;
use App\Models\Notification; 
use App\Models\PostNotification; 

class PostController extends Controller
{
    public function list()
    {
        $posts = [];

        if (Auth::check()) {
            $this->authorize('list', Post::class);
            $posts = Auth::user()->visiblePosts()->get();
        } else {
            $posts = Post::publicPosts()->get();
        }

        return view('pages.home', ['posts' => $posts]);
    }

    public function create(Request $request)
    {
        $this->authorize('create', Post::class);

        $content = $request->input('content');
        $imageFile = $request->file('image');

        if (empty($content) && !$imageFile) {
            return redirect()->back()->with('error', 'You cannot create an empty post');
        }

        if ($imageFile && in_array($imageFile->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'gif', 'mp4', 'mov'])) {
            // Processar a imagem
        } else {
            return redirect()->back()->with('error', 'File format not supported');
        }
        

        $post = new Post();
        $post->content = $content;
        $post->description_ = $request->input('description');
        $post->time_ = time();
        $post->created_by = Auth::user()->userID; 
        $post->content_type = $imageFile ? 'image' : null; /
        $post->save();

        ImageController::create($post->postID, 'post', $request);

        return redirect()->back()->with('success', 'Post successfully created');
    }

    public function delete(Request $request)
    {
        $post = Post::find($request->input('id'));
    
        $this->authorize('delete', $post);
    
        $post->comments()->delete();
    
        ImageController::delete($post->id, 'post');
    
        // Excluir o post
        $post->delete();
    
        return redirect()->route('pagina_de_redirecionamento_apos_exclusao');
    }

    public function edit(Request $request, $id)
    {
    $post = Post::find($id);

    $this->authorize('edit', $post);

    $post->content = $request->input('content');
    $post->description_ = $request->input('description');

    $post->save();

    return redirect()->route('nomedarota')->with('success', 'Post successfully edited');
    }


    public function like(Request $request)
    {
        $post = Post::find($request->id);

        $this->authorize('like', Post::class);

        if (PostLike::where([
            'userID' => Auth::user()->userID,
            'postID' => $post->postID,
        ])->exists()) {
            return;
        }

        PostLike::insert([
            'userID' => Auth::user()->userID,
            'postID' => $post->postID,
        ]);

        if (Auth::user()->userID == $post->created_by) {
            return;
        }

        DB::beginTransaction();

        Notification::insert([
            'description_' => 'User liked your post',
            'time_' => now(),
            'notifies' => $post->created_by,
            'sends_notif' => Auth::user()->userID,
        ]);

        $newNotification = Notification::latest()->first();

        PostNotification::insert([
            'notificationID' => $newNotification->notificationID,
            'postID' => $post->postID,
            'notification_type' => 'liked_post',
        ]);

        DB::commit();
    }
}
