<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pregunta extends Model
{
    use HasFactory;
    protected $casts=[
        'encuesta'=>'string',
        'aspecto'=>'string',
        'no_pregunta'=>'integer',
        'pregunta'=>'string',
        'respuestas'=>'string',
        'resp_val'=>'integer',
        'consecutivo'=>'integer'
    ];
}
