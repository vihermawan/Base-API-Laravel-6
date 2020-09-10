<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DetailEvent extends Model
{
    protected $table = 'detail_event';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $primaryKey = 'id_detail_event';

    protected $fillable = [
        'deskripsi_event','audien','open_registation','end_registration','time_start','id_provinsi','id_kabupaten',
        'time_end','limit_participant','lokasi','venue','picture','video','start_event','end_event'
    ];

    // public function getOpenRegistrationAttribute()
    // {
    //     return \Carbon\Carbon::parse($this->attributes['open_registration'])->locale('id_ID')->isoFormat('LL');
    // }
    // public function getEndRegistrationAttribute()
    // {
    //     return \Carbon\Carbon::parse($this->attributes['end_registration'])->locale('id_ID')->isoFormat('LL');
    // }
    // public function getStartEventAttribute()
    // {
    //     return \Carbon\Carbon::parse($this->attributes['start_event'])->locale('id_ID')->isoFormat('LL');
    // }
    // public function getEndEventAttribute()
    // {
    //     return \Carbon\Carbon::parse($this->attributes['end_event'])->locale('id_ID')->isoFormat('LL');
    // }

    //   protected $dates = ['end'];
    //   protected $dateFormat = 'd-m-Y';
    /**
     * The attributes excluded from the model's JSON form.l
     *
     * @var array
     */
    protected $hidden = [];
    
    public function event(){
        return $this->hasOne(Event::class,'id_detail_event');
    }
    public function kabupaten(){
        return $this->hasOne(Kabupaten::class,'id_kabupaten','id_kabupaten');
    }
    public function provinsi(){
        return $this->hasOne(Provinsi::class,'id_provinsi','id_provinsi');
    }
    protected $appends = ['image_URL'];
    public function getImageURLAttribute()
    {
        if ($this->picture == null) {
            abort(404);
        }
        return asset('uploads/event/' . $this->picture);
    }
    
}