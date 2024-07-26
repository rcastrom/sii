<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Especialidad
 * @package App
 * @mixin Builder
 */

class Especialidad extends Model
{
    use HasFactory;
    protected $table='especialidades';
    protected $primaryKey="carrera";
}
