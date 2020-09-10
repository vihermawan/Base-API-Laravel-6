<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBiodataPenandatanganTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    
    public function up()
    {
        Schema::create('biodata_penandatangan', function (Blueprint $table) {
            $table->bigIncrements('id_biodata_penandatangan');
            $table->bigInteger('id_panitia')->unsigned();
            $table->bigInteger('id_provinsi')->unsigned();
            $table->bigInteger('id_kabupaten')->unsigned();
            $table->string('email');
            $table->string('nama');
            $table->string('instansi');
            $table->string('jabatan');
            $table->string('telepon');
            $table->string('nip');
            $table->string('profile_picture');
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
        Schema::dropIfExists('biodata_penandatangan');
    }
}
