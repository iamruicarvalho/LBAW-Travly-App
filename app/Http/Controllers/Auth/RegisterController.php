<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    // only guests (unauthenticated users) can access the registration form
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Display a login form.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Validate user inputs.
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name_' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password_' => 'required|string|min:8|confirmed',
        ]);
    }

    /**
     * Register a new user.
     */
    protected function create(array $data)
    {
        return User::create([
            'name_' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password_' => Hash::make($data['password']),
        ]);

        $credentials = [
            'email' => $data['email'],
            'password' => $data['password'],
        ];

        // Attempt to log in
        if (Auth::attempt($credentials)) {
            session_regenerate_id();
            // $request->session()->regenerate();

            return redirect()->route('/')->with('success', 'You have successfully registered and logged in!');
        }

        // If login fails, handle it accordingly
        return redirect()->route('/register')
            ->with('error', 'Failed to register. Try again.');
    }
}
