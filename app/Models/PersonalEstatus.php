<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class PersonalEstatus
 * @package App
 * @mixin Builder
 */
class PersonalEstatus extends Model
{
    use HasFactory;

    protected $table="personal_estatus";

    protected $primaryKey="estatus";

    protected $casts=[
        "estatus"=>"string"
    ];

    protected $fillable=['estatus','descripcion'];
}
