<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenandatanganTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penandatangan', function (Blueprint $table) {
            $table->bigIncrements('id_penandatangan');
            $table->bigInteger('id_users')->unsigned();
            $table->bigInteger('id_provinsi')->unsigned();
            $table->bigInteger('id_kabupaten')->unsigned();
            $table->string('nama_penandatangan');
            $table->string('instansi');
            $table->string('jabatan');
            $table->bigInteger('nip');
            $table->string('file_p12')->nullable();
            $table->string('profile_picture');
            $table->string('telepon');
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
        Schema::dropIfExists('penandatangan');
    }
}
