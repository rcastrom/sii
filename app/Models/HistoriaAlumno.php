<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class HistoriaAlumno
 * @package App
 * @mixin Builder
 */

class HistoriaAlumno extends Model
{
    use HasFactory;
    protected $table='historia_alumno';
    protected $fillable=['no_de_control','periodo','materia','calificacion','tipo_evaluacion'];
    protected $casts=[
        'no_de_control'=>'string',
        'tipo_evaluacion' => 'string',
        'plan_de_estudios' => 'string',
        'periodo' => 'string',
        'calificacion' => 'integer'
    ];
}
