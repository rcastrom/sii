<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsuarioAspirante extends Model
{
    protected $connection='nuevo_ingreso';
    protected $table='users';
    protected $casts=['password'=>'string'];
    protected $hidden=['password'];
    protected $fillable=['id','password'];
}
