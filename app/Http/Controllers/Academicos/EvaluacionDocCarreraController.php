<?php

namespace App\Http\Controllers\Academicos;

use App\Http\Controllers\Acciones\AccionesController;
use App\Http\Controllers\Acciones\EvalDocenteController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MenuAcademicosController;
use App\Models\Carrera;
use App\Models\EvaluacionAlumno;
use App\Models\EvaluacionAspecto;
use App\Models\PeriodoEscolar;
use App\Models\Pregunta;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Http\Request;
use Amenadiel\JpGraph\Graph;
use Amenadiel\JpGraph\Plot;

class EvaluacionDocCarreraController extends Controller
{
    public function __construct(Dispatcher $events)
    {
        new MenuAcademicosController($events);
    }
    public function resultados_evaluacion(Request $request){
        $encabezado="Resultados evaluacion al docente por carrera";
        $periodo=$request->get('periodo');
        $nperiodo=PeriodoEscolar::where('periodo',$periodo)
            ->select('identificacion_corta')
            ->first();
        $datos_carrera=$request->get('datos_carrera');
        $datos=explode("_",$datos_carrera);
        $carrera=$datos[0];
        $reticula=$datos[1];
        $nombre_carrera=Carrera::where('carrera',$carrera)
            ->where('reticula',$reticula)
            ->select('nombre_reducido')
            ->first();
        $consecutivo=EvaluacionAspecto::where('encuesta','=','A')
            ->max('consecutivo');
        $numero_preguntas=Pregunta::where('encuesta','A')
            ->where('consecutivo',$consecutivo)
            ->max('no_pregunta');
        $info_materias=(new EvalDocenteController)
            ->concentrado1_resultado_por_carrera($periodo,$carrera,$reticula,$numero_preguntas);
        $materias_activas=0;
        $materias_evaluadas=0;
        $docentes_activos=0;
        $docentes_evaluados=0;
        foreach($info_materias as $info){
            $materias_activas+=$info->mat_existen;
            $materias_evaluadas+=$info->mat_eval;
            $docentes_activos+=$info->docentes;
            $docentes_evaluados+=$info->doc_eval;
        }
        $resultados=(new EvalDocenteController)->resultados_x_carrera($periodo,$carrera,$reticula);
        $estudiantes=(new EvalDocenteController)->alumnos_evaluaron($periodo,$carrera,$reticula);
        $alumnos_activos=$estudiantes[0]->inscritos;
        $alumnos_evaluados=$estudiantes[0]->evaluaron;
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
        return view('academicos.evaldocente_carrera')
            ->with(compact('encabezado','nperiodo',
                'materias_activas','materias_evaluadas','docentes_activos','docentes_evaluados',
                'carrera','reticula','alumnos_activos','alumnos_evaluados','periodo','nombre_carrera',
                'resultados','promedio','cal'));
    }

    public function grafica_evaluacion_carrera($periodo,$carrera,$reticula,$promedio)
    {
        // Create the graph and setup the basic parameters
        $__width  = 460;
        $__height = 200;
        $resultados=(new EvalDocenteController)->resultados_x_carrera($periodo,$carrera,$reticula);;
        $valores=[];
        $i=0;
        foreach ($resultados as $key=>$value){
            $valores[$i]=$value["porcentaje"];
            $i++;
        }
        $valores[] = $promedio;
        $graph = new Graph\Graph($__width, $__height, 'auto');
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
        $graph->title->Set(mb_convert_encoding('Evaluación Docente por carrera', 'ISO-8859-1', 'UTF-8'));
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
