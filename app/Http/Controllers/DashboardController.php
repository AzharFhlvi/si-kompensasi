<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kompensasi;
use Carbon\Carbon;
use App\Models\Mahasiswa;
use App\Utils\UserUtils;

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
        $now = Carbon::now();

        $total_kompensasi = Kompensasi::whereYear('jadwal_kompensasi', $now->year)
                                    ->whereMonth('jadwal_kompensasi', $now->month)
                                    ->count();

        $alfaCount =  Kompensasi::whereYear('jadwal_kompensasi', $now->year)
        ->whereMonth('jadwal_kompensasi', $now->month)
        ->sum('alfa');

        $izinCount = Kompensasi::whereYear('jadwal_kompensasi', $now->year)
        ->whereMonth('jadwal_kompensasi', $now->month)
        ->sum('izin');

        $sakitCount = Kompensasi::whereYear('jadwal_kompensasi', $now->year)
        ->whereMonth('jadwal_kompensasi', $now->month)
        ->sum('sakit');

        $chartData = [
            ['absen' => 'alfa', 'kompensasi' => $alfaCount],
            ['absen' => 'izin', 'kompensasi' => $izinCount],
            ['absen' => 'sakit', 'kompensasi' => $sakitCount],
        ];

        // dd($chartData);
        return view('dashboard.admin', compact('total_kompensasi','chartData'));
    }

    public function dashMhs()
    {
        $mahasiswa = UserUtils::getCurrentMahasiswa();

        $kompensasi = $mahasiswa->kompensasi;

        $sumAbsen = $kompensasi->alfa + $kompensasi->izin + $kompensasi->sakit;
        

       $chartData = [
            ['type' => 'Alfa', 'value' => $kompensasi->alfa ?? 0],
            ['type' => 'Izin', 'value' => $kompensasi->izin ?? 0],
            ['type' => 'Sakit', 'value' => $kompensasi->sakit ?? 0],
        ];

        return view('dashboard.mahasiswa', compact('chartData', 'kompensasi', 'sumAbsen'));
    }

}
