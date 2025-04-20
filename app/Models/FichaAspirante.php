<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FichaAspirante extends Model
{
    protected $connection='nuevo_ingreso';
    protected $table='fichas';
    protected $casts=[
        'periodo'=>'string',
        'aspirante'=>'integer',
        'pago_ficha'=>'integer',
        'pago_propedeutico'=>'integer',
        'pago_inscripcion'=>'integer',
    ];

}
