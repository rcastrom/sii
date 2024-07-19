<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
