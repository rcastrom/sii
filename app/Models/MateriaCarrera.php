<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MateriaCarrera extends Model
{
    use HasFactory;
    protected $table="materias_carreras";
    protected $primaryKey='materia';
}
