<?php

namespace App\Http\Controllers\Escolares;

use App\Http\Controllers\Acciones\AccionesController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MenuEscolaresController;
use App\Models\Alumno;
use App\Models\AlumnosGeneral;
use App\Models\AvisoReinscripcion;
use App\Models\Carrera;
use App\Models\EntidadesFederativa;
use App\Models\Especialidad;
use App\Models\EstatusAlumno;
use App\Models\HistoriaAlumno;
use App\Models\IdiomasLiberacion;
use App\Models\Jefe;
use App\Models\MateriaCarrera;
use App\Models\Parametro;
use App\Models\PeriodoEscolar;
use App\Models\Personal;
use App\Models\PlanDeEstudio;
use App\Models\SeleccionMateria;
use App\Models\TiposIngreso;
use Carbon\Carbon;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use IntlDateFormatter;
use PDF;

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

    public function imprimirboleta(Request $request)
    {
        $control = $request->control;
        $periodo = $request->periodo;
        $alumno = Alumno::findOrfail($control);
        $cal_periodo = (new AccionesController)->boleta($control, $periodo);
        $nombre_periodo = PeriodoEscolar::where('periodo', $periodo)->first();
        if(Jefe::where('clave_area','120600')->count()>0){
            $jefatura=Jefe::where('clave_area','120600')->first();
            $cargo=$jefatura->descripcion_area;
            $jefe=Personal::where('id',$jefatura->id_jefe)->first();
            $nombre_jefe=$jefe->siglas.' '.$jefe->nombre_empleado.' '.$jefe->apellido_paterno.' '.$jefe->apellido_materno;
        }else{
            $cargo="SERVICIOS ESCOLARES";
            $nombre_jefe='';
        }
        $fmt1=new IntlDateFormatter(
            'es_ES',
            IntlDateFormatter::SHORT,
            0,
            'America/Tijuana',
            1,
            "dd/MMMM/YYYY",
        );
        $fecha=$fmt1->format(time());
        $data = [
            'alumno' => $alumno,
            'cal_periodo' => $cal_periodo,
            'nombre_periodo' => $nombre_periodo,
            'periodo' => $periodo,
            'cargo'=>$cargo,
            'nombre_jefe'=>$nombre_jefe,
            'fecha'=>$fecha,
        ];
        $pdf = PDF::loadView('escolares.pdf_boleta', $data)
            ->setPaper('Letter');
        return $pdf->download('boleta.pdf');
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

    public function alta()
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
        }
        $mensaje = "El último número de control asignado en " . $nperiodo->identificacion_corta . " fue " . $ultimo;
        $periodos = PeriodoEscolar::orderBy('periodo', 'desc')->get();
        $carreras = Carrera::orderBy('nombre_reducido')->get();
        $planes = PlanDeEstudio::all();
        $tipos_ingreso = TiposIngreso::all();
        $encabezado="Alta de estudiante al sistema";
        return view('escolares.nuevo_alumno')->with(compact('estados', 'periodo',
            'mensaje', 'periodos', 'carreras', 'planes','tipos_ingreso','encabezado'));
    }
    public function alta_nuevo(Request $request)
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
            $datos_tec=Parametro::where('id',1)->first();
            $ciudad=$datos_tec->ciudad;
            $tec=$datos_tec->tec;
            $data = [
                'appat' => $appat,
                'apmat' => $apmat,
                'nombre' => $nombre,
                'control' => $control,
                'ncarrera' => $ncarrera,
                'nip' => $nip,
                'ciudad'=>$ciudad,
                'tec'=>$tec,
            ];
            $pdf = PDF::loadView('escolares.pdf_nuevo', $data)
                ->setPaper('Letter');
            return $pdf->download('alta.pdf');
        }
    }
    public function accion_re(Request $request)
    {
        $periodo = $request->get('periodo');
        $carrera = $request->get('carrera');
        $accion = $request->get('accion');
        if ($accion == 1) {
            $this->fechas_reinscripcion($carrera,$periodo);
        } elseif ($accion == 2) {
            $this->crear_reinscripcion($periodo,$carrera);
        } else {
            $this->listado_reinscripcion($periodo,$carrera);
        }
    }
}
