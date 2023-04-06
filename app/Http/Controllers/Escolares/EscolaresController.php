<?php

namespace App\Http\Controllers\Escolares;

use App\Models\AcumuladoHistorico;
use App\Models\Alumno;
use App\Models\AlumnosGeneral;
use App\Models\AvisoReinscripcion;
use App\Models\Carrera;
use App\Models\EntidadesFederativa;
use App\Models\FechasCarrera;
use App\Models\GenerarListasTemporal;
use App\Models\Grupo;
use App\Models\Idioma;
use App\Models\IdiomasGrupo;
use App\Models\IdiomasLiberacion;
use App\Models\Materia;
use App\Models\MateriaCarrera;
use App\Models\Organigrama;
use App\Models\PeriodoEscolar;
use App\Models\EstatusAlumno;
use App\Models\Especialidad;
use App\Models\Personal;
use App\Models\PlanDeEstudio;
use App\Models\TipoEvaluacion;
use App\Models\HistoriaAlumno;
use App\Models\SeleccionMateria;
use App\Http\Controllers\Controller;
use App\Models\TiposIngreso;
use App\Models\User;
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
            $encabezado2="Acciones adicionales sobre las materias";
            $encabezado3="Imprimir kardex";
            $informacion = (new AccionesController)->kardex($control);
            $calificaciones=$informacion[0];
            $nombre_periodo=$informacion[1];
            return view('escolares.kardex')
                ->with(compact('alumno', 'calificaciones', 'estatus',
                    'ncarrera','control','encabezado','nombre_periodo','encabezado2','encabezado3'));
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
                return view('escolares.no')->with(compact('mensaje','encabezado'));
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
            $carreras = Carrera::where('ofertar', '1')->orderBy('nombre_carrera', 'ASC')->get();
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
            if (IdiomasLiberacion::where('control', $control)->count() > 0) {
                $encabezado="Idioma extranjero";
                return view('escolares.prelibidiomas')->with(compact( 'alumno','encabezado'));
            } else {
                $encabezado="Sin liberación idioma extranjero";
                $mensaje = "No existe registro que el estudiante haya liberado idioma extranjero";
                return view('escolares.no')->with(compact('mensaje','encabezado'));
            }
        } elseif ($accion == 15) {
            $encabezado="Emisión de Certificado";
            $periodos = PeriodoEscolar::orderBy('periodo', 'desc')->get();
            return view('escolares.datos_certificado')->with(compact('alumno',
                'encabezado', 'periodo', 'periodos','encabezado'));
        } elseif ($accion == 16){
            $planes = PlanDeEstudio::all();
            $periodos = PeriodoEscolar::orderBy('periodo', 'desc')->get();
            $tipos_ingreso=TiposIngreso::all();
            $generales = AlumnosGeneral::where('no_de_control',$control)->first();
            if(empty($generales)){
                $generales='';
                $bandera=0;
            }else{
                $bandera=1;
            }
            $encabezado="Datos Generales del Estudiante";
            return view('escolares.modificar_alumno')->with(compact('alumno',
                'planes','periodos','tipos_ingreso','generales','bandera','encabezado'));
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
        $ingreso = PeriodoEscolar::where('periodo', $alumno->periodo_ingreso_it)
            ->select('identificacion_corta')->first();
        $encabezado="Materia eliminada de Kardex";
        return view('escolares.datos')->
        with(compact('alumno', 'ncarrera', 'datos', 'periodo',
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
        $ingreso = PeriodoEscolar::where('periodo', $alumno->periodo_ingreso_it)
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
        $estatus = $request->get('situacion');
        Alumno::find($control)->update([
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
    public function certificado(Request $request)
    {
        request()->validate([
            'femision' => 'required',
            'iniciales' => 'required',
            'director' => 'required',
            'registro' => 'required',
            'libro' => 'required',
            'foja' => 'required',
            'fregistro' => 'required'
        ], [
            'femision.required' => 'Debe indicar la fecha de cuando se emite el certificado',
            'iniciales.required' => 'Debe indicar las iniciales del Jefe de Servicios Escolares',
            'director.required' => 'Debe indicar el nombre completo del(la) Director(a)',
            'registro.required' => 'Debe indicar el número de registro para el certificado',
            'libro.required' => 'Debe indicar el libro del registro del certificado',
            'foja.required' => 'Debe indicar la foja del registro del certificado',
            'fregistro.required' => 'Debe especificar la fecha del registro del certificado'
        ]);
        $info = $request->all();
        $encabezado="Imprimir certificado";
        return view('escolares.imprimir_certificado')->with(compact('info','encabezado'));
    }
    public function modificar_datos(Request $request){
        request()->validate([
            'control' => 'required',
            'apmat' => 'required',
            'nombre' => 'required',
            'plan'=>'required',
            'ingreso'=>'required',
            'semestre' => 'required',
            'curp' => 'required',
            'tipo' => 'required'
        ], [
            'control.required' => 'Debe indicar el numero de control',
            'apmat.required' => 'Debe escribir el apellido materno',
            'nombre.required' => 'Debe escribir el nombre',
            'plan.required'=> 'Especifique el plan de estudios',
            'ingreso.required'=>'Especifique el período de ingreso',
            'semestre.required' => 'Debe indicar el semestre que se encuentra actualmente',
            'curp.required' => 'Debe escribir el CURP',
            'tipo.required' => 'Debe especificar el tipo de ingreso del estudiante'
        ]);
        $control=$request->get('control');
        $appat = $request->get('appat');
        $apmat = $request->get('apmat');
        $nombre = $request->get('nombre');
        $plan = $request->get('plan');
        $ingreso = $request->get('ingreso');
        $semestre = $request->get('semestre');
        $nss = $request->get('nss');
        $curp = $request->get('curp');
        $calle = $request->get('calle');
        $colonia = $request->get('colonia');
        $cp = $request->get('cp');
        $telcel = $request->get('telcel');
        $correo = $request->get('correo');
        $rev = $request->get('periodos_revalidacion');
        $tipo = $request->get('tipo');
        $quien = Auth::user()->email;
        Alumno::where('no_de_control',$control)
            ->update([
                'apellido_paterno' => $appat,
                'apellido_materno' => $apmat,
                'nombre_alumno' => $nombre,
                'semestre' => $semestre,
                'plan_de_estudios' => $plan,
                'curp_alumno' => $curp,
                'tipo_ingreso' => $tipo,
                'periodo_ingreso_it' => $ingreso,
                'correo_electronico' => $correo,
                'periodos_revalidacion' => $rev,
                'usuario' => $quien,
                'fecha_actualizacion' => null,
                'nss' => $nss
            ]);
        AlumnosGeneral::where('no_de_control',$control)
            ->update([
                'domicilio_calle' => $calle,
                'domicilio_colonia' => $colonia,
                'codigo_postal' => $cp,
                'telefono' => $telcel
            ]);
        $encabezado="Datos del estudiante modificados";
        $mensaje="Se actualizó la información de la base de datos del estudiante con número de control ".$control;
        return view('escolares.si')->with(compact('encabezado','mensaje'));
    }
    public function nuevo()
    {
        $estados = EntidadesFederativa::all();
        $periodo_actual = (new AccionesController)->periodo();
        $periodo=$periodo_actual[0]->periodo;
        if (Alumno::where('periodo_ingreso_it', $periodo)->where('nivel_escolar', 'L')
                ->where('tipo_ingreso', '1')->count() > 0) {
            $ultimo = Alumno::where('periodo_ingreso_it', $periodo)
                ->where('nivel_escolar', 'L')
                ->where('tipo_ingreso', '1')->max('no_de_control');
            $nperiodo = PeriodoEscolar::where('periodo', $periodo)->first();
            $mensaje = "El último número de control asignado en " . $nperiodo->identificacion_corta . " fue " . $ultimo;
        } else {
            $last = substr($periodo, -1);
            $anio = substr($periodo, 0, 4);
            if ($last == 1) {
                $anio_ant = $anio - 1;
                $per_ult = $anio_ant . "3";
            } else {
                $per_ult = $anio . "1";
            }
            $ultimo = Alumno::where('periodo_ingreso_it', $per_ult)
                ->where('nivel_escolar', 'L')
                ->where('tipo_ingreso', '1')->max('no_de_control');
            $nperiodo = PeriodoEscolar::where('periodo', $per_ult)->first();
            $mensaje = "El último número de control asignado en " . $nperiodo->identificacion_corta . " fue " . $ultimo;
        }
        $periodos = PeriodoEscolar::orderBy('periodo', 'desc')->get();
        $carreras = Carrera::orderBy('nombre_reducido')->get();
        $planes = PlanDeEstudio::all();
        $tipos_ingreso = TiposIngreso::all();
        $encabezado="Alta de estudiante al sistema";
        return view('escolares.nuevo_alumno')->with(compact('estados', 'periodo',
            'mensaje', 'periodos', 'carreras', 'planes','tipos_ingreso','encabezado'));
    }
    public function altanuevo(Request $request)
    {
        request()->validate([
            'control' => 'required',
            'apmat' => 'required',
            'nombre' => 'required',
            'semestre' => 'required',
            'curp' => 'required',
            'fnac' => 'required'
        ], [
            'control.required' => 'Debe indicar el numero de control',
            'apmat.required' => 'Debe escribir el apellido materno',
            'nombre.required' => 'Debe escribir el nombre',
            'semestre.required' => 'Debe indicar el semestre que se encuentra actualmente',
            'curp.required' => 'Debe escribir el CURP',
            'fnac.required' => 'Debe escribir la fecha de nacimiento'
        ]);
        $control = $request->get('control');
        if (Alumno::where('no_de_control', $control)->count() > 0) {
            $encabezado="Error de alta de alumno";
            $mensaje = "El numero de control ya existe en la base de datos";
            return view('escolares.no')->with(compact('mensaje','encabezado'));
        } else {
            $appat = $request->get('appat');
            $apmat = $request->get('apmat');
            $nombre = $request->get('nombre');
            $carr = $request->get('carrera');
            $datos = explode("_", $carr);
            $carrera = $datos[0];
            $reticula = $datos[1];
            $nivel = $datos[2];
            $semestre = $request->get('semestre');
            $plan = $request->get('plan');
            $ingreso = $request->get('ingreso');
            $nss = $request->get('nss');
            $curp = $request->get('curp');
            $nip = rand(1000, 9999);
            $lnac = $request->get('lnac');
            $ciudad = $request->get('ciudad');
            $fnac = $request->get('fnac');
            $sexo = $request->get('sexo');
            $ecivil = $request->get('ecivil');
            $calle = $request->get('calle');
            $colonia = $request->get('colonia');
            $cp = $request->get('cp');
            $telcel = $request->get('telcel');
            $correo = $request->get('correo');
            $proc = $request->get('proc');
            $rev = $request->get('rev');
            $tipo = $request->get('tipo');
            $quien = Auth::user()->email;
            $alumno=new Alumno();
            $alumno->no_de_control=$control;
            $alumno->carrera=$carrera;
            $alumno->reticula=$reticula;
            $alumno->especialidad=null;
            $alumno->nivel_escolar=$nivel;
            $alumno->semestre=$semestre;
            $alumno->estatus_alumno='ACT';
            $alumno->plan_de_estudios=$plan;
            $alumno->apellido_paterno=$appat;
            $alumno->apellido_materno=$apmat;
            $alumno->nombre_alumno=$nombre;
            $alumno->curp_alumno=$curp;
            $alumno->fecha_nacimiento=$fnac;
            $alumno->sexo=$sexo;
            $alumno->estado_civil=$ecivil;
            $alumno->tipo_ingreso=$tipo;
            $alumno->periodo_ingreso_it=$ingreso;
            $alumno->ultimo_periodo_inscrito=null;
            $alumno->promedio_periodo_anterior=null;
            $alumno->promedio_aritmetico_acumulado=null;
            $alumno->creditos_aprobados=null;
            $alumno->creditos_cursados=null;
            $alumno->promedio_final_alcanzado=null;
            $alumno->escuela_procedencia=$proc;
            $alumno->entidad_procedencia=$lnac;
            $alumno->ciudad_procedencia=$ciudad;
            $alumno->correo_electronico=$correo;
            $alumno->periodos_revalidacion=$rev;
            $alumno->becado_por=null;
            $alumno->nip=$nip;
            $alumno->usuario=$quien;
            $alumno->fecha_actualizacion=null;
            $alumno->fecha_titulacion=null;
            $alumno->opcion_titulacion=null;
            $alumno->periodo_titulacion=null;
            $alumno->registro_patronal=null;
            $alumno->digito_registro_patronal=null;
            $alumno->nss=$nss;
            $alumno->save();
            $generales=new AlumnosGeneral();
            $generales->no_de_control=$control;
            $generales->domicilio_calle=$calle;
            $generales->domicilio_colonia=$colonia;
            $generales->codigo_postal=$cp;
            $generales->telefono=$telcel;
            $generales->facebook=null;
            $generales->save();
            $ncarrera = (new AccionesController)->ncarrera($carrera,$reticula);
            $data = [
                'appat' => $appat,
                'apmat' => $apmat,
                'nombre' => $nombre,
                'control' => $control,
                'ncarrera' => $ncarrera,
                'nip' => $nip
            ];
            $pdf = PDF::loadView('escolares.pdf_nuevo', $data)
                ->setPaper('Letter');
            return $pdf->download('alta.pdf');
        }
    }
    public function periodos()
    {
        $yr = date('Y');
        $encabezado="Alta de período escolar";
        return view('escolares.periodos')->with(compact('yr','encabezado'));
    }
    public function periodoalta(Request $request)
    {
        request()->validate([
            'finicio' => 'required',
            'ftermino' => 'required',
            'finicio_vac' => 'required',
            'ftermino_vac' => 'required',
            'finicio_cap' => 'required',
            'ftermino_cap' => 'required',
            'finicio_est' => 'required',
            'ftermino_est' => 'required'
        ], [
            'finicio.required' => 'Debe indicar la fecha de inicio del semestre',
            'ftermino.required' => 'Debe escribir la fecha de término del semestre',
            'finicio_vac.required' => 'Debe indicar la fecha de inicio de vacaciones para el semestre',
            'ftermino_vac.required' => 'Debe escribir la fecha de término de vacaciones para el semestre',
            'finicio_cap.required' => 'Debe indicar la fecha de inicio de captura docente para el semestre',
            'ftermino_cap.required' => 'Debe escribir la fecha de término de captura docente para el semestre',
            'finicio_est.required' => 'Debe indicar la fecha de inicio de selección de materias del estudiante para el semestre',
            'ftermino_est.required' => 'Debe escribir la fecha de término de selección de materias del estudiante para el semestre'
        ]);
        $anio = $request->get('anio');
        $tper = $request->get('tper');
        $periodo = $anio . $tper;
        if (PeriodoEscolar::where('periodo', $periodo)->count() > 0) {
            $encabezado="Error de creación de período";
            $mensaje = "No se puede crear el período porque ya existe en la base de datos";
            return view('escolares.no')->with(compact('mensaje','encabezado'));
        } else {
            switch ($tper) {
                case 1:
                {
                    $id_largo = "ENERO-JUNIO/" . $anio;
                    $id_corto = "ENE-JUN/" . $anio;
                    break;
                }
                case 2:
                {
                    $id_largo = "VERANO/" . $anio;
                    $id_corto = "Verano/" . $anio;
                    break;
                }
                case 3:
                {
                    $id_largo = "AGOSTO-DICIEMBRE/" . $anio;
                    $id_corto = "AGO-DIC/" . $anio;
                    break;
                }
            }
            $finicio = $request->get('finicio');
            $ftermino = $request->get('ftermino');
            $finicio_ss1 = $request->get('finicio_ss');
            $ftermino_ss1 = $request->get('ftermino_ss');
            $finicio_ss = empty($finicio_ss1) ? null : $finicio_ss1;
            $ftermino_ss = empty($ftermino_ss1) ? null : $ftermino_ss1;
            $finicio_vac = $request->get('finicio_vac');
            $ftermino_vac = $request->get('ftermino_vac');
            $finicio_cap = $request->get('finicio_cap');
            $ftermino_cap = $request->get('ftermino_cap');
            $finicio_est = $request->get('finicio_est');
            $ftermino_est = $request->get('ftermino_est');
            $nperiodo=new PeriodoEscolar();
            $nperiodo->periodo=$periodo;
            $nperiodo->identificacion_larga=$id_largo;
            $nperiodo->identificacion_corta=$id_corto;
            $nperiodo->fecha_inicio=$finicio;
            $nperiodo->fecha_termino=$ftermino;
            $nperiodo->inicio_vacacional_ss=$finicio_ss;
            $nperiodo->fin_vacacional_ss=$ftermino_ss;
            $nperiodo->inicio_especial=null;
            $nperiodo->fin_especial=null;
            $nperiodo->cierre_horarios='S';
            $nperiodo->cierre_seleccion='S';
            $nperiodo->inicio_sele_alumnos=$finicio_est;
            $nperiodo->fin_sele_alumnos=$ftermino_est;
            $nperiodo->inicio_vacacional=$finicio_vac;
            $nperiodo->termino_vacacional=$ftermino_vac;
            $nperiodo->inicio_cal_docentes=$finicio_cap;
            $nperiodo->fin_cal_docentes=$ftermino_cap;
            $nperiodo->ccarrera=0;
            $nperiodo->save();
            $encabezado="Creación de período";
            $mensaje='El período '.$id_corto." fue creado en la base de datos";
            return view('escolares.si')->with(compact('encabezado','mensaje'));
        }
    }
    public function periodomodifica()
    {
        $encabezado="Modificación de período escolar";
        $periodo_actual = (new AccionesController)->periodo();
        $periodos = PeriodoEscolar::orderBy('periodo','desc')->get();
        return view('escolares.periodo_mod')->with(compact('periodo_actual', 'periodos','encabezado'));
    }
    public function periodomodificar(Request $request)
    {
        $encabezado="Modificación de período escolar";
        $periodo = $request->get('periodo');
        $periodos = PeriodoEscolar::where('periodo', $periodo)->first();
        return view('escolares.periodo_modifica')->with(compact('periodo', 'periodos','encabezado'));
    }
    public function periodoupdate(Request $request)
    {
        request()->validate([
            'finicio' => 'required',
            'ftermino' => 'required',
            'finicio_vac' => 'required',
            'ftermino_vac' => 'required',
            'finicio_cap' => 'required',
            'ftermino_cap' => 'required',
            'finicio_est' => 'required',
            'ftermino_est' => 'required'
        ], [
            'finicio.required' => 'Debe indicar la fecha de inicio del semestre',
            'ftermino.required' => 'Debe escribir la fecha de término del semestre',
            'finicio_vac.required' => 'Debe indicar la fecha de inicio de vacaciones para el semestre',
            'ftermino_vac.required' => 'Debe escribir la fecha de término de vacaciones para el semestre',
            'finicio_cap.required' => 'Debe indicar la fecha de inicio de captura docente para el semestre',
            'ftermino_cap.required' => 'Debe escribir la fecha de término de captura docente para el semestre',
            'finicio_est.required' => 'Debe indicar la fecha de inicio de selección de materias del estudiante para el semestre',
            'ftermino_est.required' => 'Debe escribir la fecha de término de selección de materias del estudiante para el semestre'
        ]);
        $periodo = $request->get('periodo');
        $ccarrera = $request->get('ccarrera');
        $finicio = $request->get('finicio');
        $ftermino = $request->get('ftermino');
        $finicio_ss1 = $request->get('finicio_ss');
        $ftermino_ss1 = $request->get('ftermino_ss');
        $finicio_ss = empty($finicio_ss1) ? null : $finicio_ss1;
        $ftermino_ss = empty($ftermino_ss1) ? null : $ftermino_ss1;
        $finicio_vac = $request->get('finicio_vac');
        $ftermino_vac = $request->get('ftermino_vac');
        $finicio_cap = $request->get('finicio_cap');
        $ftermino_cap = $request->get('ftermino_cap');
        $finicio_est = $request->get('finicio_est');
        $ftermino_est = $request->get('ftermino_est');
        $horarios = $request->get('horarios');
        $seleccion = $request->get('seleccion');
        PeriodoEscolar::where('periodo', $periodo)
            ->update([
                'fecha_inicio' => $finicio,
                'fecha_termino' => $ftermino,
                'inicio_vacacional_ss' => $finicio_ss,
                'fin_vacacional_ss' => $ftermino_ss,
                'cierre_horarios' => $horarios,
                'cierre_seleccion' => $seleccion,
                'inicio_sele_alumnos' => $finicio_est,
                'fin_sele_alumnos' => $ftermino_est,
                'inicio_vacacional' => $finicio_vac,
                'termino_vacacional' => $ftermino_vac,
                'inicio_cal_docentes' => $finicio_cap,
                'ccarrera' => $ccarrera,
                'fin_cal_docentes' => $ftermino_cap
            ]);
        $encabezado="Actualización de período";
        $mensaje="Se actualizó la información del período en la base de datos ";
        return view('escolares.si')->with(compact('encabezado','mensaje'));
    }
    public function reinscripcion(){
        $encabezado="Parámetros para reinscripción";
        $periodo_actual = (new AccionesController)->periodo();
        $periodos = PeriodoEscolar::orderBy('periodo','desc')->get();
        $carreras = Carrera::distinct('carrera')->orderBy('carrera', 'asc')
            ->get();
        return view('escolares.prereinscripcion')->with(compact('periodos',
            'periodo_actual', 'carreras','encabezado'));
    }
    public function accion_re(Request $request)
    {
        $periodo = $request->get('periodo');
        $carrera = $request->get('carrera');
        $accion = $request->get('accion');
        if ($accion == 1) {
            if (FechasCarrera::where('carrera', $carrera)->where('periodo', $periodo)->count() > 0) {
                $encabezado="Error en selección de carrera";
                $mensaje = "Ya registró una fecha para la reinscripción de la carrera";
                return view('escolares.no')->with(compact('mensaje','encabezado'));
            } else {
                $encabezado="Horario para reinscripción por carrera";
                $nperiodo = PeriodoEscolar::where('periodo', $periodo)->first();
                $ncarrera = Carrera::where('carrera', $carrera)->select('nombre_reducido')->first();
                return view('escolares.fechas_re')->with(compact('periodo', 'carrera',
                    'nperiodo', 'ncarrera','encabezado'));
            }
        } elseif ($accion == 2) {
            $anio_extraido = substr($periodo, 0, 4);
            $numero_periodo = (substr($periodo, 4, 1) == '3' || substr($periodo, 4, 1) == '2') ? '1' : '3';
            $anio = $numero_periodo == '1' ? $anio_extraido : $anio_extraido - 1;
            $periodo_anterior = $anio . $numero_periodo;
            if (FechasCarrera::where('periodo', $periodo)->where('carrera', $carrera)->count() > 0) {
                $valores = FechasCarrera::where('periodo', $periodo)->where('carrera', $carrera)->first();
                $fecha = $valores->fecha_inscripcion;
                $hora_inicio = $valores->fecha_inicio;
                $hora_fin = $valores->fecha_fin;
                $intervalo = $valores->intervalo;
                $personas = $valores->personas;
                $hora_inicio = substr($hora_inicio, 0, 2);
                if (substr($hora_inicio, 0, 1) == "0") {
                    $hora_inicio = substr($hora_inicio, 1, 1);
                }
                $hora_fin = substr($hora_fin, 0, 2);
                $inicio = $hora_inicio;
                $fin = $hora_fin;
                $sumadorT = 0;
                $j = 0;
                while ($inicio < $fin) {
                    if ($sumadorT < 60) {
                        if ($inicio < 10) {
                            if ($sumadorT > 0) {
                                $horas[$j] = "0" . $inicio . ":" . $sumadorT . ":00.0";
                            } else {
                                $horas[$j] = "0" . $inicio . ":00:00.0";
                            }
                        } else {
                            $horas[$j] = $inicio . ":" . $sumadorT . ":00.0";
                        }
                    } else {
                        $inicio += 1;
                        $sumadorT -= 60;
                        if ($sumadorT < 1) {
                            if ($inicio < 10) {
                                $horas[$j] = "0" . $inicio . ":00:00.0";
                            } else {
                                $horas[$j] = $inicio . ":00:00.0";
                            }
                        } else {
                            $horas[$j] = $inicio . ":" . $sumadorT . ":00.0";
                        }
                    }
                    $sumadorT += $intervalo;
                    $j++;
                }
                $hora_puesta = 1;
                $p = 0;
                $avisos =DB::table('avisos_reinscripcion as AR')->where('periodo', $periodo)
                    ->join('alumnos as A', 'A.no_de_control', '=', 'AR.no_de_control')
                    ->where('A.estatus_alumno', 'ACT')
                    ->where('carrera', $carrera)
                    ->select('AR.no_de_control', 'A.apellido_paterno', 'A.apellido_materno', 'A.nombre_alumno', 'A.semestre', 'AR.fecha_hora_seleccion')
                    ->orderBy('A.semestre', 'asc')
                    ->get();
                $cont = 1;
                foreach ($avisos as $seleccion) {
                    if (SeleccionMateria::where('no_de_control', $seleccion->no_de_control)
                            ->where('periodo', $periodo_anterior)->join('materias', 'materias.materia', '=', 'seleccion_materias.materia')
                            ->where('nombre_completo_materia', 'LIKE', "%RESIDENCIA%")->count() == 0) {
                        $consultar_promedio = AcumuladoHistorico::where('periodo', $periodo_anterior)
                            ->where('no_de_control', $seleccion->no_de_control)->select('promedio_ponderado')
                            ->first();
                        if (empty($consultar_promedio)) {
                            $promedio_ponderado = 0;
                        } else {
                            $promedio_ponderado = trim($consultar_promedio->promedio_ponderado);
                            $promedio_ponderado = substr($promedio_ponderado, 0, 5);
                        }
                        $listado=new GenerarListasTemporal();
                        $listado->no_de_control=$seleccion->no_de_control;
                        $listado->apellido_paterno=$seleccion->apellido_paterno;
                        $listado->apellido_materno=$seleccion->apellido_materno;
                        $listado->nombre_alumno=$seleccion->nombre_alumno;
                        $listado->semestre=$seleccion->semestre;
                        $listado->promedio_ponderado=$promedio_ponderado;
                        $listado->save();
                        $cont++;
                    }
                }
                $consulta = GenerarListasTemporal::orderBy('semestre', 'asc')
                    ->orderBy('promedio_ponderado', 'desc')
                    ->get();
                foreach ($consulta as $resultado) {
                    if ($hora_puesta < $personas) {
                        $fecha_asig = $fecha . " " . $horas[$p];
                        $hora_puesta++;
                    } else {
                        $fecha_asig = $fecha . " " . $horas[$p];
                        $hora_puesta = 1;
                        $p++;
                    }
                    $no_de_control = $resultado->no_de_control;
                    AvisoReinscripcion::where('no_de_control', $no_de_control)
                        ->where('periodo', $periodo)
                        ->update([
                            'fecha_hora_seleccion' => $fecha_asig
                        ]);
                }
                GenerarListasTemporal::delete();
                return redirect('/escolares/periodos/reinscripcion');
            } else {
                $encabezado="Error de parámetro de reinscripción";
                $mensaje = "No ha indicado la fecha de reinscripción para la carrera";
                return view('escolares.no')->with(compact('mensaje','encabezado'));
            }
        } elseif ($accion == 3) {
            $avisos = DB::table('avisos_reinscripcion as AR')
                ->where('periodo', $periodo)
                ->join('alumnos as A', 'A.no_de_control', '=', 'AR.no_de_control')
                ->where('A.estatus_alumno', 'ACT')
                ->where('carrera', $carrera)
                ->whereNotNull('AR.fecha_hora_seleccion')
                ->select('AR.no_de_control', 'A.apellido_paterno', 'A.apellido_materno', 'A.nombre_alumno', 'A.semestre', 'AR.fecha_hora_seleccion')
                ->orderBy('A.semestre', 'asc')
                ->orderBy('A.apellido_paterno', 'asc')
                ->orderBy('A.apellido_materno', 'asc')
                ->orderBy('A.no_de_control', 'asc')
                ->get();
            $nperiodo = PeriodoEscolar::where('periodo', $periodo)->select('identificacion_corta')->first();
            $ncarrera = Carrera::where('carrera', $carrera)->select('nombre_reducido')->first();
            $data = [
                'alumnos' => $avisos,
                'nperiodo' => $nperiodo,
                'ncarrera' => $ncarrera
            ];
            $pdf = PDF::loadView('escolares.pdf_listado', $data)
                ->setPaper('Letter');
            return $pdf->download('listado.pdf');
        }
    }
    public function altaf_re(Request $request)
    {
        request()->validate([
            'dia' => 'required',
            'horaini' => 'required',
            'horafin' => 'required'
        ], [
            'dia.required' => 'Debe indicar el día para la reinscripción de la carrera',
            'horaini.required' => 'Debe indicar la hora en la que inicia la reinscripción de la carrera',
            'horafin.required' => 'Debe indicar la hora en la que termina la reinscripción de la carrera'
        ]);
        $carrera = $request->get('carrera');
        $periodo = $request->get('periodo');
        $dia = $request->get('dia');
        $horaini = $request->get('horaini');
        $horafin = $request->get('horafin');
        $intervalo = $request->get('intervalo');
        $personas = $request->get('personas');
        $fecha=new FechasCarrera();
        $fecha->carrera=$carrera;
        $fecha->fecha_inscripcion=$dia;
        $fecha->fecha_inicio=$horaini;
        $fecha->fecha_fin=$horafin;
        $fecha->intervalo=$intervalo;
        $fecha->personas=$personas;
        $fecha->periodo=$periodo;
        $fecha->puntero=0;
        $fecha->save();
        return redirect('/escolares/periodos/reinscripcion');
    }
    public function cierre(){
        $encabezado="Parámetros para cierre de semestre";
        $periodo_actual = (new AccionesController)->periodo();
        $periodos = PeriodoEscolar::orderBy('periodo','desc')->get();
        return view('escolares.cierre_index')->with(compact('periodo_actual',
            'periodos','encabezado'));
    }
    public function periodoactas1()
    {
        $encabezado="Entrega de actas del período por el docente a Escolares";
        $periodo_actual = (new AccionesController)->periodo();
        $periodos = PeriodoEscolar::orderBy('periodo','desc')->get();
        return view('escolares.periodo_actas1')->with(compact('periodo_actual',
            'periodos','encabezado'));
    }
    public function periodoactas2(Request $request)
    {
        $periodo = $request->get('periodo');
        $docentes = Grupo::where('periodo', $periodo)
            ->join('personal', 'personal.rfc', '=', 'grupos.rfc')
            ->select('grupos.rfc', 'apellidos_empleado', 'nombre_empleado')
            ->distinct()
            ->orderBy('apellidos_empleado', 'asc')
            ->orderBy('nombre_empleado', 'asc')
            ->get();
        $nperiodo = PeriodoEscolar::where('periodo', $periodo)->first();
        $encabezado="Entrega de actas del período por el docente a Escolares";
        return view('escolares.periodo_actas2')->with(compact('periodo', 'docentes',
            'nperiodo','encabezado'));
    }
    public function periodoactas3(Request $request)
    {
        $periodo = $request->get('periodo');
        $docente = $request->get('docente');
        $grupos = Grupo::where('periodo', $periodo)
            ->where('rfc', $docente)
            ->join('materias', 'materias.materia', '=', 'grupos.materia')
            ->select('grupos.materia', 'grupo', 'nombre_abreviado_materia','entrego')
            ->orderBy('nombre_abreviado_materia', 'asc')
            ->get();
        $ndocente = Personal::where('rfc', $docente)->first();
        $nperiodo = PeriodoEscolar::where('periodo', $periodo)->first();
        $encabezado="Entrega de actas del período por el docente a Escolares";
        return view('escolares.periodo_actas3')->with(compact('periodo',
            'docente', 'nperiodo', 'grupos', 'ndocente','encabezado'));
    }
    public function periodoactas_m1()
    {
        $periodo_actual = (new AccionesController)->periodo();
        $periodos = PeriodoEscolar::orderBy('periodo','desc')->get();
        $encabezado="Actas del período";
        return view('escolares.periodo_actas_1')->with(compact('periodo_actual',
            'periodos','encabezado'));
    }
    public function periodoactas_m2(Request $request)
    {
        $periodo = $request->get('periodo');
        $docentes = Grupo::where('periodo', $periodo)
            ->join('personal', 'personal.rfc', '=', 'grupos.rfc')
            ->select('grupos.rfc', 'apellidos_empleado', 'nombre_empleado')
            ->distinct()
            ->orderBy('apellidos_empleado', 'asc')
            ->orderBy('nombre_empleado', 'asc')
            ->get();
        $nperiodo = PeriodoEscolar::where('periodo', $periodo)->first();
        $encabezado="Actas del período";
        return view('escolares.periodo_actas_2')->with(compact('periodo', 'docentes',
            'nperiodo','encabezado'));
    }
    public function periodoactas_m3(Request $request)
    {
        $periodo = $request->get('periodo');
        $docente = $request->get('docente');
        $grupos = Grupo::where('periodo', $periodo)
            ->where('rfc', $docente)
            ->join('materias', 'materias.materia', '=', 'grupos.materia')
            ->select('grupos.materia', 'grupo', 'nombre_abreviado_materia')
            ->orderBy('nombre_abreviado_materia', 'asc')
            ->get();
        $ndocente = Personal::where('rfc', $docente)->first();
        $nperiodo = PeriodoEscolar::where('periodo', $periodo)->first();
        $encabezado="Actas del período";
        return view('escolares.periodo_actas_3')->with(compact('periodo',
            'docente', 'nperiodo', 'grupos', 'ndocente','encabezado'));
    }
    public function periodoactas_m4(Request $request)
    {
        $periodo = $request->get('periodo');
        $docente = $request->get('docente');
        foreach ($request->all() as $key => $value) {
            if (($key != "periodo") && ($key != "docente") && ($key != "_token")) {
                $info = explode("_", $key);
                $materia = $info[0];
                $gpo = $info[1];
                $asignar=$value==1?true:false;
                Grupo::where([
                    'periodo'=>$periodo,
                    'rfc'=>$docente,
                    'materia'=>$materia,
                    'grupo'=>$gpo
                ])->update([
                    'entrego' => $asignar
                ]);
            }
        }
        $encabezado="Actas del período";
        $mensaje="Se registró en el sistemas las actas que fueron entregas al Departamento de Escolares";
        return view('escolares.si')->with(compact('encabezado','mensaje'));
    }
    public function modificaracta($per, $rfc, $mat, $gpo)
    {
        $periodo=base64_decode($per); $docente=base64_decode($rfc);
        $materia=base64_decode($mat); $grupo=base64_decode($gpo);
        $alumnos = SeleccionMateria::where('periodo', $periodo)
            ->where('materia', $materia)
            ->where('grupo', $grupo)
            ->join('alumnos', 'alumnos.no_de_control', '=', 'seleccion_materias.no_de_control')
            ->distinct()
            ->select('seleccion_materias.no_de_control', 'apellido_paterno', 'apellido_materno', 'nombre_alumno', 'calificacion', 'tipo_evaluacion', 'plan_de_estudios')
            ->orderBy('apellido_paterno', 'asc')
            ->orderBy('apellido_materno', 'asc')
            ->orderBy('nombre_alumno', 'asc')
            ->get();
        $ndocente = Personal::where('rfc', $docente)->first();
        $nperiodo = PeriodoEscolar::where('periodo', $periodo)->first();
        $nmateria = Materia::where('materia', $materia)->first();
        $tipo_3 = TipoEvaluacion::where('plan_de_estudios', '3')
            ->where('tipo_evaluacion', '!=', 'AC')
            ->get();
        $tipo_4 = TipoEvaluacion::where('plan_de_estudios', '4')
            ->where('tipo_evaluacion', '!=', 'AC')
            ->get();
        $encabezado="Actualización de calificaciones en acta";
        return view('escolares.actas_modificar')
            ->with(compact('periodo', 'nperiodo', 'alumnos',
                'ndocente', 'nmateria', 'materia', 'grupo', 'tipo_3', 'tipo_4','encabezado'));
    }
    public function actasupdate(Request $request)
    {
        $materia = $request->get('materia');
        $grupo = $request->get('grupo');
        $periodo = $request->get('periodo');
        $inscritos = SeleccionMateria::where([
            'periodo'=>$periodo,
            'materia'=>$materia,
            'grupo'=>$grupo
        ])->select('no_de_control')->get();
        foreach ($inscritos as $alumnos) {
            $control = $alumnos->no_de_control;
            $obtener = $materia . "_" . $grupo . "_" . $control;
            $op = "op_" . $control;
            $cal = $request->get($obtener);
            $oport = $request->get($op);
            SeleccionMateria::where([
                'periodo'=>$periodo,
                'materia'=>$materia,
                'grupo'=>$grupo,
                'no_de_control'=> $control
            ])->update([
                    'calificacion' => $cal,
                    'tipo_evaluacion' => $oport,
                    'updated_at' => Carbon::now()
                ]);
            if (HistoriaAlumno::where([
                    'periodo'=>$periodo,
                    'materia'=>$materia,
                    'grupo'=>$grupo,
                    'no_de_control'=> $control
                ])->count() > 0
            ) {
                HistoriaAlumno::where([
                    'periodo'=>$periodo,
                    'materia'=>$materia,
                    'grupo'=>$grupo,
                    'no_de_control'=> $control
                ])->update([
                    'calificacion' => $cal,
                    'tipo_evaluacion' => $oport,
                    'updated_at' => Carbon::now()
                ]);
            }
        }
        $encabezado="Actualización de calificaciones en acta";
        $mensaje="Se actualizó la información de la materia ".$materia." del grupo ".$grupo;
        return view('escolares.si')->with(compact('encabezado','mensaje'));
    }
    public function imprimiracta($periodo, $doc, $materia, $grupo)
    {
        if (SeleccionMateria::where('periodo', $periodo)
                ->where('materia', $materia)
                ->where('grupo', $grupo)
                ->count() > 0) {
            if (SeleccionMateria::where('periodo', $periodo)
                    ->where('materia', $materia)
                    ->where('grupo', $grupo)
                    ->whereNotNull('calificacion')
                    ->count() > 0) {
                $inscritos = SeleccionMateria::where('periodo', $periodo)
                    ->where('materia', $materia)
                    ->where('grupo', $grupo)
                    ->join('alumnos', 'seleccion_materias.no_de_control', '=', 'alumnos.no_de_control')
                    ->join('tipos_evaluacion', function ($join) {
                        $join->on('alumnos.plan_de_estudios', '=', 'tipos_evaluacion.plan_de_estudios')
                            ->on('tipos_evaluacion.tipo_evaluacion', '=', 'seleccion_materias.tipo_evaluacion');
                    })
                    ->orderBy('apellido_paterno', 'asc')
                    ->orderBy('apellido_materno', 'asc')
                    ->orderBy('nombre_alumno', 'asc')
                    ->get();
                $datos = Grupo::where('periodo', $periodo)
                    ->where('materia', $materia)
                    ->where('grupo', $grupo)
                    ->first();
                $nombre_mat = Materia::where('materia', $materia)->first();
                $ndocente = Personal::where('rfc', $doc)->select('apellidos_empleado', 'nombre_empleado')->first();
                $nperiodo = PeriodoEscolar::where('periodo', $periodo)->first();
                $data = [
                    'alumnos' => $inscritos,
                    'docente' => $ndocente,
                    'nombre_periodo' => $nperiodo,
                    'datos' => $datos,
                    'nmateria' => $nombre_mat,
                    'materia' => $materia,
                    'grupo' => $grupo
                ];
                $pdf = PDF::loadView('escolares.pdf_acta', $data);
                return $pdf->download('acta.pdf');
            } else {
                $inscritos = SeleccionMateria::where('periodo', $periodo)
                    ->where('materia', $materia)
                    ->where('grupo', $grupo)
                    ->join('alumnos', 'seleccion_materias.no_de_control', '=', 'alumnos.no_de_control')
                    ->orderBy('apellido_paterno', 'asc')
                    ->orderBy('apellido_materno', 'asc')
                    ->orderBy('nombre_alumno', 'asc')
                    ->get();
                $datos = Grupo::where('periodo', $periodo)
                    ->where('materia', $materia)
                    ->where('grupo', $grupo)
                    ->first();
                $nombre_mat = Materia::where('materia', $materia)->first();
                $ndocente = Personal::where('rfc', $doc)->select('apellidos_empleado', 'nombre_empleado')->first();
                $nperiodo = PeriodoEscolar::where('periodo', $periodo)->first();
                $data = [
                    'alumnos' => $inscritos,
                    'docente' => $ndocente,
                    'nombre_periodo' => $nperiodo,
                    'datos' => $datos,
                    'nmateria' => $nombre_mat,
                    'materia' => $materia,
                    'grupo' => $grupo
                ];
                $pdf = PDF::loadView('escolares.pdf_acta2', $data)
                    ->setPaper('Letter');
                return $pdf->download('acta.pdf');
            }
        } else {
            $encabezado="Error para impresión de acta";
            $mensaje = "No cuenta con alumnos inscritos en la materia";
            return view('personal.no')->with(compact('mensaje','encabezado'));
        }
    }
    public function actas_mantenimiento()
    {
        $periodo_actual = (new AccionesController)->periodo();
        $periodos = PeriodoEscolar::orderBy('periodo','desc')->get();
        $encabezado="Estatus de actas";
        return view('escolares.actas_mantenimiento1')->with(compact('periodo_actual',
            'periodos','encabezado'));
    }
    public function actas_estatus(Request $request)
    {
        $periodo = $request->get('periodo');
        $accion = $request->get('accion');
        if ($accion == 1) {
            $resultado = (new AccionesController)->sin_evaluar($periodo);
            $encabezado = "Materias sin ser evaluadas";
        }elseif ($accion == 2) {
            $resultado = (new AccionesController)->evaluadas($periodo);
            $encabezado = "Materias evaluadas";
        }elseif ($accion == 3) {
            $resultado = (new AccionesController)->actas_faltantes($periodo);
            $encabezado = "Actas faltantes por entregar en Escolares";
        }
        $nperiodo = PeriodoEscolar::where('periodo', $periodo)->first();
        return view('escolares.actas_estatus')->with(compact('nperiodo',
            'resultado', 'encabezado'));
    }
    public function carrerasalta()
    {
        $cant = Carrera::where('nivel_escolar', 'L')->select('carrera')
            ->distinct('carrera')->count();
        $encabezado="Alta de carrera";
        return view('escolares.carrera_alta')->with(compact('cant','encabezado'));
    }
    public function carreranueva(Request $request)
    {
        request()->validate([
            'carrera' => 'required',
            'reticula' => 'required',
            'cve' => 'required',
            'ncarrera' => 'required',
            'nreducido' => 'required',
            'siglas' => 'required',
            'cred_max' => 'required',
            'cred_min' => 'required',
            'cred_tot' => 'required'
        ], [
            'carrera.required' => 'Debe escribir la clave para la carrera',
            'reticula.required' => 'Debe indicar a que retícula corresponde la carrera',
            'cve.required' => 'Debe indicar la clave oficial de la carrera',
            'ncarrera.required' => 'Debe indicar el nombre completo de la carrera',
            'nreducido.required' => 'Debe indicar el nombre abreviado para la carrera',
            'siglas.required' => 'Debe indicar las siglas para la carrera',
            'cred_max.required' => 'Debe indicar la carga máxima en créditos para la carrera',
            'cred_min.required' => 'Debe indicar la carga mínima en créditos para la carrera',
            'cred_tot.required' => 'Debe indicar la carga total que consta para la carrera'
        ]);
        $carrera = $request->get('carrera');
        $reticula = $request->get('reticula');
        if (Carrera::where('carrera', $carrera)->where('reticula', $reticula)->count() > 0) {
            $encabezado = "Error de alta de carrera";
            $mensaje = "Ya existe una carrera con la misma retícula dada de alta, por lo que no fue posible
            crearla";
            return view('escolares.no')->with(compact('mensaje', 'encabezado'));
        } else {
            $nivel = $request->get('nivel');
            $cve_oficial = $request->get('cve');
            $nombre_carrera = $request->get('ncarrera');
            $nombre_reducido = $request->get('nreducido');
            $siglas = $request->get('siglas');
            $cred_max = $request->get('cred_max');
            $cred_min = $request->get('cred_min');
            $cred_tot = $request->get('cred_tot');
            $modalidad = $request->get('modalidad');
            $ncarrera = new Carrera();
            $ncarrera->carrera = $carrera;
            $ncarrera->reticula = $reticula;
            $ncarrera->nivel_escolar = $nivel;
            $ncarrera->clave_oficial = $cve_oficial;
            $ncarrera->nombre_carrera = $nombre_carrera;
            $ncarrera->nombre_reducido = $nombre_reducido;
            $ncarrera->siglas = $siglas;
            $ncarrera->carga_maxima = $cred_max;
            $ncarrera->carga_minima = $cred_min;
            $ncarrera->creditos_totales = $cred_tot;
            $ncarrera->modalidad = $modalidad;
            $ncarrera->nreal = $nombre_carrera;
            $ncarrera->ofertar = 0;
            $ncarrera->abrev = $siglas;
            $ncarrera->nombre_ofertar = null;
            $ncarrera->save();
            $encabezado = "Alta de carrera";
            $mensaje = "Se dió de alta en la base de datos a la carrera " . $nombre_carrera . " con retícula " . $reticula;
            return view('escolares.si')->with(compact('encabezado', 'mensaje'));
        }
    }
    public function especialidadesalta()
    {
        $carreras = Carrera::select('carrera', 'reticula', 'nombre_reducido')->orderBy('carrera')
            ->orderBy('reticula')->get();
        $encabezado="Alta de especialidades";
        return view('escolares.especialidad_alta')->with(compact('carreras','encabezado'));
    }
    public function especialidadnueva(Request $request)
    {
        request()->validate([
            'espe' => 'required',
            'nespecialidad' => 'required',
            'cred_especialidad' => 'required',
            'cred_optativos' => 'required'
        ], [
            'espe.required' => 'Debe escribir la clave para la especialidad',
            'nespecialidad.required' => 'Debe indicar el nombre de la especialidad',
            'cred_especialidad.required' => 'Debe indicar la carga en créditos para la especialidad',
            'cred_optativos.required' => 'Debe indicar la carga en créditos optativos (0 si no lleva)'
        ]);
        $info = $request->all();
        $datos = explode("_", $info["carrera"]);
        $carrera = trim($datos[0]);
        $reticula = $datos[1];
        if (Especialidad::where('especialidad', $info["espe"])->count() > 0) {
            $encabezado="Error de alta en especialidad";
            $mensaje = "Ya existe una especialidad con esa clave, por lo que no
            es posible duplicar la información";
            return view('escolares.no')->with(compact('mensaje','encabezado'));
        } else {
            $espe= new Especialidad();
            $espe->especialidad=$info["espe"];
            $espe->carrera=$carrera;
            $espe->reticula=$reticula;
            $espe->nombre_especialidad=$info["nespecialidad"];
            $espe->creditos_optativos=$info["cred_optativos"];
            $espe->creditos_especialidad=$info["cred_especialidad"];
            $espe->activa=true;
            $espe->save();
            return view('escolares.si');
        }
    }
    public function materianueva()
    {
        $carreras = Carrera::select('carrera', 'reticula', 'nombre_reducido')->orderBy('carrera')
            ->orderBy('reticula')->get();
        $encabezado="Materias - Carreras";
        return view('escolares.materias_alta')->with(compact('carreras','encabezado'));
    }
    public function materiasacciones(Request $request)
    {
        $accion = $request->get('accion');
        $carr = $request->get('carrera');
        $datos = explode("_", $carr);
        $carrera = trim($datos[0]);
        $reticula = $datos[1];
        if ($accion == 1) {
            $encabezado="Alta de materia en carrera";
            $acad = Organigrama::where('area_depende', 'like', '110%')
                ->where('clave_area', 'like', '%00')
                ->get();
            $espe = Especialidad::where('carrera', $carrera)->where('reticula', $reticula)->get();
            $materias = MateriaCarrera::where('carrera', $carrera)->where('reticula', $reticula)
                ->join('materias', 'materias_carreras.materia', '=', 'materias.materia')
                ->whereNull('especialidad')
                ->select('materias_carreras.materia as matteria', 'nombre_abreviado_materia',
                    'creditos_materia', 'horas_teoricas', 'horas_practicas', 'semestre_reticula', 'renglon')
                ->get();
            $ncarrera = Carrera::where('carrera', $carrera)->where('reticula', $reticula)->first();
            return view('escolares.materia_nueva')->with(compact('carrera',
                'reticula', 'acad', 'espe', 'materias', 'ncarrera','encabezado'));
        } elseif ($accion == 3) {
            $encabezado="Vista retícula de la carrera con especialidad";
            $espe = Especialidad::where('carrera', $carrera)->where('reticula', $reticula)->get();
            $ncarrera = Carrera::where('carrera', $carrera)->where('reticula', $reticula)->first();
            return view('escolares.reticulas')->with(compact('carrera',
                'reticula', 'espe', 'ncarrera','encabezado'));
        }
    }
    public function materiaalta(Request $request)
    {
        request()->validate([
            'cve' => 'required',
            'cve_of' => 'required',
            'nombre_completo' => 'required',
            'nombre_abrev' => 'required',
            'horas_teoricas' => 'required',
            'horas_practicas' => 'required',
            'creditos' => 'required',
            'certificado' => 'required'

        ], [
            'cve.required' => 'Debe escribir la clave interna para la materia',
            'cve_of.required' => 'Debe escribir la clave oficial de la materia',
            'nombre_completo.required' => 'Debe indicar el nombre completo para la materia',
            'nombre_abrev.required' => 'Debe indicar el nombre corto para la materia',
            'horas_teoricas.required' => 'Debe indicar el número de horas teóricas de la materia',
            'horas_practicas.required' => 'Debe indicar el número de horas prácticas de la materia',
            'creditos.required' => 'Indique la cantidad de créditos para la materia',
            'certificado.required' => 'Indique la ubicación de la materia en el certificado'
        ]);
        $info = $request->all();
        $materia= new Materia();
        $materia->materia=$info["cve"];
        $materia->nivel_escolar=$info["nivel"];
        $materia->tipo_materia=$info["tipo_materia"];
        $materia->clave_area=$info["area"];
        $materia->nombre_completo_materia=$info["nombre_completo"];
        $materia->nombre_abreviado_materia=$info["nombre_abrev"];
        $materia->caracterizacion=null;
        $materia->generales=null;
        $materia->save();
        $espe = $info["especialidad"] == 0 ? null : $info["especialidad"];
        $mat_car=new MateriaCarrera();
        $mat_car->carrera=$info["carrera"];
        $mat_car->reticula=$info["reticula"];
        $mat_car->materia=$info["cve"];
        $mat_car->creditos_materia=$info["creditos"];
        $mat_car->horas_teoricas=$info["horas_teoricas"];
        $mat_car->horas_practicas=$info["horas_practicas"];
        $mat_car->orden_certificado=$info["certificado"];
        $mat_car->semestre_reticula=$info["semestre"];
        $mat_car->creditos_prerrequisito=0;
        $mat_car->especialidad=$espe;
        $mat_car->clave_oficial_materia=$info["cve_of"];
        $mat_car->renglon=$info["renglon"];
        $mat_car->save();
        $acad = Organigrama::where('area_depende', 'like', '110%')
            ->where('clave_area', 'like', '%00')
            ->get();
        $espe = Especialidad::where('carrera', $info["carrera"])->where('reticula', $info["reticula"])->get();
        $materias = MateriaCarrera::where('carrera', $info["carrera"])->where('reticula', $info["reticula"])
            ->join('materias', 'materias_carreras.materia', '=', 'materias.materia')
            ->whereNull('especialidad')
            ->select('materias_carreras.materia as matteria', 'nombre_abreviado_materia', 'creditos_materia',
                'horas_teoricas', 'horas_practicas', 'semestre_reticula', 'renglon')
            ->get();
        $carrera = $info["carrera"];
        $reticula = $info["reticula"];
        $ncarrera = Carrera::where('carrera', $info["carrera"])->where('reticula', $info["reticula"])->first();
        return view('escolares.materia_nueva')->with(compact('carrera',
            'reticula', 'acad', 'espe', 'materias', 'ncarrera'));
    }
    public function vistareticula(Request $request)
    {
        $carrera = $request->get('carrera');
        $reticula = $request->get('reticula');
        $especialidad = $request->get('espe');
        $materias_carrera = MateriaCarrera::where('carrera', $carrera)->where('reticula', $reticula)
            ->join('materias', 'materias_carreras.materia', '=', 'materias.materia')
            ->where(function ($query) use ($especialidad) {
                $query->whereNull('especialidad')
                    ->orWhere('especialidad', '=', $especialidad);
            })
            ->select('materias_carreras.materia as mate', 'nombre_abreviado_materia', 'creditos_materia',
                'horas_teoricas', 'horas_practicas', 'semestre_reticula', 'renglon')
            ->get();
        foreach($materias_carrera as $mater){
            $semestre_reticula = $mater->semestre_reticula;
            $renglon = $mater->renglon;
            $array_reticula[$renglon][$semestre_reticula]['clave'] = $mater->mate;
            $array_reticula[$renglon][$semestre_reticula]['materia'] = $mater->nombre_abreviado_materia;
            $array_reticula[$renglon][$semestre_reticula]['creditos_materia'] = $mater->creditos_materia;
            $array_reticula[$renglon][$semestre_reticula]['horas_teoricas'] = $mater->horas_teoricas;
            $array_reticula[$renglon][$semestre_reticula]['horas_practicas'] = $mater->horas_practicas;
        }
        $espe = Especialidad::where('carrera', $carrera)
            ->where('reticula', $reticula)->where('especialidad', $especialidad)
            ->first();
        $ncarrera = Carrera::where('carrera', $carrera)->where('reticula', $reticula)->first();
        return view('escolares.reticula_vista')->with(compact('espe',
            'array_reticula', 'ncarrera'));
    }
    public function idiomas_lib1(){
        $idiomas=Idioma::all();
        $encabezado="Idiomas";
        return view('escolares.liberacion1_idiomas')->with(compact('idiomas','encabezado'));
    }
    public function idiomas_lib2(Request $request){
        request()->validate([
            'control' => 'required'
        ], [
            'control.required' => 'Debe indicar el número de control'
        ]);
        $control=$request->get('control');
        $alumno = Alumno::findOrfail($control);
        $idioma=$request->get('idioma');
        if(IdiomasLiberacion::where('control',$control)->count()>0){
            $encabezado="Error de liberación en idioma extranjero";
            $mensaje="No es posible continuar porque el estudiante ya cuenta con la liberación del idioma";
            return view('escolares.no')->with(compact('mensaje','encabezado'));
        }else{
            $encabezado="Liberación de Idioma Extranjero";
            $lengua_extranjera=Idioma::where('id',$idioma)->first();
            return view('escolares.liberar_idioma')->with(compact('control',
                'idioma','alumno','lengua_extranjera','encabezado'));
        }
    }
    public function idiomas_lib3(Request $request){
        $alumno = Alumno::findOrfail($request->get('control'));
        $lib=new IdiomasLiberacion();
        $lib->periodo=null;
        $lib->control=$request->get('control');
        $lib->calif=null;
        $lib->liberacion=null;
        $lib->idioma=$request->get('idioma');
        $lib->opcion=$request->get('opcion');
        $lib->save();
        $encabezado="Liberación de Idioma Extranjero";
        return view('escolares.prelibidiomas')->with(compact('alumno','encabezado'));
    }
    public function idiomas_impre(){
        $encabezado="Impresión de liberación de idioma extranjero";
        return view('escolares.idiomas_imprimir')->with(compact('encabezado'));
    }
    public function idiomas_impre2(Request $request){
        request()->validate([
            'control' => 'required'
        ], [
            'control.required' => 'Debe indicar el número de control'
        ]);
        $control=$request->get('control');
        if(IdiomasLiberacion::where('control',$control)->count()==0){
            $encabezado="Error para imprimir liberación de IE";
            $mensaje="No es posible continuar porque el estudiante no cuenta con la liberación del idioma";
            return view('escolares.no')->with(compact('mensaje','encabezado'));
        }else{
            $alumno = Alumno::findOrfail($control);
            $encabezado="Impresión de liberación de idioma extranjero";
            return view('escolares.prelibidiomas')->with(compact('alumno','encabezado'));
        }
    }
    public function idiomas_consulta(){
        $periodo_actual = (new AccionesController)->periodo();
        $periodos = PeriodoEscolar::orderBy('periodo','desc')->get();
        $idiomas=Idioma::all();
        $encabezado="Idioma Extranjero";
        return view('escolares.idiomas_consulta1')->with(compact('periodo_actual',
            'periodos','idiomas','encabezado'));
    }
    public function idiomas_consulta2(Request $request){
        $periodo=$request->get('periodo');
        $idioma=$request->get('idioma');
        if(IdiomasGrupo::where('periodo',$periodo)->where('idioma',$idioma)->count()>0){
            $nperiodo=PeriodoEscolar::where('periodo',$periodo)->first();
            $nidioma=Idioma::where('id',$idioma)->first();
            $info=(new AccionesController)->consulta_idiomas($periodo,$idioma);
            $encabezado="Idioma Extranjero";
            return view('escolares.idiomas_consulta2')->with(compact('nperiodo',
                'nidioma','info','encabezado'));
        }else{
            $encabezado="Error en consulta de grupos de idioma extranjero";
            $mensaje="No hay grupos registrados para el periodo solicitado";
            return view('escolares.no')->with(compact('mensaje','encabezado'));
        }
    }
    public function prepoblacion(){
        $periodo_actual = (new AccionesController)->periodo();
        $periodos = PeriodoEscolar::orderBy('periodo','desc')->get();
        $periodo=$periodo_actual[0]->periodo;
        $encabezado="Estadísticas";
        return view('escolares.prepoblacion')->with(compact('periodos',
            'periodo','encabezado'));
    }
    public function poblacion(Request $request)
    {
        $encabezado="Consulta de población escolar";
        $periodo = $request->get('periodo');
        $inscritos = (new AccionesController)->inscritos($periodo);
        $nperiodo = PeriodoEscolar::where('periodo', $periodo)->first();
        return view('escolares.poblacion')->with(compact('inscritos',
            'periodo', 'nperiodo','encabezado'));
    }
    public function pobxcarrera($periodo,$carrera,$reticula){
        $nperiodo = PeriodoEscolar::where('periodo', $periodo)->first();
        $ncarrera = Carrera::where('carrera', $carrera)->where('reticula', $reticula)->first();
        $hombres = array_fill(1, 10, 0);
        $mujeres = array_fill(1, 10, 0);
        $encabezado="Consulta de población escolar por carrera";
        $semestres = array(1 => "1", 2 => "2", 3 => "3", 4 => "4", 5 => "5", 6 => "6", 7 => "7", 8 => "8", 9 => "9", 10 => ">9");
        $pob_masc = SeleccionMateria::where('periodo', $periodo)
            ->join('alumnos', 'alumnos.no_de_control', '=', 'seleccion_materias.no_de_control')
            ->where('carrera', $carrera)
            ->where('reticula', $reticula)
            ->where('sexo', 'M')
            ->select('seleccion_materias.no_de_control', 'periodo_ingreso_it')
            ->distinct()
            ->get();
        $pob_fem = SeleccionMateria::where('periodo', $periodo)
            ->join('alumnos', 'alumnos.no_de_control', '=', 'seleccion_materias.no_de_control')
            ->where('carrera', $carrera)
            ->where('reticula', $reticula)
            ->where('sexo', 'F')
            ->select('seleccion_materias.no_de_control', 'periodo_ingreso_it')
            ->distinct()
            ->get();
        foreach ($pob_masc as $value) {
            $periodo_ingreso = $value->periodo_ingreso_it;
            $semestre = (new AccionesController)->semreal($periodo_ingreso, $periodo);
            switch ($semestre) {
                case 1:
                    $hombres[1]++;
                    break;
                case 2:
                    $hombres[2]++;
                    break;
                case 3:
                    $hombres[3]++;
                    break;
                case 4:
                    $hombres[4]++;
                    break;
                case 5:
                    $hombres[5]++;
                    break;
                case 6:
                    $hombres[6]++;
                    break;
                case 7:
                    $hombres[7]++;
                    break;
                case 8:
                    $hombres[8]++;
                    break;
                case 9:
                    $hombres[9]++;
                    break;
                case ($semestre > 9):
                    $hombres[10]++;
                    break;
            }
        }
        foreach ($pob_fem as $value) {
            $periodo_ingreso = $value->periodo_ingreso_it;
            $semestre = (new AccionesController)->semreal($periodo_ingreso, $periodo);
            switch ($semestre) {
                case 1:
                    $mujeres[1]++;
                    break;
                case 2:
                    $mujeres[2]++;
                    break;
                case 3:
                    $mujeres[3]++;
                    break;
                case 4:
                    $mujeres[4]++;
                    break;
                case 5:
                    $mujeres[5]++;
                    break;
                case 6:
                    $mujeres[6]++;
                    break;
                case 7:
                    $mujeres[7]++;
                    break;
                case 8:
                    $mujeres[8]++;
                    break;
                case 9:
                    $mujeres[9]++;
                    break;
                case ($semestre > 9):
                    $mujeres[10]++;
                    break;
            }
        }
        return view('escolares.poblacion2')->with(compact('semestres',
            'hombres', 'mujeres', 'ncarrera', 'reticula', 'nperiodo','encabezado'));
    }
    public function mantenimiento_inicial(){
        $encabezado="Mantenimiento en la población escolar";
        return view('escolares.mantenimiento1')->with(compact('encabezado'));
    }
    public function mantenimiento_acciones(Request $request){
        $accion=$request->accion;
        $periodo_actual = (new AccionesController)->periodo();
        $periodo=$periodo_actual[0]->periodo;
        if($accion==1){
            (new AccionesController)->modificar_estatus($periodo);
            $mensaje="Se actualizó el estatus de los estudiantes";
        }elseif($accion==2){
            (new AccionesController)->actualizar_semestre($periodo);
            $mensaje="Se actualizó el semestre de los estudiantes";
        }elseif ($accion==3){
            (new AccionesController)->actualizar_inscritos_grupo($periodo);
            $mensaje="Se actualizó la cantidad de estudiantes inscritos por grupo";
        }
        $encabezado="Mantenimiento en la población escolar";
        return view('escolares.si')->with(compact('encabezado','mensaje'));
    }
    public function contrasenia()
    {
        $encabezado="Cambio de contraseña";
        return view('escolares.contrasenia')->with(compact('encabezado'));
    }
    public function ccontrasenia(Request $request)
    {
        request()->validate([
            'contra' => 'required|required_with:verifica|same:verifica',
            'verifica' => 'required'
        ], [
            'contra.required' => 'Debe escribir la nueva contraseña',
            'contra.required_with' => 'Debe confirmar la contraseña',
            'contra.same' => 'No concuerda con la verificacion',
            'verifica.required' => 'Debe confirmar la nueva contraseña'
        ]);
        $ncontra = bcrypt($request->get('contra'));
        $data = Auth::user()->email;
        User::where('email', $data)->update([
            'password' => $ncontra,
            'updated_at' => Carbon::now()
        ]);
        return redirect('inicio_escolares');
    }
}
