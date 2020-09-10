<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Panitia;

class BiodataPenandatangan extends Model
{
    protected $table = 'biodata_penandatangan';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $primaryKey = 'id_biodata_penandatangan';

    protected $fillable = [
        'id_panitia', 'nama', 'instansi', 'jabatan', 'nip'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    public function panitia(){
        return $this->belongsTo(Panitia::class, 'id_panitia');
    }
}