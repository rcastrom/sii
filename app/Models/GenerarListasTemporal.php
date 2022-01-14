<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GenerarListasTemporal extends Model
{
    use HasFactory;
    protected $table="generar_listas_temporales";
    protected $primaryKey="no_de_control";
}
