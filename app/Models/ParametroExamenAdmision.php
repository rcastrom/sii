<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParametroExamenAdmision extends Model
{
    protected $table='parametros_fichas_examen';
    protected $primaryKey="id";
    protected $casts=[
        'periodo'=>'string',
        'carrera'=>'string',
        'fecha'=>'datetime:Y-m-d',
        'hora'=>'datetime:H:i',
        'indicaciones'=>'string',
    ];
    protected $fillable=['periodo','carrera','fecha','hora','indicaciones'];
}
