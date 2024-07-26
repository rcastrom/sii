<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Carrera
 * @package App
 * @mixin Builder
 */
class Carrera extends Model
{
    use HasFactory;
    protected $primaryKey="carrera";
    protected $casts=[
        'carrera'=>'string'
    ];
}
