<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // Exibe o perfil do usuário
    public function showProfile(Request $request, $id)
    {   
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('home')->with('error', 'Usuário não encontrado');
        }

        return view('pages.profile', compact('user'));
    }

    // Exibe edição do perfil do usuário
    public function editProfile(Request $request, $id)
    {

        $user = User::find($id);
        $allUsers = User::all();

        if (!$user) {
            return redirect()->route('home')->with('error', 'Usuário não encontrado');
        }

        return view('partials.profileEdit', compact('user', 'allUsers'));
    }

    // Atualiza o perfil do usuário
    public function updateProfile(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('home')->with('error', 'Usuário não encontrado');
        }

        $request->validate([
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('user_')->ignore($user->id),
            ],
            'description' => 'required|string',
            'location' => 'required|string',
        ]);

        $user->username = $request->input('username');
        $user->description_ = $request->input('description');
        $user->location = $request->input('location');
        $user->save();

        return redirect()->route('profile.show', $id)->with('success', 'Perfil atualizado com sucesso');
    }
}

