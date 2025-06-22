<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InscripcionPropedeutico extends Model
{
    protected $primaryKey='id';
    protected $casts=[
        'periodo'=>'string',
        'grupo_id'=>'integer',
        'aspirante'=>'integer',
        'evaluacion'=>'integer',
        'created_at'=>'datetime',
        'updated_at'=>'datetime',
    ];
    protected $fillable = ['periodo','grupo_id','aspirante','evaluacion'];
}
