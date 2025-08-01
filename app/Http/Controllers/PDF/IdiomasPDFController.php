<?php

namespace App\Http\Controllers\PDF;

use App\Models\Alumno;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Acciones\AccionesController;
use App\Models\Idioma;
use App\Models\IdiomasLiberacion;
use App\Models\Jefe;
use App\Models\Personal;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\NoReturn;

class IdiomasPDFController extends Controller
{
    public function __construct(){

    }
    public function mes($mes)
    {
        $nombre_mes='';
        switch ($mes)
        {
            case '01': $nombre_mes= 'enero'; break;
            case '02': $nombre_mes= 'febrero'; break;
            case '03': $nombre_mes= 'marzo'; break;
            case '04': $nombre_mes= 'abril'; break;
            case '05': $nombre_mes= 'mayo'; break;
            case '06': $nombre_mes= 'junio'; break;
            case '07': $nombre_mes= 'julio'; break;
            case '08': $nombre_mes= 'agosto'; break;
            case '09': $nombre_mes= 'septiembre'; break;
            case '10': $nombre_mes= 'octubre'; break;
            case '11': $nombre_mes= 'noviembre'; break;
            case '12': $nombre_mes= 'diciembre'; break;
        }
        return $nombre_mes;
    }

    public function encabezado($pdf,$depto,$folio,$dia,$mes,$anio){
        $nombre_tec = mb_convert_encoding($_ENV['NOMBRE_TEC'], 'ISO-8859-1', 'UTF-8');
        // Logo TecNM
        $pdf->Image($_ENV['RUTA_IMG_TECNM'], 20, 7, 36, 20, 'JPG');
        // Logo GobFed
        $pdf->Image($_ENV['RUTA_IMG_GOBFED'], 170, 1, 27, 28, 'JPG');
        $pdf->SetXY(154, 29);
        $pdf->SetFont('Montserrat', 'B', 8);
        $pdf->Cell(50, 6, $nombre_tec, 0, 1, 'L');
        $pdf->SetXY(146, 33);
        $ndepto=Jefe::where('clave_area',$depto)->first();
        //$pdf->Cell(50,6,$nombre_tec,0,1,'L');
        $pdf->SetFont('Montserrat','',8);
        $pdf->SetXY(140,34);
        $pdf->Cell(50,6,"DEPARTAMENTO DE SERVICIOS ESCOLARES",0,1,'L');
        $pdf->SetFont('Montserrat','B',8);
        // $pdf->Cell(200,5,utf8_decode("\"2020, Año de Leona Vicario, Benemérita Madre de la Patria \""),0,1,"C");
        $asunto= mb_convert_encoding("LIBERACIÓN IDIOMA EXTRANJERO", 'ISO-8859-1', 'UTF-8');
        $h 	= 4;
        $wt = 27;
        $wd = 36;
        $y 	= 48;//Original 48
        $xt = 140;
        $b  = $h+1.5;
        $xd = $xt + $wt;
        $pdf->SetXY($xt, $y);
        //1ra linea
        $ciudad=mb_convert_encoding($_ENV["CIUDAD_OFICIOS"],'ISO-8859-1', 'UTF-8');
        $pdf->Cell($wt,$h,$ciudad,0,0,"L");
        $pdf->SetXY($xd,$y);
        $pdf->SetTextColor(255,255,255);
        $fecha=$dia."/".$this->mes($mes)."/".$anio;
        $pdf->Cell($wd,$h,$fecha,0,1,"L",true);
        $pdf->SetTextColor(0,0,0);
        //2da linea
        $pdf->SetXY($xt,$y+$b);
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
        if(substr($periodo,4,1) == '1') {
            return (($largo)?"ENERO-JUNIO/":"ENE-JUN/").substr($periodo,0,4);
        }elseif(substr($periodo,4,1) == '2') {
            return "VERANO/".substr($periodo,0,4);
        }else{
            return (($largo)?"AGOSTO-DICIEMBRE/":"AGO-DIC/").substr($periodo,0,4);
        }
    }

