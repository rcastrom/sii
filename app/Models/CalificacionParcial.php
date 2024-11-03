<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalificacionParcial extends Model
{
    protected $table = 'calificaciones_parciales';
    protected $casts=[
        'parcial'=>'integer',
        'no_de_control'=>'string',
        'calificacion'=>'integer',
        'deserto'=>'boolean',
    ];
    protected $fillable = ['parcial','no_de_control','calificacion'];
}
