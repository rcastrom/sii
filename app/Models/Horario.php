<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Horario
 * @package App
 * @mixin Builder
 */

class Horario extends Model
{
    use HasFactory;
    protected $primaryKey='periodo';
}
