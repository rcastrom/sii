<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluacionAlumno extends Model
{
    use HasFactory;
    protected $primaryKey=(['no_de_control','periodo','materia']);
    protected $casts=[
        'no_de_control'=>'string',
    ];
    protected $fillable=['periodo','no_de_control','materia','grupo','personal','encuesta','respuestas'];
}
