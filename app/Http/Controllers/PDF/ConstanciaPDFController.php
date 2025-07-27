<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use App\Models\Alumno;
use App\Models\Horario;
use App\Models\Organigrama;
use App\Models\PeriodoEscolar;
use App\Models\FolioConstancia;
use App\Models\Jefe;
use App\Models\HistoriaAlumno;
use App\Models\SeleccionMateria;
use App\Http\Controllers\Acciones\AccionesController;
use App\Models\Personal;
use Carbon\Carbon;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ConstanciaPDFController extends Controller
{
    public function __construct(){

    }
    public function mes_espanol($mes)
    {
        switch ($mes)
        {
            case '01': $valor= 'enero'; break;
            case '02': $valor= 'febrero'; break;
            case '03': $valor= 'marzo'; break;
            case '04': $valor= 'abril'; break;
            case '05': $valor='mayo'; break;
            case '06': $valor= 'junio'; break;
            case '07': $valor= 'julio'; break;
            case '08': $valor= 'agosto'; break;
            case '09': $valor= 'septiembre'; break;
            case '10': $valor= 'octubre'; break;
            case '11': $valor= 'noviembre'; break;
            case '12': $valor= 'diciembre'; break;
            default: $valor=''; break;
        }
        return $valor;
    }/*
    public function dia_espanol($dia)
    {

        switch($dia)
        {
            case 1: return 'Domingo'; break;
            case 2: return 'Lunes'; break;
            case 3: return 'Martes'; break;
            case 4: return 'Miercoles'; break;
            case 5: return 'Jueves'; break;
            case 6: return 'Viernes'; break;
            case 7: return 'Sabado'; break;

        }
    }*/
    public function fecha_espanol($fecha = NULL)
    {
        if(!$fecha)
        {
            return date("d")." de ".$this->mes_espanol(date("m"))." del ".date("Y");
        }
        else
        {
            return substr($fecha, 8, 2)." de ".$this->mes_espanol(substr($fecha, 5, 2))." de ".substr($fecha, 0, 4);
        }
    }
    public function calcula_semestre_materia($ingreso, $cursado,$revalida)
    {
        $aI=intval(substr($ingreso,0,4));	// el año de ingreso del alumno
        $pI=intval(substr($ingreso,4,1));	// el periodo de ingreso del alumno (1= Enero/Junio 2= Agosto-Diciembre)
        $aC=intval(substr($cursado,0,4)); 	// el año de la calificacion
        $pC=intval(substr($cursado,4,1));	// el periodo de la calificacion
        $semestre=0;
        do{
            if($aI==$aC && $pI==$pC){
                if($pI!=2)
                    $semestre=$semestre+1;
                break;
            }
            switch($pI){
                case 1:
                    $semestre=$semestre+1;
                    break;
                case 3:
                    $pI=0;
                    $aI++;
                    $semestre=$semestre+1;
            }
            $pI=$pI+1;
        }while(1);
        return ($semestre+$revalida);
    }

    public function mes($mes){
        $nmes='';
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
    public function horario($pdf, $carga, $horarios,$ejey)
    {
        $non = true;
        $x_ini = 11;
        $y = ($carga == 1)?40:$ejey+2; //original 158 (122)
        $alto = 5;
        $ancho_m = 53;
        $ancho_c = 12;
        $ancho_g = 10;
        $ancho_1 = 5.5;
        $ancho_h = 16;
        $ancho_s = 0.5;
        $l = 1;
        $pdf->SetFont('Helvetica','b','6');
        $pdf->SetLineWidth(0.10);
        $pdf->SetXY($x_ini, $y);
        $pdf->Cell($ancho_m, $alto, "MATERIA / PROFESOR", $l, 0, 'L', 0);
        $pdf->Cell($ancho_s, $alto, "", 0, 0, 'C', 0);
        $pdf->Cell($ancho_c, $alto, "CVE.OFI.", $l, 0, 'C', 0);
        $pdf->Cell($ancho_s, $alto, "", 0, 0, 'C', 0);
        $pdf->Cell($ancho_g, $alto, "GRUPO", $l, 0, 'C', 0);
        $pdf->Cell($ancho_s, $alto, "", 0, 0, 'C', 0);
        $pdf->Cell($ancho_1, $alto, "REP", $l, 0, 'C', 0);
        $pdf->Cell($ancho_s, $alto, "", 0, 0, 'C', 0);
        $pdf->Cell($ancho_1, $alto, "CR", $l, 0, 'C', 0);
        $pdf->Cell($ancho_s, $alto, "", 0, 0, 'C', 0);
        $pdf->Cell($ancho_h, $alto, "LUNES", $l, 0, 'C', 0);
        $pdf->Cell($ancho_s, $alto, "", 0, 0, 'C', 0);
        $pdf->Cell($ancho_h, $alto, "MARTES", $l, 0, 'C', 0);
        $pdf->Cell($ancho_s, $alto, "", 0, 0, 'C', 0);
        $pdf->Cell($ancho_h, $alto, utf8_decode("MIÉRCOLES"), $l, 0, 'C', 0);
        $pdf->Cell($ancho_s, $alto, "", 0, 0, 'C', 0);
        $pdf->Cell($ancho_h, $alto, "JUEVES", $l, 0, 'C', 0);
        $pdf->Cell($ancho_s, $alto, "", 0, 0, 'C', 0);
        $pdf->Cell($ancho_h, $alto, "VIERNES", $l, 0, 'C', 0);
        $pdf->Cell($ancho_s, $alto, "", 0, 0, 'C', 0);
        $pdf->Cell($ancho_h, $alto, utf8_decode("SÁBADO"), $l, 0, 'C', 0);

        $l = 1;
        $y+= 5.4;
        $alto = 3.1;
        foreach($horarios as $materia => $valor)
        {
            $nmateria 		= $valor['materia'];
            $cve_oficial 	= $valor['cve_oficial'];
            $grupo 			= $valor['grupo'];
            $repeticion 	= $valor['repeticion'];
            $creditos 		= $valor['creditos'];
            $profesor 		= $valor['profesor'];

            $salto = "<br>";
            $lunes 			= ($valor['lunes'])?str_replace($salto, "\n ", $valor['lunes'])			:" \n ";
            $martes 		= ($valor['martes'])?str_replace($salto, "\n ", $valor['martes'])		:" \n ";
            $miercoles 	= ($valor['miercoles'])?str_replace($salto, "\n ", $valor['miercoles'])	:" \n ";
            $jueves 		= ($valor['jueves'])?str_replace($salto, "\n ", $valor['jueves'])		:" \n ";
            $viernes 		= ($valor['viernes'])?str_replace($salto, "\n ", $valor['viernes'])		:" \n ";
            $sabado 		= ($valor['sabado'])?str_replace($salto, "\n ", $valor['sabado'])		:" \n ";

            $color = ($non)?225:255;
            $non = !$non;

            $pdf->SetFillColor($color);
            $pdf->SetXY($x_ini, $y);
            $pdf->MultiCell($ancho_m, $alto, utf8_decode($nmateria)."\n  ".utf8_decode($profesor), $l, 'L', 1);
            $pdf->SetXY($x_ini+$ancho_m, $y);
            $pdf->Cell($ancho_s, $alto, "", 0, 0, 'C', 0);
            $pdf->Cell($ancho_c, $alto*2, $cve_oficial, $l, 0, 'C', 1);
            $pdf->Cell($ancho_s, $alto, "", 0, 0, 'C', 0);
            $pdf->Cell($ancho_g, $alto*2, $grupo, $l, 0, 'C', 1);
            $pdf->Cell($ancho_s, $alto, "", 0, 0, 'C', 0);
            $pdf->Cell($ancho_1, $alto*2, $repeticion, $l, 0, 'C', 1);
            $pdf->Cell($ancho_s, $alto, "", 0, 0, 'C', 0);
            $pdf->Cell($ancho_1, $alto*2, $creditos, $l, 0, 'C', 1);
            $pdf->Cell($ancho_s, $alto, "", 0, 0, 'C', 0);

            /** IMPRIME HORARIO **/
            $x = $x_ini + $ancho_m + $ancho_c + $ancho_g + $ancho_1 + $ancho_1 + $ancho_s*5;
            $pdf->MultiCell($ancho_h, $alto, $lunes, $l, 'C', 1);
            $pdf->SetXY($x + $ancho_h + $ancho_s, $y);
            $pdf->MultiCell($ancho_h, $alto, $martes, $l, 'C', 1);
            $pdf->SetXY($x + $ancho_h*2 + $ancho_s*2, $y);
            $pdf->MultiCell($ancho_h, $alto, $miercoles, $l, 'C', 1);
            $pdf->SetXY($x + $ancho_h*3 + $ancho_s*3, $y);
            $pdf->MultiCell($ancho_h, $alto, $jueves, $l, 'C', 1);
            $pdf->SetXY($x + $ancho_h*4 + $ancho_s*4, $y);
            $pdf->MultiCell($ancho_h, $alto, $viernes, $l, 'C', 1);
            $pdf->SetXY($x + $ancho_h*5 + $ancho_s*5, $y);
            $pdf->MultiCell($ancho_h, $alto, $sabado, $l, 'C', 1);
            $y+= 6.6;
        }
    }

    public function encabezado($pdf,$depto,$folio,$dia,$mes,$anio){
        //$pdf->Image("/var/www/html/escolares/public/img/aguila.jpg",0,0,20,15,'JPG');
        // Logo TecNM
        $pdf->Image($_ENV['RUTA_IMG_TECNM'], 20, 7, 36, 20, 'JPG');
        // Logo GobFed
        $pdf->Image($_ENV['RUTA_IMG_GOBFED'], 170, 1, 27, 28, 'JPG');
        //Leyenda
        $pdf->AddFont('MM','','Montserrat-Medium.php');
        $pdf->AddFont('MM','B','Montserrat-Bold.php');
        $pdf->SetFont('MM','B',9);
        $pdf->SetXY(140,30);

        $ndepto=Organigrama::where('clave_area',$depto)->first();
        $nombre_tec = mb_convert_encoding($_ENV['NOMBRE_TEC'], 'ISO-8859-1', 'UTF-8');
        $pdf->SetXY(154, 29);
        $pdf->SetFont('Montserrat2', 'B', 8);
        $pdf->Cell(50, 6, $nombre_tec, 0, 1, 'L');
        $pdf->SetXY(146, 33);
        $pdf->SetFont('MM','',8);
        $pdf->SetXY(140,34);
        $pdf->Cell(50,6,$ndepto->descripcion_area,0,1,'L');
        $pdf->SetFont('MM','B',8);
        $pdf->Cell(200,5,utf8_decode("\"2020, Año de Leona Vicario, Benemérita Madre de la Patria \""),0,1,"C");
        $asunto="CONSTANCIA DE ESTUDIOS";
        $l = strlen(trim($folio));
        $oficio = "SE"."-".substr("00000".trim($folio), $l, 5)."/".trim($anio);
        $h 	= 3;
        $wt = 27;
        $wd = 36;
        $y 	= 48;//Original 48
        $xt = 140;
        $b  = $h+1.5;
        $xd = $xt + $wt;
        $pdf->SetXY($xt, $y);
        //1ra linea
        $pdf->Cell($wt,$h,$_ENV["CIUDAD_OFICIOS"],0,0,"L");
        $pdf->SetXY($xd,$y);
        $pdf->SetTextColor(255,255,255);
        $fecha=$dia."/".$this->mes($mes)."/".$anio;
        $pdf->Cell($wd,$h,$fecha,0,1,"L",true);
        $pdf->SetTextColor(0,0,0);
        //2da linea
        $pdf->SetXY($xt,$y+$b);
        $pdf->Cell($wt,$h,"No. de Oficio",0,0,"L");
        $pdf->SetXY($xd,$y+$b);
        $pdf->SetTextColor(255,255,255);
        $pdf->Cell($wd,$h,$oficio,0,1,"L",true);
        $pdf->SetTextColor(0,0,0);
        //3ra linea
        $pdf->SetXY($xt,$y+2*$b);
        $pdf->Cell($wt,$h,"Clave",0,0,"L");
        $pdf->SetXY($xd,$y+2*$b);
        $pdf->SetTextColor(255,255,255);
        $pdf->Cell($wd,$h,$_ENV["CCT"],0,1,"L",true);
        $pdf->SetTextColor(0,0,0);
        //4ta linea
        $pdf->SetXY($xt,$y+3*$b);
        $pdf->Cell($wt,$h,"Asunto",0,0,"L");
        $pdf->SetXY($xd,$y+3*$b);
        $pdf->SetTextColor(255,255,255);
        $pdf->MultiCell($wd,$h,$asunto,0,"L",1);
        $pdf->SetTextColor(0,0,0);
        return $pdf;
    }
    public function semreal($periodo_ingreso,$periodo_actual){
        $anio_actual=(int) substr($periodo_actual, 0, 4);
        $anio_ingresa=(int) substr($periodo_ingreso, 0, 4);

        if(substr($periodo_ingreso, -1)==1){
            if(substr($periodo_actual,-1)==3){
                $sem=(($anio_actual-$anio_ingresa)+1)*2;
            }else{
                $sem=($anio_actual-$anio_ingresa)*2+1;
            }
        }else{
            if(substr($periodo_actual,-1)==3){
                $sem=($anio_actual-$anio_ingresa)*2+1;
            }else{
                $sem=($anio_actual-$anio_ingresa)*2;
            }
        }
        return $sem;
    }
    public function fecha_completa($fecha = NULL)
    {
        return $_ENV["CIUDAD_OFICIOS"]." a ".$this->fecha_espanol($fecha);
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

    public function num_a_letra($num, $fem = true, $dec = true) {
//if (strlen($num) > 14) die("El número introducido es demasiado grande");
        $matuni[2]  = "dos";
        $matuni[3]  = "tres";
        $matuni[4]  = "cuatro";
        $matuni[5]  = "cinco";
        $matuni[6]  = "seis";
        $matuni[7]  = "siete";
        $matuni[8]  = "ocho";
        $matuni[9]  = "nueve";
        $matuni[10] = "diez";
        $matuni[11] = "once";
        $matuni[12] = "doce";
        $matuni[13] = "trece";
        $matuni[14] = "catorce";
        $matuni[15] = "quince";
        $matuni[16] = "dieciseis";
        $matuni[17] = "diecisiete";
        $matuni[18] = "dieciocho";
        $matuni[19] = "diecinueve";
        $matuni[20] = "veinte";
        $matunisub[2] = "dos";
        $matunisub[3] = "tres";
        $matunisub[4] = "cuatro";
        $matunisub[5] = "quin";
        $matunisub[6] = "seis";
        $matunisub[7] = "sete";
        $matunisub[8] = "ocho";
        $matunisub[9] = "nove";
        $matdec[2] = "veint";
        $matdec[3] = "treinta";
        $matdec[4] = "cuarenta";
        $matdec[5] = "cincuenta";
        $matdec[6] = "sesenta";
        $matdec[7] = "setenta";
        $matdec[8] = "ochenta";
        $matdec[9] = "noventa";
        $matsub[3]  = 'mill';
        $matsub[5]  = 'bill';
        $matsub[7]  = 'mill';
        $matsub[9]  = 'trill';
        $matsub[11] = 'mill';
        $matsub[13] = 'bill';
        $matsub[15] = 'mill';
        $matmil[4]  = 'millones';
        $matmil[6]  = 'billones';
        $matmil[7]  = 'de billones';
        $matmil[8]  = 'millones de billones';
        $matmil[10] = 'trillones';
        $matmil[11] = 'de trillones';
        $matmil[12] = 'millones de trillones';
        $matmil[13] = 'de trillones';
        $matmil[14] = 'billones de trillones';
        $matmil[15] = 'de billones de trillones';
        $matmil[16] = 'millones de billones de trillones';
        $num = trim((string)@$num);
        if ($num[0] == '-') {
            $neg = 'menos ';
            $num = substr($num, 1);
        }else
            $neg = '';
        while ($num[0] == '0') $num = substr($num, 1);
        if ($num[0] < '1' or $num[0] > 9) $num = '0' . $num;
        $zeros = true;
        $punt = false;
        $ent = '';
        $fra = '';
        for ($c = 0; $c < strlen($num); $c++) {
            $n = $num[$c];
            if (! (!str_contains(".,'''", $n))) {
                if ($punt) break;
                else{
                    $punt = true;
                    //continue;
                }
            }elseif (! (!str_contains('0123456789', $n))) {
                if ($punt) {
                    if ($n != '0') $zeros = false;
                    $fra .= $n;
                }else
                    $ent .= $n;
            }else
                break;
        }

        $ent = '     ' . $ent;

        if ($dec and $fra and ! $zeros) {
            $fin = ' punto'; //$fin = ' coma';
            for ($n = 0; $n < strlen($fra); $n++) {
                if (($s = $fra[$n]) == '0')
                    $fin .= ' cero';
                elseif ($s == '1')
                    $fin .= $fem ? ' una' : ' uno';
                else
                    $fin .= ' ' . $matuni[$s];
            }
        }else
            $fin = '';
        if ((int)$ent === 0) return 'cero ' . $fin;
        $tex = '';
        $sub = 0;
        $mils = 0;
        $neutro = false;

        while ( ($num = substr($ent, -3)) != '   ') {

            $ent = substr($ent, 0, -3);
            if (++$sub < 3 and $fem) {
                $matuni[1] = 'una';
                $subcent = 'as';
            }else{
                $matuni[1] = $neutro ? 'un' : 'uno';
                $subcent = 'os';
            }
            $t = '';
            $n2 = substr($num, 1);
            if ($n2 < 21)
                $t = ' ' . $matuni[(int)$n2];
            elseif ($n2 < 30) {
                $n3 = $num[2];
                if ($n3 != 0) $t = 'i' . $matuni[$n3];
                $n2 = $num[1];
                $t = ' ' . $matdec[$n2] . $t;
            }else{
                $n3 = $num[2];
                if ($n3 != 0) $t = ' y ' . $matuni[$n3];
                $n2 = $num[1];
                $t = ' ' . $matdec[$n2] . $t;
            }

            $n = $num[0];
            if ($n == 1) {
                $t = ' cien' . $t;
            }elseif ($n == 5){
                $t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t;
            }elseif ($n != 0){
                $t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t;
            }

            if (! isset($matsub[$sub])) {
                if ($num == 1) {
                    $t = ' mil';
                }elseif ($num > 1){
                    $t .= ' mil';
                }
            }elseif ($num == 1) {
                $t .= ' ' . $matsub[$sub] . 'ón';
            }elseif ($num > 1){
                $t .= ' ' . $matsub[$sub] . 'ones';
            }
            if ($num == '000') $mils ++;
            elseif ($mils != 0) {
                if (isset($matmil[$sub])) $t .= ' ' . $matmil[$sub];
                $mils = 0;
            }
            $neutro = true;
            $tex = $t . $tex;
        }
        $tex = $neg . substr($tex, 1) . $fin;
        //$tex = $neg . $tex . $fin;
        return $tex; //ucfirst($tex);
    }

    public function crearPDF(Request $request){

        $periodo=$request->get('periodo');
        $control=$request->get('control');
        $alumno=Alumno::findOrfail($control);
        $tipo=$request->get('tconstancia');
        $fexpedicion=$request->get('fexpedicion');
        if(($alumno->estatus_alumno!='EGR')&&($tipo=='G' || $tipo=='O' || $tipo=='S' || $tipo=='T')){
            $encabezado="Error para constancia";
            $mensaje="El estudiante no es egresado";
            return view('escolares.no')->with(compact('mensaje','encabezado'));
        }
        $quien=Auth::user()->email;
        $datos_fecha=explode("-",$fexpedicion);
        $anio=$datos_fecha[0]; $mes=$datos_fecha[1]; $dia=$datos_fecha[2];
        $periodos=PeriodoEscolar::where('periodo',$periodo)->first();
        $folios=FolioConstancia::where('anio',date('Y'))->max('folio');
        $folio=$folios+1;
        $registro=new FolioConstancia();
        $registro->folio=$folio;
        $registro->periodo=$periodo;
        $registro->control=$control;
        $registro->tipo=$tipo;
        $registro->fecha=Carbon::now();
        $registro->anio=date('Y');
        $registro->quien=$quien;
        $registro->save();
        $total=(new AccionesController)->totales($control);
        if(!empty($total)){
            $creditos_aprobados=$total[0]->creditos_aprobados;
            $promedio=$total[0]->promedio;
            $periodo_final=$total[0]->periodo_final;
        }else{
            $creditos_aprobados=0;
            $promedio=0;
            $periodo_final=$periodo;
        }
        $ncarrera=(new AccionesController)->ncarrera($alumno->carrera,$alumno->reticula);
        $clave_oficial=$ncarrera->clave_oficial;
        $pre_avance = (!$creditos_aprobados)?0:round((($creditos_aprobados / $ncarrera->creditos_totales) * 100),2);
        $avance= min($pre_avance, 100);
        if(!is_null($alumno->ultimo_periodo_inscrito)){
            $ano_u = substr($alumno->ultimo_periodo_inscrito, 0, 4);
            $per = substr($alumno->ultimo_periodo_inscrito, 4, 1);
            switch($per)
            {
                case 1: $mes="junio"; 		break;
                case 2: $mes="agosto"; 		break;
                case 3: $mes="diciembre"; break;
            }
            $periodo_final = $mes." del ".$ano_u;
        }
        $nombre_alumno = trim($alumno->nombre_alumno).' '.trim($alumno->apellido_paterno).' '.trim($alumno->apellido_materno);
        if($alumno->sexo == 'F')
        {
            $genero_a = "a";
            $prop_a 	= "la";
            $interesado = "de la";
        }
        else
        {
            $genero_a = "o";
            $prop_a		="el";
            $interesado = "del";
        }
        $jefe=Jefe::where('clave_area','120600')->first();
        $jefatura=Personal::where('id',$jefe->id_jefe)->first();
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
        //$ciclo = $alumno->nivel_escolar;
        //$ide_periodo = $this->nperiodo($periodo);
        $ide_periodo_inicial = $this->nperiodo(substr($alumno->periodo_ingreso_it,0,4).((substr($alumno->periodo_ingreso_it,4,1) == '3' || substr($alumno->periodo_ingreso_it,4,1) == '2')?"3":"1"));
        $ide_periodo_final = $this->nperiodo($periodo_final);

        $es=$alumno->estatus_alumno=="ACT"?"es":"fue";
        if($tipo == 'G'){$estatus = " es egresad".$genero_a." de la carrera ".$ncarrera->nombre_carrera;}
        elseif($tipo == 'O' || $tipo == 'S'){$estatus = "concluyó completamente el Plan  de Estudios ".trim($clave_oficial).", de la carrera ".$ncarrera->nombre_carrera.", ";}
        elseif($tipo == 'TT'){$estatus = "curso las asignaturas que integran el Plan  de Estudios ".trim($clave_oficial).", de la carrera ".$ncarrera->nombre_carrera.", de la generación";}
        elseif($alumno->estatus_alumno == "ACT"){$estatus = $es." alumn".$genero_a." del periodo ".trim($periodos->identificacion_larga).", cursando el semestre ".$alumno->semestre." de la
carrera de ".$ncarrera->nombre_carrera;}
        elseif($alumno->estatus_alumno == "BT1" || $alumno->estatus_alumno == "BT2" || $alumno->estatus_alumno == "BT3"){$estatus = "es alumn".$genero_a." con baja temporal autorizada de la carrera de ".$ncarrera->nombre_carrera;}
        elseif($alumno->estatus_alumno == "BD2" || $alumno->estatus_alumno == "BD1" || $alumno->estatus_alumno == "BD3" || $alumno->estatus_alumno == "BD4" || $alumno->estatus_alumno == "BD5" || $alumno->estatus_alumno == "BD6" || $alumno->estatus_alumno == "EGR" && $tipo != 'T'){
            $estatus = "fué alumn".$genero_a." de la carrera de ".$ncarrera->nombre_carrera;
        }
        if($tipo == 'D' || $tipo == 'P' || $tipo == 'G' || $tipo == 'K') //con tira de materias
        {
            $periodo_estudios2=" durante el periodo comprendido de ".$this->fecha_espanol($periodos->fecha_inicio)." a ".$this->fecha_espanol($periodos->fecha_termino);
            $estatus.=$periodo_estudios2." y se relacionan los resultados obtenidos:";
            $acumulados=utf8_decode("CNO - Curso Normal Ordinario  CNR - Curso Normal Regularización  CNE - Curso Normal Extraordinario
CRO - Curso Repetición Ordinario  CRR - Curso Repetición Regularización  EE - Exámen Especial  EA - Exámen Autodidacta CA - Estudios Convalidados EQ- Equivalencia\n\n");
            $acumulados.= ($tipo == 'G')?"":"Porcentaje de avance ".$avance.".\n";
            $acumulados.= "Créditos acumulados ".$creditos_aprobados." de un total ".$ncarrera->creditos_totales." que integran el plan de estudios ".trim($clave_oficial);
            if($tipo == 'K'){
                $calificaciones =(new AccionesController)->constancia_kardex_completo($control);
            }else{
                $calificaciones =(new AccionesController)->constancia_kardex($control);
            }
        }
        if($tipo == 'B'){
            $nombre_largo=$periodos->identificacion_larga;
            $estatus.= " y se relacionan los resultados obtenidos del periodo ".trim($nombre_largo)." a la fecha:";
            $ano_inicial=substr($alumno->periodo_ingreso_it,0,4);
            $per_inicial=substr($alumno->periodo_ingreso_it,-1);
            if($per_inicial == 2){
                $per_inicial=1;
            }
            if($per_inicial == 1){
                $per_enero=$alumno->periodo_ingreso_it;
                $per_verano=$ano_inicial."2";
                $per_agosto=($ano_inicial-1)."3";
                $entre="(periodo='$per_enero' or periodo='$per_verano' or periodo='$per_agosto')";
            }
            if($per_inicial == 3){
                $per_enero=$ano_inicial."1";
                $per_verano=$ano_inicial."2";
                $per_agosto=$alumno->periodo_ingreso_it;
                //$entre="(periodo='$per_enero' or periodo='$per_agosto')";
                $entre="(periodo='$per_enero' or periodo='$per_verano' or periodo='$per_agosto')";
            }
            $calificaciones=HistoriaAlumno::where('no_de_control',$control)
                ->join('materias_carreras as mc','mc.materia','=','historia_alumno.materia')
                ->where('mc.carrera',$alumno->carrera)
                ->where('mc.reticula',$alumno->reticula)
                ->join('materias','materias.materia','=','historia_alumno.materia')
                ->where('periodo',$per_enero)->orWhere('periodo',$per_verano)
                ->orWhere('periodo',$per_agosto)
                ->select('historia_alumno.periodo','calificacion','nombre_completo_materia as n_materia','tipo_evaluacion','orden_certificado','creditos_materia')
                ->get();
        }
        if($tipo == '1S'){
            $calificaciones=HistoriaAlumno::where('no_de_control',$control)
                ->join('materias_carreras as mc','mc.materia','=','historia_alumno.materia')
                ->where('mc.carrera',$alumno->carrera)
                ->where('mc.reticula',$alumno->reticula)
                ->join('materias','materias.materia','=','historia_alumno.materia')
                ->where('periodo',$per_enero)
                ->select('historia_alumno.periodo','calificacion','nombre_completo_materia as n_materia','tipo_evaluacion','orden_certificado','creditos_materia')
                ->get();
        }
        $ultimo_periodo=$periodos->identificacion_larga;
        if($tipo == "TT"){
            $apertura = $prop_j." que suscribe, ".$gen_j." de Servicios Escolares de este Instituto Tecnológico, hace CONSTAR, que según documentos que existen en el archivo escolar de este instituto, ".$prop_a." C.";
            $otro_cierre = "para que inicie sus trámites de titulación de acuerdo a la opción elegida";
            $nota = "\n\nNota: La presente constancia no sustituye la Carta de no Inconveniencia.";
            $comun = "con número de control ".trim($control).", ".(($tipo == 'I')?"":$estatus);
            $separa_promedio=explode(".",$promedio);
            $enteros=$separa_promedio[0];
            if(count($separa_promedio)==1)
                $decimales=0;
            else
                $decimales=$separa_promedio[1];
            $dif = $promedio - $enteros;
            $decimales = (strlen($decimales)==1)?$decimales."0":$decimales;
            $promedio_l = ($dif>=0.1)?($this->num_a_letra($enteros, false, false)." punto ".(($decimales<10)?($this->num_a_letra($decimales."0", false, false)):$this->num_a_letra($decimales, false, false))):($this->num_a_letra($promedio, false));
            $cuerpoTT = "habiendo obtenido un promedio general de ".$enteros.".".$decimales." (".$promedio_l.")";
            //$hojas=0;
            $egreso = " ".trim($ultimo_periodo)." ".$cuerpoTT;
        }
        else{
            $apertura = $prop_j." que suscribe, ".$gen_j." de Servicios Escolares de este Instituto Tecnológico, hace constar que ".$prop_a." C.";
            $otro_cierre = "y para los fines a que haya lugar";
            $nota = "";
            $comun = "número de control ".trim($control).", ".(($tipo == 'I')?"":$estatus);
            $egreso = "el pasado ".trim($ultimo_periodo);
        }
        $cierre = "\n\nA petición ".$interesado." interesad".$genero_a." ".$otro_cierre.", se extiende la presente en la ciudad de ".$this->fecha_completa($fexpedicion).".".$nota;
        //$jefe=$jefatura->nombre_empleado.' '.$jefatura->apellidos_empleado."\n".$genero_j." DEL DEPARTAMENTO DE SERVICIOS ESCOLARES";
        $cuerpo = $comun;
        $periodo_estudios = "durante el periodo comprendido de ".$ide_periodo_inicial." a ".$ide_periodo_final;
        $creditos = $egreso.", cubriendo un total de ".$creditos_aprobados." créditos de ".$ncarrera->creditos_totales;
        $hojas=1;
        $CU=0;
        switch ($tipo){
            case 'E': {
                $cuerpo = $comun." durante el ciclo escolar comprendido del ".$this->fecha_espanol($periodos->fecha_inicio)." al ".$this->fecha_espanol($periodos->fecha_termino).".\n\n";
                $vacaciones_ss = (substr($periodo, 4, 1) == '1')?"El receso de Semana Santa comprende del ".$this->fecha_espanol($periodos->inicio_vacacional_ss)." al ".$this->fecha_espanol($periodos->fin_vacacional_ss)." y e":"E";
                $vacaciones = (substr($periodo, 4, 1) == '3')?"invierno":"verano";
                $cuerpo.= $vacaciones_ss."l periodo de vacaciones de ".$vacaciones." comprende del ".$this->fecha_espanol($periodos->inicio_vacacional)." al ".$this->fecha_espanol($periodos->termino_vacacional).".";
                break;
            }
            case 'O': {
                $separa_promedio=explode(".",$promedio);
                $enteros=$separa_promedio[0];
                if(count($separa_promedio)==1)
                    $decimales=0;
                else
                    $decimales=$separa_promedio[1];
                $dif = $promedio - $enteros;
                $decimales = (strlen($decimales)==1)?$decimales."0":$decimales;
                $promedio_l = ($dif>=0.1)?($this->num_a_letra($enteros, false, false)." punto ".(($decimales<10)?($this->num_a_letra($decimales."0", false, false)):$this->num_a_letra($decimales, false, false))):($this->num_a_letra($promedio, false));
                $cuerpo.= $creditos." que comprende el plan de estudio, con un promedio general de ".$enteros.".".$decimales." (".$promedio_l.").";
                $hojas=0;
                break;
            }
            case 'TT':$cuerpo.=$egreso."."; break;
            case 'M': {
                $cuerpo = $comun." alumn".$genero_a." de la carrera ".$ncarrera->nombre_carrera.", esta inscrit".$genero_a." en el semestre $alumno->semestre periodo ".trim($periodos->identificacion_larga).", en las materias y con los horarios que se indican:\n";
                $mats=SeleccionMateria::where('periodo',$periodo)
                    ->where('no_de_control',$control)
                    ->join('materias_carreras as mc','mc.materia','=','seleccion_materias.materia')
                    ->where('mc.carrera',$alumno->carrera)
                    ->where('mc.reticula',$alumno->reticula)
                    ->join('materias','materias.materia','=','seleccion_materias.materia')
                    ->get();
                foreach ($mats as $mat){
                    $lunes=Horario::where('periodo',$periodo)
                        ->where('materia',$mat->materia)
                        ->where('grupo',$mat->grupo)->where('dia_semana',2)
                        ->select('hora_inicial','hora_final','aula')->first();
                    if(is_null($lunes)){
                        $dl='';
                    }else{
                        $dl=date("H:i",strtotime($lunes->hora_inicial)).' '.date("H:i",strtotime($lunes->hora_final)).'<br>'.$lunes->aula;
                    }
                    $martes=Horario::where('periodo',$periodo)
                        ->where('materia',$mat->materia)
                        ->where('grupo',$mat->grupo)->where('dia_semana',3)
                        ->distinct()
                        ->select('hora_inicial','hora_final','aula')->first();
                    if(is_null($martes)){
                        $dm='';
                    }else{
                        $dm=date("H:i",strtotime($martes->hora_inicial)).' '.date("H:i",strtotime($martes->hora_final)).'<br>'.$martes->aula;
                    }
                    $miercoles=Horario::where('periodo',$periodo)
                        ->where('materia',$mat->materia)
                        ->where('grupo',$mat->grupo)->where('dia_semana',4)
                        ->distinct()
                        ->select('hora_inicial','hora_final','aula')->first();
                    if(is_null($miercoles)){
                        $dmm='';
                    }else{
                        $dmm=date("H:i",strtotime($miercoles->hora_inicial)).' '.date("H:i",strtotime($miercoles->hora_final)).'<br>'.$miercoles->aula;
                    }
                    $jueves=Horario::where('periodo',$periodo)
                        ->where('materia',$mat->materia)
                        ->where('grupo',$mat->grupo)->where('dia_semana',5)
                        ->distinct()
                        ->select('hora_inicial','hora_final','aula')->first();
                    if(is_null($jueves)){
                        $dj='';
                    }else{
                        $dj=date("H:i",strtotime($jueves->hora_inicial)).' '.date("H:i",strtotime($jueves->hora_final)).'<br>'.$jueves->aula;
                    }
                    $viernes=Horario::where('periodo',$periodo)
                        ->where('materia',$mat->materia)
                        ->where('grupo',$mat->grupo)->where('dia_semana',6)
                        ->distinct()
                        ->select('hora_inicial','hora_final','aula')->first();
                    if(is_null($viernes)){
                        $dv='';
                    }else{
                        $dv=date("H:i",strtotime($viernes->hora_inicial)).' '.date("H:i",strtotime($viernes->hora_final)).'<br>'.$viernes->aula;
                    }
                    $sabado=Horario::where('periodo',$periodo)
                        ->where('materia',$mat->materia)
                        ->where('grupo',$mat->grupo)->where('dia_semana',7)->distinct()
                        ->select('hora_inicial','hora_final','aula')->first();
                    if(is_null($sabado)){
                        $ds='';
                    }else{
                        $ds=date("H:i",strtotime($sabado->hora_inicial)).' '.date("H:i",strtotime($sabado->hora_final)).'<br>'.$sabado->aula;
                    }
                    $rfc=Horario::where('periodo',$periodo)
                        ->where('materia',$mat->materia)
                        ->where('grupo',$mat->grupo)
                        ->select('docente')
                        ->first();
                    if(!is_null($rfc->docente)){
                        $doc=Personal::select('apellidos_empleado','nombre_empleado')
                            ->where('id',$rfc->docente)->first();
                        $profesor=trim($doc->apellidos_empleado).' '.trim($doc->nombre_empleado);
                    }else{
                        $profesor='POR ASIGNAR';
                    }
                    $horarios2[$mat->materia]['materia'] = $mat->nombre_completo_materia;
                    $horarios2[$mat->materia]['cve_oficial'] = $mat->materia;
                    $horarios2[$mat->materia]['grupo'] = $mat->grupo;
                    $horarios2[$mat->materia]['repeticion'] = ($mat->repeticion == 'S')?"*":"";
                    $horarios2[$mat->materia]['creditos'] = $mat->creditos_materia;
                    $horarios2[$mat->materia]['profesor'] = $profesor;
                    $horarios2[$mat->materia]['lunes'] = $dl;
                    $horarios2[$mat->materia]['martes'] = $dm;
                    $horarios2[$mat->materia]['miercoles'] = $dmm;
                    $horarios2[$mat->materia]['jueves'] = $dj;
                    $horarios2[$mat->materia]['viernes'] = $dv;
                    $horarios2[$mat->materia]['sabado'] = $ds;
                }

            }
            case 'A': {
                $cuerpo = $comun." y a la fecha ha acreditado ".$creditos_aprobados." créditos de un total de ".$ncarrera->creditos_totales." que integran el plan de estudios ".trim($clave_oficial).". Por lo que su porcentaje de avance es de ".$avance."% y su promedio es de ".$promedio.".";
                break;
            }
            case 'T':{
                $cuerpo = $comun." egresad".$genero_a." de la carrera ".$ncarrera->nombre_carrera.", se tituló el ".$this->fecha_espanol($alumno->fecha_titulacion).", y actualmente el registro de su título y expedición de la cédula profesional correspondiente se encuentran en trámite.";
                break;
            }
            case 'N':{
                $apertura = "Por este conducto, le comunico que no existe inconveniente por parte nuestra, para que ".$prop_a." C.";
                $cuerpo = "número de control ".trim($control)." de la carrera ".$ncarrera->nombre_carrera." con clave oficial ".trim($clave_oficial).", se traslade al Instituto Tecnológico a su cargo, para lo cual se anexa el historial académico detallado por semestres.";
                break;
            }
            case 'IM': {
                $apertura = "Por este conducto me dirijo a usted de la manera más atenta para hacer de su conocimiento que el alumno:";
                $cuerpo = "De la carrera ".$ncarrera->nombre_carrera.", con el número de control ".trim($control)." y número de afiliación al ";
                $cuerpo.= "I.M.S.S. ".$alumno->nss.", cuenta con servicio médico como se hace constar en nuestro archivo.";
                $cuerpo.= "\n\nPor las facilidades que tenga en brindar a mi atento escrito, le participo mi agradecimiento.";
                break;
            }
            case 'SE': {
                $cuerpo = "con número de control ".$control." es alumn".$genero_a." del periodo ".trim($periodos->identificacion_larga).", cursando el semestre $alumno->semestre ";
                $cuerpo .= "de la carrera de ".$ncarrera->nombre_carrera." durante el ciclo escolar comprendido del ".$this->fecha_espanol($periodos->fecha_inicio)." al ".$this->fecha_espanol($periodos->fecha_termino).", y cuenta con ";
                $cuerpo .= "una POLIZA DE SEGURO de cobertura educativa";
                break;
            }
            case '1S': {
                $apertura = $prop_j." que suscribe, ".$gen_j." del Departamento de Servicios Escolares de este Instituto Tecnológico, hace constar que ";
                $apertura .= "conforme a los documentos que existen en los archivos de esta institución ".$prop_a." C.";
                $cuerpo = "número de control ".$control." fué alumn".$genero_a." del periodo ".trim($periodos->identificacion_larga).", cursando el semestre $alumno->semestre ";
                $cuerpo .= "de la carrera de ".$ncarrera->nombre_carrera." durante el ciclo escolar comprendido del ".$this->fecha_espanol($periodos->fecha_inicio)." al ".$this->fecha_espanol($periodos->fecha_termino).", ";
                if($tipo == 'BC'){
                    $cuerpo .= "y durante dicho periodo observo BUENA CONDUCTA.";
                }else{
                    $cuerpo .="y obtuvo las calificaciones siguientes: ";
                }
            }
            case 'R': {
                $apertura="Ensenada, B.C., a ".$this->fecha_espanol(date('Y-m-d'));
                $cuerpo="Me permito informarle de acuerdo a su solicitud, que no existe inconveniente para que pueda Ud. presentar su Acto de Recepción Profesional";
                $cuerpo.=", ya que su expediente quedo integrado para tal efecto.";
                break;
            }
        }
        $fpdf =new Fpdf('P','mm','Letter');
        $fpdf->AddPage();
        $fpdf->SetAutoPageBreak(0);
        //define('FPDF_FONTFILE',dirname(__FILE__).'/../../../../public/fuentes/');
        $fpdf->AddFont('MM','','Montserrat-Medium.php');
        $fpdf->AddFont('MM','B','Montserrat-Bold.php');
        $fpdf->AddFont("Montserrat2",'','Montserrat-ExtraLight.php');
        $fpdf->AddFont("Montserrat2",'I','Montserrat-Thin.php');
        $fpdf->AddFont("Montserrat2",'B','Montserrat-Light.php');
        $fpdf->AddFont("Montserrat2",'BI','Montserrat-SemiBold.php');
        $depto="120600";
        $x = 15;
        $y = 80; //Original 120
        $w = 180;
        $h = 4;
        $this->encabezado($fpdf,$depto,$folio,$dia,$mes,$anio);
        $fpdf->SetXY($x, $y);
        if($tipo != 'C' && $tipo != 'R'){
            $titulo_persona="A QUIEN CORRESPONDA:";
            $ancho = $h*4;
            $centra='L';
        }else{
            $ancho = $h*3;
            if($tipo == 'C'){
                $titulo_persona="SOLICITUD DE ACTO DE RECEPCIÓN PROFESIONAL";
                $centra='C';
            }else{
                $titulo_persona="CONSTANCIA DE NO INCONVENIENCIA PARA EL ACTO DE RECEPCIÓN PROFESIONAL";
                $centra='L';
            }
        }
        $fpdf->SetFont('MM','B',10);
        $fpdf->Cell($w, $ancho, $titulo_persona, 0, 2, $centra);
        if($tipo != 'C'){
            //$pdf->SetFont('Helvetica','','10');
            $fpdf->SetFont("MM",'',9);
            $fpdf->MultiCell($w, $h, utf8_decode($apertura), 0, 'L');
            $fpdf->SetXY($x, $fpdf->GetY());
            $fpdf->SetFont("MM",'B',9);
            //$pdf->SetFont('Helvetica','b','10');
            if($tipo != 'R')
                $fpdf->MultiCell($w, $h*3, utf8_decode($nombre_alumno), 0, 'C');
            else
                $fpdf->MultiCell($w, $h*3, "C. ".utf8_decode($nombre_alumno), 0, 'C');
            $fpdf->SetXY($x, $fpdf->GetY());
            //$pdf->SetFont('Helvetica','','10');
            $fpdf->SetFont("MM",'',9);
            $fpdf->MultiCell($w, $h, utf8_decode($cuerpo));
        }
        if($tipo == 'D' || $tipo == 'P' || $tipo == 'G' || $tipo == 'K' || $tipo == 'B' || $tipo == '1S') //con tira de materias
        {
            $xm = $x;
            $wm = $w;
            $fpdf->SetXY($xm, $fpdf->GetY() + $h);
            $fpdf->SetFont("MM", 'B', 9);
            $fpdf->Cell(($wm / 5.35) * 2.15, $h * 1.2, "MATERIA", 1, 0, 'C');
            $fpdf->Cell(($wm / 6) / 3.5, $h * 1.2, "CR", 1, 0, 'C');
            $fpdf->Cell(($wm / 9) / 1.3, $h * 1.2, "SEM", 1, 0, 'C');
            $fpdf->Cell(($wm / 6) * 0.9, $h * 1.2, "PERIODO", 1, 0, 'C');
            $fpdf->Cell(($wm / 6) / 1.1, $h * 1.2, utf8_decode("CALIFICACIÓN"), 1, 0, 'C');
            $fpdf->Cell(($wm / 6) / 1.1, $h * 1.2, "OPORTUNIDAD", 1, 1, 'C');
            $fpdf->Cell($w, 0.5, "", 0);
            $fpdf->SetFont("MM", '', 9);
            $hojas = 1;
            $suma_calificaciones = 0;
            $total_materias_periodo = 1;
            $suma_total_calif = 0;
            $total_materias = 0;
            $cuenta = 0;
            $sumatoria = 0;
            //$banderota = false;
            $bandera_REV = false;

            foreach ($calificaciones as $cal) {
                $fpdf->Ln();
                //$materia = $cal->materia;
                $n_materia = $cal->n_materia_r;
                if ($cal->tipo_evaluacion == 'RU' || $cal->tipo_evaluacion == 'RC') {
                    $calificacion = "AC";
                    $CU++;
                } elseif ($cal->tipo_evaluacion == 'AC') {
                    $calificacion = "ACA";
                    //$CU++;
                } else {
                    $calificacion = $cal->calificacion;
                    $suma_calificaciones += $calificacion;
                    if ($calificacion > 0) {
                        if ($calificacion != 60) {
                            $suma_total_calif += $calificacion;
                            $total_materias++;
                        }
                    }
                }
                $tipo_evaluacion = $cal->tipo_evaluacion;
                $creditos = $cal->creditos;
                $periodo = $cal->periodo;
                $tipoEval = match ($tipo_evaluacion) {
                    'O1', '1' => 'CNO',
                    'R1', '2' => 'CNR',
                    'E1' => 'CNE',
                    'O2' => 'CRO',
                    'R2' => 'CRR',
                    'EE' => 'EE',
                    'RC' => 'CV',
                    'RU' => 'EQ',
                    'EA' => 'EG',
                    default => "",
                };
                $fpdf->SetFont("MM", '', 9);
                $fpdf->setx($xm);
                $fpdf->Cell(($wm / 5.35) * 2.15, $h * 1.2, utf8_decode($n_materia), 1, 0, 'L');
                $fpdf->Cell(($wm / 6) / 3.5, $h * 1.2, $creditos, 1, 0, 'C');
                if ($periodo < $alumno->periodo_ingreso_it) {
                    if (!$bandera_REV) {
                        $periodo_ingreso_it2 = $periodo;
                        $bandera_REV = true;
                    }
                    $fpdf->Cell(($wm / 9) / 1.3, $h * 1.2, $this->calcula_semestre_materia($periodo_ingreso_it2, $periodo, 0), 1, 0, 'C'); // Para cuando son revalidados, convalidados
                } else
                    $fpdf->Cell(($wm / 9) / 1.3, $h * 1.2, $this->calcula_semestre_materia($alumno->periodo_ingreso_it, $periodo, $alumno->periodos_revalidacion), 1, 0, 'C'); // Para cuando son cursos, normales
                $fpdf->Cell(($wm / 6) * 0.9, $h * 1.2, trim($this->nperiodo($periodo)), 1, 0, 'C');
                $fpdf->Cell(($wm / 6) / 1.1, $h * 1.2, $calificacion, 1, 0, 'C');
                $fpdf->Cell(($wm / 6) / 1.1, $h * 1.2, $tipoEval, 1, 0, 'C');
                if ($tipo == 'K' || $tipo == 'B' || $tipo == '1S')
                    $periodo_leido = $periodo;
                if ($tipo == 'B') {
                    $cuenta++;
                    $sumatoria += $calificacion;
                }
                if ($tipo == 'K' || $tipo == 'B' || $tipo == '1S') {
                    if ($periodo_leido != $cal->periodo) {
                        $fpdf->Ln();
                        $fpdf->setx($xm);
                        $fpdf->Cell(($wm / 5.35) * 2.15, $h * 1.2, "", 0, 0, 'R');
                        $fpdf->Cell(($wm / 6) / 3.5, $h * 1.2, "", 0, 0, 'C');
                        $fpdf->Cell(($wm / 9) / 1.3, $h * 1.2, "", 0, 0, 'C');
                        $fpdf->Cell(($wm / 6) * 0.9, $h * 1.2, "", 0, 0, 'C');
                        $fpdf->Cell(27.25, $h * 1.2, "Promedio", 1, 0, 'C');
                        $fpdf->Cell(27.25, $h * 1.2, round(($suma_calificaciones / $total_materias_periodo), 2), 1, 0, 'C');
                        $total_materias_periodo = 1;
                        $suma_calificaciones = 0;
                    } else {
                        if ($cal->tipo_evaluacion != 'AC') {
                            $total_materias_periodo++;
                        }
                    }
                }
                if ($fpdf->GetY() >= 220) {
                    $fpdf->Ln();
                    $fpdf->SetFont("MM", '', 9);
                    $fpdf->Cell($w, $h, utf8_decode("Continúa..."), 0, 0, 'C');
                    //nvo_pie_oficial($pdf);
                    $fpdf->AddPage();
                    $banderota = true;
                    if ($banderota) {
                        $hojas++;
                    }
                    $encabezado = $this->encabezado($fpdf, $depto, $folio, $dia, $mes, $anio);
                    $fpdf->SetXY($xm, $fpdf->GetY() + $h);
                    $fpdf->SetFont("MM", 'B', 9);
                    $fpdf->Cell(($wm / 5.35) * 2.15, $h * 1.2, "MATERIA", 1, 0, 'C');
                    $fpdf->Cell(($wm / 6) / 3.5, $h * 1.2, "CR", 1, 0, 'C');
                    $fpdf->Cell(($wm / 9) / 1.3, $h * 1.2, "SEM", 1, 0, 'C');
                    $fpdf->Cell(($wm / 6) * 0.9, $h * 1.2, "PERIODO", 1, 0, 'C');
                    $fpdf->Cell(($wm / 6) / 1.1, $h * 1.2, utf8_decode("CALIFICACIÓN"), 1, 0, 'C');
                    $fpdf->Cell(($wm / 6) / 1.1, $h * 1.2, "OPORTUNIDAD", 1, 1, 'C');
                    $fpdf->Cell($w, 0.5);
                    $fpdf->Ln();
                    $fpdf->SetFont("MM", '', 9);
                }
            }
            if ($tipo == 'K') {
                $fpdf->Ln();
                $fpdf->setx($xm);
                $fpdf->Cell(($wm / 6) * 2.15, $h * 1.2, "", 0, 0, 'R');
                $fpdf->Cell(($wm / 6) / 3.5, $h * 1.2, "", 0, 0, 'C');
                $fpdf->Cell(($wm / 6) / 1.3, $h * 1.2, "", 0, 0, 'C');
                $fpdf->Cell(($wm / 6) * 0.9, $h * 1.2, "", 0, 0, 'C');
                $fpdf->Cell(($wm / 6) / 1.1, $h * 1.2, "Promedio Gral", 1, 0, 'C');
                $num=$total_materias==0?0:round(($suma_total_calif / $total_materias),2);
                $fpdf->Cell(($wm / 6) / 1.1, $h * 1.2, $num, 1, 0, 'C');
            }
            if ($tipo == 'D' || $tipo == 'P' || $tipo == 'G' || $tipo == 'B' || $tipo == 'K') {
                if ($tipo == 'B') {
                    $promedio = round(($sumatoria / $cuenta), 2);
                }
                if ($CU == 0) {
                    $suma=HistoriaAlumno::where('no_de_control',$control)
                        ->where('calificacion','!=',0)
                        ->where('calificacion','!=',60)
                        ->get()
                        ->sum('calificacion');
                    $total=HistoriaAlumno::where('no_de_control',$control)
                        ->where('calificacion','!=',0)
                        ->where('calificacion','!=',60)
                        ->distinct()
                        ->count('materia');
                    $promedio = $total==0?0:round($suma / $total, 2);
                    $acumulados .= "\nPromedio general: " . $promedio;
                }
                $fpdf->SetY($fpdf->GetY() + $h * 2);
                $fpdf->SetX($x);
                $fpdf->MultiCell($w, $h, $acumulados);
            }
        }
        if($tipo == 'M') // Horario
        {
            $y_hor= $fpdf->GetY();
            $this->horario($fpdf, 2, $horarios2,$y_hor);
            $fpdf->Ln(3);
        }
        if($fpdf->GetY() >= 220){
            $fpdf->SetXY($x,260);
            if($hojas==0){$hojas++;}
            if(($tipo != 'C' && $tipo != 'R')){
                $fpdf->SetXY(60,255);
                //nvo_pie_oficial($pdf);
                $fpdf->AddPage();
                $this->encabezado($fpdf,$depto,$folio,$dia,$mes,$anio);
            }
        }
        if($tipo != 'C' && $tipo != 'R'){
            $yactual= $fpdf->GetY();
            $fpdf->SetXY($x,$yactual-7);
            $fpdf->SetFont("MM",'',9);
            $fpdf->MultiCell($w, $h, utf8_decode($cierre));
            $yactual= $fpdf->GetY();
            $fpdf->SetXY($x,$yactual-18);
        }
        if(($fpdf->GetY()+15 >= 200 && $fpdf->GetY()+15 < 270) || $fpdf->GetY() < 200){
            $fpdf->Ln();
            if($tipo != 'S' && $tipo != 'A' && $tipo != 'E' && $tipo != 'T' && $tipo != 'N' && $tipo != 'IM' && $tipo != 'C' && $tipo != 'R'){
                $hojas++;
            }
            $fpdf->SetXY($x, $fpdf->GetY()+15);
        }else {
            if(($tipo != 'C' && $tipo != 'R')){
                //nvo_pie_oficial($pdf);
                $fpdf->AddPage();
                $this->encabezado($fpdf,$depto,$folio,$dia,$mes,$anio);
            }
        }
        $fpdf->SetX($x);
        $fpdf->Ln(3);
        if($tipo != 'C'){
            $fpdf->SetX($x);
            $fpdf->SetFont("MM",'B',9);
            $fpdf->Cell($w,$h,"A T E N T A M E N T E",0,1,'L');
            $fpdf->SetFont("Montserrat2",'I',8);
            //Lema TecNM
            $fpdf->SetX($x);
            $lema2=strtoupper("Excelencia en Educación Tecnológica");
            $fpdf->Cell($w,$h, mb_convert_encoding($lema2, 'ISO-8859-1', 'UTF-8'),0,1,'L');
            //Lema Tec
            $fpdf->SetFont("Montserrat2",'I',7);
            $fpdf->SetX($x);
            $fpdf->Cell($w,$h, mb_convert_encoding($_ENV["LEMA_TEC"], 'ISO-8859-1', 'UTF-8'),0,1,'L');
            //$pdf->AddFont("SoberanaSans_Bold",'','soberanasans_bold.php');
            $fpdf->SetFont("MM",'B',9);

            $fpdf->SetX($x);
            $fpdf->Cell(80,9," ",0,1,'L');
            $fpdf->SetX($x);
            $fpdf->Cell($w,$h,trim($jefatura->nombre_empleado).' '.trim($jefatura->apellidos_empleado),0,1,'L');
            $fpdf->SetX($x);
            $fpdf->Cell($w,$h,$jefe->descripcion_area,0,1,'L');
            //$pdf->MultiCell($w, $h, "ATENTAMENTE,\n".$CFG->lema."\n\n\n\n".$jefe, 0, 'J');
        }
        if($tipo == 'R'){
            $fpdf->SetFont('Helvetica','','6');
            $fpdf->SetXY($x, $fpdf->GetY()+40);
            $fpdf->MultiCell($w, $h, "c.c.p.- División de Estudios Profesionales\nc.c.p.- Archivo");
        }
        if($tipo == 'D' || $tipo == 'P' || $tipo == 'G'){
            $fpdf->SetFont('Montserrat2','B','6');
            $fpdf->SetXY($x+90, $fpdf->GetY()+5);
            $fpdf->MultiCell($w-100,$h,'LAS CALIFICACIONES QUE AMPARA EL PRESENTE DOCUMENTO SERÁN VALIDAS, PREVIO COTEJO DE LAS ACTAS CORRESPONDIENTES');
        }

        if($tipo == 'C')
            $codigo_correcto = "ITM-AC-PO-003-01";

        if($tipo == 'R')
            $codigo_correcto = "ITM-AC-PO-003-02";

        if($tipo == 'C' || $tipo == 'R'){
            $fpdf->SetFont('Helvetica','b','8');
            $fpdf->SetXY($x, 265);
            $fpdf->Cell($w/2,$h, $codigo_correcto, 0, 0, 'L');
            //$pdf->Cell($w/2,$h, "SNEST-AC--PO-008-01", 0, 0, 'L');
            $fpdf->Cell($w/2,$h, "Rev. 3", 0, 0, 'R');
        }
        $ypie = 262;
        $xpie = 10;
        $fpdf->SetLineWidth(0.1);
        $fpdf->SetDrawColor(128,0,0);
        $fpdf->Line($xpie+10,$ypie-6,190,$ypie-6);

        //$fpdf->Image("/var/www/html/escolares/public/img/escudo.jpg", 20, $ypie, 15);
        $w = 120;
        $h = 6;
        $xpie+=40+5;
        $fpdf->SetXY($xpie+5, $ypie);
        $fpdf->AddFont("Montserrat2",'','Montserrat-ExtraLight.php');
        $fpdf->AddFont("Montserrat2",'B','Montserrat-Light.php');
        $fpdf->SetFont("Montserrat2","",6);
        $fpdf->Cell($w, $h/3, "", 0, 2, 'C');
        $domicilio=mb_convert_encoding($_ENV["DOMICILIO_TEC"],'ISO-8859-1','UTF-8');
        $fpdf->Cell($w-15, $h/2, $domicilio, 0, 2, 'C');
        $fpdf->Cell($w-15, $h/2, "Tel(s). ".$_ENV["TELEFONO_TEC"], 0, 2, 'C');
        $fpdf->SetFont("Montserrat2",'B',6);
        $fpdf->Cell($w-15, $h/2, "E-mail: ".$_ENV["CORREO_ESCOLARES"]." Sitio Web ".$_ENV["SITIO_WEB"], 0, 2, 'C');
        $fpdf->Image($_ENV['RUTA_IMG_PIE_PAGINA'], 168, 263, 17,15);
        $fpdf->SetLineWidth(0.1);
        $fpdf->SetDrawColor(0);
        $fpdf->Output();
        exit();
    }
}
