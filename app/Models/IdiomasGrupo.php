<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class IdiomasGrupo
 * @package App
 * @mixin Builder
 */
class IdiomasGrupo extends Model
{
    use HasFactory;
    protected $primaryKey="periodo";
}
