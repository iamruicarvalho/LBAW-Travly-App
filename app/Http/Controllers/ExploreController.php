<?php

namespace App\Http\Controllers;
use App\Models\User; 


use Illuminate\Http\Request;

class ExploreController extends Controller
{
    public function index()
    {
        return view('pages.explore');
    }
    
    public function explore()
    {
        // Obtenha usuários aleatórios do seu modelo User
        $suggestedUsers = User::inRandomOrder()->take(8)->get();
    
        // Retorne a visão com os dados necessários
        return view('pages.explore', compact('suggestedUsers'));
    }
    
}
