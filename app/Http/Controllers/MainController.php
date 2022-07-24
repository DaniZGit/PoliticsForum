<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index() {
        return view('content.index', ['tags' => 'Janša, Golob, Svoboda, Kriza']);
    }

    public function category()
    {
        return view('content.category', ['tags' => 'Janša, Golob, Svoboda, Kriza']);
    }

    public function show()
    {
        return view('content.show', ['tags' => 'Janša, Golob, Svoboda, Kriza']);
    }
}
