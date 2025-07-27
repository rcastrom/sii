<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Acciones\AccionesController;
use App\Http\Controllers\Controller;
use App\Models\Aspirante;
use App\Models\Carrera;
use App\Models\ParametroExamenAdmision;
use App\Models\PeriodoEscolar;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\NoReturn;

class ImpresionFichaPDFController extends Controller
{
    public function encabezado($pdf){
        $nombre_tec = mb_convert_encoding($_ENV['NOMBRE_TEC'], 'ISO-8859-1', 'UTF-8');
        // Logo TecNM
        $pdf->Image($_ENV['RUTA_IMG_TECNM'], 20, 7, 36, 20, 'JPG');
        // Logo GobFed
        $pdf->Image($_ENV['RUTA_IMG_GOBFED'], 170, 1, 27, 28, 'JPG');
        $pdf->SetXY(154, 29);
        $pdf->SetFont('Montserrat2', 'B', 8);
        $pdf->Cell(50, 6, $nombre_tec, 0, 1, 'L');
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->SetXY(91, 23);
        $pdf->Cell(50,38,"ASPIRANTE A NUEVO INGRESO",0,1,'L');
        return $pdf;
    }

    public function sinDatos($pdf)
    {
        $pdf->SetXY(25, 80);
        $pdf->SetFont('Montserrat2', 'B', 12);
        $pdf->Cell(150, 6, "NO EXISTE REGISTRO ALGUNO DEL SOLICITANTE A INGRESAR",1, 1, 'C');
        return $pdf;
    }
    public function imprimirFicha($pdf,$datos_aspirante){
        $x = 15;
        $y = 85;
        $pdf->SetXY($x, $y);
        $pdf->SetFont('Arial','',13);
        $pdf->Cell(60,5,'Ficha: '.$datos_aspirante->ficha,0,0,'C');
        $pdf->Cell(60,5,'',0,0,'C');
        $nombre_periodo=PeriodoEscolar::where('periodo',$datos_aspirante->periodo)->first();
        $pdf->Cell(30,5,'Periodo: '.$nombre_periodo->identificacion_corta,0,1,'C');
        $pdf->Ln();
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(25,5,'DATOS',0,0,'L');
        $pdf->Cell(25,5,'Nombre:',0,0,'L');
        $pdf->SetFont('Arial','B',8);
        $nombre_aspirante=$datos_aspirante->apellido_paterno_aspirante." ".$datos_aspirante->apellido_materno_aspirante.
            " ".$datos_aspirante->nombre_aspirante;
        $pdf->Cell(140,5,mb_convert_encoding($nombre_aspirante,'ISO-8859-1','UTF-8'),0,1,'L');
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(25,5,'ASPIRANTE',0,1,'L');
        $pdf->Ln();
        $pdf->Cell(25,5,'DATOS',0,1,'L');
        $pdf->SetFont('Arial','',8);
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(25,5,'ESCOLARES',0,0,'L');
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(25,5,'Preparatoria',0,0,'L');
        $pdf->Cell(120,5,mb_convert_encoding($datos_aspirante->preparatoria,'ISO-8859-1','UTF-8'),0,0,'L');
        $pdf->Cell(20,5,'',0,1,'L');
        $pdf->Cell(25,5,'',0,0,'L');
        $pdf->Cell(25,5,'Municipio y Estado',0,0,'L');
        $pdf->SetFont('Arial','',6);
        $pdf->Cell(50,5,mb_convert_encoding($datos_aspirante->mun_preparatoria,'ISO-8859-1','UTF-8'),0,0,'L');
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(20,5,$datos_aspirante->edo_preparatoria,0,0,'L');
        $pdf->Cell(20,5,'',0,1,'L');

        $pdf->Ln();
        $pdf->SetFont('Arial','B',10);
        $pdf->SetFillColor(193,193,193) ;
        $pdf->Cell(95,8,"Aspirante a : ",1,0,'C',1);
        $carrera=Carrera::where([
            'ofertar'=>true,
            'carrera'=>$datos_aspirante->carrera]
        )->select('nombre_carrera')->first();
        $pdf->Cell(95,8,mb_convert_encoding($carrera->nombre_carrera,'ISO-8859-1','UTF-8'),1,1,'C',0);
        $pdf->Ln();
        $pdf->SetFont('Arial','',8);
        $leyenda='Entrega una copia de esta ficha al Departamento de Desarrollo Académico:';
        $pdf->Cell(190,5,mb_convert_encoding($leyenda,'ISO-8859-1','UTF-8'),0,1,'L');
        if(ParametroExamenAdmision::where([
            'carrera'=>$datos_aspirante->carrera,
            'periodo'=>$datos_aspirante->periodo,
        ])->count()>0){
            $datos_adicionales=ParametroExamenAdmision::where([
                'carrera'=>$datos_aspirante->carrera,
                'periodo'=>$datos_aspirante->periodo,
            ])->select('indicaciones')->first();
            $pdf->Ln();
            $indicaciones=mb_convert_encoding($datos_adicionales->indicaciones,'ISO-8859-1','UTF-8');
            $pdf->MultiCell(190,5,$indicaciones,1,'L');
        }else{
            $pdf->Cell(190,5,'Es posible que posteriormente recibas indicaciones posteriores',0,1,'L');
        }
        return $pdf;
    }
    #[NoReturn] public function crearPDF($identificador){
        $datos_aspirante=(new AccionesController)->ficha_datos($identificador)[0];
        $fpdf =new Fpdf('P','mm','Letter');
        $fpdf->AddPage();
        $fpdf->SetAutoPageBreak(0);
        $fpdf->AddFont("Montserrat2",'','Montserrat-ExtraLight.php');
        $fpdf->AddFont("Montserrat2",'I','Montserrat-ExtraLightItalic.php');
        $fpdf->AddFont("Montserrat2",'B','Montserrat-Light.php');
        $fpdf->AddFont("Montserrat2",'BI','Montserrat-SemiBoldItalic.php');
        $x = 15;
        $y = 80; //Original 120
        $w = 180;
        $h = 4;
        $this->encabezado($fpdf);
        $fpdf->SetFont("Montserrat2","",6);
        if(Aspirante::where('ficha',$datos_aspirante->ficha)->count()>0){
            $this->imprimirFicha($fpdf,$datos_aspirante);
        }else{
            $this->sinDatos($fpdf);
        }
        $fpdf->Output();
        exit();

    }
}
