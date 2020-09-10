<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call('ProvinsiSeeder');
        $this->call('KabupatenSeeder');
        $this->call('UserSeeder');
        $this->call('KategoriSeeder');
        $this->call('PanitiaSeeder');
        $this->call('PesertaSeeder');
        $this->call('PenandatanganSeeder');
        $this->call('DetailEventSeeder');
        $this->call('EventSeeder');
        $this->call('BiodataPenandatanganSeeder');
        $this->call('PesertaEventSeeder');
        $this->call('SertifikatSeeder');
        $this->call('PenandatanganSertifikatSeeder');
    }
}
