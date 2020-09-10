<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event', function (Blueprint $table) {
            $table->bigIncrements('id_event');
            $table->bigInteger('id_detail_event')->unsigned();
            $table->bigInteger('id_panitia')->unsigned();
            $table->bigInteger('id_kategori')->unsigned();
            $table->bigInteger('id_status_biaya')->unsigned();
            $table->bigInteger('id_status_event')->unsigned();
            $table->string('nama_event');
            $table->string('organisasi');
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
        Schema::dropIfExists('event');
    }
}
