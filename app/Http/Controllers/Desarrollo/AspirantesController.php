<?php

namespace App\Http\Controllers\Desarrollo;

use App\Http\Controllers\Acciones\AccionesController;
use App\Http\Controllers\Acciones\AspirantesNuevoIngresoController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MenuDesarrolloController;
use App\Models\Aspirante;
use App\Models\Carrera;
use App\Models\Grupo;
use App\Models\Materia;
use App\Models\MateriaCarrera;
use App\Models\PeriodoEscolar;
use App\Models\CarreraAspirante;
use App\Models\UsuarioAspirante;
use App\Models\FichaAspirante;
use Carbon\Carbon;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;


class AspirantesController extends Controller
{
    public function __construct(Dispatcher $events)
    {
        new MenuDesarrolloController($events);
    }

    public function estadistica(): Factory|View|Application
    {
        $periodos = PeriodoEscolar::select(['periodo', 'identificacion_corta'])
            ->orderBy('periodo', 'DESC')->get();
        $periodo_actual = (new AccionesController)->periodo_entrega_fichas();
        $encabezado = 'Periodo de búsqueda';

        return view('desarrollo.fichas_estadistica_periodo', compact('periodos',
            'periodo_actual', 'encabezado'));
    }

    public function fichas_concentrado_estadistico(Request $request): Factory|View|\Illuminate\Foundation\Application|\Illuminate\View\View
    {
        $periodo = $request->get('periodo');
        list($carreras_ofertar, $nombre_carreras) = (new AspirantesNuevoIngresoController)->carreras_por_ofertar();
        $listados=(new AccionesController)->concentrado_fichas($periodo,$carreras_ofertar,$nombre_carreras);
        return $this->extracted($periodo, $listados,2);
    }

    public function listado(): Factory|View|Application
    {
        list($periodos, $periodo_actual, $carreras) = $this->extracted1();
        $encabezado = 'Periodo de búsqueda';
        return view('desarrollo.periodo_ficha_busqueda', compact('periodos',
            'periodo_actual', 'carreras', 'encabezado'));
    }

    public function mostrar(Request $request): Factory|View|Application
    {
        $periodo = $request->get('periodo');
        $carrera_reticula = $request->get('carrera');
        $datos_carrera=explode("_", $carrera_reticula);
        $carrera=$datos_carrera[0];
        $listados=(new AccionesController)->listado_aspirantes($periodo,$carrera);
        return $this->extracted($periodo, $listados,1);
    }

    public function datos_aspirante($periodo,$aspirante): View|\Illuminate\Foundation\Application|Factory
    {
        $datos_aspirante=(new AccionesController)->ficha_datos($aspirante)[0];
        $nombre_periodo=PeriodoEscolar::where('periodo',$periodo)
            ->select('identificacion_corta')
            ->first();
        $encabezado="Datos del aspirante";
        $carreras = Carrera::select(['carrera', 'nombre_reducido'])
            ->where('nivel_escolar', '=', 'L')
            ->where('ofertar','=',1)
            ->orderBy('carrera')
            ->get();
        return view('desarrollo.datos_aspirante')->with(compact('datos_aspirante',
            'encabezado','aspirante', 'nombre_periodo','carreras','periodo'));
    }

    public function actualizar_datos_aspirante(Request $request): RedirectResponse
    {
        $aspirante=$request->get('aspirante');
        $carrera=$request->get('carrera');
        $periodo=$request->get('periodo');
        CarreraAspirante::where('aspirante_id',$aspirante)->update([
            'carrera'=>$carrera
        ]);
        return redirect()->route('desarrollo.datos_aspirante',["periodo" =>$periodo,"aspirante"=>$aspirante ]);
    }

    public function contra_aspirante(Request $request): RedirectResponse
    {
        request()->validate([
            'contra' => 'required|required_with:verifica|same:verifica',
            'verifica' => 'required',
        ], [
            'contra.required' => 'Debe escribir la nueva contraseña',
            'contra.required_with' => 'Debe confirmar la contraseña',
            'contra.same' => 'No concuerda con la verificación',
            'verifica.required' => 'Debe confirmar la nueva contraseña',
        ]);
        $aspirante=$request->get('aspirante');
        $periodo=$request->get('periodo');
        $ncontra = bcrypt($request->get('contra'));
        UsuarioAspirante::where('id',$aspirante)->update([
            'password' => $ncontra,
            'updated_at' => Carbon::now(),
        ]);
        return redirect()->route('desarrollo.datos_aspirante',["periodo" =>$periodo,"aspirante"=>$aspirante ]);
    }
    public function pago_aspirante(Request $request)
    {
        $aspirante=$request->get('aspirante');
        $periodo=$request->get('periodo');
        $pago=$request->get('pago');
        if($pago==1){
            FichaAspirante::where('aspirante',$aspirante)->update(['pago_ficha'=>1]);
        }elseif ($pago==2) {
            FichaAspirante::where('aspirante',$aspirante)->update(['pago_propedeutico'=>1]);
        }else{
            if(FichaAspirante::where(['aspirante'=>$aspirante,'aceptado'=>true])->exists())
            {
                FichaAspirante::where('aspirante',$aspirante)->update(['pago_inscripcion'=>1]);
            }else{
                $encabezado="Error en modificación de pago";
                $mensaje="Como el aspirante no ha sido aceptado, no puede marcar su pago como válido";
                return view('desarrollo.no')->with(compact('encabezado','mensaje'));
            }
        }
        return redirect()->route('desarrollo.datos_aspirante',["periodo" =>$periodo,"aspirante"=>$aspirante ]);
    }

