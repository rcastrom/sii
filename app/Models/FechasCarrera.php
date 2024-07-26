<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class FechasCarrera
 * @package App
 * @mixin Builder
 */
class FechasCarrera extends Model
{
    use HasFactory;
    protected $primaryKey="carrera";
}
