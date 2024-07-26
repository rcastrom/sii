<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class PersonalPlaza
 * @package App
 * @mixin Builder
 */

class PersonalPlaza extends Model
{
    use HasFactory;

    protected $casts=[
        'unidad'=>'string',
        'subunidad'=>'string',
        'horas'=>'integer',
        'diagonal'=>'string',
        'efectos_iniciales'=>'string',
        'efectos_finales'=>'string'
    ];

    protected $fillable=['id_personal','unidad','subunidad',
        'id_categoria','horas','id_motivo','efectos_iniciales','efectos_finales'];
}
