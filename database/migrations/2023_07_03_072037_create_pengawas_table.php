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
        Schema::create('pengawas', function (Blueprint $table) {
            $table->id();
            $table->string('nip', 255)->unique();
            $table->string('nama', 255);
            $table->text('alamat');
            $table->date('tanggal_lahir');
            $table->string('email', 255)->unique();
            $table->string('no_hp', 15);
            $table->string('jenis_kelamin', 255);
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
        Schema::dropIfExists('pengawas');
    }
};
