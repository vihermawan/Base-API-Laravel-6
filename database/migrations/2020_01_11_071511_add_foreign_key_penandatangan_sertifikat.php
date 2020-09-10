<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyPenandatanganSertifikat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('penandatangan_sertifikat', function (Blueprint $table) {
            $table->foreign('id_penandatangan')->references('id_penandatangan')->on('penandatangan')->onDelete('cascade');
            $table->foreign('id_sertifikat')->references('id_sertifikat')->on('sertifikat')->onDelete('cascade');
            $table->foreign('id_status')->references('id_status')->on('status')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
