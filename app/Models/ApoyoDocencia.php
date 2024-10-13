<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApoyoDocencia extends Model
{
    use HasFactory;

    protected $table = 'apoyo_docencia';
    protected $casts = [
        'periodo' => 'string',
        'docente' => 'integer',
        'actividad' => 'string',
        'consecutivo' => 'integer',
        'especifica_actividad' => 'string'
    ];
}
