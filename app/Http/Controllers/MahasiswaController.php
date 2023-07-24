<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use App\Models\Jurusan;
use App\Models\Prodi;
use App\Models\Kelas;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mahasiswaList = Mahasiswa::all()
        ->sortByDesc(function ($mahasiswa) {
            return explode('-', $mahasiswa->tahun_ajaran)[0];
        });
        return view('master.mahasiswa.index', compact('mahasiswaList'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $mahasiswaList = Mahasiswa::where('nama', 'like', '%' . $query . '%')
            ->orWhere('nim', 'like', '%' . $query . '%')
            ->get();

        return view('master.mahasiswa.index', compact('mahasiswaList'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $jurusan = Jurusan::all();
        $prodi = Prodi::all();
        $kelas = Kelas::all();
        return view('master.mahasiswa.create', compact('jurusan', 'prodi', 'kelas'));
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
            'nim' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'no_hp' => 'required|string|max:15',
            'jenis_kelamin' => 'required|in:L,P',
            'kelas' => 'required|exists:kelas,id',
            'tahun_ajaran_start' => 'required|integer|min:1900|max:9999',
            'tahun_ajaran_end' => 'required|integer|min:1900|max:9999',
        ]);
    
        // Combine the start and end years to create the "Tahun Ajaran"
        $tahun_ajaran = $request->tahun_ajaran_start . '-' . $request->tahun_ajaran_end;
    
        // Create a new Mahasiswa instance and fill it with the validated data
        $mahasiswa = new Mahasiswa();
        $mahasiswa->fill($validatedData);
        $mahasiswa->email = $validatedData['nim'] . '@mahasiswa.poliban.ac.id';
        $mahasiswa->id_kelas = $validatedData['kelas'];
        $mahasiswa->tahun_ajaran = $tahun_ajaran;
    
        // Save the Mahasiswa instance to the database
        $mahasiswa->save();
    
        // Redirect or do any further actions after successful storage
        // For example:
        return redirect()->route('mahasiswa')->with('success', 'Mahasiswa added successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Mahasiswa  $mahasiswa
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $mahasiswa = Mahasiswa::where('id', $id)->first();
        $jurusan = Jurusan::all();
        $prodi = Prodi::all();
        $kelas = Kelas::all();
        $kelasMhs=Kelas::find($mahasiswa->id_kelas);
        $prodiMhs=Prodi::find($kelasMhs->id_prodi);
        $jurusanMhs=Jurusan::find($prodiMhs->id_jurusan);
        return view('master.mahasiswa.show', compact('mahasiswa', 'jurusan', 'prodi', 'kelas', 'jurusanMhs', 'prodiMhs', 'kelasMhs'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Mahasiswa  $mahasiswa
     * @return \Illuminate\Http\Response
     */
    public function edit(Mahasiswa $mahasiswa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Mahasiswa  $mahasiswa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);

        // Validate the request data
        $validatedData = $request->validate([
            'nim' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'no_hp' => 'required|string|max:15',
            'jenis_kelamin' => 'required|in:L,P',
            'kelas' => 'required|exists:kelas,id',
            'tahun_ajaran_start' => 'required|integer|min:1900|max:9999',
            'tahun_ajaran_end' => 'required|integer|min:1900|max:9999',
        ]);

        // Combine the start and end years to create the "Tahun Ajaran" from the request data
        $tahun_ajaran = $request->tahun_ajaran_start . '-' . $request->tahun_ajaran_end;

        // Check if the "Tahun Ajaran" has changed
        if ($mahasiswa->tahun_ajaran != $tahun_ajaran) {
            // If the "Tahun Ajaran" is different, create a new Mahasiswa record using the store method
            $newMahasiswa = new Mahasiswa();
            $newMahasiswa->fill($validatedData);
            $newMahasiswa->email = $validatedData['nim'] . '@mahasiswa.poliban.ac.id';
            $newMahasiswa->id_kelas = $validatedData['kelas'];
            $newMahasiswa->tahun_ajaran = $tahun_ajaran;
            $newMahasiswa->save();
        } else {
            // If the "Tahun Ajaran" is the same, update the existing Mahasiswa record
            $mahasiswa->fill($validatedData);
            $mahasiswa->email = $validatedData['nim'] . '@mahasiswa.poliban.ac.id';
            $mahasiswa->id_kelas = $validatedData['kelas'];
            $mahasiswa->save();
        }

        // Redirect or do any further actions after successful update
        // For example:
        return redirect()->route('mahasiswa')->with('success', 'Mahasiswa updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Mahasiswa  $mahasiswa
     * @return \Illuminate\Http\Response
     */
    public function destroy(Mahasiswa $mahasiswa)
    {
        //
    }
}
