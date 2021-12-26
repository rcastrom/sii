<?php

namespace App\Http\Controllers\Acciones;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Carrera;
use App\Models\HistoriaAlumno;
use App\Models\PeriodoEscolar;

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
    /*
     * Devuelve el kardex del estudiante
     * @param string $ncontrol
     * @return mixed
     */
    public function kardex($ncontrol)
    {
        //Primero, busco los periodos que ha tenido
        $inscrito_en = HistoriaAlumno::select('periodo')
            ->where('no_de_control', $ncontrol)
            ->distinct()
            ->get();
        $calificaciones=array();
        $nombres=array();
        foreach ($inscrito_en as $cuando) {
            //Ahora, materias y calificaciones
            $data = DB::select("select * from pac_calificaciones('$ncontrol','$cuando->periodo')");
            $data2=PeriodoEscolar::where('periodo',$cuando->periodo)->first();
            $calificaciones[$cuando->periodo] = $data;
            $nombres[$cuando->periodo]=$data2;
        }
        $info=array($calificaciones,$nombres);
        return $info;
    }
    /*
     * Devuelve el listado de materias de acuerdo al plan de estudios del estudiante (retícula)
     *
     * @param string ncontrol
     * @return mixed
     */
    public function cmaterias($ncontrol){
        $data=DB::select("SELECT * FROM cmaterias('$ncontrol')");
        return $data;
    }

}
