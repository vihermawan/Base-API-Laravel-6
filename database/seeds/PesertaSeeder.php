<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

use Faker\Factory as Faker;
class PesertaSeeder extends Seeder
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
            ['Icin','1999-08-12',20,4,'Universitas Gadjah Mada','2652343.jpg','perempuan','083849971010','mahasiswi'], //1
            ['Vicky','1999-06-30',20,5,'Institut Teknologi Bandung','2652343.jpg','laki-laki','085643746273','mahasiswa'], //2
            ['Zainul','1999-06-30',20,6,'Universitas Indonesia','2652343.jpg','laki-laki','083849971010','mahasiswa'], //3
            ['Rena Nora','1999-06-30',20,7,'Universitas Diponegoro','2652343.jpg','perempuan','083849971010','mahasiswi'], //4
            ['Deni Sumargo','1999-06-30',20,9,'Universitas Diponegoro','2652343.jpg','laki-laki','085649971010','mahasiswa'], //4
            ['Tania Dwi','1999-06-30',20,10,'Institut Teknologi Bandung','2652343.jpg','perempuan','083849971010','mahasiswi'], //4
            ['Putri Kia','1999-06-30',20,11,'Universitas Indonesia','2652343.jpg','perempuan','083849971010','mahasiswi'], //4
            ['Dendi Yugo','1999-06-30',20,12,'Universitas Diponegoro','2652343.jpg','laki-laki','083849971010','mahasiswa'], //4
            ['Masdan','1999-06-30',20,13,'Universitas Negeri Semarang','2652343.jpg','laki-laki','083849971010','mahasiswa'], //4
            ['Ramzi','1999-06-30',20,14,'Universitas Islam Indonesia','2652343.jpg','laki-laki','083849971010','mahasiswa'], //4
        ];

        for ($i=0; $i < count($data); $i++) {
            $nama_peserta = $data[$i][0];
            $tanggal_lahir = $data[$i][1];
            $umur = $data[$i][2];
            $id_users = $data[$i][3];
            $organisasi = $data[$i][4];
            $foto_peserta = $data[$i][5];
            $jenis_kelamin = $data[$i][6];
            $telepon = $data[$i][7];
            $pekerjaan = $data[$i][8];
            $created_at = Carbon::now();
            $updated_at = Carbon::now();

            DB::table('peserta')->insert([
                'nama_peserta' => $nama_peserta,   
                'tanggal_lahir' => $tanggal_lahir,  
                'umur' => $umur, 
                'id_users' => $id_users,  
                'organisasi' => $organisasi,  
                'foto_peserta' => $foto_peserta,  
                'jenis_kelamin' => $jenis_kelamin, 
                'telepon' => $telepon,
                'pekerjaan' => $pekerjaan, 
                'created_at' => $created_at,
                'updated_at' => $updated_at,
            ]);
        }
    
        // for ($i=0; $i <10 ; $i++) { 
        //     App\Peserta::create([
        //         'nama_peserta' => $faker->name,
        //         'tanggal_lahir' =>  $faker->dateTimeBetween($startDate = '-23 years', $endDate = '-17 years')->format('Y-m-d'),
        //         'umur' => $faker->numberBetween($min = 17, $max = 23),
        //         'id_users' => $i+6,
        //         'organisasi' => $faker->randomElement(['Universitas Gadjah Mada','Universitas Indonesia', 'Institut Teknologi Bandung' ,'Universitas Padjajaran', 'Universitas Diponegoro','Institut Seni Indonesia','Insitut Teknologi Surabaya']),
        //         'foto_peserta' => $faker->randomNumber(7).'.jpg',
        //         'jenis_kelamin' => 'laki-laki',
        //         'created_at'=>'2019-11-18 05:52:01',
        //         'updated_at' => '2019-11-18 05:52:01',
        //     ]);
        // }
    }
}
