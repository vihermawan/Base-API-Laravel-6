<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $primaryKey='id_role';
    protected $table = 'role';
    public function users(){
        return $this->hasMany(Users::class,'id_roles');
    }
}
