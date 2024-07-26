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
}
