<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluacionAspecto extends Model
{
    protected $casts=[
        'aspecto'=>'string',
        'encuesta'=>'string',
        'descripcion' =>'string',
        'consecutivo'=>'integer',
    ];
}
