<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TableKategori extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kategori', function (Blueprint $table) {
            $table->bigIncrements('id_kategori');
            $table->string('nama_kategori');
            $table->timestamps();
        });
        DB::statement('CREATE UNIQUE INDEX users_nama_kategori_unique on kategori (LOWER(nama_kategori));');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kategori');
    }
}
