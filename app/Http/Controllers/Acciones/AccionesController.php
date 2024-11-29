<?php

namespace App\Http\Controllers\Acciones;

use App\Http\Controllers\Controller;
use App\Models\Grupo;
use App\Models\MateriaCarrera;
use App\Models\PeriodoFicha;
use App\Models\PermisosCarrera;
use App\Models\SeleccionMateria;
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
     * Devuelve el nombre del periodo
     */
    public function nombre_periodo($periodo)
    {
        return PeriodoEscolar::where('periodo',$periodo)
            ->select('identificacion_corta')
            ->first();
    }

    /*
     * Verifica si el docente tiene cruce de horario
     */
    public function cruce($periodo,$materia,$grupo,$docente,$dia){
        return DB::select("select cruce from cruce_horario('$periodo','$materia','$grupo','$docente','$dia')");
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
        return DB::select("select * from pac_idiomas_consulta('$periodo','$idioma')");
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
    public function residencias($periodo,$docente)
    {
        return DB::select("select * from pac_cresidencias('$periodo','$docente')");
    }
    /*
     * Devuelve a los estudiantes asignados del docente para residencias en el periodo señalado
     *
     * @param string $periodo
     * @param string $rfc
     * @return array $data
     */
    public function inforesidencias($periodo,$docente){
        return DB::select("select * from pac_dataresidencias('$periodo','$docente')");
    }

    /*
     * Devuelve los estudios que el personal tenga registrados en la BD
     *
     * @param integer $personal
     * @return array $data
     */
    public function personal_estudios($personal){
        return DB::select("select * from pap_estudios_personal('$personal')");
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
        return PermisosCarrera::where('email',$correo)
            ->join('carreras',function(JoinClause $join){
                $join->on('carreras.carrera','=','permisos_carreras.carrera')
                    ->on('carreras.reticula','=','permisos_carreras.reticula');
            })->select(['carreras.carrera','carreras.reticula','carreras.nombre_reducido'])
            ->orderBy('nombre_reducido','ASC')
            ->orderBy('reticula','ASC')
            ->get();
    }

    /*
     * Devolver el listado de materias por ofertar
     *
     * @param string carrera
     * @param int reticula
     * @return mixed
     */
    public function listado_por_ofertar($carrera, $reticula){
        return MateriaCarrera::where('carrera',$carrera)
            ->where('reticula',$reticula)
            ->leftjoin('materias','materias_carreras.materia','=','materias.materia')
            ->select(['materias.materia as mater','materias.nombre_abreviado_materia',
                'materias_carreras.semestre_reticula'])
            ->orderBy('semestre_reticula','ASC')
            ->orderBy('nombre_completo_materia','ASC')
            ->get();
    }

    /*
     * Devolver el listado de materias que se ofertan en el semestre
     *
     * @param string carrera
     * @param int reticula
     * @param string periodo
     * @return mixed
     */
    public function listado_materias($carrera, $reticula, $periodo){
        return MateriaCarrera::where('materias_carreras.carrera',$carrera)
            ->where('materias_carreras.reticula',$reticula)
            ->join('grupos','materias_carreras.materia','=','grupos.materia')
            ->where('grupos.periodo',$periodo)
            ->join('materias','materias_carreras.materia','=','materias.materia')
            ->orderBy('semestre_reticula','ASC')
            ->orderBy('nombre_completo_materia','ASC')
            ->get();
    }

    /*
     * Devolver el listado de alumnos en base a la materia
     *
     * @param string periodo
     * @param string materia
     * @param string grupo
     * @return mixed
     */
    public function listado_alumnos($periodo, $materia, $grupo){
        return SeleccionMateria::where('periodo',$periodo)
            ->where('materia',$materia)
            ->where('grupo',$grupo)
            ->join('alumnos','seleccion_materias.no_de_control','=','alumnos.no_de_control')
            ->orderBy('apellido_paterno','ASC')
            ->orderBy('apellido_materno','ASC')
            ->orderBy('nombre_alumno','ASC')
            ->get();
    }

    /*
     * Devolver el nombre del docente de una materia
     *
     * @param string periodo
     * @param string materia
     * @param string grupo
     * @return mixed
     */
    public function nombre_docente($periodo, $materia, $grupo){
        return Grupo::select('docente')
            ->where('periodo',$periodo)
            ->where('materia',$materia)
            ->where('grupo',$grupo)
            ->first();
    }

    /*
     * Indica el nivel académico del docente para el horario
     * @param int $docente
     * @return array $data
     */
    public function nivel_academico_docente($id_docente)
    {
        return DB::select("select * from pac_nivel_academico('$id_docente')");
    }

    /*
     * Indica las materias en las que el estudiante no ha evaluado al docente
     * @param string $periodo
     * @param string $control
     * @return array $data
     */
    public function materias_evaluar($periodo, $control){
        return DB::select("SELECT * FROM evl_omitir_mat_alu('$periodo','$control');");
    }

    /*
     * Indica si el estudiante está en fecha de reinscripción
     */
    public function en_fecha($periodo)
    {
        return DB::select('SELECT 1 AS si FROM periodos_escolares WHERE periodo = :periodo
        AND CURRENT_DATE BETWEEN inicio_sele_alumnos AND fin_sele_alumnos',['periodo'=>$periodo]);
    }

    /*
     * Indica si el estudiante está en su fecha - hora de selección materias
     */
    public function en_tiempo_reinscripcion($periodo,$control)
    {
        return DB::select('SELECT 1 AS si FROM avisos_reinscripcion WHERE periodo = :periodo
        AND no_de_control = :control AND CURRENT_TIMESTAMP > fecha_hora_seleccion',['periodo'=>$periodo,'control'=>$control]);
    }

    /*
     * Determina si la materia está en especial o no, y si fue seleccionada
     */
    public function verifica_especial($control, $periodo)
    {
        return DB::select("select * from pac_verifica_especial('$control','$periodo')");
    }

    /*
     * Determina si la materia está en repetición o no, y si fue seleccionada
     */
    public function verifica_repite($control, $periodo)
    {
        return DB::select("select * from pac_verifica_repite('$control','$periodo')");
    }

    /*
     * Grupos que se ofertan para la reinscripción
     */
    public function grupos_materia($periodo, $control, $materia)
    {
        return DB::select("select * from pac_gruposmateria('$periodo','$control','$materia')");
    }

    /*
     * Determina si el estudiante tiene otra materia que le impida el cruce
     */
    public function cruce_horario($periodo,$control,$dia,$hora_inicial,$hora_final)
    {
        return DB::select('select 1 as si from seleccion_materias SM, horarios H where SM.periodo = H.periodo
                and SM.materia = H.materia
                and SM.grupo = H.grupo
                and SM.periodo = :periodo
                and SM.no_de_control = :no_de_control
                and  H.dia_semana = :dia
                and  ( H.hora_inicial = :hora_inicial  or
                    ( (hora_inicial < :hora_inicial) and (:hora_inicial < hora_final) )  or
                    ( (hora_inicial < :hora_final) and (:hora_final < hora_final) )  or
                    ( (:hora_inicial < hora_inicial) and (hora_inicial < :hora_final)) or
                    ( (hora_inicial > :hora_inicial) and (hora_final < :hora_final))
                )',
            [
                'periodo'=>$periodo,
                'no_de_control'=>$control,
                'dia'=>$dia,
                'hora_inicial'=>$hora_inicial,
                'hora_final'=>$hora_final
            ]
        );
    }

    /*
     * Dar los datos de las materias que han sido evaluadas (eval docente), inscritos,
     * y cuántos han evaluado
     */
    public function evaluacion_al_docente_datos($periodo, $docente, $maximo)
    {
        return DB::select("select * from pac_evl_docente('$periodo','$docente','$maximo')");
    }

    /*
     * Resultados por preguntas, de la evaluación al docente
     */
    public function resultados_evaluacion_al_docente($periodo,$pregunta,$materia,$grupo)
    {
        return DB::select("SELECT * FROM pac_eval_docente('$periodo','$pregunta','$materia','$grupo')");
    }

    /*
     * Resultados en evaluación al docente por carrera
     */
    public function resultados_carrera_evaluacion_al_docente($periodo,$pregunta,$carrera,$reticula)
    {
        return DB::select("SELECT * FROM pac_eval_carr_ret('$periodo','$pregunta','$carrera','$reticula')");
    }

}
