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
use App\Models\Preficha;

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
        $identificador=$request->get("identificador");
        $datos_aspirante=(new AccionesController)->ficha_datos($identificador)[0];
        if(Aspirante::where([
            'periodo'=>$datos_aspirante->periodo,
            'ficha'=>$datos_aspirante->ficha,
        ])->count()>0){
            $mensaje="Ya existe un registro previo del aspirante.";
            $encabezado="Nueva ficha";
            return view('escolares.no')->with(compact('mensaje','encabezado'));
        }
        if($request->pago_ficha){
            $aspirante = new Aspirante();
            $aspirante->periodo=$datos_aspirante->periodo;
            $aspirante->ficha=$datos_aspirante->ficha;
            $aspirante->apellido_paterno=$datos_aspirante->apellido_paterno_aspirante;
            $aspirante->apellido_materno=$datos_aspirante->apellido_materno_aspirante;
            $aspirante->nombre_aspirante=$datos_aspirante->nombre_aspirante;
            $aspirante->fecha_nacimiento=$datos_aspirante->fecha_nacimiento;
            $aspirante->sexo=$datos_aspirante->sexo;
            $aspirante->pais=$datos_aspirante->pais;
            $aspirante->carrera=$datos_aspirante->carrera;
            foreach ($request->get('documentos') as $key => $value) {
                $aspirante[addslashes($value)]=1;
            }
            $aspirante->migratorio=$request->get('migratorio');
            $aspirante->pago_ficha=$request->get('pago_ficha');
            $aspirante->save();
            (new AccionesController)->pago_ficha($identificador);
            $encabezado="Ficha generada";
            $mensaje="Se generó la ficha correspondiente";
            return view('escolares.ficha_generada')
                ->with(compact('encabezado','mensaje','identificador'));
        }else{
            $mensaje="No se dió de alta la ficha al no contar con el pago correspondiente";
            $encabezado="Nueva ficha";
            return view('escolares.no')->with(compact('mensaje','encabezado'));
        }
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
            ->with(compact('aspirante','carreras','encabezado','documentos','ficha'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $ficha)
    {
        $aspirante=(new AccionesController)->ficha_datos($ficha)[0];
        $documentos=(new AccionesController)->documentos_aspirante($ficha)[0];
        $documentos_capturados=Aspirante::where(
            [
                'ficha'=>$aspirante->ficha,
                'periodo'=>$aspirante->periodo
            ]
        )->select(['cert_prepa','const_terminacion','acta_nacimiento','curp','nss','migratorio'])
            ->first();
        $carrera_aspirante=Carrera::where(
            [
                'ofertar'=>true,
                'carrera'=>$aspirante->carrera,
            ]
        )->select('nombre_carrera')->first();
        $periodo_aspirante=PeriodoEscolar::where('periodo',$aspirante->periodo)->first();
        $encabezado="Datos del aspirante a ingresar en el período ".$periodo_aspirante->identificacion_corta;
        return view('escolares.fichas_documentos_aspirante')
            ->with(compact('encabezado','aspirante','documentos',
                'carrera_aspirante','documentos_capturados','ficha'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $ficha)
    {
        Preficha::where('aspirante_id',$ficha)
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
    public function destroy(int $ficha)
    {

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
            ->with(compact('datos','encabezado','datos_periodo'));
    }

    public function estadistica()
    {
        $parametros=PeriodoFicha::where('activo',true)->first();
        $periodo_ficha=$parametros->fichas;
        $periodos = PeriodoEscolar::orderBy('periodo', 'DESC')->get();
        $encabezado="Aspirantes nuevo ingreso";
        return view('escolares.fichas_periodo_estadistica')->with(compact('encabezado',
            'periodos','periodo_ficha'));
    }

    public function actualizar_documentos(Request $request, int $ficha)
    {
        $aspirante=(new AccionesController)->ficha_datos($ficha)[0];
        Aspirante::where([
            'ficha'=>$aspirante->ficha,
            'periodo'=>$aspirante->periodo
        ])->update(
            [
                'cert_prepa'=>false,
                'const_terminacion'=>false,
                'acta_nacimiento'=>false,
                'curp'=>false,
                'nss'=>false,
            ]
        );
        $registro=Aspirante::where(
            [
                'ficha'=>$aspirante->ficha,
                'periodo'=>$aspirante->periodo
            ]
        )->first();
        foreach ($request->get('documentos') as $key => $value) {
            $registro[addslashes($value)]=1;
        }
        $registro->update();
        $encabezado="Documentos del aspirante actualizados";
        $mensaje="Se actualizó la información de los documentos que el aspirante ha entregado a Servicios Escolares";
        return view('escolares.si')->with(compact('encabezado','mensaje'));
    }

}