    #[NoReturn] public function crearPDF(Request $request){
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
        $jefatura=Personal::where('id',$rfc_jefe->id_jefe)->first();
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
            "Se extiende la presente CONSTANCIA en la ciudad de ".$_ENV["CIUDAD_OFICIOS"].", a los $dia días del mes de ".$this->mes($mes)." del ".
            "año $anio, para los fines que al interesado convengan.";
        $fpdf =new Fpdf('P','mm','Letter');
        $fpdf->AddPage();
        $fpdf->SetAutoPageBreak(0);
        $fpdf->AddFont("Montserrat",'','Montserrat-Regular.php');
        $fpdf->AddFont("Montserrat",'I','Montserrat-ExtraLightItalic.php');
        $fpdf->AddFont("Montserrat",'B','Montserrat-Bold.php');
        $fpdf->AddFont("Montserrat",'BI','Montserrat-BoldItalic.php');
        $x = 15;
        $y = 80; //Original 120
        $w = 180;
        $h = 4;
        $yr=date("y");
        $oficio="EXPEDIENTE ".$control."/".$yr;
        $this->encabezado($fpdf,$depto,$oficio,$dia,$mes,$anio);
        $fpdf->SetXY($x, $y);
        $titulo_persona="A QUIEN CORRESPONDA:";
        $ancho = $h*4;
        $centra='L';
        $fpdf->SetFont('Montserrat','B',10);
        $fpdf->Cell($w, $ancho, $titulo_persona, 0, 2, $centra);
        $fpdf->SetFont("Montserrat",'',9);
        $fpdf->SetFont("Montserrat",'',9);
        $fpdf->MultiCell($w, $h, mb_convert_encoding($cuerpo, 'ISO-8859-1', 'UTF-8'));
        $fpdf->SetX($x);
        $fpdf->Ln(3);
        $fpdf->SetX($x);
        $fpdf->SetFont("Montserrat",'B',9);
        $fpdf->Cell($w,$h,"A T E N T A M E N T E",0,1,'L');
        $fpdf->SetFont("Montserrat",'BI',8);
        //Lema TecNM
        $fpdf->SetX($x);
        $lema2=strtoupper("Excelencia en Educación Tecnológica");
        $fpdf->Cell($w,$h, mb_convert_encoding($lema2, 'ISO-8859-1', 'UTF-8'),0,1,'L');
        //Lema Tec
        //$this->fpdf->SetFont("Montserrat2",'I',7);
        $fpdf->SetX($x);
        $lema=$_ENV["LEMA_TEC"];
        $fpdf->Cell($w,$h, mb_convert_encoding($lema, 'ISO-8859-1', 'UTF-8'),0,1,'L');
        //$pdf->AddFont("SoberanaSans_Bold",'','soberanasans_bold.php');
        $fpdf->SetFont("Montserrat",'B',9);
        $jefe2 = trim($jefatura->nombre_empleado).' '.trim($jefatura->apellidos_empleado);
        $jefe2g=$genero_j." DEL DEPARTAMENTO DE SERVICIOS ESCOLARES";
        $fpdf->SetX($x);
        $fpdf->Cell(80,9," ",0,1,'L');
        $fpdf->SetX($x);
        $fpdf->Cell($w,$h,$jefe2,0,1,'L');
        $fpdf->SetX($x);
        $fpdf->Cell($w,$h,$jefe2g,0,1,'L');
        //$pdf->MultiCell($w, $h, "ATENTAMENTE,\n".$CFG->lema."\n\n\n\n".$jefe, 0, 'J');


        $ypie = 262;
        $xpie = 10;
        $fpdf->SetLineWidth(0.1);
        $fpdf->SetDrawColor(128,0,0);
        $fpdf->Line($xpie+10,$ypie-6,190,$ypie-6);

        $fpdf->Image("/var/www/html/escolares/public/img/escudo.jpg", 20, $ypie, 15);
        $fpdf->Image("/var/www/html/escolares/public/img/calidad1.jpg", 35, $ypie, 15);
        $fpdf->Image("/var/www/html/escolares/public/img/reciclado1.jpg", 50, $ypie, 15);

        $w = 120;
        $h = 6;
        $xpie+=40+5;
        $fpdf->SetXY($xpie+5, $ypie);
        $fpdf->AddFont("Montserrat2",'','Montserrat-ExtraLight.php');
        $fpdf->AddFont("Montserrat2",'B','Montserrat-Light.php');
        $fpdf->SetFont("Montserrat2","",6);
        $fpdf->Cell($w, $h/3, "", 0, 2, 'C');
        $fpdf->Cell($w-15, $h/2, mb_convert_encoding($_ENV["DOMICILIO_TEC"], 'ISO-8859-1', 'UTF-8'), 0, 2, 'C');
        $fpdf->Cell($w-15, $h/2, "Tel(s). ".$_ENV["TELEFONO_TEC"], 0, 2, 'C');
        $fpdf->SetFont("Montserrat2",'B',6);
        $fpdf->Cell($w-15, $h/2, "E-mail: escolares@ite.edu.mx, Sitio Web https://www.ensenada.tecnm.mx", 0, 2, 'C');
        //$this->fpdf->Image("/var/www/html/escolares/public/img/calidad.jpg", 168, 263, 17,15);
        $fpdf->Image("/var/www/html/escolares/public/img/escudo_aguila2.jpg", 168, $ypie-5, 25,21);
        $fpdf->SetLineWidth(0.1);
        $fpdf->SetDrawColor(0);
        $fpdf->Output();
        exit();
    }
}
