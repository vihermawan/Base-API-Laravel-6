<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ProvinsiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [ 'Aceh', 'ID-AC'],
            [ 'Sumatra Utara', 'ID-SU'],
            [ 'Sumatra Barat', 'ID-SB'],
            [ 'Riau', 'ID-RI'],
            [ 'Jambi', 'ID-JA'],
            [ 'Sumatra Selatan', 'ID-SS'],
            [ 'Bengkulu', 'ID-BE'],
            [ 'Lampung', 'ID-LA'],
            [ 'Kepulauan Bangka Belitung', 'ID-BB'],
            [ 'Kepulauan Riau', 'ID-KR'],
            [ 'Daerah Khusus Ibukota Jakarta', 'ID-JB'],
            [ 'Jawa Barat', 'ID-JB'],
            [ 'Jawa Tengah', 'ID-JT'],
            [ 'Daerah Istimewa Yogyakarta', 'ID-YO'],
            [ 'Jawa Timur', 'ID-JI'],
            [ 'Banten', 'ID-BT'],
            ['Bali', 'ID-BA'],
            [ 'Nusa Tenggara Barat', 'ID-NB'],
            [ 'Nusa Tenggara Timur', 'ID-NT'],
            ['Kalimantan Barat', 'ID-KB'],
            [ 'Kalimantan Tengah', 'ID-KT'],
            [ 'Kalimantan Selatan', 'ID-KS'],
            [ 'Kalimantan Timur', 'ID-KI'],
            [ 'Kalimantan Utara', 'ID-KU'],
            [ 'Sulawesi Utara', 'ID-SA'],
            [ 'Sulawesi Tengah', 'ID-ST'],
            [ 'Sulawesi Selatan', 'ID-SN'],
            [ 'Sulawesi Tenggara', 'ID-SG'],
            [ 'Gorontalo', 'ID-GO'],
            [ 'Sulawesi Barat', 'ID-SR'],
            [ 'Maluku', 'ID-MA'],
            [ 'Maluku Utara', 'ID-MU'],
            [ 'Papua', 'ID-PA'],
            [ 'Papua Barat', 'ID-PB'],
        ];

        for ($i=0; $i < count($data); $i++) {
            $provinsi = $data[$i][0];
            $p_bsni = $data[$i][1];
            $created_at = Carbon::now();
            $updated_at = Carbon::now();

            DB::table('provinsi')->insert([
                'provinsi' => $provinsi,
                'p_bsni' => $p_bsni, 
            ]);
        }
    }
}
