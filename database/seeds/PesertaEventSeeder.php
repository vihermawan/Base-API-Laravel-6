<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

use Faker\Factory as Faker;
class PesertaEventSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');

        $data = [

            //UGMTalks
            [6,1,1,'Icin-1589781324.pdf','UGMTalks','2020-06-18 07:00:00'],
            [6,2,1,'Vicky-1589781275.pdf','UGMTalks','2020-06-18 07:00:00'],
            [6,3,1,'Zainul-1589781329.pdf','UGMTalks','2020-06-18 07:00:00'],
            [6,4,1,'Rena Nora-1589781281.pdf','UGMTalks','2020-06-18 07:00:00'],
            [6,5,1,'Deni Sumargo-1589781288.pdf','UGMTalks','2020-06-18 07:00:00'],
            [6,6,1,'Tania Dwi-1589781294.pdf','UGMTalks','2020-06-18 07:00:00'],
            [6,7,1,'Putri Kia-1589781301.pdf','UGMTalks','2020-06-18 07:00:00'],
            [6,8,1,'Dendi Yugo-1589781306.pdf','UGMTalks','2020-06-18 07:00:00'],
            [6,9,1,'Masdan-1589781312.pdf','UGMTalks','2020-06-18 07:00:00'],
            [6,10,1,'Ramzi-1589781318.pdf','UGMTalks','2020-06-18 07:00:00'],

            //BukaTalks
            [5,1,8,null,null,'2020-06-18 07:00:00'],
            [5,2,8,null,null,'2020-06-18 07:00:00'],
            [6,3,8,null,null,'2020-06-18 07:00:00'],
            [6,4,8,null,null,'2020-06-18 07:00:00'],
            [6,5,8,null,null,'2020-06-18 07:00:00'],
            [6,6,8,null,null,'2020-06-18 07:00:00'],
            [6,7,8,null,null,'2020-06-18 07:00:00'],
            [6,8,8,null,null,'2020-06-18 07:00:00'],
            [6,9,8,null,null,'2020-06-18 07:00:00'],
            [6,10,8,null,null,'2020-06-18 07:00:00'],

            
            //Seminar Kebudayaan
            [5,1,5,null,null,'2020-06-18 07:00:00'],
            [5,2,5,null,null,'2020-06-18 07:00:00'],
            [6,3,5,null,null,'2020-06-18 07:00:00'],
            [6,4,5,null,null,'2020-06-18 07:00:00'],
            [6,5,5,null,null,'2020-06-18 07:00:00'],
            [6,6,5,null,null,'2020-06-18 07:00:00'],
            [6,7,5,null,null,'2020-06-18 07:00:00'],
            [6,8,5,null,null,'2020-06-18 07:00:00'],
            [6,9,5,null,null,'2020-06-18 07:00:00'],
            [6,10,5,null,null,'2020-06-18 07:00:00'],


            //siraman qalbu
            [5,1,2,null,null,'2020-06-18 07:00:00'],
            [5,2,2,null,null,'2020-06-18 07:00:00'],
            [6,3,2,null,null,'2020-06-18 07:00:00'],
            [6,4,2,null,null,'2020-06-18 07:00:00'],
            [6,5,2,null,null,'2020-06-18 07:00:00'],
            [6,6,2,null,null,'2020-06-18 07:00:00'],
            [6,7,2,null,null,'2020-06-18 07:00:00'],
            [6,8,2,null,null,'2020-06-18 07:00:00'],
            [6,9,2,null,null,'2020-06-18 07:00:00'],
            [6,10,2,null,null,'2020-06-18 07:00:00'],

            //seminar kewirausahaan
            [6,1,3,null,null,'2020-06-18 07:00:00'],
            [6,2,3,null,null,'2020-06-18 07:00:00'],
            [6,3,3,null,null,'2020-06-18 07:00:00'],
            [6,4,3,null,null,'2020-06-18 07:00:00'],
            [6,5,3,null,null,'2020-06-18 07:00:00'],
            [6,6,3,null,null,'2020-06-18 07:00:00'],
            [6,7,3,null,null,'2020-06-18 07:00:00'],
            [6,8,3,null,null,'2020-06-18 07:00:00'],
            [6,9,3,null,null,'2020-06-18 07:00:00'],
            [6,10,3,null,null,'2020-06-18 07:00:00'],

            //Ruang guru talks
            [1,1,10,null,null,'2020-06-17 07:00:00'],
            [1,4,10,null,null,'2020-06-17 07:00:00'],
            [1,2,16,null,null,'2020-06-17 07:00:00'],
            [1,3,16,null,null,'2020-06-17 07:00:00'],
            [1,1,16,null,null,'2020-06-17 07:00:00'],

            //IALF
            [1,4,17,null,null,'2020-06-17 07:00:00'],
            [1,3,17,null,null,'2020-06-17 07:00:00'],

            //uji peserta belum absen di setiap event
            
            //uji approve peserta yg mendaftar
            //Talkshow kewirausahaan
            [6,1,7,null,null,'2020-06-17 07:00:00'],
            [6,2,7,null,null,'2020-06-17 07:00:00'],
            [6,3,7,null,null,'2020-06-17 07:00:00'],
            [6,4,7,null,null,'2020-06-17 07:00:00'],
            [6,5,7,null,null,'2020-06-17 07:00:00'],
            [6,6,7,null,null,'2020-06-17 07:00:00'],
            [6,7,7,null,null,'2020-06-17 07:00:00'],
            [6,8,7,null,null,'2020-06-17 07:00:00'],
            [6,9,7,null,null,'2020-06-17 07:00:00'],
            [6,10,7,null,null,'2020-06-17 07:00:00'],

            //MLBB Competition
            [6,1,9,null,null,'2020-06-17 07:00:00'],
            [6,2,9,null,null,'2020-06-17 07:00:00'],
            [6,3,9,null,null,'2020-06-17 07:00:00'],
            [6,4,9,null,null,'2020-06-17 07:00:00'],
            [6,5,9,null,null,'2020-06-17 07:00:00'],
            [6,6,9,null,null,'2020-06-17 07:00:00'],
            [6,7,9,null,null,'2020-06-17 07:00:00'],
            [6,8,9,null,null,'2020-06-17 07:00:00'],
            [6,9,9,null,null,'2020-06-17 07:00:00'],
            [6,10,9,null,null,'2020-06-17 07:00:00'],
            
        ];

        for ($i=0; $i < count($data); $i++) {
            $id_status = $data[$i][0];
            $id_peserta = $data[$i][1];
            $id_event = $data[$i][2];
            $nama_sertifikat = $data[$i][3];
            $nama_event = $data[$i][4];
            $created_at = $data[$i][5];
            $updated_at = Carbon::now();

            DB::table('peserta_event')->insert([
                'id_status' => $id_status, 
                'id_peserta' => $id_peserta,   
                'id_event' => $id_event, 
                'nama_sertifikat' => $nama_sertifikat,
                'nama_event' => $nama_event,
                'created_at' => $created_at,
                'updated_at' => $updated_at,
            ]);
        }
       
    }
}