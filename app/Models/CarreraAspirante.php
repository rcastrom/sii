<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarreraAspirante extends Model
{
    protected $connection='nuevo_ingreso';
    protected $table='datos_personales';
    protected $casts=['carrera'=>'string'];
}
