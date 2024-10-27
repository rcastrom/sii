<?php

namespace App\Http\Controllers\Alumnos;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MenuAlumnosController;
use App\Models\Alumno;
use App\Models\Carrera;
use App\Models\EstatusAlumno;
use App\Models\EvaluacionAlumno;
use App\Models\Grupo;
use App\Models\HistoriaAlumno;
use App\Models\Materia;
use App\Models\PeriodoEscolar;
use App\Models\Personal;
use App\Models\Pregunta;
use App\Models\SeleccionMateria;
use Carbon\Carbon;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Acciones\AccionesController;
use PDF;

class AlumnosController extends Controller
{
    public function __construct(Dispatcher $events)
    {
        new MenuAlumnosController($events);
    }

    public function index(){
        return view('alumnos.index');
    }

    public function control(){
        $data=Auth::user()->email;
        $alumno=Alumno::where('correo_electronico',$data)->first();
        return $alumno->no_de_control;
    }

    public function ver_kardex(Request $request){
        $control=$this->control();
        $historial = (new AccionesController)->kardex($control);
        $calificaciones=$historial[0];
        $nperiodos=$historial[1];
        //$nperiodos = $this->nombres_periodos($control);
        $alumno = Alumno::findOrfail($control);
        $nombre_carrera = Carrera::where(
            [
                'carrera'=>$alumno->carrera,
                'reticula' => $alumno->reticula,
            ]
        )->select(['nombre_carrera', 'creditos_totales'])->first();
        $estatus = EstatusAlumno::where('estatus', $alumno->estatus_alumno)->first();
        $opcion=$request->opcion;
        if($opcion==1){
            $encabezado="Historial académico";
            return view('alumnos.kardex')
                ->with(compact('alumno', 'calificaciones',
                    'estatus', 'nombre_carrera', 'nperiodos', 'control','encabezado'));
        }else{
            $data = [
                'alumno' => $alumno,
                'control' => $control,
                'carrera' => $nombre_carrera,
                'nperiodos' => $nperiodos,
                'calificaciones' => $calificaciones
            ];
            $pdf = PDF::loadView('alumnos.pdf_kardex', $data);
            return $pdf->download('kardex.pdf');
        }
    }
    public function boleta()
    {
        $control=$this->control();
        $periodos=HistoriaAlumno::where('no_de_control',$control)
            ->select(['historia_alumno.periodo','periodos_escolares.identificacion_corta'])
            ->distinct()
            ->join('periodos_escolares','historia_alumno.periodo','=','periodos_escolares.periodo')
            ->orderBy('periodo','DESC')
            ->get();
        $encabezado="Búsqueda de boletas";
        return view('alumnos.preboleta')
            ->with(compact('encabezado','periodos'));
    }
    public function verboleta(Request $request){
        $control=$this->control();
        $alumno=Alumno::findOrfail($control);
        $periodo=$request->get('periodo_busqueda');
        $calificaciones=(new AccionesController)->boleta($control,$periodo);
        $nombre_periodo=PeriodoEscolar::where('periodo',$periodo)->first();
        $encabezado="Boleta del período ".$nombre_periodo->identificacion_larga;
        return view('alumnos.boleta')
            ->with(compact('alumno','encabezado',
                'calificaciones','nombre_periodo','periodo'));
    }
    public function reticula()
    {
        $control=$this->control();
        $historial=(new AccionesController)->reticula($control);
        $periodo_actual=(new AccionesController)->periodo();
        $periodo=$periodo_actual[0]->periodo;
        $carga=SeleccionMateria::where('periodo',$periodo)
            ->join('alumnos','alumnos.no_de_control','=','seleccion_materias.no_de_control')
            ->where('alumnos.no_de_control',$control)
            ->join('materias','seleccion_materias.materia','=','materias.materia')
            ->join('materias_carreras as mc','seleccion_materias.materia','=','mc.materia')
            ->join('alumnos as al1','al1.carrera','=','mc.carrera')
            ->join('alumnos as al2','al2.reticula','=','mc.reticula')
            ->selectRaw('distinct(seleccion_materias.materia),grupo,nombre_abreviado_materia,creditos_materia,repeticion,global')
            ->get();
        $encabezado="Vista reticular de la carrera";
        return view('alumnos.reticula')
            ->with(compact('encabezado','historial','carga'));
    }
    public function horario()
    {
        $control=$this->control();
        $periodo_actual=(new AccionesController)->periodo();
        $periodo=$periodo_actual[0]->periodo;
        if(SeleccionMateria::where(
            [
                'no_de_control' => $control,
                'periodo' => $periodo
            ]
        )->count()>0){
            $alumno=Alumno::findOrfail($control);
            $datos_horario=(new AccionesController)->horario($control,$periodo);
            $nombre_periodo=PeriodoEscolar::where('periodo',$periodo)->first();
            $encabezado="Horario del periodo ".$nombre_periodo->identificacion_larga;
            return view('alumnos.horario')
                ->with(compact('alumno','datos_horario',
                    'encabezado','periodo'));
        }else{
            $encabezado="Sin horario";
            $mensaje="El estudiante no cuenta con carga académica asignada";
            return view('alumnos.no')->with(compact('mensaje','encabezado'));
        }
    }
    public function evaluacion(){
        $control=$this->control();
        $periodo_actual=(new AccionesController)->periodo();
        $periodo=$periodo_actual[0]->periodo;
        $nombre_periodo=PeriodoEscolar::where('periodo',$periodo)->first();
        if(SeleccionMateria::where('no_de_control',$control)
                ->where('periodo',$periodo)
                ->count()>0){
            $materias=(new AccionesController)->materias_evaluar($periodo,$control);
            if(empty($materias)){
                $encabezado="Evaluación docente ya realizada";
                $mensaje="Ya finalizaste la evaluación docente del período ".$nombre_periodo->identificacion_corta;
                return view('alumnos.no')->with(compact('mensaje','encabezado'));
            }else{
                $carga=array();
                $i=1;
                foreach ($materias as $materia){
                    $nmat=Materia::where('materia',$materia->materia)
                        ->select('nombre_abreviado_materia')
                        ->first();
                    $nombre_mat=$nmat->nombre_abreviado_materia;
                    $carga[$i]=$materia->materia."_".$materia->grupo."_".$nombre_mat;
                    $i++;
                }
            }
            $encabezado="Evaluación docente";
            return view('alumnos.preencuesta')
                ->with(compact('nombre_periodo','carga','encabezado'));
        }else{
            $mensaje="NO CUENTA CON CARGA ACADÉMICA ASIGNADA";
            return view('alumnos.no')->with(compact('mensaje'));
        }
    }
    public function evaluar(Request $request){
        $materia=$request->get('materia');
        $data=explode("_",$materia);
        $mat=$data[0]; $gpo=$data[1];
        $periodo_actual=(new AccionesController)->periodo();
        $periodo=$periodo_actual[0]->periodo;
        $nombre_periodo=PeriodoEscolar::where('periodo',$periodo)->first();
        $preguntas=Pregunta::where('encuesta','=','A')
            ->where('consecutivo','=',2)->get();
        $nmat=Materia::where('materia',$mat)->first();
        $doc=Grupo::where('periodo',$periodo)
            ->where('materia',$mat)
            ->where('grupo',$gpo)
            ->select('docente')
            ->first();
        if(is_null($doc->docente)){
            $nombre_docente="POR ASIGNAR";
            $docente=null;
        }else{
            $nombre_maestro=Personal::where('id',$doc->docente)->first();
            $docente=$doc->id;
            $nombre_docente=trim($nombre_maestro->nombre_empleado)." ".trim($nombre_maestro->apellidos_empleado);
        }
        $encabezado="Evaluación al docente";
        return view('alumnos.encuesta')
            ->with(compact('mat','gpo','encabezado',
                'nmat','preguntas','docente','nombre_docente','nombre_periodo'));
    }
    public function evaluaciondoc(Request $request){
        $materia=$request->get('materia');
        $gpo=$request->get('gpo');
        $docente=$request->get('docente');
        $control=$this->control();
        $periodo_actual=(new AccionesController)->periodo();
        $periodo=$periodo_actual[0]->periodo;
        $respuestas="";
        foreach ($request->all() as $key=>$value){
            if(($key!="materia")&&($key!="gpo")&&($key!="docente")&&($key!="_token")){
                $respuestas.=$value;
            }
        }
        $respuesta=trim($respuestas);
        if(is_null($docente)){
            $cve=NULL;
        }else{
            $doc=Personal::where('id',$docente)
                ->select('clave_area')
                ->first();
            $cve=$doc->clave_area;
        }
        EvaluacionAlumno::insert([
            'periodo'=>$periodo,
            'no_de_control'=>$control,
            'materia'=>$materia,
            'grupo'=>$gpo,
            'personal'=>$docente,
            'clave_area'=>$cve,
            'encuesta'=>'A',
            'respuestas'=>$respuesta,
            'resp_abierta'=>'',
            'consecutivo'=>'2',
            'created_at'=>Carbon::now(),
            'updated_at'=>null
        ]);
        return redirect('/estudiante/periodo/eval');
    }
}
