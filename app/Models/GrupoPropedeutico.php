<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class GrupoPropedeutico extends Model
{
    use HasFactory;
    protected $table='grupos_propedeuticos';
    protected $primaryKey='id';
    protected $casts=[
        'periodo'=>'string',
        'grupo'=>'string',
        'nombre_corto'=>'string',
        'materia'=>'string',
        'docente'=>'integer',
        'entrada_1'=>'datetime:H:i',
        'entrada_2'=>'datetime:H:i',
        'entrada_3'=>'datetime:H:i',
        'entrada_4'=>'datetime:H:i',
        'entrada_5'=>'datetime:H:i',
        'salida_1'=>'datetime:H:i',
        'salida_2'=>'datetime:H:i',
        'salida_3'=>'datetime:H:i',
        'salida_4'=>'datetime:H:i',
        'salida_5'=>'datetime:H:i',
    ];
    protected $fillable=[
        'grupo','nombre_corto','materia','docente',
        'entrada_1','entrada_2','entrada_3','entrada_4','entrada_5',
        'salida_1','salida_2','salida_3','salida_4','salida_5'
    ];
}
