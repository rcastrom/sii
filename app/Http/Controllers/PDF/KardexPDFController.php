<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use App\Models\Alumno;
use App\Models\Parametro;
use App\Http\Controllers\Acciones\AccionesController;
use Illuminate\Http\Request;
use Codedge\Fpdf\Fpdf\Fpdf;
use IntlDateFormatter;
use JetBrains\PhpStorm\NoReturn;

class KardexPDFController extends Controller
{
    private $fpdf;

    public function __construct(){

    }

     function Header($control){
        $x = 40;
        $y = 19;
        $ancho_imagen = 115;
        $altura_imagen = 80;
        $imagen_tecnm=asset('img/tecnm.jpg');
        $imagen_tec=asset('img/escudo.jpg');
        $this->fpdf->Image($imagen_tecnm,$x,$y,$ancho_imagen,$altura_imagen);
        $this->fpdf->SetXY($x+$ancho_imagen + 40,$y+$altura_imagen*0.28);
        $this->fpdf->SetFont('Times', 'B', 9);
        $this->fpdf->Cell(250,4,utf8_decode("TECNOLÓGICO NACIONAL DE MÉXICO"), 0,0,"C");
        $this->fpdf->Image($imagen_tec,$x+470,$y+10,55,50);
        $this->fpdf->SetXY($x+$ancho_imagen + 40,$y+$altura_imagen*0.48);
        $this->fpdf->SetFont('Times', 'B', 8);
        $generales = Parametro::first();
        $this->fpdf->Cell(250,4,utf8_decode($generales->tec), 0,1,"C");
        $this->fpdf->SetXY($x+$ancho_imagen + 40,$y+$altura_imagen*0.71);
        $this->fpdf->Cell(250,4,utf8_decode("HISTORIAL ACADÉMICO"), 0,1,"C");
        $y = $this->fpdf->GetY()+30;
        $this->fpdf->SetXY($x,$y);
        $this->fpdf->SetFont('Times', 'B', 10);
         $this->fpdf->Cell(100,6,utf8_decode("Número de control: "),0,0,'L');
        $this->fpdf->Cell(80,6,$control,"B",0,'C');
        $this->fpdf->SetXY($x+240,$y);
        $alumno = Alumno::find($control);
        $nombre_alumno=$alumno->apellido_paterno." ".$alumno->apellido_materno." ".$alumno->nombre_alumno;
         $this->fpdf->Cell(50,6,"Nombre: ",0,0,'L');
        $this->fpdf->Cell(270,6,utf8_decode($nombre_alumno),'B',1,'L');
        $y = $this->fpdf->GetY()+10;
        $this->fpdf->SetXY($x,$y);
        $ncarrera = (new AccionesController)->ncarrera($alumno->carrera,$alumno->reticula);
        $this->fpdf->SetFont('Times', '', 8);
        $this->fpdf->Cell(200,6,utf8_decode("Carrera ".$ncarrera->nombre_carrera),0,0,'L');
        $this->fpdf->SetXY($x+350,$y);
        $this->fpdf->Cell(200,6,"Clave ".$ncarrera->clave_oficial,0,1,'L');
        $this->fpdf->Ln(10);
    }
    function Footer(){
        $this->fpdf->SetY(-15);
        // Arial italic 8
        $this->fpdf->SetFont('Arial','I',9);
        // Fecha de impresión

    }

