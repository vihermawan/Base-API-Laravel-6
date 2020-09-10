<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PenandatanganSertifikat extends Model
{
    protected $primaryKey='id_penandatangan_sertifikat';
    protected $table = 'penandatangan_sertifikat';
   
    protected $fillable=['id_penandatangan','id_status','id_sertifikat','nama_sertifikat'];
    
    public function penandatangan(){
        return $this->belongsTo(Penandatangan::class,'id_penandatangan');
    }
   
    public function status(){
        return $this->hasOne(Status::class,'id_status','id_status');
    }
    public function sertifikat(){
        return $this->belongsTo(Sertifikat::class,'id_sertifikat');
    }

    protected $appends = ['sertifikat_URL'];
    public function getSertifikatURLAttribute()
    {
        if ($this->nama_sertifikat == null) {
            return 'sertifikat belum ada';
        }
        else if($this->id_status == 3){
            return asset('uploads/sertifikat/'.$this->nama_event.'/'.$this->nama_sertifikat);
        }
        else if($this->id_status == 1){
            return asset('uploads/sertifikat_sign/'.$this->nama_sertifikat);
        }
    }
}
