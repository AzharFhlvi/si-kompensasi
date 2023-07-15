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
            'jurusan' => 'required|numeric',
            'prodi' => 'required|numeric',
            'kelas' => 'required|numeric',
            'pengawas' => 'required|numeric',
            'ruangan' => 'required|numeric',
            'mulai_kompensasi' => 'required|date|after_or_equal:today',
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
            $kompensasi->jadwal_kompensasi = $validatedData['mulai_kompensasi'];
            
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

        $pengawas = Pengawas::where('id', $validatedData['pengawas'])->first();
        $kelas = Kelas::where('id', $validatedData['kelas'])->first();
        $ruangan = Ruangan::where('id', $validatedData['ruangan'])->first();

        $this->sendWhatsapp($pengawas, $kelas, $ruangan, $validatedData['mulai_kompensasi']);

        // Redirect or return a response
        return redirect()->back()->with('success', 'Kompensasi records created successfully.');
    }

    public function sendWhatsapp($pengawas, $kelas, $ruangan, $jadwal)
    {
        $twilioSid = getenv('TWILIO_SID');
        $twilioToken = getenv('TWILIO_AUTH_TOKEN');
        $twilioFrom = getenv('TWILIO_FROM');
    
        $client = new Client($twilioSid, $twilioToken);
        $messageBody = "Pengawas dengan nama ". $pengawas->nama ." telah dijadwalkan untuk mengawas kelas " . $kelas->nama . " di ruangan " . $ruangan->nama . "pada tanggal " . $jadwal;
        $message = $client->messages
                  ->create("whatsapp:".$pengawas->no_hp, // to
                  array(
                    "from" => "whatsapp:". $twilioFrom,
                    "body" => $messageBody,
                  )
                  );

    //               $message = $twilio->messages
    //   ->create("whatsapp:+6283863115468", // to
    //     array(
    //       "from" => "whatsapp:+14155238886",
    //       "body" => Your appointment is coming up on July 21 at 3PM
    //     )
    //   );
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
        $currentDate = Carbon::now()->toDateTimeString();
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
        $currentDate = Carbon::now()->toDateTimeString();
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
