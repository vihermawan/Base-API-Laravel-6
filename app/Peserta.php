<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Peserta extends Model
{
    //use SoftDeletes;

    protected $primaryKey='id_peserta';
    protected $table = 'peserta';
    protected $fillable = ['nama_peserta','organisasi','foto_peserta','id_users','pekerjaan','no_telefon','jenis_kelamin','foto_peserta','tanggal_lahir','umur'];
    protected $appends = ['image_URL'];
    //protected $dates = ['deleted_at'];

    public function getImageURLAttribute()
    {
        if ($this->foto_peserta == null) {
            abort(404);
        }
        return asset('uploads/peserta/' . $this->foto_peserta);
    }
    public function users(){
        return $this->belongsTo(User::class,'id_users');
    }
    public function peserta_event(){
        return $this->hasMany(PesertaEvent::class,'id_peserta');
    }
}
