<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class TipoEvaluacion
 * @package App
 * @mixin Builder
 */
class TipoEvaluacion extends Model
{
    use HasFactory;
    protected $table='tipos_evaluacion';
    protected $primaryKey="plan_de_estudios";
    protected $casts=
        [
            'plan_de_estudios'=>'string',
            'tipo_evaluacion'=>'string',
            'descripcion_evaluacion'=>'string',
            'descripcion_corta_evaluacion'=>'string',
            'segunda_oportunidad'=>'boolean',
            'complementaria'=>'boolean',
            'rec'=>'boolean'
        ];
}
