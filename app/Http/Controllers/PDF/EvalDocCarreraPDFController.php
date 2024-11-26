<?php

namespace App\Http\Controllers\PDF;


use App\Http\Controllers\Controller;
use App\Http\Controllers\Acciones\EvalDocenteController;
use App\Models\Carrera;
use App\Models\EvaluacionAspecto;
use App\Models\PeriodoEscolar;
use App\Models\Pregunta;
use Illuminate\Http\Request;
use Codedge\Fpdf\Fpdf\Fpdf;
use Amenadiel\JpGraph\Graph;
use Amenadiel\JpGraph\Plot;
use JetBrains\PhpStorm\NoReturn;

class EvalDocCarreraPDFController extends Controller
{
    #[NoReturn] public function __invoke(Request $request)
    {
        $periodo = $request->periodo;
        $carr=$request->get('carrera');
        $datos=explode('_',$carr);
        $carrera=trim($datos[0]);
        $reticula=trim($datos[1]);
        $estudiantes=(new EvalDocenteController)->alumnos_evaluaron($periodo,$carrera,$reticula);
        $pdf = new Fpdf('P','mm','Letter');
        $pdf->AddPage();
        if($estudiantes[0]->evaluaron==0){
            $pdf->SetFont('Arial','B',16);
            $pdf->Cell("130","5","Aun no hay datos por mostrar",0,1,'C');
            $pdf->Output();
            exit();
        }
        $pdf->SetAutoPageBreak(0);
        $pdf->AddFont('MM','','Montserrat-Medium.php');
        $pdf->AddFont('MM','B','Montserrat-Bold.php');
        $pdf->AddFont("Montserrat2",'','Montserrat-ExtraLight.php');
        $pdf->AddFont("Montserrat2",'I','Montserrat-Thin.php');
        $pdf->AddFont("Montserrat2",'B','Montserrat-Light.php');
        $pdf->AddFont("Montserrat2",'BI','Montserrat-SemiBold.php');
        $x = 25;
        $y = 50;
        $w = 180;
        $h = 4;
        $this->encabezado($pdf);
        $nombre_periodo=PeriodoEscolar::where('periodo',$periodo)
            ->select('identificacion_larga')
            ->first();
        $nombre_carrera=Carrera::where('carrera',$carrera)
            ->where('reticula',$reticula)
            ->select('nombre_carrera')
            ->first();
        $pdf->SetXY($x, $y);
        $pdf->SetFont('MM','B',10);
        $pdf->Cell($w,$h,
            mb_convert_encoding("RESULTADOS DE LA EVALUACIÓN DOCENTE DEL PERÍODO " . $nombre_periodo->identificacion_larga,
                'ISO-8859-1', 'UTF-8'),0,2,"L");
        $pdf->SetFont('MM','B',8);
        $pdf->Cell($w,$h,
            mb_convert_encoding("CARRERA " . trim($nombre_carrera->nombre_carrera) . ' RETICULA ' . $reticula,
                'ISO-8859-1', 'UTF-8'),0,2,"L");
        $this->tabla_materias($pdf,$periodo,$carrera,$reticula);
        //Resultados
        $pdf->Ln(4);
        $x = 41;
        $pdf->SetX($x);
        $pdf->SetFont('MM','B',8);
        $data=(new EvalDocenteController)->resultados_x_carrera($periodo,$carrera,$reticula);
        $header=array("Aspectos a evaluar","Porcentaje","Descripción");
        $this->FancyTable($pdf,$header,$data);
        $valores=[];
        $i=0;
        $suma=0;
        foreach ($data as $value){
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
        }
        //$x = 41;
        $pdf->SetX($x);
        $pdf->SetFont('MM','B',9);
        $pdf->Cell(80,4,"Promedio General","LR",0,"R");
        $pdf->Cell(20,4,$promedio,"LR",0,"C");
        $pdf->Cell(35,4,$cal,"LR",1,"C");
        //Ahora, el gráfico
        $pdf->Ln(5);
        $nombre=$_ENV["UBICACION_CREAR_IMAGENES"].$carrera."_ret".$reticula.$periodo.".png";
        $this->grafica($pdf,$valores,$nombre,$promedio);
        //Pie de página
        $this->footer($pdf);
        $pdf->Output();
        exit();
    }

