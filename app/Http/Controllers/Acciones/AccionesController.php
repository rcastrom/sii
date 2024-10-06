<?php

namespace App\Http\Controllers\Acciones;

use App\Http\Controllers\Controller;
use App\Models\AlumnosGeneral;
use App\Models\PeriodoFicha;
use App\Models\PermisosCarrera;
use Illuminate\Database\Query\JoinClause;
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
        return Carrera::where([
            'carrera'=>$carrera,
            'reticula'=>$reticula
        ])->first();
    }
    /*
     * Devolver el período actual
     * @return mixed
     */
    public function periodo()
    {
        return DB::Select('select periodo from pac_periodo_actual()');
    }
    /*
     * Devolver los datos del alumno
     * @return mixed
     */
    public function datos_generales_alumno($control)
    {
        return AlumnosGeneral::where('no_de_control',$control)->first();
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
        return array($calificaciones,$nombres);
    }
    /*
     * Devuelve el listado de materias de acuerdo al plan de estudios del estudiante (retícula)
     *
     * @param string $control
     * @return mixed
     */
    public function cmaterias($control){
        return DB::select("SELECT * FROM cmaterias('$control')");
    }
    /*
     * Devuelve la información para la vista retícula
     *
     * @param string $control
     * @return mixed
     */
    public function reticula($control)
    {
        return DB::select("select * from pac_reticulaalumno('$control')");
    }
    /*
     * Devuelve los datos para la constancia
     *
     * @param string $control
     * @return mixed
     */
    public function totales($control){
        return DB::select("select * from pac_calcula_totales_alumno('$control')");
    }
    /*
     * Devuelve información del kardex para constancias vista completa
     *
     * @param string $control
     * @return mixed
     */
    public function constancia_kardex_completo($control){
        return DB::select("select * from pac_constancias_kardex ('$control')");
    }
    /*
     * Devuelve información del kardex para constancias vista corta
     *
     * @param string $control
     * @return mixed
     */
    public function constancia_kardex($control){
        return DB::select("select * from pac_constancias ('$control')");
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
        return DB::select("select * from pac_calificaciones('$control','$periodo')");
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
        return DB::select("select * from pac_horario('$control','$periodo')");
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
        return DB::select("select * from pac_certificado_cal('$control')");
    }
    /*
     *Devuelve las materias que no han sido evaluadas
     *
     * @param string $periodo
     * @return mixed
     */
    public function sin_evaluar($periodo){
        return DB::select("select * from pac_materias_faltan('$periodo')");
    }
    /*
     *Devuelve las materias que ya fueron evaluadas
     *
     * @param string $periodo
     * @return mixed
     */
    public function evaluadas($periodo){
        return DB::select("select * from pac_materias_calificadas('$periodo')");
    }
    /*
     *Devuelve las actas que no han sido entregadas en Escolares
     *
     * @param string $periodo
     * @return mixed
     */
    public function actas_faltantes($periodo){
        return DB::select("select * from pac_actas_faltan('$periodo')");
    }
    /*
     * Devuelve los grupos que se ofertaron en Idioma Extranjero
     *
     * @param string $periodo
     * @param string $idioma
     * @return mixed
     */
    public function consulta_idiomas($periodo,$idioma){
        return DB::select("select * from pac_idiomas_consulta('$periodo',$idioma)");
    }
    /*
     * Devuelve la población escolar
     *
     * @param string $periodo
     * @return mixed
     */
    public function inscritos($periodo){
        return DB::select("select * from pac_poblacion('$periodo')");
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
        return DB::select('SELECT 1 AS si FROM periodos_escolares WHERE periodo = :periodo
        AND CURRENT_DATE BETWEEN inicio_cal_docentes AND fin_cal_docentes',['periodo'=>$periodo]);
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
        return DB::select("select * from pac_cresidencias('$periodo','$rfc')");
    }
    /*
     * Devuelve a los estudiantes asignados del docente para residencias en el periodo señalado
     *
     * @param string $periodo
     * @param string $rfc
     * @return array $data
     */
    public function inforesidencias($periodo,$rfc){
        return DB::select("select * from pac_dataresidencias('$periodo','$rfc')");
    }

    /*
     * Devuelve los estudios que el personal tenga registrados en la BD
     *
     * @param integer $personal
     * @return array $data
     */
    public function personal_estudios($personal){
        return DB::select("select * from pap_estudios_personal($personal)");
    }

    /*
     * Actualiza la información de egresado cuando se modifica el estatus
     *
     * @param string $control
     * @return void
     */
    public function actualizar_egresado($control){
        return DB::select("SELECT * FROM actualizar_egreso_ind('$control')");
    }

    /*
    * Devuelve la información del período de entrega de fichas para aspirantes a ingresar
    *
    * @return void
    */
    public function periodo_entrega_fichas(){
        $periodo_ficha = PeriodoFicha::where('activo',1)->first();
        return PeriodoEscolar::where('periodo',$periodo_ficha->fichas)->first();
    }

    /*
     * Indicar los permisos sobre la carrera que tenga el usuario
     *
     * @param string correo
     * @return mixed
     */
    public function permisos_carreras($correo){
        $carreras=PermisosCarrera::where('email',$correo)
            ->join('carreras',function(JoinClause $join){
                $join->on('carreras.carrera','=','permisos_carreras.carrera')
                    ->on('carreras.reticula','=','permisos_carreras.reticula');
            })->select('carreras.carrera','carreras.reticula','carreras.nombre_reducido')
            ->orderBy('nombre_reducido','ASC')
            ->orderBy('reticula','ASC')
            ->get();
        return $carreras;
    }
}
