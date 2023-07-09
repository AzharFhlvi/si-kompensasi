<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    public function kompensasi()
    {
        return $this->hasOne(Kompensasi::class, 'id_mahasiswa');
    }
}
