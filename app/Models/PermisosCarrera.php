<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class PermisosCarrera
 * @package App
 * @mixin Builder
 */
class PermisosCarrera extends Model
{
    use HasFactory;
    protected $primaryKey="email";
}
