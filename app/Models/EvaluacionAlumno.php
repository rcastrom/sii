<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class EvaluacionAlumno
 * @package App
 * @mixin Builder
 */

class EvaluacionAlumno extends Model
{
    use HasFactory;
    protected $primaryKey='no_de_control';
    protected $casts=[
        'no_de_control'=>'string',
        'periodo'=>'string',
        'materia'=>'string',
        'grupo'=>'string',
        'personal'=>'integer',
        'clave_area' => 'string',
        'encuesta' => 'string',
        'respuestas' => 'string',
        'consecutivo' => 'integer',
    ];
    protected $fillable=['periodo','no_de_control','materia',
        'grupo','personal','encuesta','respuestas','consecutivo'];
}