    public function encabezado($pdf)
    {
        $escudo_tecnm=$_ENV["RUTA_IMG_TECNM"];
        $mujer_emblema=$_ENV["RUTA_IMG_GOBFED"];
        $nombre_tec= mb_convert_encoding($_ENV["NOMBRE_TEC"], 'ISO-8859-1', 'UTF-8');
        $departamento= mb_convert_encoding("Departamento de Desarrollo Académico", 'ISO-8859-1', 'UTF-8');
        // Logo TecNM
        $pdf->Image($escudo_tecnm,20,7,36,20,'JPG');
        //Logo GobFed
        $pdf->Image($mujer_emblema,170,1,27,28,'JPG');
        $pdf->SetXY(154,29);
        $pdf->SetFont('Montserrat2','B',8);
        $pdf->Cell(50, 6, $nombre_tec, 0, 1, 'L');
        $pdf->SetXY(146,33);
        $pdf->Cell(50, 4, $departamento, 0, 1, 'L');
        return $pdf;
    }
    public function tabla_materias($pdf,$periodo,$carrera,$ret){
        $x = 25;
        $y = 65;
        $h = 4;
        $cp=45;
        $cs=25.7;
        $seccion = 20;
        $pdf->SetXY($x, $y);
        $pdf->SetFont('MM','B',9);
        $pdf->Cell($cp-14,$h,"","T",0,"C");
        $pdf->Cell($cp,$h,"MATERIAS","T",0,"C");
        $pdf->Cell($cp+8,$h,"DOCENTES","T",0,"C");
        $pdf->Cell($cp+4,$h,"ALUMNOS","T",1,"C");
        $pdf->SetX($x);
        $pdf->Cell($cs,$h,"","B",0,"C");
        $pdf->Cell($cs,$h,"ACTIVAS","B",0,"C");
        $pdf->Cell($cs,$h,"EVALUADAS","B",0,"C");
        $pdf->Cell($cs,$h,"ACTIVOS","B",0,"C");
        $pdf->Cell($cs,$h,"EVALUADOS","B",0,"C");
        $pdf->Cell($cs,$h,"INSCRITOS","B",0,"C");
        $pdf->Cell($cs-2,$h,"EVALUARON","B",1,"C");
        $consecutivo=EvaluacionAspecto::where('encuesta','=','A')
            ->max('consecutivo');
        $numero_preguntas=Pregunta::where('encuesta','A')
            ->where('consecutivo',$consecutivo)
            ->max('no_pregunta');
        $info_materias=(new EvalDocenteController)
            ->concentrado1_resultado_por_carrera($periodo,$carrera,$ret,$numero_preguntas);
        $mat_existentes=0; $mat_eval=0;
        $doc_asignados=0; $doc_evaluados=0;
        foreach($info_materias as $info){
            $mat_existentes+=$info->mat_existen;
            $mat_eval+=$info->mat_eval;
            $doc_asignados+=$info->docentes;
            $doc_evaluados+=$info->doc_eval;
        }
        $pdf->SetX($x);
        $pdf->SetFont('MM','',9);
        $pdf->Cell($seccion+6,$h,"TOTAL","B",0,"R");
        $pdf->Cell($seccion+4,$h,$mat_existentes,"B",0,"C");
        $pdf->Cell($seccion+8,$h,$mat_eval,"B",0,"C");
        $pdf->Cell($seccion+4,$h,$doc_asignados,"B",0,"C");
        $pdf->Cell($seccion+4,$h,$doc_evaluados,"B",0,"C");
        $estudiantes=(new EvalDocenteController)->alumnos_evaluaron($periodo,$carrera,$ret);
        $pdf->Cell($seccion+6,$h,$estudiantes[0]->inscritos,"B",0,"C");
        $pdf->Cell($seccion+6,$h,$estudiantes[0]->evaluaron,"B",1,"C");
        $pdf->SetX($x);
        $pdf->Cell($seccion+6,$h,"PORCENTAJE","B",0,"C");
        $pdf->Cell($cp+4,$h,round(($mat_eval/$mat_existentes)*100,2)."%","B",0,"C");
        $pdf->Cell($cp+10,$h,round(($doc_evaluados/$doc_asignados)*100,2)."%","B",0,"C");
        $pdf->Cell($cp+3,$h,round(($estudiantes[0]->evaluaron/$estudiantes[0]->inscritos)*100,2)."%","B",1,"C");
        return $pdf;
    }
    public function FancyTable($pdf,$header, $data)
    {
        /* Colors, line width and bold font */
        //$pdf->SetFillColor(69, 171, 82); Este es el original
        $pdf->SetFillColor(8, 0, 120);
        $pdf->SetTextColor(255);
        $pdf->SetDrawColor(209, 212, 207);
        $pdf->SetLineWidth(.3);
        $pdf->SetFont('','B');
        /* Header */
        $w = array(80, 20, 35);
        for($i=0;$i<count($header);$i++)
            $pdf->Cell($w[$i],5, mb_convert_encoding($header[$i], 'ISO-8859-1', 'UTF-8'),1,0,'C',true);
        $pdf->Ln();
        /* Color and font restoration */
        $pdf->SetFillColor(224,235,255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        /* Data */
        $fill = false;
        $x = 41;
        foreach($data as $key=>$value)
        {
            $pdf->SetX($x);
            $pdf->Cell($w[0],4,$value["aspecto"].' '. mb_convert_encoding($value["descripcion"], 'ISO-8859-1', 'UTF-8'),
                'LR',0,'L',$fill);
            $pdf->Cell($w[1],4,$value["porcentaje"],'LR',0,'C',$fill);
            $pdf->Cell($w[2],4,$value["calificacion"],'LR',0,'C',$fill);
            $pdf->Ln();
            $fill = !$fill;
        }
        /* Closing line */
        $pdf->SetX($x);
        $pdf->Cell(array_sum($w),0,'','T');
        return $pdf;
    }
    public function grafica($pdf,$valores,$ruta,$promedio){
        $x=40;
        // Create the graph and setup the basic parameters
        $__width  = 460;
        $__height = 200;
        $valores[]=$promedio;
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
        // Finally stroke the graph
        $graph->Stroke($ruta);
        $pdf->Image($ruta,$pdf->SetX($x),$pdf->GetY(),140,65);
        unlink($ruta);
    }
    public function footer($pdf)
    {
        $img_pie_pagina=$_ENV["RUTA_IMG_PIE_PAGINA"];
        $ypie = 252;
        $xpie = 10;
        $pdf->Image($img_pie_pagina,$xpie,$ypie,200);
        return $pdf;
    }
}
