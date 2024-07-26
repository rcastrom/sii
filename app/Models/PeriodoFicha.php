<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class PeriodoFicha
 * @package App
 * @mixin Builder
 */
class PeriodoFicha extends Model
{
    use HasFactory;
    protected $table='parametros_fichas';
    protected $primaryKey="fichas";
}
