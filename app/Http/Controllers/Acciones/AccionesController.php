<?php

namespace App\Http\Controllers\Acciones;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Carrera;

class AccionesController extends Controller
{
    /*
     * Devolver el nombre de una carrera
     *
     * @param string $carrera
     * @param int $reticula
     * @return mixed
     */
    public function ncarrera($carrera,$reticula)
    {
        $data=Carrera::where([
            'carrera'=>$carrera,
            'reticula'=>$reticula
        ])->first();
        return $data;
    }
    /*
     * Devolver el período actual
     * @return mixed
     */
    public function periodo()
    {
        $periodo_actual = DB::Select('select periodo from pac_periodo_actual()');
        return $periodo_actual;
    }
}
