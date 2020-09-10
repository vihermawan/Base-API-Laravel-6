<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class PenandatanganSertifikatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [1,1,1,'Icin-1589781324.pdf','UGMTalks','2020-05-20 14:32:10'],
            [1,2,8,null,null,'2020-05-20 15:32:10'],
            [2,5,8,null,null,'2020-05-20 16:32:10'],
            [2,6,8,null,null,'2020-05-20 17:32:10'],
            [1,1,3,'Zainul-1589781329.pdf','UGMTalks','2020-05-20 14:32:10'],
            [1,1,3,'Vicky-1589781275.pdf','UGMTalks','2020-05-20 14:32:10'],
            [1,1,3,'Rena Nora-1589781281.pdf','UGMTalks','2020-05-20 14:32:10'],
            [1,1,3,'Deni Sumargo-1589781288.pdf','UGMTalks','2020-05-20 14:32:10'],
            [1,1,3,'Tania Dwi-1589781294.pdf','UGMTalks','2020-05-20 14:32:10'],
            [1,1,3,'Putri Kia-1589781301.pdf','UGMTalks','2020-05-20 14:32:10'],
            [1,1,3,'Dendi Yugo-1589781306.pdf','UGMTalks','2020-05-20 14:32:10'],
            [1,1,3,'Masdan-1589781312.pdf','UGMTalks','2020-05-20 14:32:10'],
            [1,1,3,'Ramzi-1589781318.pdf','UGMTalks','2020-05-20 14:32:10'],
        ];

        for ($i=0; $i < count($data); $i++) {
            $id_penandatangan = $data[$i][0];
            $id_sertifikat = $data[$i][1];
            $id_status = $data[$i][2];
            $nama_sertifikat = $data[$i][3];
            $nama_event = $data[$i][4];
            $created_at = $data[$i][5];
            $updated_at = Carbon::now();

            DB::table('penandatangan_sertifikat')->insert([
                'id_penandatangan' => $id_penandatangan, 
                'id_sertifikat' => $id_sertifikat,   
                'id_status' => $id_status,
                'nama_sertifikat' => $nama_sertifikat,
                'nama_event' => $nama_event,
                'created_at' => $created_at,
                'updated_at' => $updated_at,
            ]);
        }
    }
}
