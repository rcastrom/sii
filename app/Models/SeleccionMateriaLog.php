<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeleccionMateriaLog extends Model
{
    use HasFactory;

    protected $table='seleccion_materias_log';
    protected $casts=[
        'periodo'=>'string',
        'materia'=>'string',
        'no_de_control'=>'string',
        'grupo'=>'string',
        'movimiento'=>'string',
        'responsable'=>'string',
    ];
    protected $fillable=['periodo','materia','no_de_control','grupo','movimiento','responsable'];
}
