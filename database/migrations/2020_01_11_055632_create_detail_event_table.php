<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailEventTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_event', function (Blueprint $table) {
            $table->bigIncrements('id_detail_event');
            $table->text('deskripsi_event');
            $table->bigInteger('id_provinsi')->unsigned();
            $table->bigInteger('id_kabupaten')->unsigned();
            $table->string('biaya')->nullable();
            $table->string('bank');
            $table->string('nomor_rekening')->nullable();
            $table->date('open_registration');
            $table->date('end_registration');
            $table->date('start_event');
            $table->date('end_event');
            $table->time('time_start');
            $table->time('time_end');
            $table->string('limit_participant');
            $table->string('lokasi');
            $table->string('venue');
            $table->string('picture');
            $table->string('telepon');
            $table->string('instagram');
            $table->string('email_event');
            $table->timestamps();
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
        Schema::dropIfExists('detail_event');
    }
}
