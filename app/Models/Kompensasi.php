<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pengawas;
use App\Models\Ruangan;

class Kompensasi extends Model
{
    use HasFactory;

    public function pengawas()
    {
        return $this->belongsTo(Pengawas::class, 'id_pengawas');
    }

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'id_ruangan');
    }
}
