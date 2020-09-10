<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Faker\Factory as Faker;
class PanitiaSeeder extends Seeder
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
            ['UGM Panitia',2,'Universitas Gadjah Mada','2652763.jpg','083849971010','instagram.com/panitiaugm'],
            ['ECC',3,'Universitas Gadjah Mada','2652763.jpg','0010101','instagram.com/eccugm'],
            ['IMAGO',16,'SMA Negeri 1 Bojonegoro','2652763.jpg','0010101','instagram.com/osissmaq'],
        ];

        for ($i=0; $i < count($data); $i++) {
            $nama_panitia = $data[$i][0];
            $id_users = $data[$i][1];
            $organisasi = $data[$i][2];
            $foto_panitia = $data[$i][3];
            $telepon = $data[$i][4];
            $instagram = $data[$i][5];
            $created_at = Carbon::now();
            $updated_at = Carbon::now();

            DB::table('panitia')->insert([
                'nama_panitia' => $nama_panitia,   
                'id_users' => $id_users,  
                'organisasi' => $organisasi,  
                'foto_panitia' => $foto_panitia,
                'telepon' => $telepon, 
                'instagram' => $instagram, 
                'created_at' => $created_at,
                'updated_at' => $updated_at,
            ]);
        }
        // for ($i=0; $i <10 ; $i++) { 
        //     App\Panitia::create([
        //         'nama_panitia' => $faker->name,
        //         'id_users' => $i+16,
        //         'organisasi' => $faker->randomElement(['Universitas Gadjah Mada','Universitas Indonesia', 'Institut Teknologi Bandung' ,'Universitas Padjajaran', 'Universitas Diponegoro','Institut Seni Indonesia','Insitut Teknologi Surabaya']),
        //         'foto_panitia' => $faker->randomNumber(7).'.jpg',
        //         'no_telepon' => $faker->randomNumber(9),
        //         'instagram' => $faker->name,
        //         'created_at'=>'2019-11-18 05:52:01',
        //         'updated_at' => '2019-11-18 05:52:01',
        //     ]);
        // }
    }
}
