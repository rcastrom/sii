<?php

namespace App\Http\Controllers\Docentes;

use App\Http\Controllers\Acciones\AccionesController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MenuDocenteController;
use App\Models\EvaluacionAlumno;
use App\Models\EvaluacionAspecto;
use App\Models\PeriodoEscolar;
use App\Models\Pregunta;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Http\Request;

class EvaluacionDocenteController extends Controller
{
    public function __construct(Dispatcher $events)
    {
        new MenuDocenteController($events);
    }
    public function evaluacion_docente2(Request $request)
    {
        $periodo=$request->get('periodo');
        $personal=base64_decode($request->get('personal'));
        $nperiodo=PeriodoEscolar::where('periodo',$periodo)
            ->select('identificacion_corta')->first();
        $encabezado="Resultados de evaluación al docente";
        if(EvaluacionAlumno::where(
                [
                    'personal' => $personal,
                    'periodo' => $periodo,
                ]
            )->count() == 0){
            $mensaje="No hay información que mostrar";
            return view('personal.no')->with(compact('encabezado', 'mensaje'));
        }
        // Esta variable se emplea por si en algún momento llegara a cambiar la encuesta
        $maximo=Pregunta::where('consecutivo','=',2)
            ->where('encuesta','=','A')
            ->count();
        $materias=(new AccionesController)->evaluacion_al_docente_datos($periodo,$personal,$maximo);
        $resultados=$this->resultados_evaluacion_docente($periodo,$personal);
        $valores=[];
        $i=0;
        $suma=0;
        foreach ($resultados as $key=>$value){
            $valores[$i]=$value["porcentaje"];
            $suma+=$value["porcentaje"];
            $i++;
        }
        $promedio=round($suma/$i,2);

        switch ($promedio){
            case ($promedio>=1&&$promedio<=3.24): $cal="INSUFICIENTE"; break;
            case ($promedio>=3.25&&$promedio<=3.74): $cal="SUFICIENTE"; break;
            case ($promedio>=3.75&&$promedio<=4.24): $cal="BUENO"; break;
            case ($promedio>=4.25&&$promedio<=4.74): $cal="NOTABLE"; break;
            case ($promedio>=4.75&&$promedio<=5): $cal="EXCELENTE"; break;
            default : $cal="Otros"; break;
        }
        return view('personal.evaldocente2')
            ->with(compact('materias','nperiodo',
                'periodo','personal',
                'resultados','encabezado','promedio','cal','valores'));
    }
    public function resultados_evaluacion_docente($periodo,$docente){
        $resultados=array();
        $i=0; //Este es el contador inicial para el registro de información
        $consecutivo=EvaluacionAlumno::where('encuesta','A')
            ->max('consecutivo');
        $maximo=Pregunta::where('consecutivo',$consecutivo)
            ->where('encuesta','A')
            ->count();
        $info_evaluado=(new AccionesController)->evaluacion_al_docente_datos($periodo,$docente,$maximo);
        $aspectos=EvaluacionAspecto::where('encuesta','A')
            ->where('consecutivo',$consecutivo)
            ->orderBy('aspecto')
            ->get();
        $valor_resp=[];
        foreach ($aspectos as $aspecto){
            $valor_resp[0]=0;
            $valor_resp[1]=0;
            $valor_resp[2]=0;
            $valor_resp[3]=0;
            $valor_resp[4]=0;
            $valor_resp[5]=0;
            $suma=0;
            $num_res=0;
            $preguntas=Pregunta::where('encuesta','A')
                ->where('consecutivo',$consecutivo)
                ->where('aspecto',$aspecto->aspecto)
                ->select('no_pregunta')
                ->get();
            foreach ($info_evaluado as $value){
                $materia=$value->materia;
                $gpo=$value->grupo;
                foreach ($preguntas as $pregunta){
                    $obtenido=(new AccionesController)->resultados_evaluacion_al_docente($periodo,$pregunta->no_pregunta,$materia,$gpo);
                    foreach ($obtenido as $obt){
                        switch ($obt->respuesta){
                            case 'A': $valor_resp[0]+=$obt->cantidad;break;
                            case 'B': $valor_resp[1]+=$obt->cantidad;break;
                            case 'C': $valor_resp[2]+=$obt->cantidad;break;
                            case 'D': $valor_resp[3]+=$obt->cantidad;break;
                            case 'E': $valor_resp[4]+=$obt->cantidad;break;
                            case 'F': $valor_resp[5]+=$obt->cantidad;break;
                            case '1' :$valor_resp[0]+=$obt->cantidad;break;
                            case '2' :$valor_resp[1]+=$obt->cantidad;break;
                            case '3' :$valor_resp[2]+=$obt->cantidad;break;
                            case '4' :$valor_resp[3]+=$obt->cantidad;break;
                            case '5' :$valor_resp[4]+=$obt->cantidad;break;
                            default: $valor_resp[5]+=$obt->cantidad; break;
                        }
                    }
                }
            }
            for($a=0;$a<5;$a++){
                $suma+=$valor_resp[$a]*($a+1);
                $num_res+=$valor_resp[$a];
            }
            $porcentaje = round($suma/$num_res,2);
            switch ($porcentaje){
                case ($porcentaje>=1&&$porcentaje<=3.24): $cal="INSUFICIENTE"; break;
                case ($porcentaje>=3.25&&$porcentaje<=3.74): $cal="SUFICIENTE"; break;
                case ($porcentaje>=3.75&&$porcentaje<=4.24): $cal="BUENO"; break;
                case ($porcentaje>=4.25&&$porcentaje<=4.74): $cal="NOTABLE"; break;
                case ($porcentaje>=4.75&&$porcentaje<=5): $cal="EXCELENTE"; break;
                default: $cal="S/D"; break;
            }
            $resultados[$i]["aspecto"]=$aspecto->aspecto;
            $resultados[$i]["descripcion"]=$aspecto->descripcion;
            $resultados[$i]["porcentaje"]=$porcentaje;
            $resultados[$i]["calificacion"]=$cal;
            $i++;
        }
        return $resultados;
    }
}
