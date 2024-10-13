<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HorarioAdministrativo extends Model
{
    use HasFactory;

    protected $table = 'horarios_administrativos';

    protected $casts=[
        'periodo'=>'string',
        'docente'=>'integer',
        'consecutivo_admvo'=>'integer',
        'descripcion_horario'=>'integer',
    ];
}
