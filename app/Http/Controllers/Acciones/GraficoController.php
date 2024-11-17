<?php

namespace App\Http\Controllers\Acciones;

use App\Http\Controllers\Controller;
use Amenadiel\JpGraph\Graph;
use Amenadiel\JpGraph\Plot;

class GraficoController extends Controller
{
    public function evaluacion_docente($periodo,$docente,$promedio)
    {

        // Create the graph and setup the basic parameters
        $__width  = 460;
        $__height = 200;
        $resultados=(new AccionesController)->resultados_evaluacion_docente($periodo,$docente);
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
