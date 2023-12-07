<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\PostLike;

class LikeController extends Controller
{
    public function showLikes($postid)
    {
        $post = Post::find($postid);

        if (!$post) {
            return redirect()->back()->with('error', 'Post not found');
        }

        $likers = PostLike::where('postid', $postid)->with('user')->get();

        return view('likes.show', compact('post', 'likers'));
    }

    public function likePost($postid)
    {
        $post = Post::find($postid);

        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }

        $user = auth()->user();

        $existingLike = PostLike::where(['id' => $user->id, 'postid' => $postid])->first();

        if ($existingLike) {
            $existingLike->delete();
            return response()->json(['message' => 'Like removed successfully']);
        }

        $like = new PostLike(['id' => $user->id, 'postid' => $postid]);
        $like->save();

        return response()->json(['message' => 'Post liked successfully']);
    }

    public function unlikePost($postid)
    {
        $user = auth()->user();

        PostLike::where(['id' => $user->id, 'postid' => $postid])->delete();

        return response()->json(['message' => 'Like removed successfully']);
    }

}
