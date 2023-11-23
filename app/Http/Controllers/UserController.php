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
    public function updateProfile(Request $request)
    {
        $user = Auth::user(); // Retrieve the authenticated user

        $user->name_ = $request->input('name');
        $user->description = $request->input('description');
        $user->location = $request->input('location');

        //$user->save(); // Save the changes to the database

        return redirect()->route('profile.show'); // Redirect to the profile page after updating
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
