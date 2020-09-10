<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PesertaEvent extends Model
{
    protected $primaryKey='id_peserta_event';
    protected $table = 'peserta_event';
   
    protected $fillable=['id_peserta','id_status','id_event'];
    
    public function event(){
        return $this->belongsTo(Event::class,'id_event');
    }
    public function peserta(){
        return $this->belongsTo(Peserta::class,'id_peserta');
    }
    public function status(){
        return $this->hasOne(Status::class,'id_status','id_status');
    }

    protected $appends = ['sertifikat_URL'];
    public function getSertifikatURLAttribute()
    {
        
        if ($this->nama_sertifikat == null) {
            return 'sertifikat belum ada';
        }
        else if($this->id_status == 5){
            return asset('uploads/sertifikat/'.$this->nama_event.'/'.$this->nama_sertifikat);
        }
        else if($this->id_status == 1){
            return asset('uploads/sertifikat_sign/'.$this->nama_sertifikat);
        }
    }
}
