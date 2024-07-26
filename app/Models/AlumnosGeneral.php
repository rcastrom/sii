<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class AlumnosGeneral
 * @package App
 * @mixin Builder
 */

class AlumnosGeneral extends Model
{
    use HasFactory;
    protected $table="alumnos_generales";
    protected $primaryKey='no_de_control';
    protected $casts=[
        'no_de_control'=>'string',
    ];
}
