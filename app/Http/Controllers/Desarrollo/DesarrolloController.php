<?php

namespace App\Http\Controllers\Desarrollo;

use App\Models\Aula;
use App\Models\Carrera;
use App\Models\EvaluacionAlumno;
use App\Models\FechaEvaluacion;
use App\Models\PeriodoEscolar;
use App\Models\PeriodoFicha;
use App\Models\AulaAspirante;
use App\Http\Controllers\Acciones\AccionesController;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Events\Dispatcher;
use App\Http\Controllers\MenuDesarrolloController;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;


class DesarrolloController extends Controller
{
    public function __construct(Dispatcher $events)
    {
        new MenuDesarrolloController($events);
    }
    public function index()
    {
        return view('desarrollo.index');
    }

    public function alta_periodo_fichas($datos)
    {
        try{
            $ficha = new PeriodoFicha();
            $ficha->fichas = $datos->get('fichas');
            $ficha->activo = $datos->get('activo');
            $ficha->entrega = $datos->get('entrega');
            $ficha->termina = $datos->get('termina');
            $ficha->inicio_prope =  $datos->get('inicio_prope');
            $ficha->fin_prope = $datos->get('fin_prope');
            $ficha->save();
            return 1;
        }catch (QueryException){
            return 0;
        }
    }

    public function fichas_inicio()
    {
        $periodos = PeriodoEscolar::orderBy('periodo','desc')->get();
        if(PeriodoFicha::where('activo',1)->count()>0){
            $periodos_ficha = PeriodoFicha::where('activo',1)->first();
            $bandera=1;
        }else{
            $periodos_ficha='';
            $bandera=0;
        }
        $encabezado="Parámetros para entrega de fichas";
        return view('desarrollo.inicio_fichas')
            ->with(compact('periodos','bandera','periodos_ficha','encabezado'));
    }
    public function fichas_inicio_parametros(Request $request)
    {
        if($request->get('bandera')){
            $periodo_original_ficha = PeriodoFicha::where('activo',1)->first();
            if($periodo_original_ficha->fichas == $request->get('fichas')){
                PeriodoFicha::where('fichas',$periodo_original_ficha->fichas)
                    ->update([
                        'activo'=>$request->get('activo'),
                        'entrega'=>$request->get('entrega'),
                        'termina'=>$request->get('termina'),
                        'inicio_prope'=>$request->get('inicio_prope'),
                        'fin_prope'=>$request->get('fin_prope')
                    ]);
                $encabezado="Parámetros actualizados";
                $mensaje="Se actualizaron los datos para el período de entrega de fichas.";
                return view('desarrollo.si')->with(compact('mensaje','encabezado'));
            }else{
                PeriodoFicha::where('fichas',$periodo_original_ficha->fichas)
                    ->update([
                        'activo'=>0,
                    ]);
                $alta = $this->alta_periodo_fichas($request);
                if($alta){
                    $encabezado="Parámetros actualizados";
                    $mensaje="Se dió de alta el período de entrega de fichas y se modificó un período que tenía marcado como aún activo";
                    return view('desarrollo.si')->with(compact('mensaje','encabezado'));
                }else{
                    $encabezado="Error de alta de ficha";
                    $mensaje="No fue posible crear el período de entrega de fichas. Verifique";
                    return view('desarrollo.no')->with(compact('mensaje','encabezado'));
                }
            }
        }else{
            $alta = $this->alta_periodo_fichas($request);
            if($alta){
                $encabezado="Parámetros actualizados";
                $mensaje="Se dió de alta el período de entrega de fichas";
                return view('desarrollo.si')->with(compact('mensaje','encabezado'));
            }else{
                $encabezado="Error de alta de ficha";
                $mensaje="No fue posible crear el período de entrega de fichas. Verifique";
                return view('desarrollo.no')->with(compact('mensaje','encabezado'));
            }
        }
    }
    public function carreras_x_ofertar()
    {
        $carreras = Carrera::where('nivel_escolar','L')
            ->orderBy('nombre_carrera','ASC')
            ->orderBy('reticula','ASC')
            ->get();

        $datos_periodo=(new AccionesController())->periodo_entrega_fichas();
        $encabezado="Carreras por ofertar para el período ".$datos_periodo->identificacion_corta;
        return view('desarrollo.fichas_carreras')->with(compact('carreras','encabezado'));
    }
    public function actualizar_carreras_x_ofertar(Request $request)
    {
        //Primero, se elimina lo existente, para poder así actualizar
        Carrera::where('ofertar',1)->update([
            'ofertar'=>0
        ]);
        //Ahora, se actualizan las carreras
        foreach ($request->get('carreras') as $value){
            $datos=explode("_",$value);
            $carrera=$datos[0];
            $reticula=$datos[1];
            Carrera::where('carrera','=',$carrera)
                ->where('reticula','=',$reticula)
                ->update([
                    'ofertar'=>1
                ]);
        }
        $encabezado="Carreras actualizadas";
        $mensaje="Se actualizó la información de las carreras a ser ofertadas";
        return view('desarrollo.si')->with(compact('encabezado','mensaje'));
    }

