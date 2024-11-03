<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Parcial extends Model
{
    protected $table="parciales";
    protected $casts=[
        'periodo'=>'string',
        'materia'=>'string',
        'grupo'=>'string',
        'unidad'=>'integer',
        'docente'=>'integer'
        ];
    protected $fillable=[
        'periodo','materia','grupo','unidad','docente'
    ];
}
