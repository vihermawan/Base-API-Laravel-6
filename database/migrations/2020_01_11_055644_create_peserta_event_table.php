<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePesertaEventTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('peserta_event', function (Blueprint $table) {
            $table->bigIncrements('id_peserta_event');
            $table->bigInteger('id_status')->unsigned();
            $table->bigInteger('id_peserta')->unsigned();
            $table->bigInteger('id_event')->unsigned();
            $table->string('nama_sertifikat')->nullable();
            $table->string('nama_event')->nullable();
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
        Schema::dropIfExists('peserta_event');
    }
}
