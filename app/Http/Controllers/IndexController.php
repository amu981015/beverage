<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class IndexController extends Controller
{
    public function index()
    {
        return view("front.relly");
    }

    public function faq()
    {
        return view("front.faq");
    }
    public function about()
    {
        return view("front.about");
    }
    public function map()
    {
        return view("front.map");
    }
    public function register()
    {
        return view("front.register");
    }
    public function login()
    {
        return view("front.login");
    }
}
