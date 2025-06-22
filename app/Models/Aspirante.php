<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Aspirante extends Model
{
    protected $connection='nuevo_ingreso';
    protected $table = 'datos_personales';
    protected $primaryKey = 'aspirante_id';
    protected $casts=[
        'nombre'=>'string',
        'apellido_paterno'=>'string',
        'apellido_materno'=>'string',
        'curp'=>'string',
        'carrera'=>'string',
    ];
}
