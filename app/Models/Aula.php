<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Aula
 * @package App
 * @mixin Builder
 */

class Aula extends Model
{
    use HasFactory;
    protected $primaryKey="id";
}
