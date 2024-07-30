<?php

namespace App\Http\Controllers\Escolares;

use App\Http\Controllers\Acciones\AccionesController;

class Datos
{
    static public function datos_alumno($control)
    {
        return (new AccionesController)->datos_generales_alumno($control);
    }
}
