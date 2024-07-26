<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Alumno
 * @package App
 * @mixin Builder
 */

class Alumno extends Model
{
    use HasFactory;
    protected $primaryKey='no_de_control';
    protected $casts=[
        'no_de_control'=>'string',
        'carrera' => 'string',
        'especialidad' => 'string',
        'plan_de_estudios' => 'string',
        'periodo_ingreso_it' => 'string',

    ];
    protected $fillable=['no_de_control','carrera','reticula','nivel_escolar','nip'];
}
