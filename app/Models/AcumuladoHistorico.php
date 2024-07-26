<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class AcumuladoHistorico
 * @package App
 * @mixin Builder
 */
class AcumuladoHistorico extends Model
{
    use HasFactory;
    protected $table="acumulado_historico";
    protected $primaryKey="periodo";
}
