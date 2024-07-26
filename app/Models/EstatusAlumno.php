<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class EstatusAlumno
 * @package App
 * @mixin Builder
 */
class EstatusAlumno extends Model
{
    use HasFactory;
    protected $table='estatus_alumno';
    protected $casts=[
        'estatus'=>'string',
    ];
    protected $primaryKey='estatus';

}
