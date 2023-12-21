<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostLikeController extends Controller
{
    public function store(Post $post, Request $request)
    {
        // Verifica se o usuário já curtiu o post
        if (!$post->likes->contains($request->user()->id)) {
            // Adiciona o like
            $post->likes()->attach($request->user()->id);
            // Incrementa o contador de likes
            $post->increment('likes_');
        }

        return back();
    }

    public function destroy(Post $post, Request $request)
    {
        // Verifica se o usuário que está removendo a curtida é o mesmo que criou o post
        if ($request->user()->id == $post->created_by) {
            return back()->withErrors('Você não pode descurtir o próprio post.');
        }

        // Verifica se o usuário já curtiu o post
        if ($post->likes->contains($request->user()->id)) {
            // Remove a curtida
            $post->likes()->detach($request->user()->id);
            // Decrementa o contador de likes
            $post->decrement('likes_');
        }

        return back();
    }
}