<?php

namespace App\Http\Controllers;

use App\Models\Kompensasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Absensi;
use App\Models\Mahasiswa;
use App\Models\Matkul;
use App\Models\Pengawas;
use App\Models\Kegiatan;
use App\Models\Jurusan;
use App\Models\Prodi;
use App\Models\Kelas;
use PDF;
use App\Models\Ruangan;
use App\Utils\UserUtils;
use Carbon\Carbon;
use Twilio\Rest\Client;

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
        $tahunAjaran = Mahasiswa::distinct()->pluck('tahun_ajaran');
        return view('kompensasi.input', compact('jurusan', 'prodi', 'kelas', 'ruangan', 'pengawas', 'tahunAjaran'));
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

    private function calculateJadwalKompensasi($mulaiKompensasi, $jamTotal, $jamMaksimalPerHari)
{
    if ($jamTotal <= 0) {
        return $mulaiKompensasi; // No kompensasi needed, return the starting date as jadwal_kompensasi
    }

    // Calculate the number of days required to complete the kompensasi
    $jumlahHari = ceil($jamTotal / $jamMaksimalPerHari);

    // Add the calculated number of days to the starting date
    $jadwalKompensasi = Carbon::parse($mulaiKompensasi)->addDays($jumlahHari)->toDateString();

    return $jadwalKompensasi;
}

    public function inputStore(Request $request)
    {
        $validatedData = $request->validate([
            'tahun_ajaran' => 'required',
            'jurusan' => 'required|numeric',
            'prodi' => 'required|numeric',
            'kelas' => 'required|numeric',
            'pengawas' => 'required|numeric',
            'ruangan' => 'required|numeric',
            'mulai_kompensasi' => 'required|date|after_or_equal:today',
        ]);

        // Retrieve the mahasiswas with the specified kelas_id
        $mahasiswas = Mahasiswa::where('id_kelas', $validatedData['kelas'])
            ->where('tahun_ajaran', $validatedData['tahun_ajaran'])
        ->get();

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

// Calculate the jadwal_kompensasi using the dynamic method
$jamMaksimalPerHari = 6; // Set the maximum hours per day
$kompensasi->jadwal_kompensasi = $this->calculateJadwalKompensasi($validatedData['mulai_kompensasi'], $jumlahKompensasi, $jamMaksimalPerHari);

$kompensasis[] = $kompensasi;
        }
        
        // Save the new Kompensasi instances to the database
        foreach ($kompensasis as $kompensasi) {
            $kompensasi->save();
        }

        $pengawas = Pengawas::where('id', $validatedData['pengawas'])->first();
        $kelas = Kelas::where('id', $validatedData['kelas'])->first();
        $ruangan = Ruangan::where('id', $validatedData['ruangan'])->first();

        // $this->sendWhatsapp($pengawas, $kelas, $ruangan, $validatedData['mulai_kompensasi']);

        // Redirect or return a response
        return redirect()->back()->with('success', 'Kompensasi records created successfully.');
    }

    public function sendWhatsapp($pengawas, $kelas, $ruangan, $jadwal)
    {
        $twilioSid = getenv('TWILIO_SID');
        $twilioToken = getenv('TWILIO_AUTH_TOKEN');
        $twilioFrom = getenv('TWILIO_FROM');
    
        $client = new Client($twilioSid, $twilioToken);
        $messageBody = "Pengawas dengan nama ". $pengawas->nama ." telah dijadwalkan untuk mengawas kelas " . $kelas->nama_kelas . " di ruangan " . $ruangan->nama_ruangan . "pada tanggal " . $jadwal;
        $message = $client->messages
                  ->create("whatsapp:".$pengawas->no_hp, // to
                  array(
                    "from" => "whatsapp:". $twilioFrom,
                    "body" => $messageBody,
                  )
                  );
    }

    private function calculateJumlahKompensasi($absensis, $mahasiswaId)
    {
        $mahasiswaAbsensis = $absensis->where('id_mahasiswa', $mahasiswaId);

        $jumlahKompensasi = $mahasiswaAbsensis->sum(function ($absensi) {
            $absensiArray = collect(json_decode($absensi->absensi, true));
            $matkulId = $absensi->id_matkul;

            // Retrieve the sks value from the matkuls table based on the matkulId
            $sks = Matkul::where('id', $matkulId)->value('sks');

            $izinCount = $absensiArray->filter(function ($item) {
                return $item === 'izin';
            })->count();

            $sakitCount = $absensiArray->filter(function ($item) {
                return $item === 'sakit';
            })->count();

            $alfaCount = $absensiArray->filter(function ($item) {
                return $item === 'alfa';
            })->count();

            $jumlahKompensasi = ($izinCount * 1 * $sks) + ($sakitCount * 0.5 * $sks) + ($alfaCount * 2 * $sks);

            return $jumlahKompensasi;
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

    public function master()
    {
        $currentDate = Carbon::now()->toDateString();
        $kompensasiList = Kompensasi::where(function ($query) use ($currentDate) {
            $query->where('jumlah_kompensasi', 0)
                ->where('status', 0);
        })
        ->orWhere(function ($query) use ($currentDate) {
            $query->where('jadwal_kompensasi', '<', $currentDate)
                ->where('status', 0);
        })
        ->get();
            
        return view('kompensasi.master', compact('kompensasiList'));
    }

    public function tuntas(Request $request)
    {
        Kompensasi::where('jumlah_kompensasi', 0)
        ->where('status', 0)
        ->update(['status' => 1]);

        return redirect()->back()->with('success', 'Kompensasi berhasil ditandai tuntas.');
    }

    public function tolak(Request $request)
    {
        $currentDate = Carbon::now()->toDateString();
        Kompensasi::where('jadwal_kompensasi', '<', $currentDate)
        ->where('status', 0)
        ->update(['status' => 2]);

        return redirect()->back()->with('success', 'Kompensasi berhasil ditandai tuntas.');
    }

    public function pengawasDetilKelas($id_kelas)
    {
        $pengawas = UserUtils::getCurrentPengawas();

        $kompensasiList = Kompensasi::whereHas('mahasiswa', function ($query) use ($id_kelas) {
            $query->where('id_kelas', $id_kelas);
        })
            ->where('id_pengawas', $pengawas->id)
            ->get();

        $namaKelas = Kelas::where('id', $id_kelas)->value('nama_kelas');

        return view('kompensasi.detil-mhs', compact('kompensasiList', 'namaKelas'));
    }

    public function downloadSurat($id)
    {
        $kompensasi = Kompensasi::find($id);
        $mahasiswa = $kompensasi->mahasiswa; // Retrieve the associated Mahasiswa model
        $kegiatanList = $mahasiswa->kegiatan;
        $pdf = \PDF::loadView('kompensasi.surat', compact('mahasiswa', 'kompensasi', 'kegiatanList'));
        return $pdf->download('surat-kompensasi.pdf');
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
