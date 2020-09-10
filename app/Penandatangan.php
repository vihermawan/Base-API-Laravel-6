<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;

class Penandatangan extends Model
{
    //use SoftDeletes;

    protected $table = 'penandatangan';
    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $primaryKey = 'id_penandatangan';

    protected $fillable = [
        'nama_penandatangan', 'instansi', 'jabatan', 'file_p12', 'profile_picture'
    ];

    //protected $dates = ['deleted_at'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
    protected $appends = ['image_URL'];
    public function getImageURLAttribute()
    {
        if ($this->profile_picture == null) {
            // abort(404);
        }
        return asset('uploads/penandatangan/' . $this->profile_picture);
    }
    public function users(){
        return $this->belongsTo(User::class,'id_users');
    }
    public function penandatangan_sertifikat(){
        return $this->hasMany(PenandatanganSertifikat::class,'id_penandatangan');
    }
    public function kabupaten(){
        return $this->hasOne(Kabupaten::class,'id_kabupaten','id_kabupaten');
    }
    public function provinsi(){
        return $this->hasOne(Provinsi::class,'id_provinsi','id_provinsi');
    }
}