<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Acciones\AccionesController;
use App\Models\Alumno;
use App\Http\Controllers\Controller;
use App\Models\HistoriaAlumno;
use App\Models\Jefe;
use App\Models\Personal;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CertificadoPDFController extends Controller
{
    private $fpdf;
    public function __construct(){

    }
    public function calcula_periodo_ingreso_octaware($periodo_ingreso,$revalidacion){
        $anio=substr($periodo_ingreso,0,4);
        $periodo=substr($periodo_ingreso,4,1);
        for($i=1;$i<=$revalidacion;$i++){
            if($periodo==1){
                $periodo=3;
                $anio=$anio-1;
            }else{
                $periodo=1;
            }
        }
        $valido=$anio."".$periodo;
        return $valido;
    }
    public function mes_romano($mes)
    {
        switch ($mes)
        {
            case '01': return 'I'; break;
            case '02': return 'II'; break;
            case '03': return 'III'; break;
            case '04': return 'IV'; break;
            case '05': return 'V'; break;
            case '06': return 'VI'; break;
            case '07': return 'VII'; break;
            case '08': return 'VIII'; break;
            case '09': return 'IX'; break;
            case '10': return 'X'; break;
            case '11': return 'XI'; break;
            case '12': return 'XII'; break;
        }
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
    public function crearPDF(Request $request){
        $control=$request->get('control');
        $director=$request->get('director');
        $registro=$request->get('registro');
        $libro=$request->get('libro');
        $fojas=$request->get('foja');
        $fecha_registro=$request->get('fecha_registro');
        $fecha_emision=$request->get('fecha_emision');
        $iniciales=$request->get('iniciales');
        $tipo=$request->get('tipo');

        $alumno=Alumno::findOrfail($control);
        $nombre_alumno=$alumno->nombre_alumno." ".$alumno->apellido_paterno." ".$alumno->apellido_materno;
        $ncarrera=(new AccionesController)->ncarrera($alumno->carrera,$alumno->reticula);
        $ultimo_periodo=HistoriaAlumno::where('no_de_control',$control)
            ->whereIn('tipo_evaluacion',array('01','02','1','2','R1','R2','E1','OO','RC','RU','RO','CE','RP','EA','PG'))
            ->max('periodo');
        if((substr($ultimo_periodo,-1)==1)||(substr($ultimo_periodo,-1)==2)){
            $mes_final="JUNIO";
            $anio_final=substr($ultimo_periodo,0,4);
        }else{
            $mes_final="DICIEMBRE";
            $anio_final=substr($ultimo_periodo,0,4);
        }
        if(substr($alumno->periodo_ingreso_it,-1)==1){
            $mes_inicial="ENERO";
        }else{
            $mes_inicial="AGOSTO";
        }
        $anio_inicial=substr($alumno->periodo_ingreso_it,0,4);

        $this->fpdf=new Fpdf('P','mm','Letter');
        $this->fpdf->AddPage();
        $this->fpdf->SetAutoPageBreak(false);
        //Texto Inicial
        $tamano_fuente = '7';
        $y_texto_inicial = 19;//original 17
        $x_texto_inicial_y_final = 53;//original 51
        $this->fpdf->SetXY($x_texto_inicial_y_final,$y_texto_inicial);
        //Renglon 1
        $consulta=Jefe::where('clave_area','=','100000')->first();
        $rfc=$consulta->rfc;
        $personal=Personal::where('rfc',$rfc)->first();
        $genero=$personal->sexo_empleado;
        $gTexto=$genero=='F'?"LA":"EL";
        $this->fpdf->SetFont('Helvetica','',$tamano_fuente);
        if($alumno->periodos_revalidacion > 0){
            $periodo_original=$this->calcula_periodo_ingreso_octaware($alumno->periodo_ingreso_it,$alumno->periodos_revalidacion);
            $periodo_original_in=substr($periodo_original,4,1);
            $mes_inicial=$periodo_original_in==1?"ENERO":"AGOSTO";
        }
        if($alumno->tipo_ingreso=='2')
        {
            $autoridad_educativa=$request->get('autoridad_educativa');
            $folio=$request->get('folio');
            $fecha_elaboracion=$request->get('fecha_elaboracion');
            $texto_equivalencia = "Equivalencia de Estudios expedida por ".$autoridad_educativa." número de folio ".$folio." fecha ".substr($fecha_elaboracion,8,2)."-".$this->mes_romano(substr($fecha_elaboracion,5,2))."-".substr($fecha_elaboracion,0,4);
            $equivalencia_impresa = 0;
        }
        $texto_encapsulado = $gTexto . " C. " . $director . " DIRECTOR DEL INSTITUTO TECNOLÓGICO DE ENSENADA";
        $texto_encapsulado.=" CLAVE 02DIT0023K CERTIFICA QUE SEGÚN CONSTANCIAS QUE EXISTEN EN EL ARCHIVO ";
        $texto_encapsulado.="ESCOLAR DE ESTE INSTITUTO EL (LA) C. ".$nombre_alumno." CURSÓ LAS ASIGNATURAS QUE";
        $texto_encapsulado.=" INTEGRAN EL PLAN DE ESTUDIOS DE ".strtoupper($ncarrera->nreal)." DE ";
        $texto_encapsulado.=$mes_inicial." ".$anio_inicial;
        $texto_encapsulado.=" A ".$mes_final." ".$anio_final." CON LOS RESULTADOS QUE A CONTINUACIÓN SE ANOTAN:";
        $this->fpdf->MultiCell(160,3,utf8_decode($texto_encapsulado),0,'J');

        //Número de Control
        $x_nc_r_c = 7;
        $y_no_control = 191;//original 189
        $this->fpdf->SetFont('Helvetica','','7');
        $this->fpdf->SetXY($x_nc_r_c,$y_no_control);
        $this->fpdf->Cell(27,3,"No. DE CONTROL",1,2,'C');
        $this->fpdf->SetFont('Helvetica','b','8');
        $this->fpdf->Cell(27,4,$control,1,0,'C');

        //Registro
        $y_registro = 199; //original 197
        $this->fpdf->SetXY($x_nc_r_c,$y_registro);
        $this->fpdf->SetFont('Helvetica','','6');
        $this->fpdf->Cell(27,2,"REGISTRADO EN EL",0,2,'L');
        $this->fpdf->Cell(27,2,"DEPARTAMENTO DE",0,2,'L');
        $this->fpdf->Cell(27,2,"SERVICIOS ESCOLARES",0,2,'L');
        $this->fpdf->Cell(27,2,"",0,2,'C');
        $this->fpdf->SetFont('Helvetica','','6');
        $this->fpdf->Cell(15,4,"CON No.:",0,2,'L');
        $this->fpdf->Cell(15,4,"EN EL LIBRO:",0,2,'L');
        $this->fpdf->Cell(15,4,"A FOJAS:",0,2,'L');
        $this->fpdf->Cell(15,4,"FECHA:",0,2,'L');
        $this->fpdf->SetXY($x_nc_r_c+14,$y_registro+8);
        $this->fpdf->SetFont('Helvetica','b','6');
        $this->fpdf->Cell(12,4,$registro,0,2,'L');
        $this->fpdf->Cell(12,4,$libro,0,2,'L');
        $this->fpdf->Cell(12,4,$fojas,0,2,'L');
        $this->fpdf->Cell(12,4,substr($fecha_registro,8,2)."-".$this->mes_romano(substr($fecha_registro,5,2))."-".substr($fecha_registro,0,4),0,2,'L');
        $this->fpdf->SetXY($x_nc_r_c,$y_registro-1);
        $this->fpdf->Cell(27,8,"",1,2,'L');
        $this->fpdf->Cell(27,17,"",1,2,'L');

        //Cotejo
        $y_cotejo = 235;//Original 235
        $this->fpdf->SetXY($x_nc_r_c,$y_cotejo);
        $this->fpdf->SetFont('Helvetica','','6');
        $this->fpdf->Cell(27,3,"COTEJO",1,2,'C');
        $this->fpdf->SetFont('Helvetica','b','6');
        if(isset($tipo)){$this->fpdf->Cell(27,6,($tipo=='D')?"RUBRICA":"",0,2,'C');}
        $this->fpdf->Cell(27,3,$iniciales,0,2,'C');
        $this->fpdf->SetXY($x_nc_r_c,$y_cotejo+3);
        $this->fpdf->Cell(27,9,"",1,2,'C');

        //Lista de Materias en orden para la emision de certificado
        $x_materias = 58;
        $y_materias = 49;//original 49
        $ancho_nombre_materia = 100;
        $ancho_observaciones = 38;

        $sumacreditos = 0;
        $suma_calif	= 0;
        $num_materias = 0;
        $renglon_libre = 0;
        $y_renglon_libre = $y_materias;
        $this->fpdf->SetY($y_materias);
        $this->fpdf->SetFont('Helvetica','','7');
        $equivalencias=0;
        $convalidacion=0;

        $qry_materias = (new AccionesController)->historial($control);
        foreach($qry_materias as $key=>$value)
        {
            $this->fpdf->SetX($x_materias);
            //Nombre de la materia
            if(($value->tipo_evaluacion == "RU")||($value->tipo_evaluacion == "RC")){
                $this->fpdf->Cell($ancho_nombre_materia,3,strtoupper(utf8_decode($value->nombre_completo_materia))." *",0,0,'L');
            }else{
                $this->fpdf->Cell($ancho_nombre_materia,3,strtoupper(utf8_decode($value->nombre_completo_materia)),0,0,'L');
                if(($value->tipo_evaluacion!= "AC")&&($value->tipo_evaluacion!= "PG")){
                    $num_materias += 1;
                    $suma_calif += $value->calificacion;
                }
            }
            //Calificacion
            if($value->tipo_evaluacion == "RU"||$value->tipo_evaluacion == "PG"){
                $this->fpdf->Cell(3,3,'AC',0,0,'R');
            }elseif(($value->tipo_evaluacion) == "AC"){
                $alumno->reticula==15?(substr($control,0,2)<19?$this->fpdf->Cell(3,3,'ACA',0,0,'R'):$this->fpdf->Cell(3,3,'',0,0,'R')):$this->fpdf->Cell(3,3,'ACA',0,0,'R');
            }else{
                $this->fpdf->Cell(3,3,$value->calificacion,0,0,'R');
            }
            $this->fpdf->Cell(2,3,"",0,0,'C');
            //<---
            //Observaciones
            if(($value->tipo_evaluacion) == "EE"){
                $fecha_explotada=explode("-",$value->fecha_calificacion);
                $dia_certificado=$fecha_explotada[0];
                $mes_certificado=$fecha_explotada[1];
                $anio_certificado=$fecha_explotada[2];
                switch($mes_certificado){
                    case 1: $mes_romano="I"; break;
                    case 2: $mes_romano="II"; break;
                    case 3: $mes_romano="III"; break;
                    case 4: $mes_romano="IV"; break;
                    case 5: $mes_romano="V"; break;
                    case 6: $mes_romano="VI"; break;
                    case 7: $mes_romano="VII"; break;
                    case 8: $mes_romano="VIII"; break;
                    case 9: $mes_romano="IX"; break;
                    case 10: $mes_romano="X"; break;
                    case 11: $mes_romano="XI"; break;
                    case 12: $mes_romano="XII"; break;
                }
                $this->fpdf->Cell($ancho_observaciones,3,"EE/".$dia_certificado."-".$mes_romano."-".$anio_certificado,0,0,'C');
                $renglon_libre = 0;
                $y_renglon_libre = $y_materias;
            }else{
                $renglon_libre++;
                if(($value->tipo_evaluacion) == "RU"){
                    $this->fpdf->Cell($ancho_observaciones,3,"",0,0,'C');
                    $renglon_libre=0;
                    $y_renglon_libre = $y_materias;
                    $equivalencias++;
                }elseif(($value->tipo_evaluacion) == "AC"){
                    if($alumno->reticula==15){
                        if(substr($control,0,2)<19){
                            $this->fpdf->Cell($ancho_observaciones,3,"",0,0,'C');
                        }else{
                            /*$qry_complementa="SELECT COUNT(*) as existe FROM actcomplementa WHERE
							 control='$control'";
                            $res_complementa=ejecutar_sql($qry_complementa);
                            if($res_complementa->"existe")){
                                $qry_calif=" SELECT CAST(ROUND(calif, 2) AS NUMERIC(12,2)) as
									prom_compl FROM actcomplementa WHERE control='$control'";
                                $res_calif=ejecutar_sql($qry_calif);
                                $leyenda_comp=observacion($res_calif->"prom_compl"));
                                $this->fpdf->Cell($ancho_observaciones,3,$leyenda_comp,0,0,'C');
                            }else{*/
                            $this->fpdf->Cell($ancho_observaciones,3,"EXCELENTE",0,0,'C');
                        }
                        //}
                    }else{
                        $this->fpdf->Cell($ancho_observaciones,3,"",0,0,'C');
                    }
                    $renglon_libre=0;
                    $y_renglon_libre = $y_materias;                       //<-------AGREGADO
                }elseif(($value->tipo_evaluacion) == "RC"){
                    $this->fpdf->Cell($ancho_observaciones,3,"",0,0,'C');
                    $renglon_libre=0;
                    $y_renglon_libre = $y_materias;
                    $convalidacion++;
                }else{
                    if($renglon_libre==5 && $alumno->tipo_ingreso=='2' && $equivalencia_impresa==0){
                        $equivalencia_impresa = 1;
                        $x_temp = $this->fpdf->GetX();
                        $y_temp = $this->fpdf->GetY();
                        $this->fpdf->SetXY($x_temp,$y_renglon_libre);
                        $this->fpdf->MultiCell($ancho_observaciones,3,$texto_equivalencia,0,'L');
                        $this->fpdf->SetXY($x_temp+$ancho_observaciones,$y_temp);
                    }else{
                        if($renglon_libre==1)
                            $y_renglon_libre = $this->fpdf->GetY();
                        $this->fpdf->Cell($ancho_observaciones,3,"",0,0,'L');
                    }
                }
            }

            //Creditos
            $this->fpdf->Cell(2,3,"",0,0,'C');
            $this->fpdf->Cell(5,3,$value->creditos_materia,0,0,'R');
            $this->fpdf->Cell(2,3,"",0,1,'C');
            $sumacreditos += $value->creditos_materia;
        }
        if($equivalencias>0){
            $this->fpdf->SetXY(162,$y_materias+6);//En 12 original
            $this->fpdf->MultiCell(40,4,"* ".$texto_equivalencia,0,'J');
        }
        if($convalidacion>0){
            //$this->fpdf->SetXY(157,$this->fpdf->GetY());
            $this->fpdf->SetXY(162,$y_materias+12);
            if($control=='09760165'){
                $this->fpdf->MultiCell(40,4,"* Convalidacion de estudios con ".$convalidacion." materias de Agosto de 2006 a Junio de 2009 con folio No. SE-001-13",0,'J');
            }elseif($control=='10760226'){
                $this->fpdf->MultiCell(40,4,"* Convalidacion de estudios con ".$convalidacion." materias de Agosto de 2010 a Junio de 2012 con folio No. SE-002-13",0,'J');
            }elseif($control=='09760208'){
                $this->fpdf->MultiCell(40,4,"* Convalidacion de estudios con ".$convalidacion." materias de Agosto de 2009 a Diciembre de 2009 con folio No. SE-005-14",0,'J');
            }elseif($control=='09760248'){
                $this->fpdf->MultiCell(40,4,"* Convalidacion de estudios con ".$convalidacion." materias de Agosto de 2011 a Diciembre de 2011 con folio No. SE-006-15",0,'J');
            }elseif($control=='11760067'){
                $this->fpdf->MultiCell(40,4,"* Convalidacion de estudios con ".$convalidacion." materias de Enero de 2011 a Diciembre de 2011 con folio No. SE-007-15",0,'J');
            }elseif($control=='11760053'){
                $this->fpdf->MultiCell(40,4,"* Convalidacion de estudios con ".$convalidacion." materias de Enero de 2011 a Diciembre de 2011 con folio No. SE-008-15",0,'J');
            }elseif($control=='09380192'){
                $this->fpdf->MultiCell(40,4,"* Convalidacion de estudios con ".$convalidacion." materias de Agosto de 2010 a Diciembre de 2010 con folio No. SE-009-16",0,'J');
            }elseif($control=='09760404'){
                $this->fpdf->MultiCell(40,4,"* Convalidacion de estudios con ".$convalidacion." materias de Agosto de 2010 a Diciembre de 2010 con folio No. SE-010-16",0,'J');
            }elseif($control=='09760124'){
                $this->fpdf->MultiCell(40,4,"* Convalidacion de estudios con ".$convalidacion." materias de Agosto de 2009 a Diciembre de 2009 con folio No. SE-011-17",0,'J');
            }elseif($control=='12760411'){
                $this->fpdf->MultiCell(40,4,"* Convalidacion de estudios con ".$convalidacion." materias de Agosto de 2012 a Junio 2013 con folio No. SE-012-17",0,'J');
            }elseif($control=='12760642'){
                $this->fpdf->MultiCell(40,4,"* Convalidacion de estudios con ".$convalidacion." materias de Agosto de 2012 a Junio 2013 con folio No. SE-015-17",0,'J');
            }elseif($control=='12760638'){
                $this->fpdf->MultiCell(40,4,"* Convalidacion de estudios con ".$convalidacion." materias de Agosto de 2012 a Diciembre 2013 con folio No. SE-016-17",0,'J');
            }elseif($control=='11760553'){
                $this->fpdf->MultiCell(40,4,"* Convalidacion de estudios con ".$convalidacion." materias de Agosto de 2011 a Junio 2012 con folio No. SE-017-17",0,'J');
            }elseif($control=='12760386'){
                $this->fpdf->MultiCell(40,4,"* Convalidacion de estudios con ".$convalidacion." materias de Agosto de 2012 a Junio 2013 con folio No. SE-018-18",0,'J');
            }else{
                $this->fpdf->MultiCell(40,4,"* Convalidacion de estudios con ".$convalidacion." materias a partir de Agosto 2007",0,'J');
            }
        }
        //Promedio
        $x_promedio = 146;
        $y_promedio = 248;
        $promedio = ($num_materias==0)?0:round($suma_calif/$num_materias,2);
        $this->fpdf->SetXY($x_promedio,$y_promedio);
        $this->fpdf->SetFont('Helvetica','b','8');
        if($equivalencias==0)
            $this->fpdf->Cell(10,3,$promedio,0,0,'R');

        //Texto Final
        $y_texto_final = 255;//original 258
        if($sumacreditos >  $ncarrera->creditos_totales){
            $sumacreditos=$ncarrera->creditos_totales;
        }
        $texto_final  = "SE EXPIDE EL PRESENTE CERTIFICADO QUE AMPARA ".$sumacreditos." CREDITOS DE UN TOTAL DE ".$ncarrera->creditos_totales;
        $texto_final .= " QUE INTEGRAN EL PLAN DE ESTUDIOS CON CLAVE ".trim($ncarrera->clave_oficial)." EN LA CIUDAD DE ENSENADA, BAJA CALIFORNIA A";
        $texto_final .= " LOS ".substr($fecha_emision,8,2)." DIAS DEL MES DE ".strtoupper($this->mes_espanol(substr($fecha_emision,5,2)))." DE ".substr($fecha_emision,0,4).".";
        $this->fpdf->SetXY($x_texto_inicial_y_final,$y_texto_final);
        $this->fpdf->SetFont('Helvetica','','7');
        $this->fpdf->MultiCell(160,4,$texto_final,0,'J');

        //Nombre del director
        $x_director = 42;
        $y_director = 264;//original 269
        $this->fpdf->SetXY($x_director,$y_director);
        $this->fpdf->SetFont('Helvetica','b','8');
        if(isset($tipo)){$this->fpdf->Cell(168,4,($tipo=='D')?"RUBRICA":"",0,2,'C');}
        $this->fpdf->Cell(168,4,utf8_decode($director)
            ,0,2,'C');
        $this->fpdf->Cell(168,3,"Director",0,2,'C');
        //Leyenda para certificados Incompletos, Reposiciones o Duplicados
        $leyenda = "";//(($sumacreditos<$res_alumno->fields("creditos_totales"))?"INCOMPLETO":""));
        if(isset($tipo)){$leyenda .= ($tipo=='D')?"DUPLICADO":(($tipo=='R')?"REPOSICION":"");}
        $leyenda .= " ";
        $leyenda .= ($sumacreditos<$ncarrera->creditos_totales)?"INCOMPLETO":"";
        $this->fpdf->SetXY($x_texto_inicial_y_final,$y_texto_inicial-3);
        $this->fpdf->SetFont('Helvetica','b',$tamano_fuente);
        $this->fpdf->Cell(140,3,$leyenda,0,0,'C');

        $this->fpdf->Output();
        exit();
    }
}