    public function seleccionar()
    {
        list($periodos, $periodo_actual, $carreras) = $this->extracted1();
        $encabezado="Seleccionar aspirantes aceptados";
        return view('desarrollo.fichas_seleccionar', compact('periodos',
            'periodo_actual', 'carreras', 'encabezado'));
    }

    public function seleccionar_listado(Request $request): View
    {
        $periodo=$request->get('periodo');
        $carrera=$request->get('carrera');
        if(Aspirante::where(['periodo'=>$periodo,'carrera'=>$carrera])->count()>0)
        {
            list($aspirantes, $grupos, $bandera, $nombre_periodo, $nombre_carrera) = $this->extracted2($periodo, $carrera);
            $encabezado="Selección de aspirantes aceptados del período ".$nombre_periodo;
            return view('desarrollo.fichas_seleccion')
                ->with(compact('encabezado','aspirantes','bandera','grupos','nombre_carrera'));
        }else{
            $encabezado="Error de selección";
            $mensaje="No hay solicitudes de ingreso para el período y carrera seleccionado";
            return view('desarrollo.no')->with(compact('encabezado','mensaje'));
        }
    }

    public function grupo_aspirante(Request $request)
    {
        $aspirante=Aspirante::where('id',$request->get('id'))->first();
        $aspirante->grupo=$request->get('grupo');
        $aspirante->aceptado=true;
        $aspirante->save();
        $periodo=$aspirante->periodo;
        $carrera=$aspirante->carrera;
        list($aspirantes, $grupos, $bandera, $nombre_periodo, $nombre_carrera) = $this->extracted2($periodo, $carrera);
        $encabezado="Selección de aspirantes aceptados del período ".$nombre_periodo;
        return view('desarrollo.fichas_seleccion')
            ->with(compact('encabezado','aspirantes','bandera','grupos','nombre_carrera'));
    }

    /**
     * @param mixed $periodo
     * @param $listados
     * @param $bandera
     * @return \Illuminate\View\View|View|\Illuminate\Foundation\Application|Factory
     */
    public function extracted(mixed $periodo, $listados,$bandera): \Illuminate\View\View|View|\Illuminate\Foundation\Application|Factory
    {
        $nombre_periodo = PeriodoEscolar::where('periodo', $periodo)
            ->select('identificacion_corta')
            ->first();
        $encabezado = "Aspirantes del periodo ".$nombre_periodo->identificacion_corta;
        if (empty($listados)) {
            $mensaje = 'No hay fichas de aspirantes para el periodo seleccionado';
            return view('desarrollo.no')
                ->with(compact('encabezado', 'mensaje'));
        } else {
            if($bandera==1){
                $encabezado = "Listado de aspirantes";
                return view('desarrollo.listado2')
                ->with(compact('listados', 'encabezado', 'periodo'));
            }else{
                $encabezado = "Concentrado de aspirantes";
                return view('desarrollo.fichas_concentrado')
                    ->with(compact('listados', 'encabezado', 'nombre_periodo',
                        'periodo'));
            }

        }
    }

    /**
     * @return array
     */
    public function extracted1(): array
    {
        $periodos = PeriodoEscolar::select(['periodo', 'identificacion_corta'])
            ->orderBy('periodo', 'DESC')->get();
        $periodo_actual = (new AccionesController)->periodo_entrega_fichas();
        $carreras = Carrera::select(['carrera', 'reticula', 'nombre_reducido'])
            ->where('nivel_escolar', '=', 'L')
            ->where('ofertar', true)
            ->orderBy('carrera')
            ->orderBy('reticula')->get();
        return array($periodos, $periodo_actual, $carreras);
    }

    /**
     * @param mixed $periodo
     * @param mixed $carrera
     * @return array
     */
    public function extracted2(mixed $periodo, mixed $carrera): array
    {
        $aspirantes = Aspirante::where(['periodo' => $periodo, 'carrera' => $carrera])
            ->orderBy('apellido_paterno', 'ASC')
            ->orderBy('apellido_materno', 'ASC')
            ->orderBy('nombre_aspirante', 'ASC')
            ->get();
        $reticula = Carrera::where(['carrera' => $carrera, 'ofertar' => true])->first()->reticula;
        $materias = MateriaCarrera::where(
            [
                'carrera' => $carrera,
                'reticula' => $reticula,
                'semestre_reticula' => 1
            ]
        )->select('materia')->get()->toArray();
        $grupos = Grupo::where(
            [
                'carrera' => $carrera,
                'reticula' => $reticula,
                'periodo' => $periodo
            ]
        )->whereIn('materia', $materias)
            ->select('grupo')
            ->distinct()
            ->get();
        $bandera = $grupos->count() > 0;
        $nombre_periodo = PeriodoEscolar::where('periodo', $periodo)->first()->identificacion_corta;
        $nombre_carrera = Carrera::where(['carrera' => $carrera, 'ofertar' => true])->first()->nombre_carrera;
        return array($aspirantes, $grupos, $bandera, $nombre_periodo, $nombre_carrera);
    }
}
