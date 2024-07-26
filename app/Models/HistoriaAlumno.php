<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class HistoriaAlumno
 * @package App
 * @mixin Builder
 */

class HistoriaAlumno extends Model
{
    use HasFactory;
    protected $table='historia_alumno';
    protected $primaryKey="no_de_control";
    protected $casts=[
        'no_de_control'=>'string',
    ];
}
