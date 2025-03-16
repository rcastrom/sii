<?php

namespace App\Http\Controllers\PDF;

use Amenadiel\JpGraph\Graph;
use Amenadiel\JpGraph\Plot;
use App\Http\Controllers\Acciones\AccionesController;
use App\Http\Controllers\Controller;
use App\Models\EvaluacionAlumno;
use App\Models\EvaluacionAspecto;
use App\Models\PeriodoEscolar;
use App\Models\Personal;
use App\Models\Pregunta;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\NoReturn;

class EvalDocDocentePDFController extends Controller
{
    /**
     * Handle the incoming request.
     */
    #[NoReturn]
    public function __invoke(Request $request)
    {
        $periodo = $request->periodo;
        $docente = $request->docente;
        $pdf = new Fpdf('P', 'mm', 'Letter');
        $pdf->AddPage();
        $pdf->SetAutoPageBreak(0);
        if (EvaluacionAlumno::where('periodo', $periodo)
            ->where('personal', $docente)->count() == 0) {
            $pdf->SetFont('Arial', 'B', 16);
            $pdf->Cell('130', '5', 'Aun no hay datos por mostrar', 0, 1, 'C');
            $pdf->Output();
            exit();
        }
        $pdf->AddFont('MM', '', 'Montserrat-Medium.php');
        $pdf->AddFont('MM', 'B', 'Montserrat-Bold.php');
        $pdf->AddFont('Montserrat2', '', 'Montserrat-ExtraLight.php');
        $pdf->AddFont('Montserrat2', 'I', 'Montserrat-Thin.php');
        $pdf->AddFont('Montserrat2', 'B', 'Montserrat-Light.php');
        $pdf->AddFont('Montserrat2', 'BI', 'Montserrat-SemiBold.php');
        $x = 25;
        $y = 50;
        $w = 180;
        $h = 4;
        $this->encabezado($pdf);
        $nombre_periodo = PeriodoEscolar::where('periodo', $periodo)
            ->select('identificacion_larga')
            ->first();
        $nombre_docente = Personal::where('id', $docente)
            ->select(['apellidos_empleado', 'nombre_empleado'])
            ->first();
        $consecutivo = EvaluacionAspecto::where('encuesta', '=', 'A')
            ->max('consecutivo');
        $numero_preguntas = Pregunta::where('encuesta', 'A')
            ->where('consecutivo', $consecutivo)
            ->max('no_pregunta');
        $pdf->SetXY($x, $y);
        $pdf->SetFont('MM', 'B', 10);
        $pdf->Cell($w, $h,
            mb_convert_encoding('RESULTADOS DE LA EVALUACIÓN DOCENTE DEL PERÍODO '.$nombre_periodo->identificacion_larga,
                'ISO-8859-1', 'UTF-8'), 0, 2, 'L');
        $pdf->SetFont('MM', 'B', 8);
        $pdf->Cell($w, $h, mb_convert_encoding(
            trim($nombre_docente->apellidos_empleado).' '.trim($nombre_docente->nombre_empleado),
            'ISO-8859-1', 'UTF-8'),
            0, 2, 'L');
        $this->tabla_materias($pdf, $periodo, $docente, $numero_preguntas);
        // Resultados
        $pdf->Ln(4);
        $x = 41;
        $pdf->SetX($x);
        $pdf->SetFont('MM', 'B', 8);
        $data = $this->resultados($periodo, $docente);
        $header = ['Aspectos a evaluar', 'Porcentaje', 'Descripción'];
        $this->FancyTable($pdf, $header, $data);
        $valores = [];
        $i = 0;
        $suma = 0;
        foreach ($data as $value) {
            $valores[$i] = $value['porcentaje'];
            $suma += $value['porcentaje'];
            $i++;
        }
        $promedio = round($suma / $i, 2);
        switch ($promedio) {
            case $promedio >= 1 && $promedio <= 3.24: $cal = 'INSUFICIENTE';
                break;
            case $promedio >= 3.25 && $promedio <= 3.74: $cal = 'SUFICIENTE';
                break;
            case $promedio >= 3.75 && $promedio <= 4.24: $cal = 'BUENO';
                break;
            case $promedio >= 4.25 && $promedio <= 4.74: $cal = 'NOTABLE';
                break;
            case $promedio >= 4.75 && $promedio <= 5: $cal = 'EXCELENTE';
                break;

        }
        $pdf->SetX($x);
        $pdf->SetFont('MM', 'B', 9);
        $pdf->Cell(80, 4, 'Promedio General', 'LR', 0, 'R');
        $pdf->Cell(20, 4, $promedio, 'LR', 0, 'C');
        $pdf->Cell(35, 4, $cal, 'LR', 1, 'C');
        // Ahora, el gráfico
        $pdf->Ln(5);
        $nombre = $_ENV['UBICACION_CREAR_IMAGENES'].$docente.$periodo.'.png';
        $this->grafica($pdf, $valores, $nombre, $promedio);
        // Pie de página
        $this->footer($pdf);
        $pdf->Output();
        exit();
    }

