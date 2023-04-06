<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstatusAlumno extends Model
{
    use HasFactory;
    protected $table='estatus_alumno';
    protected $casts=[
        'estatus'=>'string',
    ];
    protected $primaryKey='estatus';

}
