<?php

namespace App\Http\Controllers\Escolares;

use App\Models\Alumno;
use App\Models\AlumnosGeneral;
use App\Models\AvisoReinscripcion;
use App\Models\Carrera;
use App\Models\MateriaCarrera;
use App\Models\PeriodoEscolar;
use App\Models\EstatusAlumno;
use App\Models\Especialidad;
use App\Models\PlanDeEstudio;
use App\Models\TipoEvaluacion;
use App\Models\HistoriaAlumno;
use App\Models\SeleccionMateria;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\MenuEscolaresController;
use Illuminate\Contracts\Events\Dispatcher;
use App\Http\Controllers\Acciones\AccionesController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;

class EscolaresController extends Controller
{
    public function __construct(Dispatcher $events)
    {
        new MenuEscolaresController($events);
    }
    public function index(){
        return view('escolares.index');
    }
    public function buscar(){
        $encabezado="Búsqueda de Estudiante";
        return view('escolares.busqueda')->with(compact('encabezado'));
    }
    public function busqueda(Request $request)
    {
        request()->validate([
            'control' => 'required',
        ], [
            'control.required' => 'Debe indicar un dato para ser buscado'
        ]);
        $encabezado="Búsqueda de Estudiante";
        $control = $request->control;
        $tbusqueda = $request->tbusqueda;
        $periodo = (new AccionesController)->periodo();
        $periodos = PeriodoEscolar::orderBy('periodo', 'desc')->get();
        if ($tbusqueda == "1") {
            $alumno = Alumno::findOrfail($control);
            $datos = AlumnosGeneral::where('no_de_control',$control)->first();
            if(empty($datos)){
                $info=collect(['domicilio_calle','domicilio_colonia','codigo_postal','telefono']);
                $datos=$info->combine(['','','','']);
                $bandera=0;
            }else{
                $bandera=1;
            }
            $ncarrera = (new AccionesController)->ncarrera($alumno->carrera,$alumno->reticula);
            $ingreso = PeriodoEscolar::where('periodo', $alumno->periodo_ingreso_it)
                ->select('identificacion_corta')->first();
            $estatus = EstatusAlumno::where('estatus', $alumno->estatus_alumno)->first();
            $espe = Especialidad::where('especialidad', $alumno->especialidad)
                ->where('carrera', $alumno->carrera)->where('reticula', $alumno->reticula)->first();
            if (empty($espe)) {
                $especialidad = "POR ASIGNAR";
            } else {
                $especialidad = $espe->nombre_especialidad;
            }
            return view('escolares.datos')->
            with(compact('alumno', 'ncarrera', 'datos', 'control', 'periodo',
                'periodos', 'estatus', 'especialidad', 'ingreso','bandera','encabezado'));
        } elseif ($tbusqueda == '2') {
            $arroja = Alumno::where('apellido_paterno', strtoupper($control))
                ->orWhere('apellido_materno', strtoupper($control))
                ->orWhere('nombre_alumno', strtoupper($control))
                ->orderBY('apellido_paterno')
                ->orderBy('apellido_materno')
                ->orderBy('nombre_alumno')
                ->get();
            return view('escolares.datos2')->with(compact('arroja', 'periodo', 'periodos','encabezado'));
        }
    }
    public function accion(Request $request)
    {
        $control = $request->control;
        $periodo = $request->periodo;
        $accion = $request->accion;
        $alumno = Alumno::findOrfail($control);
        $ncarrera = (new AccionesController)->ncarrera($alumno->carrera,$alumno->reticula);
        $estatus = EstatusAlumno::where('estatus', $alumno->estatus_alumno)->first();
        if ($accion == 1) {
            $encabezado="Kardex del estudiante";
            $encabezado2="Datos adicionales";
            $informacion = (new AccionesController)->kardex($control);
            $calificaciones=$informacion[0];
            $nombre_periodo=$informacion[1];
            return view('escolares.kardex')
                ->with(compact('alumno', 'calificaciones', 'estatus',
                    'ncarrera','control','encabezado','nombre_periodo','encabezado2'));
        } elseif ($accion == 2) {
            $historial = (new AccionesController)->reticula($control);
            return view('escolares.reticula')->with(compact('alumno', 'historial'));
        } elseif ($accion == 3) {
            if (SeleccionMateria::where('periodo', $periodo)
                    ->where('no_de_control', $control)->count() > 0) {
                $encabezado="Constancia para estudiante";
                return view('escolares.preconstancia')->with(compact('alumno', 'periodo','encabezado'));
            } else {
                $encabezado="Error de período para constancia";
                $mensaje = "No se puede generar la constancia porque el estudiante no cuenta con carga académica";
                return view('escolares.no')->with(compact('mensaje','encabezado'));
            }
        } elseif ($accion == 4) {
            if (HistoriaAlumno::where('periodo', $periodo)
                    ->where('no_de_control', $control)
                    ->count() > 0) {
                $encabezado="Boleta de estudiante";
                $cal_periodo = (new AccionesController)->boleta($control, $periodo);
                $nombre_periodo = PeriodoEscolar::where('periodo', $periodo)->first();
                return view('escolares.boleta')
                    ->with(compact('alumno', 'cal_periodo', 'nombre_periodo', 'periodo','encabezado'));
            } else {
                $encabezado="Error de período para boleta";
                $mensaje = "El estudiante no cuenta con calificaciones registradas para el período señalado";
                return view('escolares.no')->with(compact('mensaje','encabezado'));
            }
        } elseif ($accion == 5) {
            if (SeleccionMateria::where('no_de_control', $control)
                    ->where('periodo', $periodo)
                    ->count() > 0) {
                $encabezado="Horario del alumno";
                $datos_horario =(new AccionesController)->horario($control,$periodo);
                $nombre_periodo = PeriodoEscolar::where('periodo', $periodo)->first();
                return view('escolares.horario')->with(compact('alumno',
                    'datos_horario', 'nombre_periodo', 'periodo','control','encabezado'));
            } else {
                $encabezado="Error de período para horario";
                $mensaje = "NO CUENTA CON CARGA ACADÉMICA ASIGNADA";
                return view('escolares.no')->with(compact('mensaje'));
            }
        } elseif ($accion == 6) {
            $encabezado="Cambio de estatus";
            $estatus_alumno = EstatusAlumno::all();
            $nombre_periodo = PeriodoEscolar::where('periodo', $periodo)->first();
            return view('escolares.modificar_estatus')->with(compact('alumno',
                'periodo', 'estatus_alumno', 'nombre_periodo', 'control','encabezado'));
        } elseif ($accion == 7) {
            if (AvisoReinscripcion::where('periodo', $periodo)->where('no_de_control', $control)->count() > 0) {
                AvisoReinscripcion::where('periodo', $periodo)
                    ->where('no_de_control', $control)->update([
                        'autoriza_escolar' => 'S',
                        'recibo_pago' => '1',
                        'fecha_hora_seleccion' => Carbon::now(),
                        'encuesto' => 'S',
                        'updated_at' => Carbon::now()
                    ]);
            } else {
                $creditos = Carrera::where('carrera', $alumno->carrera)
                    ->where('reticula', $alumno->reticula)->select('carga_minima')->first();
                $semestre = (new AccionesController)->semreal($alumno->periodo_ingreso_it, $periodo);
                $inscripcion = new AvisoReinscripcion();
                $inscripcion->periodo=$periodo;
                $inscripcion->no_de_control=$control;
                $inscripcion->autoriza_escolar='S';
                $inscripcion->recibo_pago=1;
                $inscripcion->fecha_recibo=null;
                $inscripcion->cuenta_pago=null;
                $inscripcion->fecha_hora_seleccion= Carbon::now();
                $inscripcion->lugar_seleccion=null;
                $inscripcion->fecha_hora_pago=null;
                $inscripcion->lugar_pago=null;
                $inscripcion->adeuda_escolar=null;
                $inscripcion->adeuda_biblioteca=null;
                $inscripcion->adeuda_financieros=null;
                $inscripcion->otro_mensaje=null;
                $inscripcion->baja=null;
                $inscripcion->motivo_aviso_baja=null;
                $inscripcion->egresa=null;
                $inscripcion->encuesto='S';
                $inscripcion->vobo_adelanta_sel=null;
                $inscripcion->regular=null;
                $inscripcion->indice_reprobacion=0;
                $inscripcion->creditos_autorizados=$creditos->carga_minima;
                $inscripcion->estatus_reinscripcion=null;
                $inscripcion->semestre=$semestre;
                $inscripcion->promedio=0;
                $inscripcion->adeudo_especial='N';
                $inscripcion->promedio_acumulado=null;
                $inscripcion->proareas=null;
                $inscripcion->save();
                Alumno::where('no_de_control', $control)->update([
                    'semestre' => $semestre
                ]);
            }
            $encabezado="Autorización de reinscripción";
            $mensaje="El estudiante puede inscribirse a partir de éste momento";
            return view('escolares.si')->with(compact('mensaje','encabezado'));
        } elseif ($accion == 8) {
            $encabezado="Especialidades";
            $especialidades = Especialidad::where('carrera', $alumno->carrera)
                ->where('reticula', $alumno->reticula)->get();
            return view('escolares.modificar_especialidad')->with(compact('alumno',
                'especialidades','encabezado'));
        } elseif ($accion == 9) {
            $encabezado="Cambio de carrera";
            $carreras = Carrera::where('ofertar', '1')
                ->orderBy('nombre_carrera', 'ASC')->get();
            return view('escolares.modificar_carrera')->with(compact('alumno',
                'carreras', 'encabezado'));
        } elseif ($accion == 10) {
            $encabezado="Anulación de número de control";
            return view('escolares.confirmar_borrado')->with(compact('alumno', 'encabezado'));
        } elseif ($accion == 11) {
            $encabezado="Asignación de baja temporal o definitiva";
            return view('escolares.confirmar_bajatemp')->with(compact('alumno', 'periodo','encabezado'));
        } elseif ($accion == 12) {
            $encabezado="Asignación de NSS";
            return view('escolares.alta_nss')->with(compact('alumno','encabezado'));
        } elseif ($accion == 13) {
            $mat = MateriaCarrera::where('carrera', $alumno->carrera)
                ->where('reticula', $alumno->reticula)
                ->join('materias', 'materias.materia', '=', 'materias_carreras.materia')
                ->where('nombre_completo_materia', 'LIKE', "%COMPLEMENTARIAS%")
                ->first();
            if (HistoriaAlumno::where('no_de_control', $control)
                    ->where('materia', $mat->materia)->count() > 0) {
                $encabezado="Error en liberación de act. complementaria";
                $mensaje = "La materia ya está acreditada por lo que no es posible volverla a activar";
                return view('escolares.no')->with(compact('mensaje','encabezado'));
            } else {
                $comple=new HistoriaAlumno();
                $comple->periodo=$periodo;
                $comple->no_de_control=$control;
                $comple->materia=$mat->materia;
                $comple->grupo=null;
                $comple->calificacion=60;
                $comple->tipo_evaluacion='AC';
                $comple->fecha_calificacion=Carbon::now();
                $comple->plan_de_estudios=$alumno->plan_de_estudios;
                $comple->estatus_materia='A';
                $comple->nopresento='N';
                $comple->usuario=Auth::user()->email;
                $comple->fecha_actualizacion=Carbon::now();
                $comple->periodo_acredita_materia=$periodo;
                $comple->save();
                $encabezado="Liberación de actividad complementaria";
                $mensaje="Se llevó a cabo la asignación correspondiente";
                return view('escolares.si')->with(compact('encabezado','mensaje'));
            }
        } elseif ($accion == 14) {
            if (DB::table('idiomas_liberacion')->where('control', $control)->count() > 0) {
                return view('escolares.prelibidiomas')->with(compact('control', 'alumno'));
            } else {
                $mensaje = "No existe registro que el estudiante haya liberado idioma extranjero";
                return view('escolares.no')->with(compact('mensaje'));
            }
        } elseif ($accion == 15) {
            $periodos = DB::table('periodos_escolares')->orderBy('periodo', 'desc')->get();
            return view('escolares.datos_certificado')->with(compact('alumno', 'control', 'periodo', 'periodos'));
        } elseif ($accion == 16){
            $planes = DB::table('planes_de_estudio')->get();
            $alumno_plan=$alumno->plan_de_estudios;
            $periodos = DB::table('periodos_escolares')->orderBy('periodo', 'desc')->get();
            $periodo_ingreso=$alumno->periodo_ingreso_it;
            $tipos_ingreso=DB::table('tipos_ingreso')->get();
            $tipo_ingreso=$alumno->tipo_ingreso;
            $generales = AlumnosGenerales::where('no_de_control',$control)->first();
            if(empty($generales)){
                $generales='';
                $bandera=0;
            }else{
                $bandera=1;
            }
            return view('escolares.modificar_alumno')->with(compact('control','alumno','planes','periodos','periodo_ingreso','alumno_plan','tipo_ingreso','tipos_ingreso','generales','bandera'));
        }
    }
    public function accionk(Request $request)
    {
        $control = $request->control;
        $accion = $request->accion;
        $alumno = Alumno::findOrfail($control);
        if ($accion == 1) {
            $encabezado="Alta de materia en kardex";
            $carga_acad =(new AccionesController)->cmaterias($control);
            $periodos = PeriodoEscolar::orderBy('periodo', 'desc')->get();
            $tipo_ev = TipoEvaluacion::where('plan_de_estudios', $alumno->plan_de_estudios)->get();
            return view('escolares.akardex')
                ->with(compact('alumno', 'periodos', 'carga_acad',
                    'tipo_ev', 'control','encabezado'));
        } elseif ($accion == 2) {
            $periodos = HistoriaAlumno::where('no_de_control', $control)
                ->join('periodos_escolares', 'historia_alumno.periodo', '=', 'periodos_escolares.periodo')
                ->distinct('historia_alumno.periodo')
                ->select('historia_alumno.periodo', 'identificacion_corta')
                ->get();
            $encabezado="Modificar materia en kardex";
            return view('escolares.m1kardex')->with(compact('alumno', 'periodos',
                'control','encabezado'));
        } elseif ($accion == 3) {
            $informacion = (new AccionesController)->kardex($control);
            $calificaciones=$informacion[0];
            $nombre_periodo=$informacion[1];
            $ncarrera = (new AccionesController)->ncarrera($alumno->carrera,$alumno->reticula);
            $data = [
                'alumno' => $alumno,
                'control' => $control,
                'carrera' => $ncarrera,
                'nperiodos' => $nombre_periodo,
                'calificaciones' => $calificaciones
            ];
            $pdf = PDF::loadView('escolares.pdf_kardex', $data)->setPaper('Letter');
            return $pdf->download('kardex.pdf');
        }
    }
    public function accionkalta(Request $request)
    {
        request()->validate([
            'calif' => 'required',
        ], [
            'calif.required' => 'Debe indicar una calificacion'
        ]);
        $control = $request->control;
        $materia = $request->alta;
        $calif = $request->calif;
        $periodo = $request->nper;
        $tipo_ev = $request->tipo_e;
        $alumno = Alumno::findOrfail($control);
        if (HistoriaAlumno::where([
                'no_de_control'=>$control,
                'materia'=>$materia,
                'periodo'=>$periodo
            ])->count() > 0) {
            $mensaje = "Ya está registrado el dato en el kardex del estudiante";
            $encabezado="Error de alta en materia";
            return view('escolares.no')->with(compact('mensaje','encabezado'));
        } else {
            $ha = new HistoriaAlumno();
            $ha->periodo = $periodo;
            $ha->no_de_control = $control;
            $ha->materia = $materia;
            $ha->calificacion = $calif;
            $ha->tipo_evaluacion = $tipo_ev;
            $ha->fecha_calificacion = Carbon::now();
            $ha->plan_de_estudios = $alumno->plan_de_estudios;
            if ($calif >= 70 || ($tipo_ev == 'AC' || $tipo_ev == 'CE' || $tipo_ev == 'RU')) {
                $ha->estatus_materia = 'A';
            } else {
                $ha->estatus_materia = 'R';
            }
            $ha->usuario = Auth::user()->email;
            $ha->save();
            $encabezado="Alta de materia";
            $mensaje="Se llevó a cabo la alta solicitada; por favor, verifique el kardex del estudiante";
            return view('escolares.si')->with(compact('encabezado','mensaje'));
        }
    }
    public function accionkperiodo(Request $request)
    {
        $control = $request->control;
        $alumno = Alumno::findOrfail($control);
        $periodo = $request->get('pbusqueda');
        $nperiodo = PeriodoEscolar::where('periodo', $periodo)->first();
        $mat = HistoriaAlumno::where('periodo', $periodo)
            ->where('no_de_control', $control)
            ->join('materias_carreras as mc', 'mc.materia', '=', 'historia_alumno.materia')
            ->where('mc.carrera', $alumno->carrera)
            ->where('mc.reticula', $alumno->reticula)
            ->join('materias', 'materias.materia', '=', 'mc.materia')
            ->join('tipos_evaluacion as te', 'te.tipo_evaluacion', '=', 'historia_alumno.tipo_evaluacion')
            ->where('te.plan_de_estudios', $alumno->plan_de_estudios)
            ->select('periodo', 'historia_alumno.materia', 'calificacion', 'nombre_abreviado_materia', 'historia_alumno.tipo_evaluacion', 'descripcion_corta_evaluacion')
            ->get();
        $encabezado="Modificación de materia en kardex";
        return view('escolares.m2kardex')->with(compact('alumno', 'nperiodo',
            'mat', 'periodo', 'control','encabezado'));
    }
    public function modificarkardex($periodo, $control, $materia)
    {
        $alumno = Alumno::findOrfail($control);
        $mat = HistoriaAlumno::where('periodo', $periodo)
            ->where('no_de_control', $control)
            ->where('historia_alumno.materia', $materia)
            ->join('materias', 'materias.materia', '=', 'historia_alumno.materia')
            ->join('tipos_evaluacion as te', 'te.tipo_evaluacion', '=', 'historia_alumno.tipo_evaluacion')
            ->where('te.plan_de_estudios', $alumno->plan_de_estudios)
            ->select('calificacion', 'nombre_abreviado_materia', 'historia_alumno.tipo_evaluacion')
            ->first();
        $periodos = PeriodoEscolar::orderBy('periodo', 'desc')->get();
        $tipos = TipoEvaluacion::where('plan_de_estudios', $alumno->plan_de_estudios)->get();
        $encabezado="Modificar materia en Kardex";
        return view('escolares.modificar_kardex')->with(compact('alumno', 'periodo',
            'mat', 'materia', 'periodos', 'tipos', 'control','encabezado'));
    }

