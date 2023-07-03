<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // dd('home');
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // dd('home');
        $chartData = [
            ['year' => '2019', 'sales' => 100],
            ['year' => '2020', 'sales' => 200],
            ['year' => '2021', 'sales' => 150],
        ];
        return view('home', compact('chartData'));
    }
}
