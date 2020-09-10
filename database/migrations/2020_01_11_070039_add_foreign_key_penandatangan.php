<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyPenandatangan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('penandatangan', function (Blueprint $table) {
            $table->foreign('id_users')->references('id_users')->on('users')->onDelete('cascade');
            $table->foreign('id_provinsi')->references('id_provinsi')->on('provinsi')->onDelete('cascade');
            $table->foreign('id_kabupaten')->references('id_kabupaten')->on('kabupaten')->onDelete('cascade');
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
