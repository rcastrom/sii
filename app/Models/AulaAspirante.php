<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class AulaAspirante
 * @package App
 * @mixin Builder
 */
class AulaAspirante extends Model
{
    use HasFactory;
    protected $table="aulas_aspirantes";
    protected $primaryKey="id";

    protected $fillable=["periodo","aula","capacidad","disponibles","carrera"];
}