    public function aulas_para_examen()
    {
        $carreras = Carrera::where('ofertar',1)
            ->where('nivel_escolar','L')
            ->orderBy('nombre_carrera','ASC')
            ->orderBy('reticula','ASC')
            ->get();
        $salones=Aula::where('estatus',1)->get();
        $datos_periodo=(new AccionesController())->periodo_entrega_fichas();
        $periodo=$datos_periodo->periodo;
        if(AulaAspirante::where('periodo',$periodo)->count()>0){
            $bandera=1;
            $registros=AulaAspirante::select('aulas_aspirantes.*','carreras.*','aulas.aula')
                ->join('aulas','aulas_aspirantes.id','=','aulas.id')
                ->join('carreras','aulas_aspirantes.carrera','=','carreras.carrera')
                ->where('periodo',$periodo)
                ->where('carreras.ofertar','1')
                ->orderBy('carreras.nombre_reducido')
                ->get();
        }else{
            $bandera=0;
            $registros='';
        }
        $encabezado="Aspirantes a ingresar para el período ".$datos_periodo->identificacion_corta;
        return view('desarrollo.fichas_aulas')
            ->with(compact('carreras','salones','encabezado',
                'periodo','bandera','registros'));
    }

    public function alta_aula_examen(Request $request)
    {
        $aula = new AulaAspirante();
        $aula->periodo = $request->get("periodo");
        $aula->aula = $request->get("salon");
        $aula->capacidad = $request->get("cupo");
        $aula->disponibles=$request->get("cupo");
        $aula->carrera=$request->get("carrera");
        $aula->save();
        $salon=Aula::find($request->get("salon"));
        $encabezado="Alta de aula para examen de admisión";
        $mensaje="Se dió de alta al salón ".$salon->aula." para aplicar el examen de admisión";
        return view('desarrollo.si')->with(compact('encabezado','mensaje'));
    }

    public function evaluacion_inicio()
    {
        $datos_periodo=(new AccionesController())->periodo_entrega_fichas();
        $periodo_actual=$datos_periodo->periodo;
        $periodos=PeriodoEscolar::orderBy('periodo','ASC')->get();
        $encabezado="Evaluación al Docente";
        return view('desarrollo.evaluacion_periodo')->with(compact('periodo_actual',
        'periodos','encabezado'));
    }
    public function evaluacion_periodo(Request $request)
    {
        $periodo=$request->get('periodo');
        $encuesta=$request->get('encuesta');
        $datos_periodo=PeriodoEscolar::where('periodo',$periodo)->first();
        if(FechaEvaluacion::where('periodo',$periodo)->where('encuesta',$encuesta)->count()>0){
            $datos=FechaEvaluacion::where('periodo',$periodo)->where('encuesta',$encuesta)->first();
            $encabezado='Modificación de evaluación al docente';
            return view('desarrollo.evaluacion_periodo_modifica')->with(compact('periodo',
                'datos_periodo','encabezado','datos'));
        }else{
            $encabezado='Alta de evaluación al docente';
            return view('desarrollo.evaluacion_periodo_alta')->with(compact('periodo',
            'datos_periodo','encabezado'));
        }
    }

    public function resultados_evaluacion1(){
        $periodo_actual_consulta=(new AccionesController())->periodo();
        $periodo_actual=$periodo_actual_consulta[0]->periodo;
        $periodos=PeriodoEscolar::orderBy('periodo','DESC')->get();
        $encabezado="Evaluación al Docente";
        return view('desarrollo.evaluacion_resultados')->with(compact('periodo_actual',
            'periodos','encabezado'));
    }

    public function resultados_evaluacion2(Request $request){
        $periodo=$request->get('periodo');
        $encuesta=$request->get('encuesta');
        $tipo_busqueda=$request->get('busqueda');
        if(FechaEvaluacion::where('periodo',$periodo)
            ->where('encuesta',$encuesta)->count()>0){
            if(EvaluacionAlumno::where('periodo',$periodo)
            ->where('encuesta',$encuesta)->count()>0){
                $bandera=1;
            }else{
                $encabezado="Evaluación al docente";
                $mensaje="Los estudiantes no han registrados respuestas, por lo que ".
                    "lo que no hay información que mostrar";
                return view('desarrollo.no')->with(compact('encabezado','mensaje'));
            }
        }else{
            $encabezado="Evaluación al docente";
            $mensaje="No ha indicado parámetros para la evaluación al docente, por ".
                "lo que no hay información que mostrar";
            return view('desarrollo.no')->with(compact('encabezado','mensaje'));
        }
    }
}
