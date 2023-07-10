<?php

namespace App\Http\Controllers;

use App\Models\Kompensasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Absensi;
use App\Models\Mahasiswa;
use App\Models\Pengawas;
use App\Models\Kegiatan;
use App\Models\Jurusan;
use App\Models\Prodi;
use App\Models\Kelas;
use App\Models\Ruangan;
use App\Utils\UserUtils;

class KompensasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mahasiswa = UserUtils::getCurrentMahasiswa();

        $kompensasi = $mahasiswa->kompensasi;
        $kegiatanList = $mahasiswa->kegiatan;

        return view('kompensasi.index', compact('kompensasi', 'kegiatanList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('kompensasi.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'deskripsi' => 'required',
            'jam' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        // Create a new Kegiatan instance
        $kegiatan = new Kegiatan();
        $kegiatan->deskripsi = $validatedData['deskripsi'];
        $kegiatan->jam = $validatedData['jam'];
        $kegiatan->status = 0;
        $kegiatan->id_mahasiswa = UserUtils::getCurrentMahasiswa()->id;
        $kegiatan->save();
        return redirect()->back()->with('success', 'Form submitted successfully!');
    }

    public function input()
    {
        $jurusan = Jurusan::all();
        $prodi = Prodi::all();
        $kelas = Kelas::all();
        $ruangan = Ruangan::all();
        $pengawas = Pengawas::all();
        return view('kompensasi.input', compact('jurusan', 'prodi', 'kelas', 'ruangan', 'pengawas'));
    }
    public function getProdi($jurusanId)
    {
        $prodi = Prodi::where('id_jurusan', $jurusanId)->get();
        return response()->json($prodi);
    }

    public function getKelas($prodiId)
    {
        $kelas = Kelas::where('id_prodi', $prodiId)->get();
        return response()->json($kelas);
    }

    public function inputStore(Request $request)
    {
        $validatedData = $request->validate([
            'jurusan' => 'required',
            'prodi' => 'required',
            'kelas' => 'required',
            'pengawas' => 'required',
            'ruangan' => 'required',
            'mulai_kompensasi' => 'required',
        ]);

        // Retrieve the mahasiswas with the specified kelas_id
        $mahasiswas = Mahasiswa::where('id_kelas', $validatedData['kelas'])->get();

        // Retrieve the absensi records for the mahasiswas
        $absensis = Absensi::whereIn('id_mahasiswa', $mahasiswas->pluck('id'))->get();

        // Create new Kompensasi instances based on the mahasiswas and absensis
        $kompensasis = [];
        foreach ($mahasiswas as $mahasiswa) {
            $kompensasi = new Kompensasi();
            $kompensasi->id_mahasiswa = $mahasiswa->id;
            $kompensasi->id_ruangan = $validatedData['ruangan'];
            $kompensasi->id_pengawas = $validatedData['pengawas'];
            $kompensasi->mulai_kompensasi = $validatedData['mulai_kompensasi'];
            
            // Calculate jumlah_kompensasi based on the absensi records for the mahasiswa
            $jumlahKompensasi = $this->calculateJumlahKompensasi($absensis, $mahasiswa->id);
            $alfa = $this->calculateAbsensiValue($absensis, $mahasiswa->id, 'alfa');
            $izin = $this->calculateAbsensiValue($absensis, $mahasiswa->id, 'izin');
            $sakit = $this->calculateAbsensiValue($absensis, $mahasiswa->id, 'sakit');

            $kompensasi->jumlah_kompensasi = $jumlahKompensasi;
            $kompensasi->alfa = $alfa;
            $kompensasi->izin = $izin;
            $kompensasi->sakit = $sakit;

            $kompensasis[] = $kompensasi;
        }
        
        // Save the new Kompensasi instances to the database
        foreach ($kompensasis as $kompensasi) {
            $kompensasi->save();
        }

        // Redirect or return a response
        return redirect()->back()->with('success', 'Kompensasi records created successfully.');
    }

    private function calculateJumlahKompensasi($absensis, $mahasiswaId)
    {
        $mahasiswaAbsensis = $absensis->where('id_mahasiswa', $mahasiswaId);
        
        $jumlahKompensasi = $mahasiswaAbsensis->sum(function ($absensi) {
            $absensiArray = collect(json_decode($absensi->absensi, true));
            $alfaCount = $absensiArray->filter(function ($item) {
                return $item === 'alfa';
            })->count();
            $izinCount = $absensiArray->filter(function ($item) {
                return $item === 'izin';
            })->count();
            $sakitCount = $absensiArray->filter(function ($item) {
                return $item === 'sakit';
            })->count();
            
            return $alfaCount + $izinCount + $sakitCount;
        });
        
        return $jumlahKompensasi;
    }

    private function calculateAbsensiValue($absensis, $mahasiswaId, $key)
    {
        $mahasiswaAbsensis = $absensis->where('id_mahasiswa', $mahasiswaId);
        
        $value = $mahasiswaAbsensis->sum(function ($absensi) use ($key) {
            $absensiArray = collect(json_decode($absensi->absensi, true));
            $count = $absensiArray->filter(function ($item) use ($key) {
                return $item === $key;
            })->count();
            
            return $count;
        });
        
        return $value;
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Kompensasi  $kompensasi
     * @return \Illuminate\Http\Response
     */
    public function show(Kompensasi $kompensasi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Kompensasi  $kompensasi
     * @return \Illuminate\Http\Response
     */
    public function edit(Kompensasi $kompensasi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Kompensasi  $kompensasi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kompensasi $kompensasi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Kompensasi  $kompensasi
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kompensasi $kompensasi)
    {
        //
    }
}
