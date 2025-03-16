<?php

namespace App\Http\Controllers\Acciones;

use App\Http\Controllers\Controller;
use App\Models\EvaluacionAspecto;
use App\Models\Pregunta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EvalDocenteController extends Controller
{
    /*
     * Devuelve la información de materias evaluadas, etcétera, de la evaluación docente
     * por carrera
     */
    public function concentrado1_resultado_por_carrera($periodo,$carrera, $reticula,$longitud)
    {
        return DB::select("SELECT * FROM pac_resul_x_carrera1(:periodo,:carrera,:reticula,:longitud)",
        [
            'periodo' => $periodo,
            'carrera' => $carrera,
            'reticula' => $reticula,
            'longitud' => $longitud
        ]);
    }

    /*
     * Devuelve la información de materias evaluadas, etcétera, de la evaluación docente
     * por departamento
     */
    public function concentrado2_resultado_por_departamento($periodo,$departamento,$longitud)
    {
        return DB::select("SELECT * FROM pac_resul_x_carrera3(:periodo,:departamento,:longitud)",
            [
                'periodo' => $periodo,
                'departamento' => $departamento,
                'longitud' => $longitud
            ]);
    }

    /*
     * Devuelve el número de inscritos y evaluaron por carrera
     */
    public function alumnos_evaluaron($periodo,$carrera, $reticula)
    {
        return DB::select("SELECT * FROM pac_resul_x_carrera2(:periodo,:carrera,:reticula)",
        [
            'periodo' => $periodo,
            'carrera' => $carrera,
            'reticula' => $reticula,
        ]);
    }

    /*
     * Devuelve el número de inscritos y evaluaron por departamento
     */
    public function alumnos_evaluaron_depto($periodo,$departamento, $longitud)
    {
        return DB::select("SELECT * FROM pac_resul_x_carrera4(:periodo,:departamento,:longitud)",
            [
                'periodo' => $periodo,
                'departamento' => $departamento,
                'longitud' => $longitud
            ]);
    }

    /*
     * Devuelve el resultado de la evaluación docente por carrera - retícula/pregunta
     */
    public function resultado_evaluacion_docente_x_carrera_x_pregunta($periodo,$pregunta,$carrera,$reticula){
        return DB::select("SELECT * FROM pac_eval_carr_ret(:periodo,:pregunta,:carrera,:reticula)",
        [
            'periodo' => $periodo,
            'pregunta' => $pregunta,
            'carrera' => $carrera,
            'reticula' => $reticula
        ]);
    }

    /*
     * Devuelve el resultado de la evaluación docente por departamento/pregunta
     */
    public function resultado_evaluacion_docente_x_departamento($periodo,$pregunta,$departamento,$longitud){
        return DB::select("SELECT * FROM pac_eval_depto(:periodo,:pregunta,:departamento,:longitud)",
            [
                'periodo' => $periodo,
                'pregunta' => $pregunta,
                'departamento' => $departamento,
                'longitud' => $longitud
            ]);
    }

    /*
     * Devuelve el listado de aquellos alumnos que aún no han realizado la evaluación al docente
     */
    public function alumnos_no_han_evaluado($periodo,$carrera){
        return DB::select("SELECT * FROM pac_alumnos_faltan_ev(:periodo,:carrera)",
            [
                'periodo' => $periodo,
                'carrera' => $carrera
            ]);
    }

    /*
     * Devuelve el listado de docentes que no han sido evaluados
     */
    public function docentes_no_evaluados($periodo)
    {
        return DB::select("SELECT * FROM pac_docentes_faltan_ev(:periodo)",
            [
                'periodo' => $periodo,
            ]);
    }
    /*
     * Devuelve el resultado de la evaluación docente por carrera - retícula
     */
    public function resultados_x_carrera($periodo,$carrera,$reticula){
        $resultados=array();
        $i=0; //Este es el contador inicial para el registro de información
        $consecutivo=EvaluacionAspecto::where('encuesta','=','A')
            ->max('consecutivo');
        $aspectos=EvaluacionAspecto::where('encuesta','=','A')
            ->where('consecutivo',$consecutivo)
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
            $preguntas= Pregunta::where('encuesta','A')
                ->where('aspecto',$aspecto->aspecto)
                ->where('consecutivo',$consecutivo)
                ->select('no_pregunta')
                ->get();
            foreach ($preguntas as $pregunta){
                $obtenido=$this->resultado_evaluacion_docente_x_carrera_x_pregunta(
                        $periodo,
                        $pregunta->no_pregunta,
                        $carrera,
                        $reticula);
                foreach ($obtenido as $obt){
                    switch ($obt->respuesta){
                        case '1':
                        case 'A': $valor_resp[0]+=$obt->cantidad;break;
                        case '2':
                        case 'B': $valor_resp[1]+=$obt->cantidad;break;
                        case '3':
                        case 'C': $valor_resp[2]+=$obt->cantidad;break;
                        case '4':
                        case 'D': $valor_resp[3]+=$obt->cantidad;break;
                        case '5':
                        case 'E': $valor_resp[4]+=$obt->cantidad;break;
                        default: $valor_resp[5]+=$obt->cantidad; break;
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
            }
            $resultados[$i]["aspecto"]=$aspecto->aspecto;
            $resultados[$i]["descripcion"]=$aspecto->descripcion;
            $resultados[$i]["porcentaje"]=$porcentaje;
            $resultados[$i]["calificacion"]=$cal;
            $i++;
        }
        return $resultados;
    }

    /*
     * Devuelve el resultado de la evaluación docente por departamento
     */
    public function resultados_x_depto($periodo,$depto,$longitud){
        $resultados=array();
        $i=0; //Este es el contador inicial para el registro de información
        $consecutivo=EvaluacionAspecto::where('encuesta','=','A')
            ->max('consecutivo');
        $aspectos=EvaluacionAspecto::where('encuesta','=','A')
            ->where('consecutivo',$consecutivo)
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
            $preguntas= Pregunta::where('encuesta','A')
                ->where('aspecto',$aspecto->aspecto)
                ->where('consecutivo',$consecutivo)
                ->select('no_pregunta')
                ->get();
            foreach ($preguntas as $pregunta){
                $obtenido=$this->resultado_evaluacion_docente_x_departamento(
                    $periodo,
                    $pregunta->no_pregunta,
                    $depto,
                    $longitud);
                foreach ($obtenido as $obt){
                    switch ($obt->respuesta){
                        case '1':
                        case 'A': $valor_resp[0]+=$obt->cantidad;break;
                        case '2':
                        case 'B': $valor_resp[1]+=$obt->cantidad;break;
                        case '3':
                        case 'C': $valor_resp[2]+=$obt->cantidad;break;
                        case '4':
                        case 'D': $valor_resp[3]+=$obt->cantidad;break;
                        case '5':
                        case 'E': $valor_resp[4]+=$obt->cantidad;break;
                        default: $valor_resp[5]+=$obt->cantidad; break;
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
