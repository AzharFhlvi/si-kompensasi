<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;
    protected $fillable = [
        'nim', // Add 'nim' to the fillable array
        'nama',
        'alamat',
        'tanggal_lahir',
        'no_hp',
        'jenis_kelamin',
        'jurusan',
        'prodi',
        'id_kelas',
        'tahun_ajaran',
    ];

    public function kompensasi()
    {
        return $this->hasOne(Kompensasi::class, 'id_mahasiswa');
    }

    public function kegiatan()
    {
        return $this->hasMany(Kegiatan::class, 'id_mahasiswa');
    }
    
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }
}