    public function encabezado($pdf)
    {
        $escudo_tecnm = $_ENV['RUTA_IMG_TECNM'];
        $mujer_emblema = $_ENV['RUTA_IMG_GOBFED'];
        $nombre_tec = mb_convert_encoding($_ENV['NOMBRE_TEC'], 'ISO-8859-1', 'UTF-8');
        $departamento = mb_convert_encoding('Departamento de Desarrollo Académico', 'ISO-8859-1', 'UTF-8');
        // Logo TecNM
        $pdf->Image($escudo_tecnm, 20, 7, 36, 20, 'JPG');
        // Logo GobFed
        $pdf->Image($mujer_emblema, 170, 1, 27, 28, 'JPG');
        $pdf->SetXY(154, 29);
        $pdf->SetFont('Montserrat2', 'B', 8);
        $pdf->Cell(50, 6, $nombre_tec, 0, 1, 'L');
        $pdf->SetXY(146, 33);
        $pdf->Cell(50, 4, $departamento, 0, 1, 'L');

        return $pdf;
    }

    public function tabla_materias($pdf, $periodo, $docente, $maximo)
    {
        $x = 25;
        $y = 65;
        $w = 100;
        $h = 4;
        $seccion = 20;
        $pdf->SetXY($x, $y);
        $pdf->SetFont('MM', 'B', 9);
        $pdf->Cell($w, $h, 'MATERIA', 'B', 0, 'L');
        $pdf->Cell($seccion, $h, 'GRUPO', 'B', 0, 'C');
        $pdf->Cell($seccion + 4, $h, 'INSCRITOS', 'B', 0, 'C');
        $pdf->Cell($seccion + 4, $h, 'EVALUARON', 'B', 1, 'C');
        $info_evaluado = (new AccionesController)->evaluacion_al_docente_datos($periodo, $docente, $maximo);
        $inscritos = 0;
        $evaluados = 0;
        $pdf->SetFont('MM', '', 9);
        foreach ($info_evaluado as $info) {
            $pdf->SetX($x);
            $pdf->Cell($w, $h, mb_convert_encoding($info->nombre_completo_materia, 'ISO-8859-1', 'UTF-8').'/'.$info->materia, 'B', 0, 'L');
            $pdf->Cell($seccion, $h, $info->grupo, 'B', 0, 'C');
            $pdf->Cell($seccion + 4, $h, $info->alumnos_inscritos, 'B', 0, 'C');
            $pdf->Cell($seccion + 4, $h, $info->evaluaron, 'B', 1, 'C');
            $inscritos += $info->alumnos_inscritos;
            $evaluados += $info->evaluaron;
        }
        $pdf->SetX($x);
        $pdf->SetFont('MM', 'B', 9);
        $pdf->Cell($w + $seccion, $h, 'TOTAL', 'B', 0, 'R');
        $pdf->Cell($seccion + 4, $h, $inscritos, 'B', 0, 'C');
        $pdf->Cell($seccion + 4, $h, $evaluados, 'B', 1, 'C');
        $pdf->SetX($x);
        $pdf->Cell($w + $seccion + $seccion + 2, $h, 'PORCENTAJE', 'B', 0, 'C');
        $pdf->Cell($seccion + 6, $h, round(($evaluados / $inscritos) * 100, 2).'%', 'B', 1, 'C');

        return $pdf;
    }

    public function resultados($periodo, $docente)
    {
        $resultados = [];
        $i = 0; // Este es el contador inicial para el registro de información
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
                    $obtenido = (new AccionesController)
                        ->resultados_evaluacion_al_docente($periodo, $pregunta->no_pregunta, $materia, $gpo);
                    foreach ($obtenido as $obt) {
                        switch ($obt->respuesta) {
                            case '1':
                            case 'A': $valor_resp[0] += $obt->cantidad;
                                break;
                            case '2':
                            case 'B': $valor_resp[1] += $obt->cantidad;
                                break;
                            case '3':
                            case 'C': $valor_resp[2] += $obt->cantidad;
                                break;
                            case '4':
                            case 'D': $valor_resp[3] += $obt->cantidad;
                                break;
                            case '5':
                            case 'E': $valor_resp[4] += $obt->cantidad;
                                break;
                            default: $valor_resp[5] += $obt->cantidad;
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
                case $porcentaje >= 1 && $porcentaje <= 3.24: $cal = 'INSUFICIENTE';
                    break;
                case $porcentaje >= 3.25 && $porcentaje <= 3.74: $cal = 'SUFICIENTE';
                    break;
                case $porcentaje >= 3.75 && $porcentaje <= 4.24: $cal = 'BUENO';
                    break;
                case $porcentaje >= 4.25 && $porcentaje <= 4.74: $cal = 'NOTABLE';
                    break;
                case $porcentaje >= 4.75 && $porcentaje <= 5: $cal = 'EXCELENTE';
                    break;
            }
            $resultados[$i]['aspecto'] = $aspecto->aspecto;
            $resultados[$i]['descripcion'] = $aspecto->descripcion;
            $resultados[$i]['porcentaje'] = $porcentaje;
            $resultados[$i]['calificacion'] = $cal;
            $i++;
        }

        return $resultados;
    }

