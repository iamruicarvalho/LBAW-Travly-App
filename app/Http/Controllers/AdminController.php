<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Models\Admin;

class AdminController extends Controller
{
    public function index()
    {
        // Página inicial do painel de administração
        $this->authorize('show', Admin::class);
        return view('admin.index');
    }
}
