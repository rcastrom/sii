<?php

namespace App\Http\Controllers\Desarrollo;

use App\Http\Controllers\Acciones\AccionesController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MenuDesarrolloController;
use App\Models\ParametroExamenAdmision;
use App\Models\Carrera;
use Carbon\Carbon;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Http\Request;

class FechasExamenController extends Controller
{
    public function __construct(Dispatcher $events)
    {
        new MenuDesarrolloController($events);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $datos_periodo = (new AccionesController)->periodo_entrega_fichas();
        $nombre_periodo=$datos_periodo->identificacion_corta;
        $periodo = $datos_periodo->periodo;
        $bandera=ParametroExamenAdmision::where('periodo',$periodo)->count() > 0?1:0;
        $carreras = Carrera::where('ofertar', 1)
            ->where('nivel_escolar', 'L')
            ->orderBy('nombre_carrera', 'ASC')
            ->orderBy('reticula', 'ASC')
            ->get();
        $encabezado = 'Aspirantes a ingresar para el período '.$nombre_periodo;
        if ($bandera) {
            $registros = ParametroExamenAdmision::select(['parametros_fichas_examen.*', 'carreras.*'])
                ->join('carreras', 'parametros_fichas_examen.carrera', '=', 'carreras.carrera')
                ->where('periodo', $periodo)
                ->where('carreras.ofertar', '1')
                ->orderBy('carreras.nombre_reducido')
                ->get();
        } else {
            $registros = '';
        }
        return view('desarrollo.fichas_examen')
            ->with(compact(  'carreras', 'encabezado','bandera','registros','periodo'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        request()->validate([
            'fecha'=>'required',
            'hora'=>'required',
            'indicaciones'=>'required',
        ],[
            'fecha.required'=>'Debe indicar la fecha de aplicación del examen',
            'hora.required'=>'Debe indicar la hora de aplicación del examen',
            'indicaciones.required'=>'Debe señalar información que se imprimirá en la ficha, respecto al examen'
        ]);
        $fecha=new ParametroExamenAdmision();
        $fecha->periodo=$request->get('periodo');
        $fecha->carrera=$request->get('carrera');
        $fecha->fecha=Carbon::parse($request->get('fecha'))->format('Y-m-d');
        $fecha->hora=Carbon::parse($request->get('hora'))->format('H:i');
        $fecha->indicaciones=$request->get('indicaciones');
        $fecha->save();
        return redirect()->route('fechas.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(ParametroExamenAdmision $fecha)
    {
        $datos_periodo=(new AccionesController)->periodo_entrega_fichas();
        $nombre_periodo=$datos_periodo->identificacion_corta;
        $carrera = Carrera::where('ofertar',1)
            ->where('carrera',$fecha->carrera)
            ->first();
        $encabezado="Eliminar fecha registrada para examen de admisión";
        return view('desarrollo.fichas_examen_eliminar')
            ->with(compact('nombre_periodo','fecha',
                'encabezado','carrera'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ParametroExamenAdmision $fecha)
    {
        $datos_periodo = (new AccionesController)->periodo_entrega_fichas();
        $nombre_periodo=$datos_periodo->identificacion_corta;
        $periodo = $datos_periodo->periodo;
        $carreras = Carrera::where('ofertar', 1)
            ->where('nivel_escolar', 'L')
            ->orderBy('nombre_carrera', 'ASC')
            ->orderBy('reticula', 'ASC')
            ->get();
        $encabezado = 'Aspirantes a ingresar para el período '.$nombre_periodo;
        return view('desarrollo.fichas_examen_editar')
            ->with(compact(  'carreras', 'encabezado','fecha','periodo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ParametroExamenAdmision $fecha)
    {
        request()->validate([
            'fecha'=>'required',
            'hora'=>'required',
            'indicaciones'=>'required',
        ],[
            'fecha.required'=>'Debe indicar la fecha de aplicación del examen',
            'hora.required'=>'Debe indicar la hora de aplicación del examen',
            'indicaciones.required'=>'Debe señalar información que se imprimirá en la ficha, respecto al examen'
        ]);
        $fecha->update([
            'fecha'=>Carbon::parse($request->get('fecha'))->format('Y-m-d'),
            'hora'=>Carbon::parse($request->get('hora'))->format('H:i'),
            'indicaciones'=>trim($request->get('indicaciones')),
            'carrera'=>$request->get('carrera')
        ]);
        return redirect()->route('fechas.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ParametroExamenAdmision $fecha)
    {
       $fecha->delete();
       return redirect()->route('fechas.index');
    }
}
