<?php

namespace App\Http\Controllers\Escolares;

use App\Http\Controllers\Acciones\AccionesController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MenuEscolaresController;
use App\Models\Alumno;
use App\Models\AlumnosGeneral;
use App\Models\AvisoReinscripcion;
use App\Models\Carrera;
use App\Models\Especialidad;
use App\Models\EstatusAlumno;
use App\Models\HistoriaAlumno;
use App\Models\IdiomasLiberacion;
use App\Models\MateriaCarrera;
use App\Models\PeriodoEscolar;
use App\Models\PlanDeEstudio;
use App\Models\SeleccionMateria;
use App\Models\TiposIngreso;
use Carbon\Carbon;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class EscolaresAlumnosController extends Controller
{


    public function __construct(Dispatcher $events)
    {
        new MenuEscolaresController($events);
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
        $control = $request->get("control");
        $tipo_busqueda = $request->get("tbusqueda");
        $periodo = (new AccionesController)->periodo();
        $periodos = PeriodoEscolar::orderBy('periodo', 'DESC')->get();
        if ($tipo_busqueda == "1") {
            try {
                $alumno=Alumno::findOrFail($control);
            }catch (ModelNotFoundException){
                $encabezado="Error en la búsqueda";
                $mensaje="El número de control ".$control." no fue localizado";
                return view('escolares.no')->with(compact('encabezado','mensaje'));
            }
            $datos = Datos::datos_alumno($control);
            if(empty($datos)){
                $info=collect(
                    [
                        'domicilio_calle',
                        'domicilio_colonia',
                        'codigo_postal',
                        'telefono'
                    ]
                );
                $datos=$info->combine(
                    [
                        '',
                        '',
                        '',
                        ''
                    ]
                );
                $bandera=0;
            }else{
                $bandera=1;
            }
            $ncarrera = (new AccionesController)->ncarrera($alumno->carrera,$alumno->reticula);
            $ingreso = PeriodoEscolar::where('periodo', $alumno->periodo_ingreso_it)
                ->select('identificacion_corta')->first();
            $estatus = EstatusAlumno::where('estatus', $alumno->estatus_alumno)->first();
            $espe = Especialidad::where('especialidad', $alumno->especialidad)
                ->where('carrera', $alumno->carrera)
                ->where('reticula', $alumno->reticula)
                ->first();
            $especialidad=empty($espe)?"POR ASIGNAR":$espe->nombre_especialidad;
            return view('escolares.datos')
                ->with(compact('alumno', 'ncarrera', 'datos', 'control', 'periodo',
                    'periodos', 'estatus', 'especialidad', 'ingreso','bandera','encabezado'));
        }else{
            $arroja = Alumno::where('apellido_paterno', strtoupper($control))
                ->orWhere('apellido_materno', strtoupper($control))
                ->orWhere('nombre_alumno', strtoupper($control))
                ->orderBY('apellido_paterno')
                ->orderBy('apellido_materno')
                ->orderBy('nombre_alumno')
                ->get();
            return view('escolares.datos2')
                ->with(compact('arroja', 'periodo', 'periodos','encabezado'));
        }
    }
    public function accion(Request $request)
    {
        $control = $request->control;
        $periodo = $request->periodo;
        $accion = $request->accion;
        $alumno = Alumno::findOrfail($control);
        if ($accion == 1) {
            return redirect()->route('kardex.index',['control'=>$control]);
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
        $encabezado="Error";
        $mensaje="Existió algún error";
        return view('escolares.no')->with(compact('encabezado','mensaje'));
    }
}
