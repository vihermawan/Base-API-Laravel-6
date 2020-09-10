<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sertifikat extends Model
{
    protected $primaryKey='id_sertifikat';
    protected $table = 'sertifikat';
    protected $fillable = ['nama', 'id_event','description','sertifikat','no_sertifikat'];
    
    public function penandatangan_sertifikat(){
        return $this->hasMany(PenandatanganSertifikat::class,'id_sertifikat');
    }
    
    public function event(){
        return $this->belongsTo(Event::class,'id_event');
    }
 

    public function penandatanganan_sertifkat(){
        return $this->hasMany(PenandatanganSertifikat::class,'id_sertifikat');
    }

    protected $appends = ['sertif_URL'];
    
    public function getSertifURLAttribute()
    {
        // if($this->id_status == 3) {
        //     return asset('uploads/sertifikat/' . $this->sertifikat);
        // }else if($this->id_status == 1){
        //     return asset('uploads/penandatangan/' . $this->sertifikat);
        // }
        if ($this->sertifikat == null) {
            return 'sertifikat belum ada';
        }
        return asset('uploads/sertifikat/'.$this->sertifikat);
    }   
}
