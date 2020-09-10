<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    protected $primaryKey='id_users';
    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'id_role', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password','remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //protected $dates = ['deleted_at'];
    
    // public function generateToken()
    // {
    //     $this->api_token = str_random(60);
    //     $this->save();

    //     return $this->api_token;
    // }

    public function role(){
        return $this->belongsTo(Role::class,'id_role');
    }
    public function peserta(){
        return $this->hasOne(Peserta::class,'id_users');
    }
    public function panitia(){
        return $this->hasOne(Panitia::class,'id_users');
    }
    public function penandatangan(){
        return $this->hasOne(Penandatangan::class,'id_users');
    }   
}
