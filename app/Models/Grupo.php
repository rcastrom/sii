<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Grupo
 * @package App
 * @mixin Builder
 */

class Grupo extends Model
{
    use HasFactory;
    protected $primaryKey="periodo";
}
