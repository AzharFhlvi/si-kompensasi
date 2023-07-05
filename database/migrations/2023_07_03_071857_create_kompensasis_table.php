<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kompensasis', function (Blueprint $table) {
            $table->id();
            $table->integer('id_mahasiswa');
            $table->integer('alfa')->default(0);
            $table->integer('sakit')->default(0);
            $table->integer('izin')->default(0);
            $table->integer('jumlah_kompensasi')->default(0);
            $table->date('jadwal_kompensasi');
            $table->integer('id_ruangan');
            $table->integer('id_pengawas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kompensasis');
    }
};
