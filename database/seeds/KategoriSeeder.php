<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['Budaya'], //#0046b8
            ['Olahraga'], //#8f1601
            ['Musik'], //#018f52
            ['Game'], //#016e8f
            ['Seni'], // #8f8f01
            ['Teknologi'], // #35018f
            ['Pendidikan'], //#8f0120
            ['Agama'], //#018f77
        ];

        for ($i=0; $i < count($data); $i++) {
            $nama_kategori = $data[$i][0];
            $created_at = Carbon::now();
            $updated_at = Carbon::now();

            DB::table('kategori')->insert([
                'nama_kategori' => $nama_kategori,   
                'created_at' => $created_at,
                'updated_at' => $updated_at,
            ]);
        }
    }
}
