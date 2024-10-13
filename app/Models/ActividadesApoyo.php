<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActividadesApoyo extends Model
{
    use HasFactory;

    protected $table = 'actividades_apoyo';
    protected $casts = [
        'actividad' => 'string',
        'descripcion_actividad' => 'string',
    ];
}
