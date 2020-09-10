<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenandatanganSertifikatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penandatangan_sertifikat', function (Blueprint $table) {
            $table->bigIncrements('id_penandatangan_sertifikat');
            $table->bigInteger('id_penandatangan')->unsigned();
            $table->bigInteger('id_sertifikat')->unsigned();
            $table->bigInteger('id_status')->unsigned();
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
        Schema::dropIfExists('penandatangan_sertifikat');
    }
}
