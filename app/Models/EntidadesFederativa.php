<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class EntidadesFederativos
 * @package App
 * @mixin Builder
 */
class EntidadesFederativa extends Model
{
    //use HasFactory;
    protected $primaryKey="entidad_federativa";
}
