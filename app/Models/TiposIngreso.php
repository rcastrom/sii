<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class TiposIngreso
 * @package App
 * @mixin Builder
 */
class TiposIngreso extends Model
{
    use HasFactory;
    protected $table="tipos_ingreso";
}
