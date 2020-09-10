<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TablePeserta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('peserta', function (Blueprint $table) {
            $table->bigIncrements('id_peserta');
            $table->bigInteger('id_users')->unsigned();
            $table->string('nama_peserta');
            $table->date('tanggal_lahir')->nullable();
            $table->integer('umur')->nullable();
            $table->string('organisasi');
            $table->string('jenis_kelamin');
            $table->string('pekerjaan')->nullable();
            $table->string('telepon')->nullable();
            $table->string('foto_peserta')->nullable();
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
        Schema::dropIfExists('peserta');
    }
}
