<?php

namespace App\Http\Controllers\PDF;

use App\Models\Alumno;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Acciones\AccionesController;
use App\Models\Idioma;
use App\Models\IdiomasLiberacion;
use App\Models\Jefe;
use App\Models\Organigrama;
use App\Models\Personal;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Http\Request;

class IdiomasPDFController extends Controller
{
    private $fpdf;
    public function __construct(){

    }
    public function mes_espanol($mes)
    {
        switch ($mes)
        {
            case '01': return 'Enero'; break;
            case '02': return 'Febrero'; break;
            case '03': return 'Marzo'; break;
            case '04': return 'Abril'; break;
            case '05': return 'Mayo'; break;
            case '06': return 'Junio'; break;
            case '07': return 'Julio'; break;
            case '08': return 'Agosto'; break;
            case '09': return 'Septiembre'; break;
            case '10': return 'Octubre'; break;
            case '11': return 'Noviembre'; break;
            case '12': return 'Diciembre'; break;
        }
    }

    public function mes($mes){
        switch($mes){
            case '01': $nmes="enero"; break;
            case '02': $nmes="febrero"; break;
            case '03': $nmes="marzo"; break;
            case '04': $nmes="abril"; break;
            case '05': $nmes="mayo"; break;
            case '06': $nmes="junio"; break;
            case '07': $nmes="julio"; break;
            case '08': $nmes="agosto"; break;
            case '09': $nmes="septiembre"; break;
            case '10': $nmes="octubre"; break;
            case '11': $nmes="noviembre"; break;
            case '12': $nmes="diciembre"; break;
        }
        return $nmes;
    }
    public function encabezado($pdf,$depto,$folio,$dia,$mes,$anio){
        //$pdf->Image("/var/www/html/escolares/public/img/aguila.jpg",0,0,'','','JPG');
        // Logo SEP
        //$pdf->Image("/var/www/html/escolares/public/img/educacion.jpg",25,10,77,22,'JPG');
        $pdf->Image("/var/www/html/escolares/public/img/escudo_2021.jpg",25,10,110,18,'JPG');
        // Logo TecNM
        //$pdf->Image("/var/www/html/escolares/public/img/tecnm.jpg",150,12,34,18,'JPG');
        //Leyenda
        $pdf->AddFont('MM','','Montserrat-Medium.php');
        $pdf->AddFont('MM','B','Montserrat-Bold.php');
        $pdf->SetFont('MM','B',9);
        $pdf->SetXY(140,30);

        $ndepto=Organigrama::where('clave_area',$depto)->first();
        $pdf->Cell(50,6,utf8_decode("Instituto Tecnológico de Ensenada"),0,1,'L');
        $pdf->SetFont('MM','',8);
        $pdf->SetXY(140,34);
        $pdf->Cell(50,6,$ndepto->descripcion_area,0,1,'L');
        $pdf->SetFont('MM','B',8);
        // $pdf->Cell(200,5,utf8_decode("\"2020, Año de Leona Vicario, Benemérita Madre de la Patria \""),0,1,"C");
        $asunto=utf8_decode("LIBERACIÓN IDIOMA EXTRANJERO");
        $h 	= 4;
        $wt = 27;
        $wd = 36;
        $y 	= 48;//Original 48
        $xt = 140;
        $b  = $h+1.5;
        $xd = $xt + $wt;
        $pdf->SetXY($xt, $y);
        //1ra linea
        $pdf->Cell($wt,$h,"Ensenada, BC.,",0,0,"L");
        $pdf->SetXY($xd,$y);
        $pdf->SetTextColor(255,255,255);
        $fecha=$dia."/".$this->mes($mes)."/".$anio;
        $pdf->Cell($wd,$h,$fecha,0,1,"L",true);
        $pdf->SetTextColor(0,0,0);
        //2da linea
        $pdf->SetXY($xt,$y+$b);
        //$pdf->Cell($wt,$h,"Oficio",0,0,"L");
        //$pdf->SetXY($xd,$y+$b);
        //$pdf->SetTextColor(255,255,255);
        $pdf->Cell($wd,$h,$folio,0,0,"L");
        $pdf->SetTextColor(0,0,0);
        //4ta linea
        $pdf->SetXY($xt,$y+2*$b);
        $pdf->Cell($wt,$h,"Asunto",0,0,"L");
        $pdf->SetXY($xd,$y+2*$b);
        //$pdf->SetTextColor(255,255,255);
        $pdf->MultiCell($wd,$h,$asunto,0,"L");
        $pdf->SetTextColor(0,0,0);
        return $pdf;
    }
    public function nperiodo($periodo, $largo=false)
    {
        if(substr($periodo,4,1) == '1') { return (($largo)?"ENERO-JUNIO/":"ENE-JUN/").substr($periodo,0,4);}
        if(substr($periodo,4,1) == '2') { return "VERANO/".substr($periodo,0,4);}
        if(substr($periodo,4,1) == '3') { return (($largo)?"AGOSTO-DICIEMBRE/":"AGO-DIC/").substr($periodo,0,4);}
    }

