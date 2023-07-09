<?php
namespace App\Utils;

use App\Models\Mahasiswa;
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
}
