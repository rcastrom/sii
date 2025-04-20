<?php

namespace App\Http\Controllers\Desarrollo;

use App\Http\Controllers\Acciones\AccionesController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MenuDesarrolloController;
use App\Models\Carrera;
use App\Models\PeriodoEscolar;
use App\Models\CarreraAspirante;
use App\Models\UsuarioAspirante;
use App\Models\FichaAspirante;
use App\Exports\FichasExport;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
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
        $periodo_actual = (new AccionesController)->periodo();
        $encabezado = 'Periodo de búsqueda';

        return view('desarrollo.fichas_estadistica_periodo', compact('periodos',
            'periodo_actual', 'encabezado'));
    }

    public function fichas_concentrado_estadistico(Request $request)
    {
        $periodo = $request->get('periodo');
        list($carreras_ofertar, $nombre_carreras) = $this->carreras_por_ofertar();
        $listados=(new AccionesController)->concentrado_fichas($periodo,$carreras_ofertar,$nombre_carreras);
        return $this->extracted($periodo, $listados,2);
    }

    public function fichas_concentrado_excel($periodo)
    {
        list($carreras_ofertar, $nombre_carreras) = $this->carreras_por_ofertar();
        $concentrado_total=(new AccionesController)->concentrado_fichas_excel($periodo,
            $carreras_ofertar,$nombre_carreras);
        $datos=collect($concentrado_total);
        return Excel::download(new FichasExport($datos), 'fichas_concentrados_'.$periodo.'.xlsx');
    }

    public function listado(): Factory|View|Application
    {
        $periodos = PeriodoEscolar::select(['periodo', 'identificacion_corta'])
            ->orderBy('periodo', 'DESC')->get();
        $periodo_actual = (new AccionesController)->periodo();
        $carreras = Carrera::select(['carrera', 'reticula', 'nombre_reducido'])
            ->where('nivel_escolar', '=', 'L')
            ->orderBy('carrera')
            ->orderBy('reticula')->get();
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

    public function datos_aspirante($periodo,$aspirante)
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

    public function actualizar_datos_aspirante(Request $request)
    {
        $aspirante=$request->get('aspirante');;
        $carrera=$request->get('carrera');
        $periodo=$request->get('periodo');
        CarreraAspirante::where('aspirante_id',$aspirante)->update([
            'carrera'=>$carrera
        ]);
        return redirect()->route('desarrollo.datos_aspirante',["periodo" =>$periodo,"aspirante"=>$aspirante ]);
    }

    public function contra_aspirante(Request $request)
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
        $aspirante=$request->get('aspirante');;
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
        $aspirante=$request->get('aspirante');;
        $periodo=$request->get('periodo');
        $pago=$request->get('pago');
        if($pago==1){
            FichaAspirante::where('aspirante',$aspirante)->update(['pago_ficha'=>1]);
        }elseif ($pago==2) {
            FichaAspirante::where('aspirante',$aspirante)->update(['pago_propedeutico'=>1]);
        }else{
            FichaAspirante::where('aspirante',$aspirante)->update(['pago_inscripcion'=>1]);
        }
        return redirect()->route('desarrollo.datos_aspirante',["periodo" =>$periodo,"aspirante"=>$aspirante ]);
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
     * @return string[]
     */
    public function carreras_por_ofertar(): array
    {
        $carreras = Carrera::select(['carrera', 'nombre_carrera'])
            ->where('nivel_escolar', '=', 'L')
            ->where('ofertar', '=', 1)
            ->orderBy('carrera')
            ->get();
        $carreras_por_ofertar = array();
        $nombre_de_carreras = array();
        foreach ($carreras as $carrera) {
            $carreras_por_ofertar[] = '"' . trim($carrera->carrera) . '"';
            $nombre_de_carreras[] = '"' . trim($carrera->nombre_carrera) . '"';
        }
        $carreras_ofertar = "{" . implode(",", $carreras_por_ofertar) . "}";
        $nombre_carreras = "{" . implode(",", $nombre_de_carreras) . "}";
        return array($carreras_ofertar, $nombre_carreras);
    }
}
