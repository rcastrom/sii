<?php

namespace App\Http\Controllers\Escolares;

use App\Models\Alumno;
use App\Models\AlumnosGeneral;
use App\Models\PeriodoEscolar;
use App\Models\EstatusAlumno;
use App\Models\Especialidad;
use App\Models\TipoEvaluacion;
use App\Models\HistoriaAlumno;
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
            $historial = $this->reticula($control);
            return view('escolares.reticula')->with(compact('alumno', 'historial'));
        } elseif ($accion == 3) {
            if (DB::table('seleccion_materias')->where('periodo', $periodo)
                    ->where('no_de_control', $control)->count() > 0) {
                return view('escolares.preconstancia')->with(compact('alumno', 'periodo'));
            } else {
                $mensaje = "No se puede generar la constancia porque el estudiante no cuenta con carga académica";
                return view('escolares.no')->with(compact('mensaje'));
            }
        } elseif ($accion == 4) {
            if (DB::table('historia_alumno')
                    ->where('periodo', $periodo)
                    ->where('no_de_control', $control)
                    ->count() > 0) {
                $cal_periodo = $this->boleta($control, $periodo);
                $nombre_periodo = DB::table('periodos_escolares')->where('periodo', $periodo)->get();
                return view('escolares.boleta')
                    ->with(compact('alumno', 'cal_periodo', 'nombre_periodo', 'periodo'));
            } else {
                $mensaje = "El estudiante no cuenta con calificaciones registradas para el período señalado";
                return view('escolares.no')->with(compact('mensaje'));
            }
        } elseif ($accion == 5) {
            if (DB::table('seleccion_materias')
                    ->where('no_de_control', $control)
                    ->where('periodo', $periodo)
                    ->count() > 0) {
                $datos_horario = DB::select("select * from pac_horario('$control','$periodo')");
                $nombre_periodo = DB::table('periodos_escolares')->where('periodo', $periodo)->get();
                return view('escolares.horario')->with(compact('alumno', 'datos_horario', 'nombre_periodo', 'periodo','control'));
            } else {
                $mensaje = "NO CUENTA CON CARGA ACADÉMICA ASIGNADA";
                return view('escolares.no')->with(compact('mensaje'));
            }
        } elseif ($accion == 6) {
            $estatus_alumno = DB::table('estatus_alumno')->get();
            $nombre_periodo = DB::table('periodos_escolares')->where('periodo', $periodo)->first();
            return view('escolares.modificar_estatus')->with(compact('alumno', 'periodo', 'estatus_alumno', 'nombre_periodo', 'control'));
        } elseif ($accion == 7) {
            if (DB::table('avisos_reinscripcion')->where('periodo', $periodo)->where('no_de_control', $control)->count() > 0) {
                DB::table('avisos_reinscripcion')->where('periodo', $periodo)
                    ->where('no_de_control', $control)->update([
                        'autoriza_escolar' => 'S',
                        'recibo_pago' => '1',
                        'fecha_hora_seleccion' => Carbon::now(),
                        'encuesto' => 'S',
                        'updated_at' => Carbon::now()
                    ]);
            } else {
                $creditos = DB::table('carreras')->where('carrera', $alumno->carrera)
                    ->where('reticula', $alumno->reticula)->select('carga_minima')->first();
                $semestre = $this->semreal($alumno->periodo_ingreso_it, $periodo);
                DB::table('avisos_reinscripcion')->insert([
                    'periodo' => $periodo,
                    'no_de_control' => $control,
                    'autoriza_escolar' => 'S',
                    'recibo_pago' => '1',
                    'fecha_recibo' => null,
                    'cuenta_pago' => null,
                    'fecha_hora_seleccion' => Carbon::now(),
                    'lugar_seleccion' => null,
                    'fecha_hora_pago' => null,
                    'lugar_pago' => null,
                    'adeuda_escolar' => null,
                    'adeuda_biblioteca' => null,
                    'adeuda_financieros' => null,
                    'otro_mensaje' => null,
                    'baja' => null,
                    'motivo_aviso_baja' => null,
                    'egresa' => null,
                    'encuesto' => 'S',
                    'vobo_adelanta_sel' => null,
                    'regular' => null,
                    'indice_reprobacion' => 0,
                    'creditos_autorizados' => $creditos->carga_minima,
                    'estatus_reinscripcion' => null,
                    'semestre' => $semestre,
                    'promedio' => 0,
                    'adeudo_especial' => 'N',
                    'promedio_acumulado' => null,
                    'proareas' => null,
                    'created_at' => Carbon::now()
                ]);
                DB::table('alumnos')->where('no_de_control', $control)->update([
                    'semestre' => $semestre
                ]);
            }
            return view('escolares.si');
        } elseif ($accion == 8) {
            $especialidades = DB::table('especialidades')->where('carrera', $alumno->carrera)
                ->where('reticula', $alumno->reticula)->get();
            return view('escolares.modificar_especialidad')->with(compact('alumno', 'especialidades'));
        } elseif ($accion == 9) {
            $carreras = DB::table('carreras')->where('ofertar', '1')
                ->orderBy('nombre_carrera', 'ASC')->get();
            return view('escolares.modificar_carrera')->with(compact('alumno', 'carreras', 'control'));
        } elseif ($accion == 10) {
            return view('escolares.confirmar_borrado')->with(compact('alumno', 'control'));
        } elseif ($accion == 11) {
            return view('escolares.confirmar_bajatemp')->with(compact('alumno', 'periodo'));
        } elseif ($accion == 12) {
            return view('escolares.alta_nss')->with(compact('alumno'));
        } elseif ($accion == 13) {
            $mat = DB::table('materias_carreras')->where('carrera', $alumno->carrera)
                ->where('reticula', $alumno->reticula)
                ->join('materias', 'materias.materia', '=', 'materias_carreras.materia')
                ->where('nombre_completo_materia', 'LIKE', "%COMPLEMENTARIAS%")
                ->first();
            if (DB::table('historia_alumno')->where('no_de_control', $control)
                    ->where('materia', $mat->materia)->count() > 0) {
                $mensaje = "La materia ya está acreditada por lo que no es posible volverla a activar";
                return view('escolares.no')->with(compact('mensaje'));
            } else {
                DB::table('historia_alumno')->insert([
                    'periodo' => $periodo,
                    'no_de_control' => $control,
                    'materia' => $mat->materia,
                    'grupo' => null,
                    'calificacion' => 60,
                    'tipo_evaluacion' => 'AC',
                    'fecha_calificacion' => Carbon::now(),
                    'plan_de_estudios' => $alumno->plan_de_estudios,
                    'estatus_materia' => 'A',
                    'nopresento' => 'N',
                    'usuario' => Auth::user()->email,
                    'fecha_actualizacion' => Carbon::now(),
                    'periodo_acredita_materia' => $periodo,
                    'created_at' => Carbon::now(),
                    'updated_at' => null
                ]);
                return view('escolares.si');
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
            $pdf = PDF::loadView('escolares.pdf_kardex', $data);
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
}
