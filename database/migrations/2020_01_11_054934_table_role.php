<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TableRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role', function (Blueprint $table) {
            $table->bigIncrements('id_role');
            $table->string('nama_role');
            $table->timestamps();
        });
        DB::table('role')->insert([
            [
                'id_role'       => '1',
                'nama_role'      => 'Admin',
            ],
            [
                'id_role'       => '2',
                'nama_role'      => 'Panitia',
            ],
            [
                'id_role'       => '3',
                'nama_role'      => 'Peserta',
            ],
            [
                'id_role'       => '4',
                'nama_role'      => 'Penandatangan',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role');
    }
}
