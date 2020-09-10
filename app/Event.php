<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\DetailEvent;
use App\Sertifikat;

class Event extends Model
{
    protected $table = 'event';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $primaryKey = 'id_event';

    protected $fillable = [
        'id_detail_event','id_status', 'nama_event', 'organisasi','id_panitia','id_sertifikat','id_kategori'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
   
    public function detail_event(){
        return $this->belongsTo(DetailEvent::class, 'id_detail_event');
    }
    
    public function peserta_event(){
        return $this->hasMany(PesertaEvent::class,'id_event');
    }

    public function sertifikat()
    {
        return $this->hasOne(Sertifikat::class,'id_event');
    }

    public function kategori(){
        return $this->hasOne(Kategori::class,'id_kategori','id_kategori');
    }

    public function status_biaya(){
        return $this->hasOne(Status::class,'id_status','id_status_biaya');
    }

    public function status_event(){
        return $this->hasOne(Status::class,'id_status','id_status_event');
    }
    
    public function panitia(){
        return $this->belongsTo(Panitia::class, 'id_panitia');
    }
}