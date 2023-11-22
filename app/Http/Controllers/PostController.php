<?php

// app/Http/Controllers/PostController.php

// app/Http/Controllers/PostController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function store(Request $request)
    {
        // Valide os dados conforme necessário
        $request->validate([
            'content' => 'required|string|max:255',
        ]);

        // Crie um novo post
        $post = new Post();
        $post->content_ = $request->input('content');
        // Defina o ID do usuário para o post (ajuste conforme necessário)
        $post->created_by = auth()->user()->userID;
        $post->save();

        // Redirecione para a página principal
        return redirect()->route('home');
    }
}


