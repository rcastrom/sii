<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Organigrama
 * @package App
 * @mixin Builder
 */
class Organigrama extends Model
{
    use HasFactory;
    protected $table="organigrama";
    protected $primaryKey='clave_area';
    protected $casts=[
        'clave_area'=>'string',
        'area_depende'=>'string'
    ];
}