    public function eliminarkardex($periodo, $control, $materia)
    {
        HistoriaAlumno::where('no_de_control', $control)->where('periodo', $periodo)
            ->where('materia', $materia)->delete();
        $alumno = Alumno::findOrfail($control);
        $datos = AlumnosGeneral::where('no_de_control',$control)->first();
        if(empty($datos)){
            $info=collect(['domicilio_calle','domicilio_colonia','codigo_postal','telefono']);
            $datos=$info->combine(['','','','']);
            $bandera=0;
        }else{
            $bandera=1;
        }
        $ncarrera = (new AccionesController)->ncarrera($alumno->carrera,$alumno->reticula);
        $periodo = (new AccionesController)->periodo();
        $periodos = PeriodoEscolar::orderBy('periodo', 'desc')->get();
        $estatus = EstatusAlumno::where('estatus', $alumno->estatus_alumno)->first();
        $espe = Especialidad::where('especialidad', $alumno->especialidad)
            ->where('carrera', $alumno->carrera)->where('reticula', $alumno->reticula)->first();
        if (empty($espe)) {
            $especialidad = "POR ASIGNAR";
        } else {
            $especialidad = $espe->nombre_especialidad;
        }
        $ingreso = DB::table('periodos_escolares')->where('periodo', $alumno->periodo_ingreso_it)
            ->select('identificacion_corta')->first();
        $encabezado="Materia eliminada de Kardex";
        return view('escolares.datos')->
        with(compact('alumno', 'ncarrera', 'datos', 'control', 'periodo',
            'periodos', 'estatus', 'especialidad', 'ingreso','bandera','encabezado'));
    }
    public function kardexupdate(Request $request)
    {
        $materia = $request->get('materia');
        $control = $request->get('control');
        $tipo_ev = $request->get('tipo_ev');
        $periodo_n = $request->get('periodo');
        $calif = $request->get('calificacion');
        $periodo_o = $request->get('periodo_o');
        HistoriaAlumno::where('no_de_control', $control)
            ->where('materia', $materia)->where('periodo', $periodo_o)->update([
                'calificacion' => $calif,
                'periodo' => $periodo_n,
                'tipo_evaluacion' => $tipo_ev,
                'updated_at' => Carbon::now()
            ]);
        $alumno = Alumno::findOrfail($control);
        $datos = AlumnosGeneral::where('no_de_control',$control)->first();
        if(empty($datos)){
            $info=collect(['domicilio_calle','domicilio_colonia','codigo_postal','telefono']);
            $datos=$info->combine(['','','','']);
            $bandera=0;
        }else{
            $bandera=1;
        }
        $ncarrera = (new AccionesController)->ncarrera($alumno->carrera,$alumno->reticula);
        $periodo = (new AccionesController)->periodo();
        $periodos = PeriodoEscolar::orderBy('periodo', 'desc')->get();
        $estatus = EstatusAlumno::where('estatus', $alumno->estatus_alumno)->first();
        $espe = Especialidad::where('especialidad', $alumno->especialidad)
            ->where('carrera', $alumno->carrera)->where('reticula', $alumno->reticula)->first();
        if (empty($espe)) {
            $especialidad = "POR ASIGNAR";
        } else {
            $especialidad = $espe->nombre_especialidad;
        }
        $ingreso = DB::table('periodos_escolares')->where('periodo', $alumno->periodo_ingreso_it)
            ->select('identificacion_corta')->first();
        $encabezado="Materia actualizada en kardex";
        return view('escolares.datos')->
        with(compact('alumno', 'ncarrera', 'datos', 'control', 'periodo',
            'periodos', 'estatus', 'especialidad', 'ingreso','bandera','encabezado'));
    }
    public function imprimirboleta(Request $request)
    {
        $control = $request->control;
        $periodo = $request->periodo;
        $alumno = Alumno::findOrfail($control);
        $cal_periodo = (new AccionesController)->boleta($control, $periodo);
        $nombre_periodo = PeriodoEscolar::where('periodo', $periodo)->first();
        $data = [
            'alumno' => $alumno,
            'cal_periodo' => $cal_periodo,
            'nombre_periodo' => $nombre_periodo,
            'periodo' => $periodo
        ];
        $pdf = PDF::loadView('escolares.pdf_boleta', $data)
            ->setPaper('Letter');
        return $pdf->download('boleta.pdf');
    }
    public function estatusupdate(Request $request)
    {
        $control = $request->get('control');
        $estatus = $request->get('estatus');
        Alumno::where('no_de_control', $control)->update([
            'estatus_alumno' => $estatus
        ]);
        $encabezado="Estatus de alumno modificado";
        $mensaje="Se cambió el estatus del alumno";
        return view('escolares.si')->with(compact('encabezado','mensaje'));
    }
    public function especialidadupdate(Request $request)
    {
        $control = $request->get('control');
        $especialidad = $request->get('espe');
        Alumno::where('no_de_control', $control)->update([
            'especialidad' => $especialidad
        ]);
        $encabezado="Asignación de Especialidad";
        $mensaje="Se llevó a cabo la asignación de una especialidad al estudiante";
        return view('escolares.si')->with(compact('encabezado','mensaje'));
    }
    public function carreraupdate(Request $request)
    {
        $control = $request->get('control');
        $alumno = Alumno::findOrfail($control);
        $carrera_n0 = $request->get('carrera_n');
        $data = explode("_", $carrera_n0);
        $carrera_n = $data[0];
        $ret_n = $data[1];
        $materias = HistoriaAlumno::where('no_de_control', $control)
            ->select('periodo', 'materia', 'tipo_evaluacion')->get();
        $i = 0;
        $plan = PlanDeEstudio::max('plan_de_estudio');
        foreach ($materias as $historia) {
            $cve_of = MateriaCarrera::where('carrera', $alumno->carrera)
                ->where('reticula', $alumno->reticula)->where('materia', $historia->materia)->first();
            if (!empty($cve_of)) {
                if (MateriaCarrera::where([
                        'carrera'=>$carrera_n,
                        'reticula'=>$ret_n,
                        'clave_oficial_materia'=>trim($cve_of->clave_oficial_materia)
                    ])->count() > 0) {
                    $nmat = MateriaCarrera::where([
                        'carrera'=>$carrera_n,
                        'reticula'=>$ret_n,
                        'clave_oficial_materia'=>trim($cve_of->clave_oficial_materia)
                    ])->select('materia')->first();
                    HistoriaAlumno::where('no_de_control', $control)->where('periodo', $historia->periodo)
                        ->where('materia', $historia->materia)->update([
                            'materia' => $nmat->materia,
                            'tipo_evaluacion' => 'RC',
                            'plan_de_estudios' => $plan
                        ]);
                    $i++;
                }else{
                   HistoriaAlumno::where([
                       'no_de_control'=>$control,
                       'periodo'=>$historia->periodo,
                       'materia'=>$historia->materia
                   ])->delete();
                }
            }
        }
        Alumno::where('no_de_control', $control)->update([
            'carrera' => $carrera_n,
            'reticula' => $ret_n,
            'plan_de_estudios' => $plan
        ]);
        return view('escolares.ccarrera_resultado')->with(compact('i'));
    }
    public function alumnodelete(Request $request)
    {
        $control = $request->get('control');
        //Primero, checar si tiene materias activas
        SeleccionMateria::where('no_de_control', $control)->delete();
        //Ahora, se borra su historial
        HistoriaAlumno::where('no_de_control', $control)->delete();
        //Borrar datos generales
        AlumnosGeneral::where('no_de_control', $control)->delete();
        //Eliminar alumno
        Alumno::where('no_de_control', $control)->delete();
        $encabezado="Baja de número de control";
        $mensaje="Se eliminó todo el historial académico del estudiante, así como sus datos generales";
        return view('escolares.si')->with(compact('encabezado','mensaje'));
    }
    public function alumnobajatemp(Request $request)
    {
        $control = $request->get('control');
        $periodo = $request->get('periodo');
        $tbaja = $request->get('tbaja');
        //Primero, checar si tiene materias activas
        if(SeleccionMateria::where([
            'no_de_control'=>$control,
            'periodo'=>$periodo
        ])->count()>0){
            SeleccionMateria::where('no_de_control', $control)
                ->where('periodo', $periodo)->delete();
        }
        Alumno::where('no_de_control', $control)->update([
            'estatus_alumno' => $tbaja
        ]);
        $encabezado="Baja de número de control";
        $mensaje="Se actualizó el estatus del estudiante";
        return view('escolares.si')->with(compact('encabezado','mensaje'));
    }
    public function alumnonss(Request $request)
    {
        request()->validate([
            'nss' => 'required',
        ], [
            'nss.required' => 'Debe indicar el NSS ha ser registrado'
        ]);
        $control = $request->get('control');
        $nss = trim($request->get('nss'));
        Alumno::where('no_de_control', $control)->update([
            'nss' => $nss
        ]);
        $encabezado="Asignación de NSS";
        $mensaje="Se actualizó la información del estudiante";
        return view('escolares.si')->with(compact('encabezado','mensaje'));
    }
}
