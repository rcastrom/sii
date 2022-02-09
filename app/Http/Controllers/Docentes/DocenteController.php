<?php

namespace App\Http\Controllers\Docentes;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Acciones\AccionesController;
use App\Models\Alumno;
use App\Models\Grupo;
use App\Models\Materia;
use App\Models\PeriodoEscolar;
use App\Models\Personal;
use App\Models\SeleccionMateria;
use Illuminate\Http\Request;
use Illuminate\Contracts\Events\Dispatcher;
use App\Http\Controllers\MenuDocenteController;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ListasExport;
use PDF;

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
    public function encurso(){
        $periodo_actual=(new AccionesController)->periodo();
        $periodo=$periodo_actual[0]->periodo;
        $doc=$this->docente();
        if(Grupo::where('periodo',$periodo)->where('rfc',$doc->rfc)->count()>0){
            $materias=Grupo::where('periodo',$periodo)
                ->where('rfc',$doc->rfc)
                ->join('materias','grupos.materia','=','materias.materia')
                ->get();
            $nperiodo=PeriodoEscolar::where('periodo',$periodo)->first();
            $encabezado="Grupos del semestre en curso";
            return view('personal.prelistas')->with(compact('materias',
                'nperiodo','encabezado','periodo'));
        }else{
            $encabezado="Sin grupos";
            $mensaje="No cuenta con grupos en el período actual";
            return view('personal.no')->with(compact('mensaje','encabezado'));
        }
    }
    public function lista($per,$mat,$gpo){
        $periodo=base64_decode($per); $materia=base64_decode($mat); $grupo=base64_decode($gpo);
        $doc=$this->docente();
        if(SeleccionMateria::where('periodo',$periodo)
                ->where('materia',$materia)
                ->where('grupo',$grupo)
                ->count()>0){
            $inscritos=SeleccionMateria::where('periodo',$periodo)
                ->where('materia',$materia)
                ->where('grupo',$grupo)
                ->join('alumnos','seleccion_materias.no_de_control','=','alumnos.no_de_control')
                ->orderBy('apellido_paterno','asc')
                ->orderBy('apellido_materno','asc')
                ->orderBy('nombre_alumno','asc')
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
    public function excel($per,$mat,$gpo){
        $periodo=base64_decode($per); $materia=base64_decode($mat); $grupo=base64_decode($gpo);
        if(SeleccionMateria::where('periodo',$periodo)
                ->where('materia',$materia)
                ->where('grupo',$grupo)
                ->count()>0){
            $inscritos=SeleccionMateria::where('periodo',$periodo)
                ->where('materia',$materia)
                ->where('grupo',$grupo)
                ->join('alumnos','seleccion_materias.no_de_control','=','alumnos.no_de_control')
                ->select('seleccion_materias.no_de_control','apellido_paterno','apellido_materno','nombre_alumno')
                ->orderBy('apellido_paterno','asc')
                ->orderBy('apellido_materno','asc')
                ->orderBy('nombre_alumno','asc')
                ->get();
            return Excel::download(new ListasExport($inscritos),'lista.xlsx');
        }else{
            $encabezado="Error de consulta de lista";
            $mensaje="No cuenta con alumnos inscritos en la materia";
            return view('personal.no')->with(compact('mensaje','encabezado'));
        }
    }
    public function evaluar($per,$mat,$gpo){
        $periodo=base64_decode($per); $materia=base64_decode($mat); $grupo=base64_decode($gpo);
        $calificar=(new AccionesController)->calificar($periodo);
        if(!empty($calificar)) {
            if (SeleccionMateria::where('periodo', $periodo)
                    ->where('materia', $materia)->where('grupo', $grupo)
                    ->whereNotNull('calificacion')->count() > 0){
                $encabezado="Error para evaluación de materia";
                $mensaje="La materia ha sido evaluada";
                return view('personal.no')->with(compact('mensaje','encabezado'));
            }else{
                $inscritos = SeleccionMateria::where('periodo', $periodo)
                    ->where('materia', $materia)
                    ->where('grupo', $grupo)
                    ->join('alumnos', 'seleccion_materias.no_de_control', '=', 'alumnos.no_de_control')
                    ->select('seleccion_materias.no_de_control', 'apellido_paterno', 'apellido_materno', 'nombre_alumno')
                    ->orderBy('apellido_paterno', 'asc')
                    ->orderBy('apellido_materno', 'asc')
                    ->orderBy('nombre_alumno', 'asc')
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
                ->select('inicio_cal_docentes','fin_cal_docentes')->first();
            $inicio=$fechas->inicio_cal_docentes;
            $fin=$fechas->fin_cal_docentes;
            $encabezado="Error de captura de calificaciones finales";
            $mensaje="No se encuentra en período de captura de calificaciones; es del ".$inicio." al ".$fin;
            return view('personal.no')->with(compact('mensaje','encabezado'));
        }
    }
    public function acta($per,$mat,$gpo){
        $periodo=base64_decode($per); $materia=base64_decode($mat); $grupo=base64_decode($gpo);
        if(SeleccionMateria::where('periodo',$periodo)
                ->where('materia',$materia)
                ->where('grupo',$grupo)
                ->count()>0){
            if(SeleccionMateria::where('periodo',$periodo)
                    ->where('materia',$materia)
                    ->where('grupo',$grupo)
                    ->whereNotNull('calificacion')
                    ->count()>0){
                $inscritos=SeleccionMateria::where('periodo',$periodo)
                    ->where('materia',$materia)
                    ->where('grupo',$grupo)
                    ->join('alumnos','seleccion_materias.no_de_control','=','alumnos.no_de_control')
                    ->select('seleccion_materias.no_de_control','apellido_paterno','apellido_materno','nombre_alumno','calificacion')
                    ->orderBy('apellido_paterno','asc')
                    ->orderBy('apellido_materno','asc')
                    ->orderBy('nombre_alumno','asc')
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
            ->select('no_de_control','repeticion')
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
        $cant=(new AccionesController)->residencias($per_residencias,$doc->rfc);
        if($cant[0]->cantidad==0){
            $encabezado="Error de periodo de evaluación en residencia profesional";
            $mensaje="No cuenta con residencias asignadas en el período señalado";
            return view('personal.no')->with(compact('mensaje','encabezado'));
        }else{
            //Esta sección debe ajustarse posteriormente
            $quienes=(new AccionesController)->inforesidencias($per_residencias,$doc->rfc);
            $encabezado="Evaluación de Residencias Profesionales";
            return view('personal.residencias2')->with(compact('per_residencias',
                'quienes','encabezado'));
        }
    }
}
