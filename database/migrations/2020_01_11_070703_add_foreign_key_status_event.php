<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyStatusEvent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event', function (Blueprint $table) {
            $table->foreign('id_detail_event')->references('id_detail_event')->on('detail_event')->onDelete('cascade');
            $table->foreign('id_panitia')->references('id_panitia')->on('panitia')->onDelete('cascade');
            $table->foreign('id_status_biaya')->references('id_status')->on('status')->onDelete('cascade');
            $table->foreign('id_status_event')->references('id_status')->on('status')->onDelete('cascade');
            $table->foreign('id_kategori')->references('id_kategori')->on('kategori')->onDelete('cascade');
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
