<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class PenandatanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [8,'Panut Mulyono','Universitas Gadjah Mada','Rektor','Panut Mulyono-9109109019091.p12','1592380261.jpg','196006011988031001','085647651234',14,227],
            [15,'Nadiem Makarim','Kemdikbud','Mendikbud',null,'2652763.jpg','35326831465398','082324678979',11,158],
        ];

        for ($i=0; $i < count($data); $i++) {
            $id_users = $data[$i][0];
            $nama_penandatangan = $data[$i][1];
            $instansi = $data[$i][2];
            $jabatan = $data[$i][3];
            $file_p12 = $data[$i][4];
            $profile_picture = $data[$i][5];
            $nip = $data[$i][6];
            $telepon = $data[$i][7];
            $id_provinsi = $data[$i][8];
            $id_kabupaten = $data[$i][9];
            $created_at = Carbon::now();
            $updated_at = Carbon::now();

            DB::table('penandatangan')->insert([
                'id_users' => $id_users, 
                'nama_penandatangan' => $nama_penandatangan,   
                'instansi' => $instansi, 
                'jabatan' => $jabatan, 
                'nip' => $nip, 
                'file_p12' => $file_p12,
                'telepon' => $telepon, 
                'profile_picture' => $profile_picture, 
                'id_kabupaten' => $id_kabupaten,
                'id_provinsi' => $id_provinsi,
                'created_at' => $created_at,
                'updated_at' => $updated_at,
            ]);
        }
    }
}