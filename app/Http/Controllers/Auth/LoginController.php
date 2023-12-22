<?php
 
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

use Illuminate\View\View;

class LoginController extends Controller
{
    public function username()
    {
        $user = User::find($this->id());
        return $user->username;
    }

    /**
     * Display a login form.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect('home');
        } else {
            return view('auth.login');
        }
    }

    /**
     * Handle an authentication attempt.
     */
    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
 
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
 
            return redirect()->intended('home');
        }
 
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Log out the user from application.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        \Log::info('User successfully logged out.');
        return redirect()->route('login')
            ->withSuccess('You have logged out successfully!');
    }
    
    public function recoverPassword(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:user_,username', 
            'email' => 'required|email|unique:user_,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([   
            'name_' => $request->input('name'),
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'password_' => Hash::make($request->input('password')),
        ]);

        $user->save();
    }
}
