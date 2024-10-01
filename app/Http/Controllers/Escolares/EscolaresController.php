<?php

namespace App\Http\Controllers\Escolares;

use App\Models\Carrera;
use App\Models\Materia;
use App\Models\MateriaCarrera;
use App\Models\Organigrama;
use App\Models\PeriodoEscolar;
use App\Models\Especialidad;
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

    public function carreraAlta()
    {
        $cant = Carrera::where('nivel_escolar', 'L')->select('carrera')
            ->distinct('carrera')->count();
        $encabezado="Alta de carrera";
        return view('escolares.carrera_alta')->with(compact('cant','encabezado'));
    }
    public function carreraNueva(Request $request)
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
    public function especialidadAlta()
    {
        $carreras = Carrera::select('carrera', 'reticula', 'nombre_reducido')->orderBy('carrera')
            ->orderBy('reticula')->get();
        $encabezado="Alta de especialidades";
        return view('escolares.especialidad_alta')->with(compact('carreras','encabezado'));
    }
    public function especialidadNueva(Request $request)
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
            return view('escolares.no')
                ->with(compact('mensaje','encabezado'));
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
    public function materiaNueva()
    {
        $carreras = Carrera::select('carrera', 'reticula', 'nombre_reducido')
            ->orderBy('carrera')
            ->orderBy('reticula')->get();
        $encabezado="Materias - Carreras";
        return view('escolares.materias_alta')
            ->with(compact('carreras','encabezado'));
    }
    public function materiasAcciones(Request $request)
    {
        $accion = $request->get('accion');
        $carr = $request->get('carrera');
        $datos = explode("_", $carr);
        $carrera = trim($datos[0]);
        $reticula = $datos[1];
        $materias = MateriaCarrera::where('carrera', $carrera)->where('reticula', $reticula)
            ->join('materias', 'materias_carreras.materia', '=', 'materias.materia')
            ->whereNull('especialidad')
            ->select('materias_carreras.materia as materia', 'nombre_abreviado_materia',
                'creditos_materia', 'horas_teoricas', 'horas_practicas', 'semestre_reticula', 'renglon')
            ->orderBy('nombre_abreviado_materia')
            ->get();
        $acad = Organigrama::where('area_depende', 'like', '110%')
            ->where('clave_area', 'like', '%00')
            ->get();
        $espe = Especialidad::where('carrera', $carrera)
            ->where('reticula', $reticula)
            ->get();
        $ncarrera = Carrera::where('carrera', $carrera)
            ->where('reticula', $reticula)
            ->first();
        if ($accion == 1) {
            $encabezado="Alta de materia en carrera";
            return view('escolares.materia_nueva')->with(compact('carrera',
                'reticula', 'acad', 'espe', 'materias', 'ncarrera','encabezado'));
        }elseif ($accion==2){
            $encabezado="Modificar datos de materia";
            return view('escolares.materia_seleccionar')
                ->with(compact('carrera', 'materias','reticula', 'ncarrera','encabezado'));
        }else {
            $encabezado="Vista retícula de la carrera con especialidad";
            return view('escolares.reticulas')
                ->with(compact(
                    'carrera', 'reticula', 'espe',
                    'ncarrera','encabezado'));
        }
    }
    public function materiaAlta(Request $request)
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
        $espe = Especialidad::where('carrera', $info["carrera"])
            ->where('reticula', $info["reticula"])
            ->get();
        $materias = MateriaCarrera::where('carrera', $info["carrera"])->where('reticula', $info["reticula"])
            ->join('materias', 'materias_carreras.materia', '=', 'materias.materia')
            ->whereNull('especialidad')
            ->select('materias_carreras.materia as materia', 'nombre_abreviado_materia', 'creditos_materia',
                'horas_teoricas', 'horas_practicas', 'semestre_reticula', 'renglon')
            ->get();
        $carrera = $info["carrera"];
        $reticula = $info["reticula"];
        $ncarrera = Carrera::where('carrera', $info["carrera"])->where('reticula', $info["reticula"])->first();
        return view('escolares.materia_nueva')->with(compact('carrera',
            'reticula', 'acad', 'espe', 'materias', 'ncarrera'));
    }
    public function materiaEditar(Request $request)
    {
        $encabezado="Actualización de materia";
        $materia=$request->get('materia');
        $carrera=$request->get('carrera');
        $reticula=$request->get('reticula');
        $datos=MateriaCarrera::where('materias_carreras.materia', $materia)
            ->where('carrera', $request->get('carrera'))
            ->where('reticula', $request->get('reticula'))
            ->join('materias', 'materias_carreras.materia', '=', 'materias.materia')
            ->select('materias_carreras.creditos_materia', 'materias_carreras.horas_teoricas',
                'materias_carreras.horas_practicas', 'materias_carreras.orden_certificado',
                'materias_carreras.semestre_reticula', 'materias_carreras.creditos_prerrequisito',
                'materias_carreras.especialidad', 'materias_carreras.clave_oficial_materia',
                'materias_carreras.renglon', 'materias.nivel_escolar',
                'materias.clave_area', 'materias.nombre_completo_materia',
                'materias.nombre_abreviado_materia','materias.caracterizacion',
                'materias.generales')
            ->first();
        $acad = Organigrama::where('area_depende', 'like', '110%')
            ->where('clave_area', 'like', '%00')
            ->get();
        $espe = Especialidad::where('carrera', $carrera)
            ->where('reticula', $reticula)
            ->get();
        return view('escolares.materia_modificar')
            ->with(compact('encabezado','datos','materia','carrera',
                'reticula','acad','espe'));
    }
    public function materiaActualizar(Request $request)
    {
        request()->validate([
            'cve_of' => 'required',
            'nombre_completo' => 'required',
            'nombre_abrev' => 'required',
            'horas_teoricas' => 'required',
            'horas_practicas' => 'required',
            'creditos' => 'required',
            'certificado' => 'required'

        ], [
            'cve_of.required' => 'Debe escribir la clave oficial de la materia',
            'nombre_completo.required' => 'Debe indicar el nombre completo para la materia',
            'nombre_abrev.required' => 'Debe indicar el nombre corto para la materia',
            'horas_teoricas.required' => 'Debe indicar el número de horas teóricas de la materia',
            'horas_practicas.required' => 'Debe indicar el número de horas prácticas de la materia',
            'creditos.required' => 'Indique la cantidad de créditos para la materia',
            'certificado.required' => 'Indique la ubicación de la materia en el certificado'
        ]);
        $materia=$request->get('materia');
        $carrera=$request->get('carrera');
        $reticula=$request->get('reticula');
        MateriaCarrera::where('materia', $materia)
            ->where('carrera', $carrera)
            ->where('reticula', $reticula)
            ->update([
                'creditos_materia' => $request->get('creditos'),
                'horas_teoricas' => $request->get('horas_teoricas'),
                'horas_practicas' => $request->get('horas_practicas'),
                'orden_certificado' => $request->get('certificado'),
                'semestre_reticula' => $request->get('semestre'),
                'especialidad' => $request->get('especialidad')==0?null:$request->get('especialidad'),
                'clave_oficial_materia' => $request->get('cve_of'),
                'renglon' => $request->get('renglon')
            ]);
        Materia::where('materia', $materia)->update([
            'nivel_escolar' => $request->get('nivel'),
            'clave_area' => $request->get('area'),
            'nombre_completo_materia' => $request->get('nombre_completo'),
            'nombre_abreviado_materia' => $request->get('nombre_abrev'),
            'caracterizacion' => $request->get('caracterizacion'),
            'generales' => $request->get('generales')
        ]);
        $encabezado="Actualización de materia";
        $mensaje="Se llevó la actualización de los datos de la materia ".$materia;
        return view('escolares.si')->with(compact('encabezado','mensaje'));
    }
    public function vistaReticula(Request $request)
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
