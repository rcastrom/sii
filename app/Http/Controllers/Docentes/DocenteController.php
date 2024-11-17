<?php

namespace App\Http\Controllers\Docentes;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Acciones\AccionesController;
use App\Models\Alumno;
use App\Models\EvaluacionAlumno;
use App\Models\Grupo;
use App\Models\Materia;
use App\Models\PeriodoEscolar;
use App\Models\Personal;
use App\Models\Pregunta;
use App\Models\SeleccionMateria;
use App\Models\Parcial;
use App\Models\CalificacionParcial;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Contracts\Events\Dispatcher;
use App\Http\Controllers\MenuDocenteController;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ListasExport;
use PDF;
use PhpOffice\PhpSpreadsheet\Exception;

class DocenteController extends Controller
{
    public function __construct(Dispatcher $events)
    {
        new MenuDocenteController($events);
    }
    public function index(){
        return view('personal.index');
    }
    public function docente(){
        $correo=Auth::user()->email;
        return Personal::where('correo_institucion',$correo)->first();
    }
    public function grupos_semestre(){
        return $this->extracted(2);
    }
    public function encurso(){
        return $this->extracted(1);
    }
    public function consulta_parciales(){
        return $this->extracted(3);
    }

    public function lista($per,$mat,$gpo){
        $periodo=base64_decode($per);
        $materia=base64_decode($mat);
        $grupo=base64_decode($gpo);
        $doc=$this->docente();
        if(SeleccionMateria::where('periodo',$periodo)
                ->where('materia',$materia)
                ->where('grupo',$grupo)
                ->count()>0){
            $inscritos=SeleccionMateria::where([
                'periodo' => $periodo,
                'materia' => $materia,
                'grupo' => $grupo
            ])->join('alumnos','seleccion_materias.no_de_control','=','alumnos.no_de_control')
                ->select(['seleccion_materias.no_de_control','alumnos.apellido_paterno',
                    'alumnos.apellido_materno','alumnos.nombre_alumno'])
                ->orderBy('alumnos.apellido_paterno','ASC')
                ->orderBy('alumnos.apellido_materno','ASC')
                ->orderBy('alumnos.nombre_alumno','ASC')
                ->get();
            $nombre_mat=Materia::where('materia',$materia)->first();
            $nperiodo=PeriodoEscolar::where('periodo',$periodo)->first();
            $data=[
                'alumnos'=>$inscritos,
                'docente'=>$doc,
                'nombre_periodo'=>$nperiodo,
                'nmateria'=>$nombre_mat,
                'grupo'=>$grupo
            ];
            $pdf = PDF::loadView('personal.pdf_lista', $data)->setPaper('Letter');
            return $pdf->download('lista.pdf');
        }else{
            $encabezado="Error de consulta de lista";
            $mensaje="No cuenta con alumnos inscritos en la materia";
            return view('personal.no')->with(compact('mensaje','encabezado'));
        }
    }

    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function excel($per, $mat, $gpo){
        $periodo=base64_decode($per);
        $materia=base64_decode($mat);
        $grupo=base64_decode($gpo);
        if(SeleccionMateria::where('periodo',$periodo)
                ->where('materia',$materia)
                ->where('grupo',$grupo)
                ->count()>0){
            $inscritos=SeleccionMateria::where([
                'periodo' => $periodo,
                'materia' => $materia,
                'grupo' => $grupo
            ])->join('alumnos','seleccion_materias.no_de_control','=','alumnos.no_de_control')
                ->select(['seleccion_materias.no_de_control','alumnos.apellido_paterno',
                    'alumnos.apellido_materno','alumnos.nombre_alumno'])
                ->orderBy('alumnos.apellido_paterno','ASC')
                ->orderBy('alumnos.apellido_materno','ASC')
                ->orderBy('alumnos.nombre_alumno','ASC')
                ->get();
            return Excel::download(new ListasExport($inscritos),'lista.xlsx');
        }
        $encabezado="Error de consulta de lista";
        $mensaje="No cuenta con alumnos inscritos en la materia";
        return view('personal.no')->with(compact('mensaje','encabezado'));
    }
    public function evaluar($per,$mat,$gpo){
        $periodo=base64_decode($per);
        $materia=base64_decode($mat);
        $grupo=base64_decode($gpo);
        $calificar=(new AccionesController)->calificar($periodo);
        if(!empty($calificar)) {
            if (SeleccionMateria::where('periodo', $periodo)
                    ->where('materia', $materia)->where('grupo', $grupo)
                    ->whereNotNull('calificacion')->count() > 0){
                $encabezado="Error para evaluación de materia";
                $mensaje="La materia ha sido evaluada";
                return view('personal.no')->with(compact('mensaje','encabezado'));
            }else{
                $inscritos = SeleccionMateria::where([
                    'periodo' => $periodo,
                    'materia' => $materia,
                    'grupo' => $grupo
                ])->join('alumnos','seleccion_materias.no_de_control','=','alumnos.no_de_control')
                    ->select(['seleccion_materias.no_de_control','alumnos.apellido_paterno',
                        'alumnos.apellido_materno','alumnos.nombre_alumno'])
                    ->orderBy('alumnos.apellido_paterno','ASC')
                    ->orderBy('alumnos.apellido_materno','ASC')
                    ->orderBy('alumnos.nombre_alumno','ASC')
                    ->get();
                $nombre_mat = Materia::where('materia', $materia)->first();
                $doc=$this->docente();
                $nperiodo = PeriodoEscolar::where('periodo', $periodo)->first();
                $encabezado="Evaluación final del período para la materia";
                return view('personal.evaluar')
                    ->with(compact('inscritos', 'nombre_mat', 'doc',
                        'nperiodo', 'materia', 'grupo','encabezado'));
            }
        }else{
            $fechas=PeriodoEscolar::where('periodo',$periodo)
                ->select(['inicio_cal_docentes','fin_cal_docentes'])
                ->first();
            $inicio=$fechas->inicio_cal_docentes;
            $fin=$fechas->fin_cal_docentes;
            $encabezado="Error de captura de calificaciones finales";
            $mensaje="No se encuentra en período de captura de calificaciones; es del ".$inicio." al ".$fin;
            return view('personal.no')->with(compact('mensaje','encabezado'));
        }
    }
    public function acta($per,$mat,$gpo){
        $periodo=base64_decode($per);
        $materia=base64_decode($mat);
        $grupo=base64_decode($gpo);
        if(SeleccionMateria::where('periodo',$periodo)
                ->where('materia',$materia)
                ->where('grupo',$grupo)
                ->count()>0){
            if(SeleccionMateria::where('periodo',$periodo)
                    ->where('materia',$materia)
                    ->where('grupo',$grupo)
                    ->whereNotNull('calificacion')
                    ->count()>0){
                $inscritos=SeleccionMateria::where([
                    'periodo' => $periodo,
                    'materia' => $materia,
                    'grupo' => $grupo
                ])->join('alumnos','seleccion_materias.no_de_control','=','alumnos.no_de_control')
                    ->select(['seleccion_materias.no_de_control','alumnos.apellido_paterno',
                        'alumnos.apellido_materno','alumnos.nombre_alumno'])
                    ->orderBy('alumnos.apellido_paterno','ASC')
                    ->orderBy('alumnos.apellido_materno','ASC')
                    ->orderBy('alumnos.nombre_alumno','ASC')
                    ->get();
                $datos=Grupo::where('periodo',$periodo)
                    ->where('materia',$materia)
                    ->where('grupo',$grupo)
                    ->first();
                $nombre_mat=Materia::where('materia',$materia)->first();
                $doc=$this->docente();
                $nperiodo=PeriodoEscolar::where('periodo',$periodo)->first();
                $data=[
                    'alumnos'=>$inscritos,
                    'docente'=>$doc,
                    'nombre_periodo'=>$nperiodo,
                    'datos'=>$datos,
                    'nmateria'=>$nombre_mat,
                    'materia'=>$materia,
                    'grupo'=>$grupo
                ];
                $pdf = PDF::loadView('personal.pdf_acta', $data)->setPaper('Letter');
                return $pdf->download('acta.pdf');
            }else{
                $encabezado="Error de consulta de lista";
                $mensaje="Aún no cuenta con calificaciones registradas";
                return view('personal.no')->with(compact('mensaje','encabezado'));
            }
        }else{
            $encabezado="Error de consulta de lista";
            $mensaje="No cuenta con alumnos inscritos en la materia";
            return view('personal.no')->with(compact('mensaje','encabezado'));
        }
    }
    public function calificar(Request $request){
        $materia=base64_decode($request->get('materia'));
        $grupo=base64_decode($request->get('grupo'));
        $periodo=base64_decode($request->get('periodo'));
        $inscritos = SeleccionMateria::where('periodo', $periodo)
            ->where('materia', $materia)
            ->where('grupo', $grupo)
            ->select(['no_de_control','repeticion'])
            ->get();
        foreach ($inscritos as $alumnos) {
            $control = $alumnos->no_de_control;
            $rep=$alumnos->repeticion;
            $plan=Alumno::select('plan_de_estudios')->where('no_de_control',$control)->first();
            $obtener=$materia."_".$control; $op="op_".$control;
            $cal=$request->get($obtener);
            $oport=$request->get($op);
            $oportunidad=$plan->plan_de_estudios==3?
                ($rep=="S"?($oport==1?"RO":"RP"):($oport==1?"OO":"OC")):
                ($rep=="S"?($oport==1?"R1":"R2"):($oport==1?"1":"2"));
            SeleccionMateria::where('periodo', $periodo)
                ->where('materia', $materia)
                ->where('grupo', $grupo)
                ->where('no_de_control',$control)
                ->update([
                    'calificacion'=>$cal,
                    'tipo_evaluacion'=>$oportunidad,
                ]);
        }
        return view('personal.gracias');
    }
    public function residencias1(){
        $periodo_actual=(new AccionesController)->periodo();
        $periodo=$periodo_actual[0]->periodo;
        $periodos=PeriodoEscolar::whereNotIn('periodo',array('99990','99999'))
            ->orderBy('periodo','desc')
            ->get();
        $encabezado="Evaluación de Residencias Profesionales";
        return view('personal.residencias1')->with(compact('periodo','periodos','encabezado'));
    }
    public function residencias2(Request $request){
        $per_residencias=$request->get('per_res');
        $doc=$this->docente();
        $cant=(new AccionesController)->residencias($per_residencias,$doc->id);
        if($cant[0]->cantidad==0){
            $encabezado="Error de periodo de evaluación en residencia profesional";
            $mensaje="No cuenta con residencias asignadas en el período señalado";
            return view('personal.no')->with(compact('mensaje','encabezado'));
        }else{
            //Esta sección debe ajustarse posteriormente
            $quienes=(new AccionesController)->inforesidencias($per_residencias,$doc->id);
            $encabezado="Evaluación de Residencias Profesionales";
            return view('personal.residencias2')->with(compact('per_residencias',
                'quienes','encabezado'));
        }
    }
    public function comparar_alumnos($periodo,$materia,$grupo,$docente){
        $parciales=Parcial::where(
            [
                'periodo'=>$periodo,
                'materia'=>$materia,
                'grupo'=>$grupo,
                'docente'=>$docente
            ]
        )->select('id')->get();
        foreach ($parciales as $parcial) {
            $alumnos=CalificacionParcial::where('parcial',$parcial->id)
                ->select('no_de_control')
                ->get();
            foreach ($alumnos as $alumno) {
                if(SeleccionMateria::where([
                    'periodo'=>$periodo,
                    'materia'=>$materia,
                    'grupo'=>$grupo,
                    'no_de_control'=>$alumno->no_de_control,
                ])->count()==0){
                    CalificacionParcial::where([
                        'parcial'=>$parcial->id,
                        'no_de_control'=>$alumno->no_de_control
                    ])->delete();
                }
            }
        }


    }

