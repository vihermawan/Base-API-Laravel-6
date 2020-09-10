<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

            // ['Budaya'], //#0046b8 1
            // ['Olahraga'], //#8f1601 2
            // ['Musik'], //#018f52 3
            // ['Game'], //#016e8f 4
            // ['Seni'], // #8f8f01 5
            // ['Teknologi'], // #35018f 6
             // ['Pendidikan'], //#8f0120 7
            // ['Agama'], //#018f77 8

        $data = [
            //panitia 1
            [1,1,9,6,7,'UGMTalks','Universitas Gadjah Mada','2020-01-02 14:32:10'], //1
            [2,1,9,6,8,'Siraman Qolbu','Universitas Gadjah Mada','2020-02-03 14:32:10'], //2
            [3,1,9,6,2,'Seminar Film Maker','Universitas Gadjah Mada','2020-01-20 15:32:10'], //3
            
            [4,1,9,7,7,'Try Out SBPMTN','Universitas Gadjah Mada','2020-03-20 16:32:10'], //4
            [5,1,9,6,1,'Seminar Kebudayaan','Fisipol UGM','2020-03-20 14:32:10'], //5
            [6,1,9,5,7,'Career Days','Universitas Gadjah Mada','2020-02-20 17:32:10'], //6
            
            [7,1,10,6,7,'Talkshow Beasiswa','Universitas Gadjah Mada','2020-02-20 14:32:10'], //7
            [8,1,10,6,6,'UGM BukaTalks','Universitas Gadjah Mada','2020-03-20 14:32:10'], //8
            [9,1,10,6,4,'MLBB Competition','Universitas Gadjah Mada','2020-03-20 14:32:10'], //9

            //panitia 2
            [10,2,10,6,7,'Ruang Guru Talks','Universitas Gadjah Mada','2020-03-20 14:32:10'], //10
            [11,2,10,6,7,'Seminar Kewirausahaan','Sekolah Vokasi UGM','2020-02-20 14:32:10'], //11
            [12,2,10,6,1,'Konser Musik','Candi Prambanan','2020-01-20 14:32:10'], //12

            [13,2,10,6,2,'Jalan Sehat','0 km Yogyakarta','2020-01-12 14:32:10'], //13
            [14,2,10,6,5,'Pameran Lukisan','FKKH UGM','2020-02-22 14:32:10'], //14
            [15,2,10,6,4,'MLBB Competition','Universitas Indonesia','2020-05-20 14:32:10'], //15

            [16,2,10,6,7,'Konsultasi Google Adwords','Surabaya','2020-05-20 14:32:10'], //16
            [17,2,10,6,7,'IALF English Club','IALF Surabaya','2020-05-20 14:32:10'], //17
            [18,2,10,6,7,'Webtalk Invite Day','Surabaya','2020-05-20 14:32:10'], //18

            [19,2,10,6,7,'E-commerce Bussiness Workshop','Webinar-Homepreneur Jakarta','2020-05-20 14:32:10'], //19
            [20,2,10,6,7,'Pulmonary Infection 2020','Garden Palace Hotel Surabaya','2020-05-20 14:32:10'], //20
            [21,2,10,6,7,'Homepreneur Workshop Seminar','Surabaya','2020-05-20 14:32:10'], //21
        ];

        for ($i=0; $i < count($data); $i++) {
            $id_detail_event = $data[$i][0];
            $id_panitia = $data[$i][1];
            $id_status_biaya = $data[$i][2];
            $id_status_event = $data [$i][3];
            $id_kategori = $data[$i][4];
            $nama_event = $data[$i][5];
            $organisasi = $data[$i][6];
            $created_at = $data[$i][7];
            $updated_at = Carbon::now();

            DB::table('event')->insert([
                'id_status_biaya' => $id_status_biaya,
                'id_status_event' => $id_status_event, 
                'id_panitia' => $id_panitia, 
                'id_detail_event' => $id_detail_event,
                'id_kategori' => $id_kategori,
                'nama_event' => $nama_event,   
                'organisasi' => $organisasi, 
                'created_at' => $created_at,
                'updated_at' => $updated_at,
            ]);
        }
    }
}
