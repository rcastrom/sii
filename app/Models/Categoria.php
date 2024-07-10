<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;
    protected $casts =[
        'categoria'=>'string',
        'horas'=>'integer',
        'nivel'=>'integer'
    ];
    protected $fillable=['categoria','descripcion','horas'];
}
