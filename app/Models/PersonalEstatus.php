<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
