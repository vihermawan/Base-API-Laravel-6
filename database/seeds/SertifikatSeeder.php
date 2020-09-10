<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class SertifikatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [1,'UGMTalks','17/416370/SV/14108','UGMTalks.docx'],
            [2,'Siraman Qolbu','17/416370/SV/14108','SQ.docx'],
            [5,'Talkshow EduTalk','17/416370/SV/14108','Edu.docx'],
            [10,'Ruang Guru Talks','17/416370/SV/14108','RG.docx'],
            [16,'Konsultasi','17/416370/SV/14108','Konsul.docx'],
            [17,'IALF','17/416370/SV/14108','IALF.docx'],
        ];

        for ($i=0; $i < count($data); $i++) {
            $id_event = $data[$i][0];
            $nama = $data[$i][1];

            $no_sertifikat = $data[$i][2];
            $sertifikat = $data[$i][3];
            $created_at = Carbon::now();
            $updated_at = Carbon::now();

            DB::table('sertifikat')->insert([
                'id_event' => $id_event, 
                'nama' => $nama,
                'no_sertifikat' => $no_sertifikat,
                'sertifikat' => $sertifikat, 
                'created_at' => $created_at,
                'updated_at' => $updated_at,
            ]);
        }
    }
}
