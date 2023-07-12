<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengawas extends Model
{
    use HasFactory;

    public function kompensasi()
    {
        return $this->hasMany(Kompensasi::class, 'id_pengawas');
    }
}
