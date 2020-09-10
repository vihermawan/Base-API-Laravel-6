<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SetPassword extends Model
{
    protected $table = 'set_password';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $primaryKey = 'id_set_password';
    protected $fillable = [
        'email', 'token'
    ];
}
