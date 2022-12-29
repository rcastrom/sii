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
    /*
     * Devuelve la información para el certificado de estudios
     *
     * @param string $control
     * @return mixed
     */
    public function historial($control){
        $data=DB::select("select * from pac_certificado_cal('$control')");
        return $data;
    }
    /*
     *Devuelve las materias que no han sido evaluadas
     *
     * @param string $periodo
     * @return mixed
     */
    public function sin_evaluar($periodo){
        $data = DB::select("select * from pac_materias_faltan('$periodo')");
        return $data;
    }
    /*
     *Devuelve las materias que ya fueron evaluadas
     *
     * @param string $periodo
     * @return mixed
     */
    public function evaluadas($periodo){
        $data = DB::select("select * from pac_materias_calificadas('$periodo')");
        return $data;
    }
    /*
     *Devuelve las actas que no han sido entregadas en Escolares
     *
     * @param string $periodo
     * @return mixed
     */
    public function actas_faltantes($periodo){
        $data = DB::select("select * from pac_actas_faltan('$periodo')");
        return $data;
    }
    /*
     * Devuelve los grupos que se ofertaron en Idioma Extranjero
     *
     * @param string $periodo
     * @param string $idioma
     * @return mixed
     */
    public function consulta_idiomas($periodo,$idioma){
        $data = DB::select("select * from pac_idiomas_consulta('$periodo',$idioma)");
        return $data;
    }
    /*
     * Devuelve la población escolar
     *
     * @param string $periodo
     * @return mixed
     */
    public function inscritos($periodo){
        $data = DB::select("select * from pac_poblacion('$periodo')");
        return $data;
    }
    /*
     * Cambia el estatus del alumno (no inscritos a baja temporal)
     *
     * @param string $periodo
     * @return void
     */
    public function modificar_estatus($periodo){
        DB::select("select * from pap_estatus_alumno('$periodo')");
    }
    /*
     * Actualiza el semestre del estudiante
     *
     * @param string $periodo
     * @return void
     */
    public function actualizar_semestre($periodo){
        DB::select("select * from pap_semestre_alumno('$periodo')");
    }
    /*
     * Actualiza la cantidad de estudiantes inscritos en el grupo de acuerdo al período
     *
     * @param string $periodo
     * @return void
     */
    public function actualizar_inscritos_grupo($periodo){
        DB::select("select * from pac_act_ins_gpoxmat('$periodo')");
    }
    /*
     * Indica si está el docente en fechas de evaluar
     *
     * @param string $periodo
     * @return string $data
     */
    public function calificar($periodo){
        $data=DB::select('SELECT 1 AS si FROM periodos_escolares WHERE periodo = :periodo
        AND CURRENT_DATE BETWEEN inicio_cal_docentes AND fin_cal_docentes',['periodo'=>$periodo]);
        return $data;
    }
    /*
     * Indica si el docente tiene residencias en el período señalado
     *
     * @param string $periodo
     * @param string $rfc
     * @return array $data
     */
    public function residencias($periodo,$rfc)
    {
        $data=DB::select("select * from pac_cresidencias('$periodo','$rfc')");
        return $data;
    }
    /*
     * Devuelve a los estudiantes asignados del docente para residencias en el periodo señalado
     *
     * @param string $periodo
     * @param string $rfc
     * @return array $data
     */
    public function inforesidencias($periodo,$rfc){
        $data=DB::select("select * from pac_dataresidencias('$periodo','$rfc')");
        return $data;
    }

    /*
     * Devuelve los estudios que el personal tenga registrados en la BD
     *
     * @param integer $personal
     * @return array $data
     */
    public function personal_estudios($personal){
        $data=DB::select("select * from pap_estudios_personal($personal)");
        return $data;
    }

}
