<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $kegiatanList = Kegiatan::where('id_mahasiswa', $id)->get();
        $mahasiswa = Mahasiswa::find($id);
        $kompensasi = $mahasiswa->kompensasi;

        return view('kegiatan.index', compact('kegiatanList', 'mahasiswa', 'kompensasi'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $kegiatan = Kegiatan::find($id);
        $mahasiswa = $kegiatan->mahasiswa; // Retrieve the associated Mahasiswa model
        $kompensasi = $mahasiswa->kompensasi;

        if($request->has('approve')){
            $kegiatan->status = 1;
            $kegiatan->jam = $request->jam;
            $kegiatan->save();
            
            $kompensasi->jumlah_kompensasi = ($kompensasi->jumlah_kompensasi < $kegiatan->jam) ? 0 : ($kompensasi->jumlah_kompensasi - $kegiatan->jam);
            $kompensasi->save();
        } else if($request->has('reject')){
            $kegiatan = Kegiatan::find($id);$kegiatan->status = 2;
            $kegiatan->save();
        }
        // dd($kegiatan->mahasiswa);
        return redirect()->route('kegiatan-mhs', $kegiatan->mahasiswa->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Kegiatan  $kegiatan
     * @return \Illuminate\Http\Response
     */
    public function show(Kegiatan $kegiatan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Kegiatan  $kegiatan
     * @return \Illuminate\Http\Response
     */
    public function edit(Kegiatan $kegiatan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Kegiatan  $kegiatan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kegiatan $kegiatan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Kegiatan  $kegiatan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kegiatan $kegiatan)
    {
        //
    }
}