    public function crearPDF(Request $request){
        $control=$request->get('control');
        $alumno=Alumno::findOrfail($control);
        $fexpedicion=$request->get('fexpedicion');
        $datos_fecha=explode("-",$fexpedicion);
        $anio=$datos_fecha[0]; $mes=$datos_fecha[1]; $dia=$datos_fecha[2];
        $depto="120600";
        $nombre_alumno = trim($alumno->nombre_alumno).' '.trim($alumno->apellido_paterno).' '.trim($alumno->apellido_materno);
        $ncarrera=(new AccionesController)->ncarrera($alumno->carrera,$alumno->reticula);
        if($alumno->sexo == 'F')
        {
            $genero_a = "a";
            $prop_a 	= "la";
        }
        else
        {
            $genero_a = "o";
            $prop_a		=	"el";
        }
        $rfc_jefe=Jefe::where('clave_area',$depto)->first();
        $jefatura=Personal::where('rfc',$rfc_jefe->rfc)->first();
        if($jefatura->sexo_empleado == 'F')
        {
            $genero_j = "JEFA";
            $prop_j 	= "La";
            $gen_j 		= "Jefa";
        }
        else
        {
            $genero_j = "JEFE";
            $prop_j		= "El";
            $gen_j 		= "Jefe";
        }
        $idiomas=IdiomasLiberacion::where('control',$control)->first();
        $idioma=Idioma::where('id',$idiomas->idioma)->first();
        $opcion=$idiomas->opcion;
        switch($opcion)
        {
            case 'X': $descrip_opcion = "Examen"; break;
            case 'A': $descrip_opcion = "Aprobación del curso"; break;
            case 'D': $descrip_opcion = "Diplomado"; break;
            case 'E': $descrip_opcion = "Institución Externa"; break;
        }
        $cuerpo=$prop_j." que suscribe, ".$gen_j." del Departamento de Servicios Escolares, hace constar ".
            "que ".$prop_a." alumn".$genero_a." $nombre_alumno con número de control ".
            $control." de la carrera de ".$ncarrera->nombre_carrera." con plan de estudios ".trim($ncarrera->clave_oficial).", ".
            "ACREDITÓ la lengua extranjera ".trim($idioma->idiomas)." por la opción ".$descrip_opcion."\n\n".
            "Se extiende la presente CONSTANCIA en la Ciudad y Puerto de Ensenada, a los $dia días del mes de ".$this->mes($mes)." del ".
            "año $anio, para los fines que al interesado convengan.";
        $this->fpdf=new Fpdf('P','mm','Letter');
        $this->fpdf->AddPage();
        $this->fpdf->SetAutoPageBreak(0);
        $this->fpdf->AddFont('MM','','Montserrat-Medium.php');
        $this->fpdf->AddFont('MM','B','Montserrat-Bold.php');
        $this->fpdf->AddFont("Montserrat2",'','Montserrat-ExtraLight.php');
        $this->fpdf->AddFont("Montserrat2",'I','Montserrat-ExtraLightItalic.php');
        $this->fpdf->AddFont("Montserrat2",'B','Montserrat-Light.php');
        $this->fpdf->AddFont("Montserrat2",'BI','Montserrat-SemiBoldItalic.php');
        $x = 15;
        $y = 80; //Original 120
        $w = 180;
        $h = 4;
        $yr=date("y");
        $oficio="EXPEDIENTE ".$control."/".$yr;
        $this->encabezado($this->fpdf,$depto,$oficio,$dia,$mes,$anio);
        $this->fpdf->SetXY($x, $y);
        $titulo_persona="A QUIEN CORRESPONDA:";
        $ancho = $h*4;
        $centra='L';
        $this->fpdf->SetFont('MM','B',10);
        $this->fpdf->Cell($w, $ancho, $titulo_persona, 0, 2, $centra);
        $this->fpdf->SetFont("MM",'',9);
        $this->fpdf->SetFont("MM",'',9);
        $this->fpdf->MultiCell($w, $h, utf8_decode($cuerpo), 0, 'J');
        $this->fpdf->SetX($x);
        $this->fpdf->Ln(3);
        $this->fpdf->SetX($x);
        $this->fpdf->SetFont("MM",'B',9);
        $this->fpdf->Cell($w,$h,"A T E N T A M E N T E",0,1,'L');
        $this->fpdf->SetFont("MM",'B',8);
        //Lema TecNM
        $this->fpdf->SetX($x);
        $lema2=strtoupper("Excelencia en Educación Tecnológica");
        $this->fpdf->Cell($w,$h,utf8_decode($lema2),0,1,'L');
        //Lema Tec
        //$this->fpdf->SetFont("Montserrat2",'I',7);
        $this->fpdf->SetX($x);
        $lema=strtoupper("Por la Tecnología de Hoy y del Futuro");
        $this->fpdf->Cell($w,$h,utf8_decode($lema),0,1,'L');
        //$pdf->AddFont("SoberanaSans_Bold",'','soberanasans_bold.php');
        $this->fpdf->SetFont("MM",'B',9);
        $jefe2 = $rfc_jefe->jefe_area;
        $jefe2g=$genero_j." DEL DEPARTAMENTO DE SERVICIOS ESCOLARES";
        $this->fpdf->SetX($x);
        $this->fpdf->Cell(80,9," ",0,1,'L');
        $this->fpdf->SetX($x);
        $this->fpdf->Cell($w,$h,$jefe2,0,1,'L');
        $this->fpdf->SetX($x);
        $this->fpdf->Cell($w,$h,$jefe2g,0,1,'L');
        //$pdf->MultiCell($w, $h, "ATENTAMENTE,\n".$CFG->lema."\n\n\n\n".$jefe, 0, 'J');


        $ypie = 262;
        $xpie = 10;
        $this->fpdf->SetLineWidth(0.1);
        $this->fpdf->SetDrawColor(128,0,0);
        $this->fpdf->Line($xpie+10,$ypie-6,190,$ypie-6);

        $this->fpdf->Image("/var/www/html/escolares/public/img/escudo.jpg", 20, $ypie, 15);
        $this->fpdf->Image("/var/www/html/escolares/public/img/calidad1.jpg", 35, $ypie, 15);
        $this->fpdf->Image("/var/www/html/escolares/public/img/reciclado1.jpg", 50, $ypie, 15);

        $w = 120;
        $h = 6;
        $xpie+=40+5;
        $this->fpdf->SetXY($xpie+5, $ypie);
        $this->fpdf->AddFont("Montserrat2",'','Montserrat-ExtraLight.php');
        $this->fpdf->AddFont("Montserrat2",'B','Montserrat-Light.php');
        $this->fpdf->SetFont("Montserrat2","",6);
        $this->fpdf->Cell($w, $h/3, "", 0, 2, 'C');
        $this->fpdf->Cell($w-15, $h/2, utf8_decode("Blvd Tecnológico # 150, Col. Ex Ejido Chapultepec, C.P. 22780, Ensenada B.C"), 0, 2, 'C');
        $this->fpdf->Cell($w-15, $h/2, "Tel(s). (646)177-5680 y 82 ", 0, 2, 'C');
        $this->fpdf->SetFont("Montserrat2",'B',6);
        $this->fpdf->Cell($w-15, $h/2, "E-mail: escolares@ite.edu.mx, Sitio Web https://www.ensenada.tecnm.mx", 0, 2, 'C');
        //$this->fpdf->Image("/var/www/html/escolares/public/img/calidad.jpg", 168, 263, 17,15);
        $this->fpdf->Image("/var/www/html/escolares/public/img/escudo_aguila2.jpg", 168, $ypie-5, 25,21);
        $this->fpdf->SetLineWidth(0.1);
        $this->fpdf->SetDrawColor(0);
        $this->fpdf->Output();
        exit();
    }
}
