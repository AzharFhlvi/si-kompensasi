<?php

namespace App\Http\Controllers;

use App\Models\Kompensasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Mahasiswa;
use App\Models\Pengawas;
use App\Models\Kegiatan;
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
