<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Horario
 * @package App
 * @mixin Builder
 */

class Horario extends Model
{
    use HasFactory;
    protected $primaryKey='periodo';
    protected $casts=[
        'docente'=>'integer',
        'tipo_horario'=>'string',
        'dia_semana'=>'integer',
        'hora_inicial'=>'datetime:H:i',
        'hora_final'=>'datetime:H:i',
        'materia'=>'string',
        'grupo'=>'string',
        'aula'=>'string',
        'actividad'=>'string',
        'consecutivo' => 'integer',
        'vigencia_inicio' => 'datetime:Y-m-d',
        'vigencia_fin' => 'datetime:Y-m-d',
        'consecutivo_admvo' => 'integer',
        'tipo_personal' => 'string'
    ];
}
