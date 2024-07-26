<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class MateriaCarrera
 * @package App
 * @mixin Builder
 */

class MateriaCarrera extends Model
{
    use HasFactory;
    protected $table="materias_carreras";
    protected $primaryKey='materia';
}
