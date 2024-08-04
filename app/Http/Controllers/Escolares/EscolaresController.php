<?php

namespace App\Http\Controllers\Escolares;

use App\Models\Alumno;
use App\Models\Carrera;
use App\Models\Grupo;
use App\Models\Idioma;
use App\Models\IdiomasGrupo;
use App\Models\IdiomasLiberacion;
use App\Models\Materia;
use App\Models\MateriaCarrera;
use App\Models\Organigrama;
use App\Models\PeriodoEscolar;
use App\Models\Especialidad;
use App\Models\Personal;
use App\Models\TipoEvaluacion;
use App\Models\HistoriaAlumno;
use App\Models\SeleccionMateria;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\MenuEscolaresController;
use Illuminate\Contracts\Events\Dispatcher;
use App\Http\Controllers\Acciones\AccionesController;
use Illuminate\Support\Facades\Auth;
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

    public function modificar_periodo(): Factory|View|Application
    {
        $periodos = PeriodoEscolar::select('periodo','identificacion_corta')
            ->orderBy('periodo','DESC')->get();
        $periodo_actual = (new AccionesController)->periodo();
        $encabezado="Modificar periodo escolar";
        return view('escolares.periodo_mod', compact('periodos',
            'periodo_actual','encabezado'));
    }

    public function mostrar_periodo(Request $request): Factory|View|Application
    {
        $encabezado="Modificación de período escolar";
        $periodo_leer = $request->get('periodo');
        $periodo = PeriodoEscolar::where('periodo', $periodo_leer)->first();
        return view('escolares.modificar_periodo')
            ->with(compact('periodo', 'encabezado'));
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
            ->orderBy('apellidos_empleado', 'ASC')
            ->orderBy('nombre_empleado', 'ASC')
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
            ->orderBy('nombre_abreviado_materia', 'ASC')
            ->get();
        $ndocente = Personal::where('rfc', $docente)->first();
        $nperiodo = PeriodoEscolar::where('periodo', $periodo)->first();
        $encabezado="Entrega de actas del período por el docente a Escolares";
        return view('escolares.periodo_actas3')->with(compact('periodo',
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
                $asignar= $value==1;
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
            ->orderBy('apellido_paterno', 'ASC')
            ->orderBy('apellido_materno', 'ASC')
            ->orderBy('nombre_alumno', 'ASC')
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
                    ->orderBy('apellido_paterno', 'ASC')
                    ->orderBy('apellido_materno', 'ASC')
                    ->orderBy('nombre_alumno', 'ASC')
                    ->get();
                $datos_grupo = Grupo::where('periodo', $periodo)
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
                    'datos' => $datos_grupo,
                    'nmateria' => $nombre_mat,
                    'materia' => $materia,
                    'grupo' => $grupo
                ];
                $pdf = PDF::loadView('escolares.pdf_acta', $data);
            } else {
                $inscritos = SeleccionMateria::where('periodo', $periodo)
                    ->where('materia', $materia)
                    ->where('grupo', $grupo)
                    ->join('alumnos', 'seleccion_materias.no_de_control', '=', 'alumnos.no_de_control')
                    ->orderBy('apellido_paterno', 'ASC')
                    ->orderBy('apellido_materno', 'ASC')
                    ->orderBy('nombre_alumno', 'ASC')
                    ->get();
                $datos_grupo= Grupo::where('periodo', $periodo)
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
                    'datos' => $datos_grupo,
                    'nmateria' => $nombre_mat,
                    'materia' => $materia,
                    'grupo' => $grupo
                ];
                $pdf = PDF::loadView('escolares.pdf_acta2', $data)
                    ->setPaper('Letter');
            }
            return $pdf->download('acta.pdf');
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
        $resultado=NULL;
        $encabezado=NULL;
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
            $espe = Especialidad::where('carrera', $carrera)
                ->where('reticula', $reticula)
                ->get();
            $materias = MateriaCarrera::where('carrera', $carrera)->where('reticula', $reticula)
                ->join('materias', 'materias_carreras.materia', '=', 'materias.materia')
                ->whereNull('especialidad')
                ->select('materias_carreras.materia as matteria', 'nombre_abreviado_materia',
                    'creditos_materia', 'horas_teoricas', 'horas_practicas', 'semestre_reticula', 'renglon')
                ->get();
            $ncarrera = Carrera::where('carrera', $carrera)
                ->where('reticula', $reticula)
                ->first();
            return view('escolares.materia_nueva')->with(compact('carrera',
                'reticula', 'acad', 'espe', 'materias', 'ncarrera','encabezado'));
        }elseif ($accion==2){
            $accion="FALTA POR PROGRAMAR";
        }else {
            $encabezado="Vista retícula de la carrera con especialidad";
            $espe = Especialidad::where('carrera', $carrera)
                ->where('reticula', $reticula)
                ->get();
            $ncarrera = Carrera::where('carrera', $carrera)
                ->where('reticula', $reticula)
                ->first();
            return view('escolares.reticulas')
                ->with(compact(
                    'carrera', 'reticula', 'espe',
                    'ncarrera','encabezado'));
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
        $array_reticula=NULL;
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
        $mensaje='';
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
            'contra.same' => 'No concuerda con la verificación',
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
