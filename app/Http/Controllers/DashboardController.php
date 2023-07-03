<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
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
        $role = Auth::user()->role; // Assuming the user role is stored in the 'role' field

        switch ($role) {
            case 1:
                return $this->redirectToDashboard('admin');
                break;
            case 2:
                return $this->redirectToDashboard('mahasiswa');
                break;
            case 3:
                return $this->redirectToDashboard('pengawas');
                break;
            default:
                return redirect()->route('login'); // Redirect to login if the user role is not recognized
                break;
        }
    }

    private function redirectToDashboard($dashboardRoute)
    {
        return redirect()->route('dashboard-' . $dashboardRoute);
    }

    public function dashAdm()
    {
        $chartData = [
            ['year' => '2019', 'sales' => 100],
            ['year' => '2020', 'sales' => 200],
            ['year' => '2021', 'sales' => 150],
        ];
        return view('dashboard.admin', compact('chartData'));
    }

    public function dashMhs()
    {
        $chartData = [
            ['year' => '2019', 'sales' => 100],
            ['year' => '2020', 'sales' => 200],
            ['year' => '2021', 'sales' => 150],
        ];
        return view('dashboard.mahasiswa', compact('chartData'));
    }

}
