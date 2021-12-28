<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoEvaluacion extends Model
{
    use HasFactory;
    protected $table='tipos_evaluacion';
    protected $primaryKey="plan_de_estudios";
}
