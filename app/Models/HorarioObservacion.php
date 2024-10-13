<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HorarioObservacion extends Model
{
    use HasFactory;

    protected $table='horarios_observaciones';

    protected $casts=[
        'periodo'=>'string',
        'docente'=>'integer',
        'observaciones'=>'string',
        'depto'=>'string'
    ];
}
