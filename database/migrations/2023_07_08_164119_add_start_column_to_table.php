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
        Schema::table('kompensasis', function (Blueprint $table) {
            $table->date('mulai_kompensasi')->nullable()->after('jumlah_kompensasi');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kompensasis', function (Blueprint $table) {
            $table->dropColumn('mulai_kompensasi');
        });
    }
};
