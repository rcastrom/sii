<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvisoReinscripcion extends Model
{
    use HasFactory;
    protected $table="avisos_reinscripcion";
    protected $primaryKey='no_de_control';
    protected $casts=[
        'no_de_control'=>'string',
    ];
}
