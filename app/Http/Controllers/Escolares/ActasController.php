<?php

namespace App\Http\Controllers\Escolares;

use App\Http\Controllers\Acciones\AccionesController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MenuEscolaresController;
use App\Models\Grupo;
use App\Models\HistoriaAlumno;
use App\Models\Materia;
use App\Models\PeriodoEscolar;
use App\Models\Personal;
use App\Models\SeleccionMateria;
use App\Models\TipoEvaluacion;
use Carbon\Carbon;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use LaravelIdea\Helper\App\Models\_IH_SeleccionMateria_C;
use PDF;

class ActasController extends Controller
{
    public function __construct(Dispatcher $events)
    {
        new MenuEscolaresController($events);
    }

    public function docentes($periodo)
    {
        return Grupo::where('periodo', $periodo)
            ->join('personal', 'personal.id', '=', 'grupos.docente')
            ->select(['grupos.docente', 'personal.apellidos_empleado', 'personal.nombre_empleado'])
            ->distinct()
            ->orderBy('apellidos_empleado', 'ASC')
            ->orderBy('nombre_empleado', 'ASC')
            ->get();
    }

    public function grupos($periodo,$docente)
    {
        return Grupo::where('periodo', $periodo)
            ->where('docente', $docente)
            ->join('materias', 'materias.materia', '=', 'grupos.materia')
            ->select(['grupos.materia', 'grupo', 'nombre_abreviado_materia','entrego'])
            ->orderBy('nombre_abreviado_materia', 'ASC')
            ->get();
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
        $docentes = $this->docentes($periodo);
        $nperiodo = PeriodoEscolar::where('periodo', $periodo)->first();
        $encabezado="Entrega de actas del período por el docente a Escolares";
        return view('escolares.periodo_actas2')->with(compact('periodo', 'docentes',
            'nperiodo','encabezado'));
    }
    public function periodoactas3(Request $request)
    {
        $periodo = $request->get('periodo');
        $docente = $request->get('docente');
        $grupos = $this->grupos($periodo,$docente);
        $ndocente = Personal::where('id', $docente)->first();
        $nperiodo = PeriodoEscolar::where('periodo', $periodo)->first();
        $encabezado="Entrega de actas del período por el docente a Escolares";
        return view('escolares.periodo_actas3')->with(compact('periodo',
            'docente', 'nperiodo', 'grupos', 'ndocente','encabezado'));
    }
    public function periodoactas_m1()
    {
        $periodo_actual = (new AccionesController)->periodo();
        $periodos = PeriodoEscolar::orderBy('periodo','DESC')->get();
        $encabezado="Actas del período";
        return view('escolares.periodo_actas_1')
            ->with(compact('periodo_actual', 'periodos','encabezado'));
    }
    public function periodoactas_m2(Request $request)
    {
        $periodo = $request->get('periodo');
        $docentes = $this->docentes($periodo);
        $nperiodo = PeriodoEscolar::where('periodo', $periodo)->first();
        $encabezado="Actas del período";
        return view('escolares.periodo_actas_2')
            ->with(compact('periodo', 'docentes',
            'nperiodo','encabezado'));
    }
    public function periodoactas_m3(Request $request)
    {
        $periodo = $request->get('periodo');
        $docente = $request->get('docente');
        $grupos = $this->grupos($periodo,$docente);
        $ndocente = Personal::where('id', $docente)->first();
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
                $asignar= $value==1;
                Grupo::where([
                    'periodo'=>$periodo,
                    'docente'=>$docente,
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
    public function modificar_acta($per, $docente, $mat, $gpo)
    {
        $periodo=base64_decode($per); $docente=base64_decode($docente);
        $materia=base64_decode($mat); $grupo=base64_decode($gpo);
        $alumnos = SeleccionMateria::where('periodo', $periodo)
            ->where('materia', $materia)
            ->where('grupo', $grupo)
            ->join('alumnos', 'alumnos.no_de_control', '=', 'seleccion_materias.no_de_control')
            ->distinct()
            ->select(['seleccion_materias.no_de_control', 'apellido_paterno', 'apellido_materno',
                'nombre_alumno', 'calificacion', 'tipo_evaluacion', 'plan_de_estudios'])
            ->orderBy('apellido_paterno', 'ASC')
            ->orderBy('apellido_materno', 'ASC')
            ->orderBy('nombre_alumno', 'ASC')
            ->get();
        $ndocente = Personal::where('id', $docente)->first();
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
    public function actualizar_acta(Request $request)
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
            $op = "op_" . $control;
            $cal = $request->get($control);
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
    public function imprimir_acta($periodo, $docente, $materia, $grupo)
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
                $data = $this->getGrupo($periodo, $materia, $grupo, $docente, $inscritos);
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
                $data = $this->getGrupo($periodo, $materia, $grupo, $docente, $inscritos);
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

    /**
     * @param $periodo
     * @param $materia
     * @param $grupo
     * @param $doc
     * @param Collection|array|_IH_SeleccionMateria_C $inscritos
     * @return array
     */
    public function getGrupo($periodo, $materia, $grupo, $doc, Collection|array|_IH_SeleccionMateria_C $inscritos): array
    {
        $tec=$_ENV["NOMBRE_TEC"];
        $ciudad=$_ENV["CIUDAD_OFICIOS"];
        $logo_tecnm=$_ENV["RUTA_IMG_TECNM"];
        $logo_tec=$_ENV["RUTA_IMG_TECNOLOGICO"];
        $datos_grupo = Grupo::where('periodo', $periodo)
            ->where('materia', $materia)
            ->where('grupo', $grupo)
            ->first();
        $nombre_mat = Materia::where('materia', $materia)->first();
        $ndocente = Personal::where('id', $doc)
            ->select(['apellidos_empleado', 'nombre_empleado'])->first();
        $nperiodo = PeriodoEscolar::where('periodo', $periodo)->first();
        return [
            'tec' => $tec,
            'ciudad' => $ciudad,
            'logo_tecnm' => $logo_tecnm,
            'logo_tec' => $logo_tec,
            'alumnos' => $inscritos,
            'docente' => $ndocente,
            'nombre_periodo' => $nperiodo,
            'datos' => $datos_grupo,
            'nmateria' => $nombre_mat,
            'materia' => $materia,
            'grupo' => $grupo
        ];
    }
}
