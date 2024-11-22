<?php

namespace App\Http\Controllers\Acciones;

use App\Http\Controllers\Controller;
use Amenadiel\JpGraph\Graph;
use Amenadiel\JpGraph\Plot;
use App\Models\EvaluacionAlumno;
use App\Models\EvaluacionAspecto;
use App\Models\Pregunta;


class GraficoController extends Controller
{
    public function resultados_evaluacion_docente($periodo,$docente)
    {
        $resultados = array();
        $i = 0; //Este es el contador inicial para el registro de información
        $consecutivo = EvaluacionAlumno::where('encuesta', 'A')
            ->max('consecutivo');
        $maximo = Pregunta::where('consecutivo', $consecutivo)
            ->where('encuesta', 'A')
            ->count();
        $info_evaluado = (new AccionesController)->evaluacion_al_docente_datos($periodo, $docente, $maximo);
        $aspectos = EvaluacionAspecto::where('encuesta', 'A')
            ->where('consecutivo', $consecutivo)
            ->orderBy('aspecto')
            ->get();
        $valor_resp = [];
        foreach ($aspectos as $aspecto) {
            $valor_resp[0] = 0;
            $valor_resp[1] = 0;
            $valor_resp[2] = 0;
            $valor_resp[3] = 0;
            $valor_resp[4] = 0;
            $valor_resp[5] = 0;
            $suma = 0;
            $num_res = 0;
            $preguntas = Pregunta::where('encuesta', 'A')
                ->where('consecutivo', $consecutivo)
                ->where('aspecto', $aspecto->aspecto)
                ->select('no_pregunta')
                ->get();
            foreach ($info_evaluado as $value) {
                $materia = $value->materia;
                $gpo = $value->grupo;
                foreach ($preguntas as $pregunta) {
                    $obtenido = (new AccionesController)->resultados_evaluacion_al_docente($periodo, $pregunta->no_pregunta, $materia, $gpo);
                    foreach ($obtenido as $obt) {
                        switch ($obt->respuesta) {
                            case '1':
                            case 'A':
                                $valor_resp[0] += $obt->cantidad;
                                break;
                            case '2':
                            case 'B':
                                $valor_resp[1] += $obt->cantidad;
                                break;
                            case '3':
                            case 'C':
                                $valor_resp[2] += $obt->cantidad;
                                break;
                            case '4':
                            case 'D':
                                $valor_resp[3] += $obt->cantidad;
                                break;
                            case '5':
                            case 'E':
                                $valor_resp[4] += $obt->cantidad;
                                break;
                            default:
                                $valor_resp[5] += $obt->cantidad;
                                break;
                        }
                    }
                }
            }
            for ($a = 0; $a < 5; $a++) {
                $suma += $valor_resp[$a] * ($a + 1);
                $num_res += $valor_resp[$a];
            }
            $porcentaje = round($suma / $num_res, 2);
            switch ($porcentaje) {
                case ($porcentaje >= 1 && $porcentaje <= 3.24):
                    $cal = "INSUFICIENTE";
                    break;
                case ($porcentaje >= 3.25 && $porcentaje <= 3.74):
                    $cal = "SUFICIENTE";
                    break;
                case ($porcentaje >= 3.75 && $porcentaje <= 4.24):
                    $cal = "BUENO";
                    break;
                case ($porcentaje >= 4.25 && $porcentaje <= 4.74):
                    $cal = "NOTABLE";
                    break;
                case ($porcentaje >= 4.75 && $porcentaje <= 5):
                    $cal = "EXCELENTE";
                    break;
                default:
                    $cal = "S/D";
                    break;
            }
            $resultados[$i]["aspecto"] = $aspecto->aspecto;
            $resultados[$i]["descripcion"] = $aspecto->descripcion;
            $resultados[$i]["porcentaje"] = $porcentaje;
            $resultados[$i]["calificacion"] = $cal;
            $i++;
        }
        return $resultados;
    }
        public function evaluacion_docente($periodo,$docente,$promedio)
        {

            // Create the graph and setup the basic parameters
            $__width  = 460;
            $__height = 200;
            $resultados=$this->resultados_evaluacion_docente($periodo,$docente);
            $valores=[];
            $i=0;
            foreach ($resultados as $key=>$value){
                $valores[$i]=$value["porcentaje"];
                $i++;
            }
            $valores[] = $promedio;
            $graph    = new Graph\Graph($__width, $__height, 'auto');
            $graph->img->SetMargin(40, 30, 30, 40);
            $graph->SetScale('textint',1,5);
            $graph->SetShadow();
            $graph->SetFrame(false); // No border around the graph
            // Add some grace to the top so that the scale doesn't
            // end exactly at the max value.
            $graph->yaxis->scale->SetGrace(7);
            // Setup X-axis labels
            $a = ["A","B","C","D","E","F","G","H","I","J","Prom"];
            $graph->xaxis->SetTickLabels($a);
            $graph->xaxis->SetFont(FF_FONT2);
            // Setup graph title ands fonts
            $graph->title->Set(mb_convert_encoding('Evaluación Docente', 'ISO-8859-1', 'UTF-8'));
            $graph->title->SetFont(FF_FONT2, FS_BOLD);
            // Create a bar pot
            $bplot = new Plot\BarPlot($valores);
            $bplot->SetFillColor('orange');
            foreach ($valores as $key=>$value){
                if($key<=9){
                    switch ($value){
                        case ($value>=1&&$value<=3.24): $barcolors[]="#FF0000"; break;
                        case ($value>=3.25&&$value<=3.74): $barcolors[]="#FFCC00"; break;
                        case ($value>=3.75&&$value<=4.24): $barcolors[]="#FF6321"; break;
                        case ($value>=4.25&&$value<=4.74): $barcolors[]="#FF42FF"; break;
                        case ($value>=4.75&&$value<=5): $barcolors[]="#0042FF"; break;
                    }
                }else{
                    $barcolors[]="green";
                }
            }
            $bplot->SetFillColor($barcolors);
            $bplot->SetValuePos('center');
            $bplot->SetWidth(0.55);
            $bplot->SetShadow();
            $bplot->value->format='%1.2f';
            // Setup the values that are displayed on top of each bar
            $bplot->value->Show();
            // Must use TTF fonts if we want text at an arbitrary angle
            $bplot->value->SetFont(FF_COURIER, FS_NORMAL,7);
            $bplot->value->SetAngle(90);
            $bplot->value->valign='center';
            // Black color for positive values and darkred for negative values
            $bplot->value->SetColor('white', 'darkred');
            $graph->Add($bplot);
            $graph->Stroke();
        }
}

