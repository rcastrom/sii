<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoriaAlumno extends Model
{
    use HasFactory;
    protected $table='historia_alumno';
    protected $primaryKey="no_de_control";
}
