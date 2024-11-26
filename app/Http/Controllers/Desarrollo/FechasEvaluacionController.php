<?php

namespace App\Http\Controllers\Desarrollo;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MenuDesarrolloController;
use App\Models\FechaEvaluacion;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Http\Request;

class FechasEvaluacionController extends Controller
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
        //
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
        $fecha_evaluacion = new FechaEvaluacion();
        $fecha_evaluacion->periodo=$request->get('periodo');
        $fecha_evaluacion->encuesta=$request->get('encuesta');
        $fecha_evaluacion->fecha_inicio=$request->get('inicio');
        $fecha_evaluacion->fecha_final=$request->get('final');
        $fecha_evaluacion->save();
        return redirect()->route('inicio_desarrollo');
    }

    /**
     * Display the specified resource.
     */
    public function show(FechaEvaluacion $fechaEvaluacion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FechaEvaluacion $fechaEvaluacion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FechaEvaluacion $periodo)
    {
        $periodo->update([
            'encuesta'=>$request->get('encuesta'),
            'fecha_inicio'=>$request->get('inicio'),
            'fecha_final'=>$request->get('final')
        ]);
        return redirect()->route('inicio_desarrollo');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FechaEvaluacion $fechaEvaluacion)
    {
        //
    }
}
