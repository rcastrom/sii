<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Jefe
 * @package App
 * @mixin Builder
 */
class Jefe extends Model
{
    use HasFactory;

    protected $table='jefes';

    protected $casts = [
        'clave_area'=>'string',
        'id_jefe'=>'integer'
    ];

    protected $fillable = ['clave_area','id_jefe','descripcion_area'];
}