    #[NoReturn] public function crearPDF(Request $request){
        $control=$request->get('control');
        $alumno = Alumno::find($control);
        $ncarrera = (new AccionesController)->ncarrera($alumno->carrera,$alumno->reticula);
        $this->fpdf = new Fpdf('P', 'pt', 'Letter');
        $this->fpdf->AliasNbPages();
        $this->fpdf->AddPage();
        $this->fpdf->SetAutoPageBreak(auto: true, margin: 54);
        $this->Header($control);
        $this->fpdf->SetFont('Arial', '', 8);
        $informacion = (new AccionesController)->kardex($control);
        $calificaciones=$informacion[0];
        $nombre_periodo=$informacion[1];
        $suma_total=0; $calificaciones_totales=0; $j=1;
        $tipos_mat=array("E2","3","4","5","R1","R2","RO","RP"); $tipos_aprob=array('AC','RC','93','92','91','RU','PG');
        foreach ($calificaciones as $key=>$value){
            $this->fpdf->SetFillColor(200,100,33); //gris: 189,189,189
            $this->fpdf->Cell(150,10,"Periodo ".$nombre_periodo[$key]->identificacion_larga,0,1,'L',1);
            $this->fpdf->SetFillColor(6,6,6);
            $this->fpdf->SetTextColor(255,255,255);
            $this->fpdf->Cell(25,8,"No",1,0,'C',1);
            $this->fpdf->Cell(50,8,"Clave oficial",1,0,'C',1);
            $this->fpdf->Cell(250,8,"Materia",1,0,'L',1);
            $this->fpdf->Cell(55,8,utf8_decode("Calificación"),1,0,'L',1);
            $this->fpdf->Cell(65,8,utf8_decode("Tipo evaluación"),1,0,'L',1);
            $this->fpdf->Cell(105,8,"Observaciones",1,1,'L',1);
            $this->fpdf->SetTextColor(0,0,0);
            $i=1;
            $suma_creditos=0;
            $suma_semestre=0;
            $cal_sem=0;
            $materias=1;
            foreach ($value as $data){
                $txt="";
                $this->fpdf->Cell(25,8,$i,1,0,'C',0);
                $this->fpdf->Cell(50,8,$data->clave,1,0,'C',0);
                $this->fpdf->Cell(250,8,utf8_decode($data->nombre_completo_materia),1,0,'L',0);
                $cal = $data->calificacion <= 70 && in_array($data->tipo_evaluacion,$tipos_aprob)?'AC':($data->calificacion < 70?"NA":$data->calificacion);
                $this->fpdf->Cell(55,8,$cal,1,0,'C',0);
                $this->fpdf->Cell(65,8,$data->descripcion_corta_evaluacion,1,0,'L',0);
                if(($data->calificacion < 70 && in_array($data->tipo_evaluacion,$tipos_mat)) || ($data->calificacion < 70 && $data->tipo_evaluacion == 'EA')){
                    if($alumno->plan_de_estudios==3||$alumno->plan_de_estudios==4){
                        $txt = "A curso especial";
                    }
                }
                $this->fpdf->Cell(105,8,$txt,1,1,'L',0);
                if($data->calificacion>=70||in_array($data->tipo_evaluacion,$tipos_aprob)){
                    $suma_creditos+=$data->creditos_materia;
                    if(!in_array($data->tipo_evaluacion,$tipos_aprob)){
                        $cal_sem+=$data->calificacion;
                        $calificaciones_totales+=$data->calificacion;
                        $materias+=1;
                        $j++;
                    }
                    $suma_total+=$data->creditos_materia;

                }elseif($data->calificacion<70&&!in_array($data->tipo_evaluacion,$tipos_aprob)){
                    $materias+=1;
                }
                $suma_semestre+=$data->creditos_materia;
                $i++;
            }
            $promedio=($materias-1)==0?0:round($cal_sem/($materias-1),2);
            $this->fpdf->SetFillColor(179,175,246);
            //$this->fpdf->SetX($x);
            $this->fpdf->Cell(115,8,utf8_decode("Créditos aprobados/solicitados"),1,0,'L',1);
            $this->fpdf->Cell(55,8,$suma_creditos."/".$suma_semestre,1,0,'L',1);
            $this->fpdf->Cell(115,8,"Promedio del semestre",1,0,'L',1);
            $this->fpdf->Cell(40,8,$promedio,1,1,'L',1);
            $this->fpdf->Ln(15);
        }
        //$this->fpdf->Footer();
        $this->fpdf->Cell(150,8,"Porcentaje de avance",0,0,'L');
        $avance1=$suma_total==0?0:round(($suma_total/$ncarrera->creditos_totales)*100,2);
        $avance = min($avance1, 100);
        $this->fpdf->Cell(50,8,$avance."%",0,1,'L');
        $this->fpdf->Cell(150,8,"Promedio general",0,0,'L');
        $prom_tot=($j-1)==0?0:round($calificaciones_totales/($j-1),2);
        $this->fpdf->Cell(50,8,$prom_tot,0,1,'L');
        $this->fpdf->Ln(9);
        $this->fpdf->SetFont('Arial', 'B', 9);
        $this->fpdf->Cell(178,8,utf8_decode("Documento no válido sin sello ni firma"),'B',1,'L');
        $this->fpdf->Ln(6);
        $this->fpdf->SetFont('Arial', 'BI', 8);
        $this->fpdf->Cell(170,8,utf8_decode("La información presentada es sujeta a revisión"),0,1,'L');
        $fmt1=new IntlDateFormatter(
            'es_ES',
            IntlDateFormatter::SHORT,
            0,
            'America/Tijuana',
            1,
            "dd/MMMM/YYYY",
        );
        $fecha=$fmt1->format(time());
        $datos_fecha=explode("/",$fecha);
        $dia=$datos_fecha[0];
        $mes=$datos_fecha[1];
        $anio=$datos_fecha[2];

        $generales = Parametro::first();
        $fecha=$generales->ciudad." a ".$dia." de ".$mes.' del '.$anio;
        $this->fpdf->Cell(0,10,$fecha,0,0,'R');
        $this->fpdf->Output();
        exit();
    }
}
