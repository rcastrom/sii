<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodoFicha extends Model
{
    use HasFactory;
    protected $table='parametros_fichas';
    protected $primaryKey="fichas";
}
