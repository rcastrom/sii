<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanDeEstudio extends Model
{
    use HasFactory;
    protected $table="planes_de_estudio";
    protected $primaryKey="plan_de_estudio";
}
