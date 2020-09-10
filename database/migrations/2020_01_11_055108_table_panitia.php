<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TablePanitia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('panitia', function (Blueprint $table) {
            $table->bigIncrements('id_panitia');
            $table->bigInteger('id_users')->unsigned();
            $table->string('nama_panitia');
            $table->string('organisasi');
            $table->string('telepon')->nullable();
            $table->string('instagram')->nullable();
            $table->string('foto_panitia')->nullable();
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
        Schema::dropIfExists('panitia');
    }
}
