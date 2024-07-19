<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personal extends Model
{
    use HasFactory;
    protected $table="personal";

    protected $casts=[
        'status_empleado'=>'string'
    ];

    protected $fillable=['apellido_materno','curp_empleado',
        'nombre_empleado','no_tarjeta'];
}
