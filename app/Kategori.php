<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $primaryKey='id_kategori';
    protected $table = 'kategori';
    protected $fillable = ['nama_kategori'];

    public function event(){
        return $this->hasMany(Event::class,'id_event');
    }
}
