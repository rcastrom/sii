<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class IdiomasLiberacion
 * @package App
 * @mixin Builder
 */

class IdiomasLiberacion extends Model
{
    use HasFactory;
    protected $table="idiomas_liberacion";
    protected $primaryKey="control";
    protected $casts=[
        'control'=>'string',
    ];
}
