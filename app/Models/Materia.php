<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Materia
 * @package App
 * @mixin Builder
 */
class Materia extends Model
{
    use HasFactory;
    protected $primaryKey="materia";

    protected $casts=[
        'materia'=>'string',
    ];
}
