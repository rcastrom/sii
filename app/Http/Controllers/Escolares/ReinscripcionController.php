<?php

namespace App\Http\Controllers\Escolares;

use App\Http\Controllers\Acciones\AccionesController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MenuEscolaresController;
use App\Models\AcumuladoHistorico;
use App\Models\AvisoReinscripcion;
use App\Models\Carrera;
use App\Models\FechasCarrera;
use App\Models\GenerarListasTemporal;
use App\Models\PeriodoEscolar;
use App\Models\SeleccionMateria;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class ReinscripcionController extends Controller
{
    public function __construct(Dispatcher $events)
    {
        new MenuEscolaresController($events);
    }

    public function reinscripcion(): Factory|View|Application
    {
        $encabezado="Parámetros para reinscripción";
        $periodo_actual = (new AccionesController)->periodo();
        $periodos = PeriodoEscolar::orderBy('periodo','desc')->get();
        $carreras = Carrera::distinct('carrera')->orderBy('carrera', 'ASC')
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
            $this->fechas_reinscripcion($carrera,$periodo);
        } elseif ($accion == 2) {
            $this->crear_reinscripcion($periodo,$carrera);
        } else {
            $this->listado_reinscripcion($periodo,$carrera);
        }
    }
    public function fechas_reinscripcion($carrera,$periodo):Factory|View|Application
    {
        if (FechasCarrera::where('carrera', $carrera)
                ->where('periodo', $periodo)
                ->count() > 0) {
            $encabezado="Error en selección de carrera";
            $mensaje = "Ya registró una fecha para la reinscripción de la carrera";
            return view('escolares.no')
                ->with(compact('mensaje','encabezado'));
        } else {
            $encabezado="Horario para reinscripción por carrera";
            $nperiodo = PeriodoEscolar::where('periodo', $periodo)->first();
            $ncarrera = Carrera::where('carrera', $carrera)
                ->select('nombre_reducido')->first();
            return view('escolares.fechas_re')
                ->with(compact('periodo', 'carrera',
                'nperiodo', 'ncarrera','encabezado'));
        }
    }
    public function crear_reinscripcion($periodo,$carrera): RedirectResponse
    {
        $anio_extraido = (int) substr($periodo, 0, 4);
        $numero_periodo = in_array(substr($periodo, 4, 1) ,['3', '2']) ? '1' : '3';
        $anio = $numero_periodo == '1' ? $anio_extraido : $anio_extraido - 1;
        $periodo_anterior = $anio . $numero_periodo;
        $horas='';
        if (FechasCarrera::where('periodo', $periodo)
                ->where('carrera', $carrera)
                ->count() > 0) {
            $valores = FechasCarrera::where('periodo', $periodo)
                ->where('carrera', $carrera)
                ->first();
            $fecha = $valores->fecha_inscripcion;
            $hora_inicio = $valores->fecha_inicio;
            $hora_fin = $valores->fecha_fin;
            $intervalo = $valores->intervalo;
            $personas = $valores->personas;
            $hora_inicio = substr($hora_inicio, 0, 2);
            if (str_starts_with($hora_inicio, "0")) {
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
            $avisos =DB::table('avisos_reinscripcion as AR')
                ->where('periodo', $periodo)
                ->join('alumnos as A', 'A.no_de_control', '=', 'AR.no_de_control')
                ->where('A.estatus_alumno', 'ACT')
                ->where('carrera', $carrera)
                ->select('AR.no_de_control', 'A.apellido_paterno', 'A.apellido_materno', 'A.nombre_alumno', 'A.semestre', 'AR.fecha_hora_seleccion')
                ->orderBy('A.semestre', 'ASC')
                ->get();
            foreach ($avisos as $seleccion) {
                if (SeleccionMateria::where('no_de_control', $seleccion->no_de_control)
                        ->where('periodo', $periodo_anterior)
                        ->join('materias', 'materias.materia', '=', 'seleccion_materias.materia')
                        ->where('nombre_completo_materia', 'LIKE', "%RESIDENCIA%")
                        ->count() == 0) {
                    $consultar_promedio = AcumuladoHistorico::where('periodo', $periodo_anterior)
                        ->where('no_de_control', $seleccion->no_de_control)
                        ->select('promedio_ponderado')
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
                }
            }
            $consulta = GenerarListasTemporal::orderBy('semestre', 'ASC')
                ->orderBy('promedio_ponderado', 'DESC')
                ->get();
            foreach ($consulta as $resultado) {
                $fecha_asig = $fecha . " " . $horas[$p];
                if ($hora_puesta < $personas) {
                    $hora_puesta++;
                } else {
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
            return redirect()->route('escolares.reinscripcion');
        }else{
            $this->error_reinscripcion_fecha();
        }
        return redirect()->route('inicio_escolares');
    }
    public function error_reinscripcion_fecha():Factory|View|Application
    {
        $encabezado="Error de parámetro de reinscripción";
        $mensaje = "No ha indicado la fecha de reinscripción para la carrera";
        return view('escolares.no')
            ->with(compact('mensaje','encabezado'));
    }
    public function listado_reinscripcion($periodo,$carrera)
    {
        $avisos = DB::table('avisos_reinscripcion as AR')
            ->where('periodo', $periodo)
            ->join('alumnos as A', 'A.no_de_control', '=', 'AR.no_de_control')
            ->where('A.estatus_alumno', 'ACT')
            ->where('carrera', $carrera)
            ->whereNotNull('AR.fecha_hora_seleccion')
            ->select('AR.no_de_control', 'A.apellido_paterno', 'A.apellido_materno', 'A.nombre_alumno', 'A.semestre', 'AR.fecha_hora_seleccion')
            ->orderBy('A.semestre', 'ASC')
            ->orderBy('A.apellido_paterno', 'ASC')
            ->orderBy('A.apellido_materno', 'ASC')
            ->orderBy('A.no_de_control', 'ASC')
            ->get();
        $nperiodo = PeriodoEscolar::where('periodo', $periodo)
            ->select('identificacion_corta')
            ->first();
        $ncarrera = Carrera::where('carrera', $carrera)
            ->select('nombre_reducido')
            ->first();
        $data = [
            'alumnos' => $avisos,
            'nperiodo' => $nperiodo,
            'ncarrera' => $ncarrera
        ];
        $pdf = PDF::loadView('escolares.pdf_listado', $data)
            ->setPaper('Letter');
        return $pdf->download('listado.pdf');
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
        return redirect()->route('escolares.reinscripcion');
    }
}
