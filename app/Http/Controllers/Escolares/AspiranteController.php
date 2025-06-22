<?php

namespace App\Http\Controllers\Escolares;

use App\Http\Controllers\Acciones\AccionesController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MenuEscolaresController;
use App\Models\Carrera;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Http\Request;
use App\Models\PeriodoEscolar;
use App\Models\PeriodoFicha;
use App\Models\Aspirante;

class AspiranteController extends Controller
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
        $parametros=PeriodoFicha::where('activo',true)->first();
        $periodo_ficha=$parametros->fichas;
        $periodos = PeriodoEscolar::orderBy('periodo', 'DESC')->get();
        $encabezado="Aspirantes nuevo ingreso";
        return view('escolares.fichas_periodo')->with(compact('encabezado',
            'periodos','periodo_ficha'));
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
    public function show(int $ficha)
    {
        $aspirante=(new AccionesController)->ficha_datos($ficha)[0];
        $carreras=Carrera::where('ofertar','=',true)
            ->select(['nombre_carrera','carrera'])
            ->orderBy('carrera','ASC')
            ->get();
        $documentos=(new AccionesController)->documentos_aspirante($ficha)[0];
        $encabezado="Datos del aspirante a ingresar";
        return view('escolares.fichas_informacion_aspirante')
            ->with(compact('aspirante','carreras','encabezado','documentos'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $ficha)
    {
        Aspirante::where('aspirante_id',$ficha)
            ->update(
                [
                    'nombre'=>$request->nombre_aspirante,
                    'apellido_paterno'=>$request->apellido_paterno,
                    'apellido_materno'=>$request->apellido_materno,
                    'carrera'=>$request->carrera,
                    'curp'=>$request->curp
                ]
            );
        $aspirante=(new AccionesController)->ficha_datos($ficha)[0];
        $carreras=Carrera::where('ofertar','=',true)
            ->select(['nombre_carrera','carrera'])
            ->orderBy('carrera','ASC')
            ->get();
        $documentos=(new AccionesController)->documentos_aspirante($ficha)[0];
        $encabezado="Datos actualizados del aspirante";
        return view('escolares.fichas_informacion_aspirante')
            ->with(compact('aspirante','carreras','encabezado','documentos'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Listado completo de fichas basándonos en el período seleccionado
     */
    public function listado(Request $request)
    {
        $periodo_ficha=$request->get('periodo');
        $datos_periodo=PeriodoEscolar::where('periodo',$periodo_ficha)->first();
        $datos=(new AccionesController)->listado_aspirantes($periodo_ficha,'T');
        $encabezado="Listado de Aspirantes para el período ".$datos_periodo->identificacion_corta;
        return view('escolares.fichas_listado_completo')
            ->with(compact('datos','encabezado'));
    }
}
