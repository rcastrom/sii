<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FechaEvaluacion extends Model
{
    use HasFactory;
    protected $table='fecha_evaluacion';
    protected $fillable=['periodo','encuesta','fecha_inicio','fecha_final'];
}
