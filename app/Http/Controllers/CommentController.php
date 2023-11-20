<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Notification;
use App\Models\CommentNotification;

class CommentController extends Controller
{
    public function create(Request $request, $postId)
    {
        $this->authorize('create', Comment::class);

        $post = Post::find($postId);

        if (!$post) {
            return redirect()->back()->with('error', 'Post not found');
        }

        $comment = new Comment();
        $comment->description_ = $request->input('description');
        $comment->time_ = now();
        $comment->userID = auth()->user()->userID;
        $comment->postID = $postId;
        $comment->save();

        return redirect()->back()->with('success', 'Comment created successfully');
    }

    public function edit(Request $request, $commentId)
    {
        $comment = Comment::find($commentId);

        $this->authorize('edit', $comment);

        $comment->description_ = $request->input('description');
        $comment->save();

        return redirect()->back()->with('success', 'Comment edited successfully');
    }

    public function delete(Request $request, $commentId)
    {
        $comment = Comment::find($commentId);

        $this->authorize('delete', $comment);

        $comment->delete();

        return redirect()->back()->with('success', 'Comment deleted successfully');
    }

    public function list($postId)
    {
        $post = Post::find($postId);

        if (!$post) {
            return redirect()->back()->with('error', 'Post not found');
        }

        $comments = $post->comments;

        return view('comments.index', [
            'post' => $post,
            'comments' => $comments,
        ]);
    }

    DB::beginTransaction();

    $post = Post::find($comment->postID);

    if ($post->created_by == Auth::user()->userID) {
        DB::commit();
        return redirect()->back()->with('success', 'Comment successfully created');
    }

    // Notificação para o dono do post
    Notification::insert([
        'description_' => 'User commented on your post',
        'time_' => now(),
        'notifies' => $post->created_by,
        'sends_notif' => Auth::user()->userID,
    ]);

    $newNotification = Notification::latest()->first();

    CommentNotification::insert([
        'notificationID' => $newNotification->notificationID,
        'commentID' => $comment->commentID,
        'notification_type' => 'comment_post'
    ]);

    $previous = Comment::find($comment->comment_replies);

    if ($previous && $previous->created_by != Auth::user()->userID) {
        // Notificação para o dono do comentário 
        Notification::insert([
            'description_' => 'User replied to your comment',
            'time_' => now(),
            'notifies' => $previous->created_by,
            'sends_notif' => Auth::user()->userID,
        ]);

        $newNotification = Notification::latest()->first();

        CommentNotification::insert([
            'notificationID' => $newNotification->notificationID,
            'commentID' => $comment->commentID,
            'notification_type' => 'reply_comment'
        ]);
    }

    DB::commit();
}
