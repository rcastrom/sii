<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    use HasFactory;
    protected $primaryKey='no_de_control';
    protected $casts=[
        'no_de_control'=>'string',
    ];
    protected $fillable=['estatus_alumno'];
}