    public function FancyTable($pdf, $header, $data)
    {
        /* Colors, line width and bold font */
        // $pdf->SetFillColor(69, 171, 82); Este es el original
        $pdf->SetFillColor(8, 0, 120);
        $pdf->SetTextColor(255);
        $pdf->SetDrawColor(209, 212, 207);
        $pdf->SetLineWidth(.3);
        $pdf->SetFont('', 'B');
        /* Header */
        $w = [80, 20, 35];
        for ($i = 0; $i < count($header); $i++) {
            $pdf->Cell($w[$i], 5, mb_convert_encoding($header[$i], 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', true);
        }
        $pdf->Ln();
        /* Color and font restoration */
        $pdf->SetFillColor(224, 235, 255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        /* Data */
        $fill = false;
        $x = 41;
        foreach ($data as $key => $value) {
            $pdf->SetX($x);
            $pdf->Cell($w[0], 4, $value['aspecto'].' '.mb_convert_encoding($value['descripcion'], 'ISO-8859-1', 'UTF-8'), 'LR', 0, 'L', $fill);
            $pdf->Cell($w[1], 4, $value['porcentaje'], 'LR', 0, 'C', $fill);
            $pdf->Cell($w[2], 4, $value['calificacion'], 'LR', 0, 'C', $fill);
            $pdf->Ln();
            $fill = ! $fill;
        }
        /* Closing line */
        $pdf->SetX($x);
        $pdf->Cell(array_sum($w), 0, '', 'T');

        return $pdf;
    }

    public function grafica($pdf, $valores, $ruta, $promedio)
    {
        $x = 40;
        // Create the graph and setup the basic parameters
        $__width = 460;
        $__height = 200;
        $valores[] = $promedio;
        $graph = new Graph\Graph($__width, $__height, 'auto');
        $graph->img->SetMargin(40, 30, 30, 40);
        $graph->SetScale('textint', 1, 5);
        $graph->SetShadow();
        $graph->SetFrame(false); // No border around the graph
        // Add some grace to the top so that the scale doesn't
        // end exactly at the max value.
        $graph->yaxis->scale->SetGrace(7);
        // Setup X-axis labels
        $a = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'Prom'];
        $graph->xaxis->SetTickLabels($a);
        $graph->xaxis->SetFont(FF_FONT2);
        // Setup graph title ands fonts
        $graph->title->Set(mb_convert_encoding('Evaluación Docente', 'ISO-8859-1', 'UTF-8'));
        $graph->title->SetFont(FF_FONT2, FS_BOLD);
        // Create a bar pot
        $bplot = new Plot\BarPlot($valores);
        $bplot->SetFillColor('orange');
        foreach ($valores as $key => $value) {
            if ($key <= 9) {
                switch ($value) {
                    case $value >= 1 && $value <= 3.24: $barcolors[] = '#FF0000';
                        break;
                    case $value >= 3.25 && $value <= 3.74: $barcolors[] = '#FFCC00';
                        break;
                    case $value >= 3.75 && $value <= 4.24: $barcolors[] = '#FF6321';
                        break;
                    case $value >= 4.25 && $value <= 4.74: $barcolors[] = '#FF42FF';
                        break;
                    case $value >= 4.75 && $value <= 5: $barcolors[] = '#0042FF';
                        break;
                }
            } else {
                $barcolors[] = 'green';
            }
        }
        $bplot->SetFillColor($barcolors);
        $bplot->SetValuePos('center');
        $bplot->SetWidth(0.55);
        $bplot->SetShadow();
        $bplot->value->format = '%1.2f';
        // Setup the values that are displayed on top of each bar
        $bplot->value->Show();
        // Must use TTF fonts if we want text at an arbitrary angle
        $bplot->value->SetFont(FF_COURIER, FS_NORMAL,7);
        $bplot->value->SetAngle(90);
        $bplot->value->valign = 'center';
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
        $img_pie_pagina = $_ENV['RUTA_IMG_PIE_PAGINA'];
        $ypie = 252;
        $xpie = 10;
        $pdf->Image($img_pie_pagina,$xpie,$ypie,200);

        return $pdf;
    }
}
