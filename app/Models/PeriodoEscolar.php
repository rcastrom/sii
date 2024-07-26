<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class PeriodoEscolar
 * @package App
 * @mixin Builder
 */
class PeriodoEscolar extends Model
{
    use HasFactory;
    protected $table='periodos_escolares';
    protected $primaryKey="periodo";
}
