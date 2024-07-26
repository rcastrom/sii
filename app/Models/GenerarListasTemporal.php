<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class GenerarListasTemporal
 * @package App
 * @mixin Builder
 */

class GenerarListasTemporal extends Model
{
    use HasFactory;
    protected $table="generar_listas_temporales";
    protected $primaryKey="no_de_control";
    protected $casts=[
        'no_de_control'=>'string',
    ];
}
