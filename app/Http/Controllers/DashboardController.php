<?php

namespace App\Http\Controllers;

use App\Models\Music;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index():View
    {

        return view('dashboard',[
            'musics' => Music::all(),
        ]);
    }
}
