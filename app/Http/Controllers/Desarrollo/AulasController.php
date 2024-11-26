<?php

namespace App\Http\Controllers\Desarrollo;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MenuDesarrolloController;
use App\Models\AulaAspirante;
use App\Models\Aula;
use App\Http\Controllers\Acciones\AccionesController;
use App\Models\Carrera;
use App\Models\PeriodoEscolar;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Http\Request;

class AulasController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(AulaAspirante $aula)
    {
        $datos_periodo=(new AccionesController())->periodo_entrega_fichas();
        $periodo=$datos_periodo->periodo;
        $nperiodo=PeriodoEscolar::where('periodo',$periodo)->first();
        $salon=Aula::where('id',$aula->aula)->first();
        $carrera = Carrera::where('ofertar',1)
            ->where('carrera',$aula->carrera)
            ->first();
        $encabezado="Eliminar aula para examen de admisión";
        return view('desarrollo.fichas_aulas_eliminar')
            ->with(compact('nperiodo','aula',
                'encabezado','salon','carrera'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AulaAspirante $aula)
    {
        $datos_periodo=(new AccionesController())->periodo_entrega_fichas();
        $periodo=$datos_periodo->periodo;
        $nperiodo=PeriodoEscolar::where('periodo',$periodo)->first();
        $salones=Aula::where('estatus',1)->get();
        $carreras = Carrera::where('ofertar',1)
            ->where('nivel_escolar','L')
            ->orderBy('nombre_carrera','ASC')
            ->orderBy('reticula','ASC')
            ->get();
        $encabezado="Modificar datos de aula para examen de admisión";
        return view('desarrollo.fichas_aulas_editar')
            ->with(compact('nperiodo','aula',
                'encabezado','salones','carreras'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AulaAspirante $aula)
    {
        $cambio=$request->get('disponibles')+($request->get('cupo')-$request->get('capacidad'));
        $aula->update([
            'carrera'=>$request->get('carrera'),
            'capacidad'=>$request->get('cupo'),
            'disponibles'=>$cambio
        ]);
        return redirect()->route('inicio_desarrollo');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AulaAspirante $aula)
    {
        $aula->delete();
        return redirect()->route('inicio_desarrollo');
    }
}
