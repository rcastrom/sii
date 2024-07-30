<?php

namespace App\Http\Controllers\Escolares;

use App\Http\Controllers\Acciones\AccionesController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MenuEscolaresController;
use App\Models\Alumno;
use App\Models\EstatusAlumno;
use App\Models\HistoriaAlumno;
use App\Models\Materia;
use App\Models\PeriodoEscolar;
use App\Models\TipoEvaluacion;
use Carbon\Carbon;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KardexController extends Controller
{
    public function __construct(Dispatcher $events)
    {
        new MenuEscolaresController($events);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $encabezado="Kárdex del estudiante";
        $encabezado2="Acciones adicionales para el kárdex";
        $control=$request->get('control');
        $alumno = Alumno::where('no_de_control',$control)->first();
        $informacion = (new AccionesController)->kardex($control);
        $ncarrera = (new AccionesController)->ncarrera($alumno->carrera,$alumno->reticula);
        $estatus = EstatusAlumno::where('estatus', $alumno->estatus_alumno)->first();
        $calificaciones=$informacion[0];
        $nombre_periodo=$informacion[1];
        return view('escolares.kardex')
            ->with(compact('alumno', 'calificaciones', 'estatus',
                'ncarrera','control','encabezado','nombre_periodo','encabezado2'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $control=$request->get('control');
        $alumno = Alumno::where('no_de_control',$control)->first();
        $encabezado="Alta de materia en kardex";
        $carga_acad =(new AccionesController)->cmaterias($control);
        $periodos = PeriodoEscolar::orderBy('periodo', 'DESC')->get();
        $tipo_ev = TipoEvaluacion::where('plan_de_estudios', $alumno->plan_de_estudios)->get();
        return view('escolares.akardex')
            ->with(compact('alumno', 'periodos', 'carga_acad',
                'tipo_ev', 'control','encabezado'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        request()->validate([
            'calif' => 'required',
        ], [
            'calif.required' => 'Debe indicar una calificación'
        ]);
        $control = $request->get('control');
        $materia = $request->get('alta');
        $calif = $request->get('calif');
        $periodo = $request->get('nper');
        $tipo_ev = $request->get('tipo_e');
        $alumno = Alumno::where('no_de_control',$control)->first();
        if (HistoriaAlumno::where([
                'no_de_control'=>$control,
                'materia'=>$materia,
                'periodo'=>$periodo
            ])->count() > 0) {
            $mensaje = "Ya está registrado el dato en el kardex del estudiante";
            $encabezado="Error de alta en materia";
            return view('escolares.no')
                ->with(compact('mensaje','encabezado'));
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
            return view('escolares.si')
                ->with(compact('encabezado','mensaje'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(HistoriaAlumno $kardex)
    {
        $alumno=Alumno::where('no_de_control',$kardex->no_de_control)->first();
        $materia=Materia::where('materia',$kardex->materia)->first();
        $periodo=PeriodoEscolar::where('periodo',$kardex->periodo)->first();
        $encabezado="Corrobore";
        return view('escolares.eliminar_materia_kardex')
            ->with(compact('alumno','materia',
                'periodo','encabezado','kardex'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HistoriaAlumno $historiaAlumno)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HistoriaAlumno $historiaAlumno)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HistoriaAlumno $kardex)
    {
       $id=$kardex->id;
       HistoriaAlumno::where('id',$id)->delete();
       $encabezado="Baja de materia";
       $mensaje="La materia ha sido eliminada";
       return view('escolares.si')
           ->with(compact('mensaje','encabezado'));
    }
}
