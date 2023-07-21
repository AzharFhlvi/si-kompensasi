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
            case 4:
                return $this->redirectToDashboard('super');
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

    public function getKompensasi()
    {
        $now = Carbon::now();

        return Kompensasi::whereYear('jadwal_kompensasi', $now->year)
        ->whereMonth('jadwal_kompensasi', $now->month)
        ->get();
    }

    public function getKompensasiOnGoing()
    {
        return $this->getKompensasi()->where('status', 0);
    }

    public function getKompensasiTuntas()
    {
        return $this->getKompensasi()->where('status', 1);
    }

    public function getKompensasiTidakTuntas()
    {
        return $this->getKompensasi()->where('status', 2);
    }

    public function dashAdm()
    {
        $total_kompensasi = $this->getKompensasi()->count();
        
        $alfaCountOnGoing = $this->getKompensasiOnGoing()->sum('alfa');
        $izinCountOnGoing = $this->getKompensasiOnGoing()->sum('izin');
        $sakitCountOnGoing = $this->getKompensasiOnGoing()->sum('sakit');
        $chartDataOnGoing = [
            ['type' => 'Alfa', 'value' => $alfaCountOnGoing],
            ['type' => 'Izin', 'value' => $izinCountOnGoing],
            ['type' => 'Sakit', 'value' => $sakitCountOnGoing],
        ];
        $onGoingTotal = $this->getKompensasiOnGoing()->count();
        
        $alfaCountTuntas = $this->getKompensasiTuntas()->sum('alfa');
        $izinCountTuntas = $this->getKompensasiTuntas()->sum('izin');
        $sakitCountTuntas = $this->getKompensasiTuntas()->sum('sakit');
        $chartDataTuntas = [
            ['type' => 'Alfa', 'value' => $alfaCountTuntas],
            ['type' => 'Izin', 'value' => $izinCountTuntas],
            ['type' => 'Sakit', 'value' => $sakitCountTuntas],
        ];
        $tuntasTotal = $this->getKompensasiTuntas()->count();
        
        $alfaCountTidakTuntas = $this->getKompensasiTidakTuntas()->sum('alfa');
        $izinCountTidakTuntas = $this->getKompensasiTidakTuntas()->sum('izin');
        $sakitCountTidakTuntas = $this->getKompensasiTidakTuntas()->sum('sakit');
        $chartDataTidakTuntas = [
            ['type' => 'Alfa', 'value' => $alfaCountTidakTuntas],
            ['type' => 'Izin', 'value' => $izinCountTidakTuntas],
            ['type' => 'Sakit', 'value' => $sakitCountTidakTuntas],
        ];
        $tidakTuntasTotal = $this->getKompensasiTidakTuntas()->count();
        
        return view('dashboard.admin', compact('total_kompensasi','chartDataOnGoing', 'onGoingTotal', 'chartDataTuntas', 'tuntasTotal', 'chartDataTidakTuntas', 'tidakTuntasTotal'));
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

    public function dashPws()
    {
        // Get the pengawas
        $pengawas = UserUtils::getCurrentPengawas();

        // Retrieve the kompensasi data based on the pengawas_id
        $kompensasis = Kompensasi::with(['mahasiswa.kelas', 'ruangan', 'pengawas'])
            ->where('id_pengawas', $pengawas->id)
            ->orderBy('mulai_kompensasi', 'asc')
            ->get()
            ->groupBy('kelas');

        return view('dashboard.pengawas', compact('kompensasis'));
    }

    public function dashSuper()
    {
        return view('dashboard.super');
    }

}
