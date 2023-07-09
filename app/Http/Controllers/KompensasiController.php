<?php

namespace App\Http\Controllers;

use App\Models\Kompensasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Mahasiswa;
use App\Models\Pengawas;

class KompensasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userEmail = Auth::user()->email;
        $domain = '@mahasiswa.poliban.ac.id';

        // Extract the nim value from the email
        $userNim = substr($userEmail, 0, strpos($userEmail, $domain));

        $mahasiswa = Mahasiswa::where('nim', $userNim)->first();

        $kompensasi = $mahasiswa->kompensasi;

        return view('kompensasi.index', compact('kompensasi'));
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
        //
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
