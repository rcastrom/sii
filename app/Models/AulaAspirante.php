<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AulaAspirante extends Model
{
    use HasFactory;
    protected $table="aulas_aspirantes";
    protected $primaryKey="id";

    protected $fillable=["periodo","aula","capacidad","disponibles","carrera"];
}
