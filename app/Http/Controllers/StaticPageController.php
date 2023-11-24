<?php

namespace App\Http\Controllers;

class StaticPageController extends Controller
{
    public function faq()
    {
        return view('static.faq');
    }

    public function about()
    {
        return view('static.about');
    }

    public function privacy_policy()
    {
        return view('static.privacy_policy');
    }
    public function help()
    {
        return view('static.help');
    }
}
