<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;
    protected $table = 'kelas';
    protected $primaryKey = 'id';

    
    
    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'id_prodi');
    }
}
