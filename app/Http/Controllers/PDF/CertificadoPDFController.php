<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Acciones\AccionesController;
use App\Models\Alumno;
use App\Http\Controllers\Controller;
use App\Models\HistoriaAlumno;
use App\Models\Jefe;
use App\Models\Parametro;
use App\Models\Personal;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\NoReturn;


class CertificadoPDFController extends Controller
{
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
        return $anio."".$periodo;
    }
    public function mes_romano($mes)
    {
        $valor='';
        switch ($mes)
        {
            case '01': $valor= 'I'; break;
            case '02': $valor= 'II'; break;
            case '03': $valor= 'III'; break;
            case '04': $valor= 'IV'; break;
            case '05': $valor= 'V'; break;
            case '06': $valor= 'VI'; break;
            case '07': $valor= 'VII'; break;
            case '08': $valor= 'VIII'; break;
            case '09': $valor= 'IX'; break;
            case '10': $valor= 'X'; break;
            case '11': $valor= 'XI'; break;
            case '12': $valor= 'XII'; break;
        }
        return $valor;
    }
    public function mes_espanol($mes)
    {
        $valor='';
        switch ($mes)
        {
            case '01': $valor= 'Enero'; break;
            case '02': $valor= 'Febrero'; break;
            case '03': $valor= 'Marzo'; break;
            case '04': $valor= 'Abril'; break;
            case '05': $valor= 'Mayo'; break;
            case '06': $valor= 'Junio'; break;
            case '07': $valor= 'Julio'; break;
            case '08': $valor= 'Agosto'; break;
            case '09': $valor= 'Septiembre'; break;
            case '10': $valor= 'Octubre'; break;
            case '11': $valor= 'Noviembre'; break;
            case '12': $valor= 'Diciembre'; break;
        }
        return $valor;
    }
    #[NoReturn] public function crearPDF(Request $request){
        $control=$request->get('control');
        $director=$request->get('director');
        $registro=$request->get('registro');
        $libro=$request->get('libro');
        $fojas=$request->get('foja');
        $fecha_registro=$request->get('fecha_registro');
        $fecha_emision=$request->get('fecha_emision');
        $iniciales=$request->get('iniciales');
        $tipo=$request->get('tipo');

        $alumno=Alumno::where('no_de_control',$control)->first();
        $nombre_alumno=$alumno->nombre_alumno." ".$alumno->apellido_paterno." ".$alumno->apellido_materno;
        $ncarrera=(new AccionesController)->ncarrera($alumno->carrera,$alumno->reticula);
        $ultimo_periodo=HistoriaAlumno::where('no_de_control',$control)
            ->whereIn('tipo_evaluacion',array('01','02','1','2','R1','R2','E1','OO','RC','RU','RO','CE','RP','EA','PG'))
            ->max('periodo');
        if(substr($ultimo_periodo,-1)==1||substr($ultimo_periodo,-1)==2){
            $mes_final="JUNIO";
        }else{
            $mes_final="DICIEMBRE";
        }
        $anio_final=substr($ultimo_periodo,0,4);
        if(substr($alumno->periodo_ingreso_it,-1)==1){
            $mes_inicial="ENERO";
        }else{
            $mes_inicial="AGOSTO";
        }
        $anio_inicial=substr($alumno->periodo_ingreso_it,0,4);

        $fpdf =new Fpdf('P','mm','Letter');
        $fpdf->AddPage();
        $fpdf->SetAutoPageBreak(false);
        //Texto Inicial
        $tamano_fuente = '7';
        $y_texto_inicial = 19;//original 17
        $x_texto_inicial_y_final = 53;//original 51
        $fpdf->SetXY($x_texto_inicial_y_final,$y_texto_inicial);
        //Renglon 1
        $consulta=Jefe::where('clave_area','=','100000')->first();
        $personal=Personal::where('id',$consulta->id_jefe)->first();
        $genero=$personal->sexo_empleado;
        $gTexto=$genero=='F'?"LA":"EL";
        $fpdf->SetFont('Helvetica','',$tamano_fuente);
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

        }else{
            $texto_equivalencia='';
        }
        $datos_tec=Parametro::where('id',1)->first();
        $tec=strtoupper($datos_tec->tec);
        $cct=strtoupper($datos_tec->cct);
        $ciudad=strtoupper($datos_tec->ciudad);
        $texto_encapsulado = $gTexto . " C. " . $director . " DIRECTOR DEL ".trim($tec);
        $texto_encapsulado.=" CLAVE ".trim($cct)." CERTIFICA QUE, SEGÚN CONSTANCIAS QUE EXISTEN EN EL ARCHIVO ";
        $texto_encapsulado.="ESCOLAR DE ESTE INSTITUTO, EL (LA) C. ".$nombre_alumno." CURSÓ LAS ASIGNATURAS QUE";
        $texto_encapsulado.=" INTEGRAN EL PLAN DE ESTUDIOS DE ".strtoupper($ncarrera->nreal)." DE ";
        $texto_encapsulado.=$mes_inicial." ".$anio_inicial;
        $texto_encapsulado.=" A ".$mes_final." ".$anio_final." CON LOS RESULTADOS QUE A CONTINUACIÓN SE ANOTAN:";
        $fpdf->MultiCell(160,3,utf8_decode($texto_encapsulado));

        //Número de Control
        $x_nc_r_c = 7;
        $y_no_control = 191;//original 189
        $fpdf->SetFont('Helvetica','','7');
        $fpdf->SetXY($x_nc_r_c,$y_no_control);
        $fpdf->Cell(27,3,"No. DE CONTROL",1,2,'C');
        $fpdf->SetFont('Helvetica','b','8');
        $fpdf->Cell(27,4,$control,1,0,'C');

        //Registro
        $y_registro = 199; //original 197
        $fpdf->SetXY($x_nc_r_c,$y_registro);
        $fpdf->SetFont('Helvetica','','6');
        $fpdf->Cell(27,2,"REGISTRADO EN EL",0,2,'L');
        $fpdf->Cell(27,2,"DEPARTAMENTO DE",0,2,'L');
        $fpdf->Cell(27,2,"SERVICIOS ESCOLARES",0,2,'L');
        $fpdf->Cell(27,2,"",0,2,'C');
        $fpdf->SetFont('Helvetica','','6');
        $fpdf->Cell(15,4,"CON No.:",0,2,'L');
        $fpdf->Cell(15,4,"EN EL LIBRO:",0,2,'L');
        $fpdf->Cell(15,4,"A FOJAS:",0,2,'L');
        $fpdf->Cell(15,4,"FECHA:",0,2,'L');
        $fpdf->SetXY($x_nc_r_c+14,$y_registro+8);
        $fpdf->SetFont('Helvetica','b','6');
        $fpdf->Cell(12,4,$registro,0,2,'L');
        $fpdf->Cell(12,4,$libro,0,2,'L');
        $fpdf->Cell(12,4,$fojas,0,2,'L');
        $fpdf->Cell(12,4,substr($fecha_registro,8,2)."-".$this->mes_romano(substr($fecha_registro,5,2))."-".substr($fecha_registro,0,4),0,2,'L');
        $fpdf->SetXY($x_nc_r_c,$y_registro-1);
        $fpdf->Cell(27,8,"",1,2,'L');
        $fpdf->Cell(27,17,"",1,2,'L');

        //Cotejo
        $y_cotejo = 235;//Original 235
        $fpdf->SetXY($x_nc_r_c,$y_cotejo);
        $fpdf->SetFont('Helvetica','','6');
        $fpdf->Cell(27,3,"COTEJO",1,2,'C');
        $fpdf->SetFont('Helvetica','b','6');
        if(isset($tipo)){
            $fpdf->Cell(27,6,($tipo=='D')?"RUBRICA":"",0,2,'C');}
        $fpdf->Cell(27,3,$iniciales,0,2,'C');
        $fpdf->SetXY($x_nc_r_c,$y_cotejo+3);
        $fpdf->Cell(27,9,"",1,2,'C');

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
        $fpdf->SetY($y_materias);
        $fpdf->SetFont('Helvetica','','7');
        $equivalencias=0;
        $convalidacion=0;

        $qry_materias = (new AccionesController)->historial($control);
        foreach($qry_materias as $value)
        {
            $fpdf->SetX($x_materias);
            //Nombre de la materia
            if(($value->tipo_evaluacion == "RU")||($value->tipo_evaluacion == "RC")){
                $fpdf->Cell($ancho_nombre_materia,3,strtoupper(utf8_decode($value->nombre_completo_materia))." *",0,0,'L');
            }else{
                $fpdf->Cell($ancho_nombre_materia,3,strtoupper(utf8_decode($value->nombre_completo_materia)),0,0,'L');
                if(($value->tipo_evaluacion!= "AC")&&($value->tipo_evaluacion!= "PG")){
                    $num_materias += 1;
                    $suma_calif += $value->calificacion;
                }
            }
            //Calificacion
            if($value->tipo_evaluacion == "RU"||$value->tipo_evaluacion == "PG"){
                $fpdf->Cell(3,3,'AC',0,0,'R');
            }elseif(($value->tipo_evaluacion) == "AC"){
                if($alumno->reticula==15){
                    if(substr($control,0,2)<19){
                        $fpdf->Cell(3,3,'ACA',0,0,'R');
                    }else{
                        $fpdf->Cell(3,3,'',0,0,'R');
                    }
                }else{
                    $fpdf->Cell(3,3,'ACA',0,0,'R');
                }
            }else{
                $fpdf->Cell(3,3,$value->calificacion,0,0,'R');
            }
            $fpdf->Cell(2,3,"",0,0,'C');
            //<---
            //Observaciones
            if(($value->tipo_evaluacion) == "EE"){
                $fecha_explotada=explode("-",$value->fecha_calificacion);
                $dia_certificado=$fecha_explotada[0];
                $mes_certificado=$fecha_explotada[1];
                $anio_certificado=$fecha_explotada[2];
                $mes_romano='';
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
                $fpdf->Cell($ancho_observaciones,3,"EE/".$dia_certificado."-".$mes_romano."-".$anio_certificado,0,0,'C');
                $renglon_libre = 0;
                $y_renglon_libre = $y_materias;
            }else{
                $renglon_libre++;
                $equivalencia_impresa='';
                $texto_equivalencia='';
                if(($value->tipo_evaluacion) == "RU"){
                    $fpdf->Cell($ancho_observaciones,3,"",0,0,'C');
                    $renglon_libre=0;
                    $y_renglon_libre = $y_materias;
                    $equivalencias++;
                }elseif(($value->tipo_evaluacion) == "AC"){
                    if($alumno->reticula==15){
                        if(substr($control,0,2)<19){
                            $fpdf->Cell($ancho_observaciones,3,"",0,0,'C');
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
                            $fpdf->Cell($ancho_observaciones,3,"EXCELENTE",0,0,'C');
                        }
                        //}
                    }else{
                        $fpdf->Cell($ancho_observaciones,3,"",0,0,'C');
                    }
                    $renglon_libre=0;
                    $y_renglon_libre = $y_materias;                       //<-------AGREGADO
                }elseif(($value->tipo_evaluacion) == "RC"){
                    $fpdf->Cell($ancho_observaciones,3,"",0,0,'C');
                    $renglon_libre=0;
                    $y_renglon_libre = $y_materias;
                    $convalidacion++;
                }else{
                    if($renglon_libre==5 && $alumno->tipo_ingreso=='2' && $equivalencia_impresa==0){
                        $x_temp = $fpdf->GetX();
                        $y_temp = $fpdf->GetY();
                        $fpdf->SetXY($x_temp,$y_renglon_libre);
                        $fpdf->MultiCell($ancho_observaciones,3,$texto_equivalencia,0,'L');
                        $fpdf->SetXY($x_temp+$ancho_observaciones,$y_temp);
                    }else{
                        if($renglon_libre==1)
                            $y_renglon_libre = $fpdf->GetY();
                        $fpdf->Cell($ancho_observaciones,3,"",0,0,'L');
                    }
                }
            }

            //Créditos
            $fpdf->Cell(2,3,"",0,0,'C');
            $fpdf->Cell(5,3,$value->creditos_materia,0,0,'R');
            $fpdf->Cell(2,3,"",0,1,'C');
            $sumacreditos += $value->creditos_materia;
        }
        if($equivalencias>0){
            $fpdf->SetXY(162,$y_materias+6);//En 12 original
            $fpdf->MultiCell(40,4,"* ".$texto_equivalencia);
        }
        if($convalidacion>0){
            //$this->fpdf->SetXY(157,$this->fpdf->GetY());
            $fpdf->SetXY(162,$y_materias+12);
            $fpdf->MultiCell(40,4,"* Convalidacion de estudios con ".$convalidacion." materias a partir de Agosto 2007");
        }
        //Promedio
        $x_promedio = 146;
        $y_promedio = 248;
        $promedio = ($num_materias==0)?0:round($suma_calif/$num_materias,2);
        $fpdf->SetXY($x_promedio,$y_promedio);
        $fpdf->SetFont('Helvetica','b','8');
        if($equivalencias==0)
            $fpdf->Cell(10,3,$promedio,0,0,'R');

        //Texto Final
        $y_texto_final = 255;//original 258
        if($sumacreditos >  $ncarrera->creditos_totales){
            $sumacreditos=$ncarrera->creditos_totales;
        }
        $texto_final  = "SE EXPIDE EL PRESENTE CERTIFICADO QUE AMPARA ".$sumacreditos." CREDITOS DE UN TOTAL DE ".$ncarrera->creditos_totales;
        $texto_final .= " QUE INTEGRAN EL PLAN DE ESTUDIOS CON CLAVE ".trim($ncarrera->clave_oficial)." EN LA CIUDAD DE ".$ciudad." A";
        $texto_final .= " LOS ".substr($fecha_emision,8,2)." DIAS DEL MES DE ".strtoupper($this->mes_espanol(substr($fecha_emision,5,2)))." DE ".substr($fecha_emision,0,4).".";
        $fpdf->SetXY($x_texto_inicial_y_final,$y_texto_final);
        $fpdf->SetFont('Helvetica','','7');
        $fpdf->MultiCell(160,4,$texto_final);

        //Nombre del director
        $x_director = 42;
        $y_director = 264;//original 269
        $fpdf->SetXY($x_director,$y_director);
        $fpdf->SetFont('Helvetica','b','8');
        if(isset($tipo)){
            $fpdf->Cell(168,4,($tipo=='D')?"RUBRICA":"",0,2,'C');}
        $fpdf->Cell(168,4,utf8_decode($director)
            ,0,2,'C');
        $fpdf->Cell(168,3,"Director",0,2,'C');
        //Leyenda para certificados Incompletos, Reposiciones o Duplicados
        $leyenda = "";//(($sumacreditos<$res_alumno->fields("creditos_totales"))?"INCOMPLETO":""));
        if(isset($tipo)){$leyenda .= ($tipo=='D')?"DUPLICADO":(($tipo=='R')?"REPOSICION":"");}
        $leyenda .= " ";
        $leyenda .= ($sumacreditos<$ncarrera->creditos_totales)?"INCOMPLETO":"";
        $fpdf->SetXY($x_texto_inicial_y_final,$y_texto_inicial-3);
        $fpdf->SetFont('Helvetica','b',$tamano_fuente);
        $fpdf->Cell(140,3,$leyenda,0,0,'C');

        $fpdf->Output();
        exit();
    }
}
