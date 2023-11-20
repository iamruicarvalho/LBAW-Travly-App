<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // Exibe o perfil do usuário
    public function showProfile($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('home')->with('error', 'Usuário não encontrado');
        }

        return view('user.profile', compact('user'));
    }

    // Exibe edição do perfil do usuário
    public function editProfile($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('home')->with('error', 'Usuário não encontrado');
        }

        return view('user.edit', compact('user'));
    }

    // Atualiza o perfil do usuário
    public function updateProfile(Request $request, $userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('home')->with('error', 'Usuário não encontrado');
        }

        $request->validate([
            'name_' => 'required|string|max:255',
            'email' => 'required|email|unique:user_,email,' . $userId,
        ]);

        $user->name_ = $request->input('name_');
        $user->email = $request->input('email');
        $user->save();

        return redirect()->route('profile.show', $userId)->with('success', 'Perfil atualizado com sucesso');
    }
}
