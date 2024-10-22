<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HorarioNoDocente extends Model
{
    use HasFactory;

    protected $primaryKey='id';

    protected $casts=[
        'periodo'=>'string',
        'personal'=>'integer',
        'descripcion_horario'=>'integer',
        'area_adscripcion'=>'string',
        'observacion'=>'string',
    ];

    protected $fillable = ['periodo','personal','descripcion_horario','area_adscripcion'];
}
