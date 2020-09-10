<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;

class Panitia extends Model
{
    //use SoftDeletes;

    protected $primaryKey='id_panitia';
    protected $table = 'panitia';
    protected $fillable = ['nama_panitia','organisasi','foto_panitia','id_users','instagram','no_telepon'];
    protected $appends = ['image_URL'];
    //protected $dates = ['deleted_at'];
    
    public function getImageURLAttribute()
    {
        if ($this->foto_panitia == null) {
            abort(404);
        }
        return asset('uploads/panitia/'. $this->foto_panitia);
    }
    public function users(){
        return $this->belongsTo(User::class,'id_users');
    }
    public function event(){
        return $this->hasMany(Event::class,'id_panitia','id_panitia');
    }

}
