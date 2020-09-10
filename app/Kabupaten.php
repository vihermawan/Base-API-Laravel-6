<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kabupaten extends Model
{
    protected $primaryKey='id_kabupaten';
    protected $table = 'kabupaten';
    public function provinsi(){
        return $this->hasOne(Provinsi::class,'id_provinsi','id_provinsi');
    }
}
