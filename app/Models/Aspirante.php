<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Aspirante extends Model
{

    protected $primaryKey = 'id';
    protected $casts=[
        'ficha'=>'integer',
        'periodo'=>'string',
        'apellido_paterno'=>'string',
        'apellido_materno'=>'string',
        'nombre_aspirante'=>'string',
        'carrera'=>'string',
        'fecha_nacimiento'=>'date',
        'sexo'=>'string',
        'pais'=>'string',
        'carrera_opcion_1'=>'string',
        'cert_prepa'=>'boolean',
        'const_terminacion'=>'boolean',
        'acta_nacimiento'=>'boolean',
        'curp'=>'boolean',
        'nss'=>'boolean',
        'migratorio'=>'integer',
        'pago_ficha'=>'boolean',
        'grupo'=>'string',
        'control'=>'string'
    ];
    protected $fillable=['periodo','ficha','apellido_materno','nombre_aspirante',
        'fecha_nacimiento','sexo','carrera','pago_ficha'];
}
