<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    /**
     * Главная страница.
     */
    public function index()
    {
        return view('main.index');
    }
}