    /**
     * @param $accion
     * @return Factory|View|Application|\Illuminate\View\View
     */
    public function extracted($accion): \Illuminate\View\View|Application|Factory|View
    {
        $periodo_actual = (new AccionesController)->periodo();
        $periodo = $periodo_actual[0]->periodo;
        $doc = $this->docente();
        if (Grupo::where('periodo', $periodo)->where('docente', $doc->id)->count() > 0) {
            $materias = Grupo::where('periodo', $periodo)
                ->where('docente', $doc->id)
                ->join('materias', 'grupos.materia', '=', 'materias.materia')
                ->get();
            $nperiodo = PeriodoEscolar::where('periodo', $periodo)->first();
            if($accion==1){
                $encabezado = "Grupos del semestre en curso";
                return view('personal.prelistas')->with(compact('materias',
                    'nperiodo', 'encabezado', 'periodo'));
            }elseif($accion==2){
                $encabezado="Calificaciones parciales";
                return view('personal.parciales')->with(compact('materias',
                    'nperiodo', 'encabezado', 'periodo'));
            }else{
                $encabezado="Consulta de calificaciones parciales";
                return view('personal.parciales_consulta')->with(compact('materias',
                    'nperiodo', 'encabezado', 'periodo'));
            }
        } else {
            $encabezado = "Sin grupos";
            $mensaje = "No cuenta con grupos en el período actual";
            return view('personal.no')->with(compact('mensaje', 'encabezado'));
        }
    }
    public function preparciales(Request $request)
    {
        $request->validate([
            'unidad'=>'required',
        ], [
            'unidad.required'=>'Debe indicar la unidad por evaluar',
        ]);
        $periodo_actual = (new AccionesController)->periodo();
        $periodo = $periodo_actual[0]->periodo;
        $doc = $this->docente();
        $datos_materia=explode('_',$request->get('materia'));
        $materia=$datos_materia[0];
        $grupo=$datos_materia[1];
        $unidad=$request->get('unidad');
        $alumnos=SeleccionMateria::where([
            'periodo' => $periodo,
            'materia' => $materia,
            'grupo' => $grupo
        ])->join('alumnos','seleccion_materias.no_de_control','=','alumnos.no_de_control')
            ->select(['seleccion_materias.no_de_control','alumnos.apellido_paterno',
                'alumnos.apellido_materno','alumnos.nombre_alumno'])
            ->orderBy('alumnos.apellido_paterno','ASC')
            ->orderBy('alumnos.apellido_materno','ASC')
            ->orderBy('alumnos.nombre_alumno','ASC')
            ->get();
        $encabezado="Calificaciones parciales del semestre";
        $nmateria=Materia::where('materia',$materia)
            ->select('nombre_completo_materia')
            ->first();
        if(Parcial::where([
            'periodo' => $periodo,
            'docente' => $doc->id,
            'materia' => $materia,
            'unidad' => $unidad,
            'grupo' => $grupo
        ])->count() > 0){
            //Si algún alumno se dio de baja, se borra de la lista de los parciales
            $this->comparar_alumnos($periodo,$materia,$grupo,$doc->id);
            $parcial=Parcial::where([
                'periodo' => $periodo,
                'docente' => $doc->id,
                'materia' => $materia,
                'unidad' => $unidad,
                'grupo' => $grupo
            ])->select('id')->first();
            if(CalificacionParcial::where('parcial',$parcial->id)->count() > 0){
                $registros=CalificacionParcial::where('parcial',$parcial->id)
                    ->select('no_de_control')
                    ->get();
                foreach ($registros as $registro) {
                    $data[]=$registro->no_de_control;
                }
                $inscritos=SeleccionMateria::where([
                    'periodo'=>$periodo,
                    'materia'=>$materia,
                    'grupo' => $grupo
                ])->select('no_de_control')
                ->whereNotIn('no_de_control',$data)
                    ->get();
                if(!empty($inscritos)){
                    foreach ($inscritos as $inscrito) {
                        CalificacionParcial::insert([
                            'parcial'=>$parcial->id,
                            'no_de_control'=>$inscrito->no_de_control,
                            'calificacion'=>0,
                            'desertor'=>false
                        ]);
                    }
                }
                $alumnos=CalificacionParcial::where('parcial',$parcial->id)
                    ->join('alumnos','calificaciones_parciales.no_de_control'
                        ,'=','alumnos.no_de_control')
                    ->select(['calificaciones_parciales.no_de_control','alumnos.apellido_paterno',
                        'alumnos.apellido_materno','alumnos.nombre_alumno',
                        'calificaciones_parciales.calificacion',
                        'calificaciones_parciales.desertor','calificaciones_parciales.id'])
                    ->orderBy('alumnos.apellido_paterno','ASC')
                    ->orderBy('alumnos.apellido_materno','ASC')
                    ->orderBy('alumnos.nombre_alumno','ASC')
                    ->get();
                $id=$parcial->id;
                return view('personal.evalparcial1')
                    ->with(compact('nmateria','encabezado','unidad','alumnos','id'));
            }
        }
        $docente=$doc->id;
        return view('personal.evalparcial')
        ->with(compact('periodo','grupo',
            'encabezado','materia','unidad','nmateria','alumnos','docente'));
    }
    public function consulta_calificaciones(Request $request){
        $periodo_actual = (new AccionesController)->periodo();
        $periodo = $periodo_actual[0]->periodo;
        $doc = $this->docente();
        $datos_materia=explode('_',$request->get('materia'));
        $materia=$datos_materia[0];
        $grupo=$datos_materia[1];
        $encabezado="Consulta de calificaciones";
        if(Parcial::where([
            'periodo' => $periodo,
            'docente' => $doc->id,
            'materia' => $materia,
            'grupo' => $grupo
        ])->count() > 0){
            $maximo=Parcial::where([
                'periodo' => $periodo,
                'docente' => $doc->id,
                'materia' => $materia,
                'grupo' => $grupo
            ])->max('unidad');
            $alumnos=SeleccionMateria::where([
                'periodo' => $periodo,
                'materia' => $materia,
                'grupo' => $grupo
            ])->join('alumnos','seleccion_materias.no_de_control','=','alumnos.no_de_control')
                ->select(['seleccion_materias.no_de_control','alumnos.apellido_paterno',
                    'alumnos.apellido_materno','alumnos.nombre_alumno'])
                ->orderBy('alumnos.apellido_paterno','ASC')
                ->orderBy('alumnos.apellido_materno','ASC')
                ->orderBy('alumnos.nombre_alumno','ASC')
                ->get();
            $nmateria=Materia::where('materia',$materia)
                ->select('nombre_completo_materia')
                ->first();
            return view('personal.consulta_calificaciones')
                ->with(compact('nmateria','periodo','doc',
                    'materia','grupo', 'encabezado','alumnos','maximo'));

        }
        $mensaje="No ha registrado ninguna calificación";
        return view('personal.no')->with(compact('mensaje', 'encabezado'));
    }
    public function evaluacion_docente1(){
        $periodo_actual = (new AccionesController)->periodo();
        $periodo = $periodo_actual[0]->periodo;
        $periodos=PeriodoEscolar::whereNotIn('periodo',array('99990','99999'))
            ->orderBy('periodo','desc')
            ->get();
        $encabezado="Evaluación al docente";
        return view('personal.evaldocente1')->with(compact('periodos','periodo','encabezado'));
    }
    public function evaluacion_docente2(Request $request)
    {
        $doc = $this->docente();
        $periodo=$request->get('periodo');
        $nperiodo=PeriodoEscolar::where('periodo',$periodo)
            ->select('identificacion_corta')->first();
        $encabezado="Resultados de evaluación al docente";
        if(EvaluacionAlumno::where(
            [
                'personal' => $doc->id,
                'periodo' => $periodo,
            ]
        )->count() == 0){
            $mensaje="No hay información que mostrar";
            return view('personal.no')->with(compact('encabezado', 'mensaje'));
        }
        // Esta variable se emplea por si en algún momento llegara a cambiar la encuesta
        $maximo=Pregunta::where('consecutivo','=',2)
            ->where('encuesta','=','A')
            ->count();
        $materias=(new AccionesController)->evaluacion_al_docente_datos($periodo,$doc->id,$maximo);
        $resultados=(new AccionesController)->resultados_evaluacion_docente($periodo,$doc->id);
        $valores=[];
        $i=0;
        $suma=0;
        foreach ($resultados as $key=>$value){
            $valores[$i]=$value["porcentaje"];
            $suma+=$value["porcentaje"];
            $i++;
        }
        $promedio=round($suma/$i,2);

        switch ($promedio){
            case ($promedio>=1&&$promedio<=3.24): $cal="INSUFICIENTE"; break;
            case ($promedio>=3.25&&$promedio<=3.74): $cal="SUFICIENTE"; break;
            case ($promedio>=3.75&&$promedio<=4.24): $cal="BUENO"; break;
            case ($promedio>=4.25&&$promedio<=4.74): $cal="NOTABLE"; break;
            case ($promedio>=4.75&&$promedio<=5): $cal="EXCELENTE"; break;
            default : $cal="Otros"; break;
        }
        return view('personal.evaldocente2')
            ->with(compact('materias','nperiodo',
                'periodo','doc',
                'resultados','encabezado','promedio','cal','valores'));
    }
}
