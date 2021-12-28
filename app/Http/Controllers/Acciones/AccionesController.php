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
     * @param string $control
     * @return mixed
     */
    public function kardex($control)
    {
        //Primero, busco los periodos que ha tenido
        $inscrito_en = HistoriaAlumno::select('periodo')
            ->where('no_de_control', $control)
            ->distinct()
            ->get();
        $calificaciones=array();
        $nombres=array();
        foreach ($inscrito_en as $cuando) {
            //Ahora, materias y calificaciones
            $data = DB::select("select * from pac_calificaciones('$control','$cuando->periodo')");
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
     * @param string $control
     * @return mixed
     */
    public function cmaterias($control){
        $data=DB::select("SELECT * FROM cmaterias('$control')");
        return $data;
    }
    /*
     * Devuelve la información para la vista retícula
     *
     * @param string $control
     * @return mixed
     */
    public function reticula($control)
    {
        $data = DB::select("select * from pac_reticulaalumno('$control')");
        return $data;
    }
    /*
     * Devuelve los datos para la constancia
     *
     * @param string $control
     * @return mixed
     */
    public function totales($control){
        $data=DB::select("select * from pac_calcula_totales_alumno('$control')");
        return $data;
    }
    /*
     * Devuelve información del kardex para constancias vista completa
     *
     * @param string $control
     * @return mixed
     */
    public function constancia_kardex_completo($control){
        $data=DB::select("select * from pac_constancias_kardex ('$control')");
        return $data;
    }
    /*
     * Devuelve información del kardex para constancias vista corta
     *
     * @param string $control
     * @return mixed
     */
    public function constancia_kardex($control){
        $data=DB::select("select * from pac_constancias ('$control')");
        return $data;
    }
    /*
     * Devuelve la información de la boleta de un periodo dado
     *
     * @param string $control
     * @param string $periodo
     * @return mixed
     */
    public function boleta($control, $periodo)
    {
        $data = DB::select("select * from pac_calificaciones('$control','$periodo')");
        return $data;
    }
    /*
     * Devuelve la información del horario de un periodo dado
     *
     * @param string $control
     * @param string $periodo
     * @return mixed
     */
    public function horario($control, $periodo)
    {
        $data = DB::select("select * from pac_horario('$control','$periodo')");
        return $data;
    }
    /*
     * Actualiza el semestre del estudiante, basándose en el período de ingreso
     * así como el período actual
     *
     * @param string $periodo_ingreso
     * @param string $periodo
     * @return int $semestre
     */
    public function semreal($periodo_ingreso, $periodo)
    {
        $anio_actual = substr($periodo, 0, 4);
        $anio_ingresa = substr($periodo_ingreso, 0, 4);
        $tipo_ingreso = substr($periodo_ingreso, -1);
        $per = substr($periodo, -1);
        if ($per == "3") {
            $semestre = ($tipo_ingreso == '3') ? (2 * ($anio_actual - $anio_ingresa) + 1) : (2 * ($anio_actual - $anio_ingresa) + 2);
        } else {
            $semestre = ($tipo_ingreso == '3') ? (2 * ($anio_actual - $anio_ingresa)) : (2 * ($anio_actual - $anio_ingresa) + 1);
        }
        return $semestre;
    }
}
