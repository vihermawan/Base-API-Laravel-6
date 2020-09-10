<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyPesertaEvent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('peserta_event', function (Blueprint $table) {
            $table->foreign('id_status')->references('id_status')->on('status')->onDelete('cascade');
            $table->foreign('id_peserta')->references('id_peserta')->on('peserta')->onDelete('cascade');
            $table->foreign('id_event')->references('id_event')->on('event')->onDelete('cascade');
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
