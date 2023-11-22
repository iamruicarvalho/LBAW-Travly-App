<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // Exibe o perfil do usuário
    public function showProfile(){
        return view('pages.profile');
    }
    /*
    public function showProfile($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('home')->with('error', 'Usuário não encontrado');
        }

        return view('pages.profile', compact('user'));
    }*/

    // Exibe edição do perfil do usuário
    public function editProfile(){
        return view('partials.ProfileEdit');
    }
    
    /*public function editProfile($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('home')->with('error', 'Usuário não encontrado');
        }

        return view('user.edit', compact('user'));
    }*/

    // Atualiza o perfil do usuário
    public function updateProfile(){
        return redirect()->route('profile.show');
    }
    /*public function updateProfile(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('home')->with('error', 'Usuário não encontrado');
        }

        $request->validate([
            'name_' => 'required|string|max:255',
            'email' => 'required|email|unique:user_,email,' . $id,
        ]);

        $user->name_ = $request->input('name_');
        $user->email = $request->input('email');
        $user->save();

        return redirect()->route('user.profile', $id)->with('success', 'Perfil atualizado com sucesso');
    }*/
}
