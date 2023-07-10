<?php
namespace App\Utils;

use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Models\Kelas;
use Illuminate\Support\Facades\Auth;

class UserUtils
{
    public static function getCurrentMahasiswa()
    {
        $userEmail = Auth::user()->email;
        $domain = '@mahasiswa.poliban.ac.id';

        // Extract the nim value from the email
        $userNim = substr($userEmail, 0, strpos($userEmail, $domain));

        return Mahasiswa::where('nim', $userNim)->first();
    }

    public static function getProdi($jurusanId)
    {
        $prodi = Prodi::where('id_jurusan', $jurusanId)->get();
        return response()->json($prodi);
    }

    public static function getKelas($prodiId)
    {
        $kelas = Kelas::where('id_prodi', $jurusanId)->get();
        return response()->json($kelas);
    }
}
