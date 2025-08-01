<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Idioma
 * @package App
 * @mixin Builder
 */
class Idioma extends Model
{
    use HasFactory;

    protected $casts=[
      'idioma'=>'string',
      'abrev'=>'string'
    ];

    protected $fillable=['idioma','abrev'];
}
