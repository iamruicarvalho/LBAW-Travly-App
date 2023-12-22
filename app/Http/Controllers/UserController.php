<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

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
            'header_picture' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Adicione esta linha
            'profile_picture' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Adicione esta linha
        ]);
    
        $user->username = $request->input('username');
        $user->description_ = $request->input('description');
        $user->location = $request->input('location');
    
        if ($request->hasFile('header_picture')) {
            $headerPicturePath = $request->file('header_picture')->store('profile_pictures', 'public');
            $user->header_picture = $headerPicturePath;
        }
    
        if ($request->hasFile('profile_picture')) {
            $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile_picture = $profilePicturePath;
        }
    
        $user->save();
    
        return redirect()->route('profile.show', $id)->with('success', 'Perfil atualizado com sucesso');
    }
    
    
    // public function visiblePosts()
    // {
    //     // returns all the posts from the users I follow
    //     return Post::select('post_.*')
    //         ->fromRaw('post_', 'follows_')
    //         ->where('follows_.followerID', '=', $this->id)
    //         ->where('follows_.followedID', '=', 'post_.created_by');
    // }

    public function searchUsers(Request $request) {
        $query = $request->input('query'); 

        $users = User::whereRaw('LOWER(username) LIKE ?', ['%' . strtolower($query) . '%'])
            ->orWhereRaw('LOWER(name_) LIKE ?', ['%' . strtolower($query) . '%'])
            ->select(['id', 'name_', 'username'])
            ->get();
        
        return response()->json($users);
    }

    public function getFollowers($id) {
        $user = User::find($id);
        
        if (!$user) {
            return redirect()->back()->with('error', 'Usuário não encontrado');   
        }

        return view('partials.displayFollowers', compact('user'));
    }
    public function getFollowing($id) {
        $user = User::find($id);
        
        if (!$user) {
            return redirect()->back()->with('error', 'Usuário não encontrado');   
        }

        return view('partials.displayFollowing', compact('user'));
    }

    public function deleteAccount($id) {
        $user = Auth::user();

        $user->username = "anonymous".$user->id;
        $user->name_ = "Anonymous";
        $user->email = "anonymous".$user->id."@example.com";
        $user->password_ = Hash::make(Str::random(40));

        $user->save();
        Auth::logout();

        return redirect()->route('login');
    }

    public function showEmailLinkRequestForm()
    {
        return view('partials.send_email');
    }

    public function showUpdatePasswordForm()
    {
        return view('partials.recover_password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:250',
            'password' => 'required|min:8|confirmed'
        ]);

        $user = User::where('email', '=', $request->input('email'))->first();
        $user->password_ = Hash::make($request->input('password'));
        $user->save();

        return redirect()->route('home')
            ->withSuccess('You have successfully changed your password!');
    }
}

