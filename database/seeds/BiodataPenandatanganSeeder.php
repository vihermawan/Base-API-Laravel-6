<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class BiodataPenandatanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [1,'zainulmutaqin15@gmail.com','Wikan Sakarinto','Universitas Gadjah Mada','Dekan','5732745368569','coba.jpg','082324210001',14,227],
            [1,'hv334458dd8@gmail.com','M Dzakwan Zaky','D3 Komputer dan Sistem Informasi','Ketua Himakomsi','7647856876788','coba.jpg','082324568989',14,227],

        ];

        for ($i=0; $i < count($data); $i++) {
            $id_panitia = $data[$i][0];
            $email = $data[$i][1];
            $nama = $data[$i][2];
            $instansi = $data[$i][3];
            $jabatan = $data[$i][4];
            $nip = $data[$i][5];
            $profile_picture = $data[$i][6];
            $telepon = $data[$i][7];
            $id_provinsi = $data[$i][8];
            $id_kabupaten = $data[$i][9];
            $created_at = Carbon::now();
            $updated_at = Carbon::now();

            DB::table('biodata_penandatangan')->insert([
                'id_panitia' => $id_panitia,
                'email' => $email, 
                'nama' => $nama,   
                'instansi' => $instansi, 
                'jabatan' => $jabatan, 
                'nip' => $nip, 
                'profile_picture' => $profile_picture,
                'telepon' => $telepon,
                'id_provinsi' => $id_provinsi,
                'id_kabupaten' => $id_kabupaten,
                'created_at' => $created_at,
                'updated_at' => $updated_at,
            ]);
        }
    }
}
