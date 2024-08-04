<?php

namespace App\Http\Controllers\Escolares;

use App\Http\Controllers\Acciones\AccionesController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MenuEscolaresController;
use App\Models\PeriodoEscolar;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Http\Request;

class PeriodoEscolarController extends Controller
{
    public function __construct(Dispatcher $events)
    {
        new MenuEscolaresController($events);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $encabezado="Modificación de período escolar";
        $periodo_actual = (new AccionesController)->periodo();
        $periodos = PeriodoEscolar::orderBy('periodo','desc')->get();
        return view('escolares.periodo_mod')
            ->with(compact('periodo_actual', 'periodos','encabezado'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $yr = date('Y');
        $encabezado="Alta de período escolar";
        return view('escolares.periodos')
            ->with(compact('yr','encabezado'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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
        $id_largo = '';
        $id_corto = '';
        if (PeriodoEscolar::where('periodo', $periodo)->count() > 0) {
            $encabezado = "Error de creación de período";
            $mensaje = "No se puede crear el período porque ya existe en la base de datos";
            return view('escolares.no')->with(compact('mensaje', 'encabezado'));
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
            $finicio_ss1 = $request->get('finicio_ss');
            $ftermino_ss1 = $request->get('ftermino_ss');
            $finicio_ss = empty($finicio_ss1) ? null : $finicio_ss1;
            $ftermino_ss = empty($ftermino_ss1) ? null : $ftermino_ss1;
            $nperiodo = new PeriodoEscolar();
            $nperiodo->periodo = $periodo;
            $nperiodo->identificacion_larga = $id_largo;
            $nperiodo->identificacion_corta = $id_corto;
            $nperiodo->fecha_inicio = $request->get('finicio');
            $nperiodo->fecha_termino = $request->get('ftermino');
            $nperiodo->inicio_vacacional_ss = $finicio_ss;
            $nperiodo->fin_vacacional_ss = $ftermino_ss;
            $nperiodo->inicio_especial = null;
            $nperiodo->fin_especial = null;
            $nperiodo->cierre_horarios = '1';
            $nperiodo->cierre_seleccion = '1';
            $nperiodo->inicio_sele_alumnos = $request->get('finicio_est');
            $nperiodo->fin_sele_alumnos = $request->get('ftermino_est');
            $nperiodo->inicio_vacacional = $request->get('finicio_vac');
            $nperiodo->termino_vacacional = $request->get('ftermino_vac');
            $nperiodo->inicio_cal_docentes = $request->get('finicio_cap');
            $nperiodo->fin_cal_docentes = $request->get('ftermino_cap');
            $nperiodo->cambio_carrera = 1;
            $nperiodo->save();
            $encabezado = "Creación de período escolar";
            $mensaje = 'El período ' . $id_corto . " fue creado en la base de datos";
            return view('escolares.si')->with(compact('encabezado', 'mensaje'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
       //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PeriodoEscolar $periodoEscolar)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PeriodoEscolar $periodo_escolar)
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
        $finicio_ss1 = $request->get('finicio_ss');
        $ftermino_ss1 = $request->get('ftermino_ss');
        $finicio_ss = empty($finicio_ss1) ? null : $finicio_ss1;
        $ftermino_ss = empty($ftermino_ss1) ? null : $ftermino_ss1;

        PeriodoEscolar::where('id', $periodo_escolar->id)
            ->update([
                'fecha_inicio' => $request->get('finicio'),
                'fecha_termino' => $request->get('ftermino'),
                'inicio_vacacional_ss' => $finicio_ss,
                'fin_vacacional_ss' => $ftermino_ss,
                'cierre_horarios' => $request->get('horarios'),
                'cierre_seleccion' => $request->get('seleccion'),
                'inicio_sele_alumnos' => $request->get('finicio_est'),
                'fin_sele_alumnos' => $request->get('ftermino_est'),
                'inicio_vacacional' => $request->get('finicio_vac'),
                'termino_vacacional' => $request->get('ftermino_vac'),
                'inicio_cal_docentes' => $request->get('finicio_cap'),
                'fin_cal_docentes' => $request->get('ftermino_cap'),
                'cambio_carrera' => $request->get('cambio_carrera'),
            ]);
        $encabezado="Actualización de período";
        $mensaje="Se actualizó la información del período en la base de datos ";
        return view('escolares.si')->with(compact('encabezado','mensaje'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PeriodoEscolar $periodoEscolar)
    {
        //
    }



}
