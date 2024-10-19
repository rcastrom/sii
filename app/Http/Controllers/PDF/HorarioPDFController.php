<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Acciones\AccionesController;
use App\Http\Controllers\Controller;
use App\Models\ApoyoDocencia;
use App\Models\Carrera;
use App\Models\Categoria;
use App\Models\Grupo;
use App\Models\Horario;
use App\Models\HorarioAdministrativo;
use App\Models\HorarioObservacion;
use App\Models\Jefe;
use App\Models\Motivo;
use App\Models\PeriodoEscolar;
use App\Models\Personal;
use App\Models\PersonalPlaza;
use App\Models\SeleccionMateria;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Codedge\Fpdf\Fpdf\Fpdf;


class HorarioPDFController extends Controller
{
    private $fpdf;

    public float $xt=0;
    public float $te=2.7;
    public float $py=5;
    public int $hcolm=0;
    public int $hcol=0;
    public int $hcolmi=0;
    public int $hcolj=0;
    public int $hcolvi=0;
    public int $hcols=0;
    public int $hycol1=0;
    public int $hycol2=0;
    public int $hycol3=0;
    public int $hycol4=0;
    public int $hycol5=0;
    public int $hycol6=0;
    public int $hacol1=0;
    public int $hacol2=0;
    public int $hacol3=0;
    public int $hacol4=0;
    public int $hacol5=0;
    public int $hacol6=0;


    public function __construct(){

    }

    public function encabezado($ruta_sep,$ruta_escudo)
    {
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->Image($ruta_sep,8,13,55);
        $this->fpdf->Image($ruta_escudo,260,11,18);
        $x = 62;
        $y = 12;
        $w = 175;
        $h = 4;
        $this->fpdf->SetXY($x+20, $y+5);
        $this->fpdf->SetFont('Arial','b','14');
        $this->fpdf->Cell($w, $h, mb_convert_encoding("Tecnológico Nacional de México", 'ISO-8859-1', 'UTF-8'), 0, 2);
        $this->fpdf->SetFont('Arial','b','11');
        $this->fpdf->Cell($w, $h, mb_convert_encoding("INSTITUTO TECNOLÓGICO DE ENSENADA", 'ISO-8859-1', 'UTF-8'), 0, 2);

        $x = 5;
        $y = 30;
        $this->fpdf->SetXY($x, $y);
        $this->fpdf->SetFont('Arial','b','5');
        $this->fpdf->SetFillColor(0,0,128);
        $this->fpdf->Rect(5, 35, 283, 1, 'F');

    }

    public function nivel_docente($id){
        return (new AccionesController)->nivel_academico_docente($id);
    }

    public function caratula($descripcion_area,$nombre_periodo,$docente){
        $this->fpdf->Text($this->xt+76,43-$this->py,"C.C.T.:");
        $this->fpdf->Rect($this->xt+75,40-$this->py,15,4,'D');
        $this->fpdf->Text($this->xt+111,43-$this->py,"PERIODO ESCOLAR");
        $this->fpdf->Rect($this->xt+110,40-$this->py,35,4,'D');
        $this->fpdf->Text($this->xt+170,43-$this->py,"RFC");
        $this->fpdf->Rect($this->xt+169,40-$this->py,12,4,'D');
        $this->fpdf->SetFont('Helvetica','b','8');
        $this->fpdf->SetLineWidth(.55);
        $this->fpdf->Rect(5,44-$this->py,283,21,'D');
        $this->fpdf->SetLineWidth(.4);

        $this->fpdf->Rect(5,44-$this->py,165,21,'D');
        $this->fpdf->Rect(5,44-$this->py,165,3,'D'); //<-
        $this->fpdf->Text(7,44+$this->te-$this->py,"NOMBRE COMPLETO:");
        $this->fpdf->Rect(5,47-$this->py,165,3,'D');
        $this->fpdf->Rect(5,50-$this->py,165,3,'D');
        $this->fpdf->Rect(5,53-$this->py,165,3,'D');
        $this->fpdf->Rect(5,59-$this->py,165,3,'D');
        $this->fpdf->Rect(5,62-$this->py,165,3,'D');
        $this->fpdf->Rect(170,44-$this->py,118,9,'D');
        $this->fpdf->Rect(170,53-$this->py,118,6,'D');
        $this->fpdf->Rect(170,53-$this->py,59,12,'D');
        $this->fpdf->Rect(139,47-$this->py,15,12,'D');//cuadro pasante o titulado
        $this->fpdf->Rect(154,47-$this->py,16,12,'D');//cuadro pasante o titulado
        $this->fpdf->SetLineWidth(.35);
        $this->fpdf->Rect($this->xt+170,47-$this->py,59.3,3,'D');//cuadros de plazas
        $this->fpdf->Rect($this->xt+170,50-$this->py,59.3,3,'D');//cuadros de plazas
        $this->fpdf->Rect($this->xt+229.3,47-$this->py,59.3,3,'D');//cuadros de plazas
        //$this->fpdf->Rect($this->xt+106,47-$this->py,59.5,3,'D');//cuadros de plazas

        $this->fpdf->SetLineWidth(.4);
        $this->fpdf->SetFont('Helvetica','b','8');
        $this->fpdf->Text(172,44+$this->te-$this->py,"CLAVE COMPLETA DE LA(S) PLAZA(S):");
        $this->fpdf->Text(172,59+$this->te-$this->py,"NO. DE TARJETA DE CONTROL:");
        $this->fpdf->Text(172,53+$this->te-$this->py,"CURP");
        $this->fpdf->Text(232,53+$this->te-$this->py,"FECHA DE INGRESO A LA S.E.P.:");
        $this->fpdf->Text(232,59+$this->te-$this->py, mb_convert_encoding("FECHA DE INGRESO A LA INSTITUCIÓN:", 'ISO-8859-1', 'UTF-8'));
        $this->fpdf->Text(140,47+$this->te-$this->py,"HORAS");
        $this->fpdf->Text(155,47+$this->te-$this->py,"TITULADO");
        $this->fpdf->Text(7,47+$this->te-$this->py,"ESCOLARIDAD DEL PERSONAL");
        $this->fpdf->Text(7,50+$this->te-$this->py,"LICENCIATURA EN:");
        $this->fpdf->Text(7,53+$this->te-$this->py, mb_convert_encoding("ESPECIALIZACIÓN EN:", 'ISO-8859-1', 'UTF-8'));
        $this->fpdf->Text(7,56+$this->te-$this->py, mb_convert_encoding("MAESTRÍA EN:", 'ISO-8859-1', 'UTF-8'));
        $this->fpdf->Text(7,59+$this->te-$this->py,"DOCTORADO EN:");
        $this->fpdf->
        Text(7,62+$this->te-$this->py, mb_convert_encoding("UNIDAD ORGÁNICA DE ADSCRIPCIÓN: " .
            $descripcion_area, 'ISO-8859-1', 'UTF-8'));
        $datos_personal = Personal::where('id',$docente)->first();
        $this->fpdf->Text($this->xt+185,43.5-$this->py,strtoupper($datos_personal->rfc));// agrega el rfc
        $this->fpdf->Text($this->xt+147,43.5-$this->py,$nombre_periodo);
        $this->fpdf->Text($this->xt+92,43.5-$this->py,"02DIT0023K");
        $nivel_docente =$this->nivel_docente($docente);
        $hrs=PersonalPlaza::where('id_personal',$docente)
            ->where('estatus_plaza','A')
            ->sum('horas');
        $this->fpdf->Text(144,50+$this->te-$this->py,$hrs);
        if ($nivel_docente[0]->nivel=='I') {
            $this->fpdf->Text(43,50+$this->te-$this->py,strtoupper($nivel_docente[0]->nombre_corto));
            $gr=50;
        }elseif ($nivel_docente[0]->nivel=='J') {
            $this->fpdf->Text(43,56+$this->te-$this->py,strtoupper($nivel_docente[0]->nombre_corto));
            $gr=56;
        }elseif ($nivel_docente[0]->nivel=='K') {
            $this->fpdf->Text(43,59+$this->te-$this->py,strtoupper($nivel_docente[0]->nombre_corto));
            $gr=59;
        }elseif ($nivel_docente[0]->nivel=='H') {
            $gr=50;
        }else{
            $gr=0;
        }
        $gr!=0?$this->fpdf->Text(159,$gr+$this->te-$this->py,"X"):$this->fpdf->Text(145,$gr+$this->te-$this->py,"X");

        //nombre
        $this->fpdf->
        Text(38,44+$this->te-$this->py,trim($datos_personal->apellidos_empleado)." ".
            trim($datos_personal->nombre_empleado));
        //curp
        $this->fpdf->Text(172,56+$this->te-$this->py,$datos_personal->curp_empleado);
        //tarjeta
        $this->fpdf->Text(172,62+$this->te-$this->py,$datos_personal->no_tarjeta);
        //INICIO PLANTEL Y SEP
        $this->fpdf->Text(232,56+$this->te-$this->py,$datos_personal->ingreso_rama);//tec
        $this->fpdf->Text(232,62+$this->te-$this->py,$datos_personal->inicio_sep);
        //plazas
        $plazas=PersonalPlaza::where('id_personal',$docente)
            ->where('estatus_plaza','A')
            ->select(['horas','unidad','subunidad','id_categoria','diagonal','id_motivo'])
            ->get();
        $nombramiento=$datos_personal->nombramiento;
        $contp=1;
        $xplaz=172;
        $yplaz=47;
        foreach ($plazas as $plaza) {
            $horas_asignadas = $plaza->horas;
            if($horas_asignadas>0 && $horas_asignadas<20){
                $horas = $horas_asignadas < 10 ? "0".$horas_asignadas.".0": $horas_asignadas.".0";
            }elseif (($horas_asignadas >= 20)&&($nombramiento =="D")){
                $horas = "00.0";
            }elseif (($horas_asignadas == 36)&&($nombramiento =="A")){
                $horas = "00.0";
            }else{
                $horas="00.0";
            }
            $unidad = $plaza->unidad;
            $subunidad = $plaza->subunidad;
            $cat = Categoria::where('id',$plaza->id_categoria)
                ->select('categoria')
                ->first();
            $categoria=$cat->categoria;
            $diagonal = $plaza->diagonal;
            $tipoMov = Motivo::where('id',$plaza->id_motivo)
                ->select('motivo')
                ->first();
            $tipo_mov = $tipoMov->motivo;
            $plaza_asignada=$unidad.$subunidad.$categoria.$horas.$diagonal." MOV. ".$tipo_mov;
            $this->fpdf->Text($xplaz,$yplaz+$this->te-$this->py,$plaza_asignada);
            $contp+=1;
            $yplaz+=3;
            if ($contp==3){
                $contp=1;
                $xplaz=232;
                $yplaz=47;
            }
        }
    }

    public function carga_academica(){
        $this->fpdf->SetFont('Helvetica','b','8');
        $this->fpdf->Text(5,65.5+$this->te-$this->py, mb_convert_encoding("I.-CARGA ACADÉMICA:", 'ISO-8859-1','UTF-8'));
        $this->fpdf->SetFillColor(192,192,192);
        $this->fpdf->Rect(5,69-$this->py,283,6,'DF');
        $this->fpdf->Rect(150.4,69-$this->py,117.8,3,'DF');
        // crea columnas para parte académica
        $rn=0;
        for($i=1;$i<=6;$i++){
            $this->fpdf->Rect(150.4+$rn,72-$this->py,19.6,35,'D');
            $rn=$rn+19.6;
        }
        //crea renglones para parte de carga académica
        $rn=0;
        for($i=1;$i<=8;$i++){
            $this->fpdf->Rect(5,75+$rn-$this->py,283,4,'D');
            $rn=$rn+4;
        }
        $this->fpdf->SetFont('Helvetica','','7');
        $this->fpdf->Text(206,69+$this->te-$this->py,"HORARIO");
        $this->fpdf->Text(158,72+$this->te-$this->py,"L");
        $this->fpdf->Text(178,72+$this->te-$this->py,"M");
        $this->fpdf->Text(198,72+$this->te-$this->py,"M");
        $this->fpdf->Text(218,72+$this->te-$this->py,"J");
        $this->fpdf->Text(238,72+$this->te-$this->py,"V");
        $this->fpdf->Text(258,72+$this->te-$this->py,"S");
        $this->fpdf->SetFont('Helvetica','','7');
        $this->fpdf->Rect(5,69-$this->py,52,34,'D'); //rect de asignatura
        $this->fpdf->Text(25,70+$this->te-$this->py,"ASIGNATURA");
        $this->fpdf->Rect(57,69-$this->py,16,34,'D');// rect de grupo
        $this->fpdf->Text(60,70+$this->te-$this->py,"GRUPO");
        $this->fpdf->Rect(73,69-$this->py,16,34,'D');// rect de alumnos
        $this->fpdf->Text(75,70+$this->te-$this->py,"ALUMNOS");
        $this->fpdf->Rect(89,69-$this->py,12,34,'D');// rect de  aula
        $this->fpdf->Text(92,70+$this->te-$this->py,"AULA");
        $this->fpdf->Rect(101,69-$this->py,16,34,'D');// rect de  nivel
        $this->fpdf->Text(105,70+$this->te-$this->py,"NIVEL");
        $this->fpdf->SetFont('Helvetica','','6.5');
        $this->fpdf->Rect(117,69-$this->py,17,34,'D');// rect de  modalidad
        $this->fpdf->Text(119,70+$this->te-$this->py,"MODALIDAD");
        $this->fpdf->Text(135,70+$this->te-$this->py,"CARRERA(S)");
        $this->fpdf->Text(270,69+$this->te-$this->py,"TOTAL HRS");
        $this->fpdf->Text(270,72+$this->te-$this->py,"SEMANALES");
        $this->fpdf->SetFont('Helvetica','','8');
        $this->fpdf
            ->Text(7,103+$this->te-$this->py,
                mb_convert_encoding("PREPARACIÓN, CONTROL Y EVALUACIÓN DE MATERIAS QUE IMPARTE", 'ISO-8859-1', 'UTF-8'));
        $this->fpdf->Text(135,103+$this->te-$this->py,"SUBTOTAL");
    }

    public function mostrar_carga_academica($periodo,$docente){
        // AQUÍ COMIENZA EL LLENADO DE HORARIO ACADÉMICO
        $linea=0;
        $x_mat=152;
        $y_mat=72;
        $grupos=Grupo::where('periodo',$periodo)
            ->where('docente',$docente)
            ->whereNull('paralelo_de')
            ->join('materias','materias.materia','=','grupos.materia')
            ->select(['materias.nombre_abreviado_materia','grupos.materia','grupos.grupo',
                'grupos.tipo_personal','materias.nivel_escolar','grupos.carrera','grupos.reticula'])
            ->get();
        foreach ($grupos as $grupo) {
            //$hts=0;
            $this->fpdf->SetFont('Helvetica', '', '6');
            if (strncasecmp($grupo->nombre_abreviado_materia, "RESID", 5) != 0) {
                $this->fpdf
                    ->Text(7, 75 + $linea + $this->te - $this->py,
                        mb_convert_encoding($grupo->materia . "|" . $grupo->nombre_abreviado_materia,
                            'ISO-8859-1', 'UTF-8'));
                $this->fpdf->SetFont('Helvetica', '', '8');
                $this->fpdf->Text(60, 75 + $linea + $this->te - $this->py, $grupo->grupo);
                $total_alumnos = SeleccionMateria::where('grupo', $grupo->grupo)
                    ->where('periodo', $periodo)
                    ->where('materia', $grupo->materia)
                    ->count();
                $this->fpdf->Text(78, 75 + $linea + $this->te - $this->py, $total_alumnos);
                $aulas = Horario::where('grupo', $grupo->grupo)
                    ->where('periodo', $periodo)
                    ->where('materia', $grupo->materia)
                    ->select('aula')
                    ->first();
                $this->fpdf->Text(92, 75 + $linea + $this->te - $this->py, $aulas->aula);
                $letra = $grupo->nivel_escolar == 'L' ? "L" : "P";
                $this->fpdf->Text(108, 75 + $linea + $this->te - $this->py, $letra);
                $datos_carrera = Carrera::where('carrera', $grupo->carrera)
                    ->where('reticula', $grupo->reticula)
                    ->select(['siglas', 'modalidad'])
                    ->first();
                $this->fpdf->Text(124, 75 + $linea + $this->te - $this->py, $datos_carrera->modalidad);//Distancia
                $this->fpdf->Text(136, 75 + $linea + $this->te - $this->py, $datos_carrera->siglas);
                $linea += 4;
                $hfr = 0;
                for ($i = 2; $i <= 7; $i++) {
                    if (Horario::where('grupo', $grupo->grupo)
                            ->where('periodo', $periodo)
                            ->where('materia', $grupo->materia)
                            ->where('docente', $docente)
                            ->where('tipo_horario', 'D')
                            ->where('dia_semana', $i)
                            ->count() > 0) {
                        $horas = Horario::where('grupo', $grupo->grupo)
                            ->where('periodo', $periodo)
                            ->where('materia', $grupo->materia)
                            ->where('tipo_horario', 'D')
                            ->where('docente', $docente)
                            ->where('dia_semana', $i)
                            ->select(['hora_inicial', 'hora_final'])
                            ->first();
                        $this->fpdf->SetFont('Helvetica', '', '6');
                        $horario = date('H:i', strtotime($horas->hora_inicial)) . "-" . date('H:i',
                                strtotime($horas->hora_final));
                        $hora_salida = Carbon::parse($horas->hora_final);
                        $hora_entrada = Carbon::parse($horas->hora_inicial);
                        $hfr += $hora_entrada->diffInHours($hora_salida);
                        switch ($i) {
                            case 2:
                                $this->fpdf->Text($x_mat, $y_mat + 1, $horario);
                                $this->hcol += $hora_entrada->diffInHours($hora_salida);
                                break;
                            case 3:
                                $this->fpdf->Text($x_mat + 20, $y_mat + 1, $horario);
                                $this->hcolm += $hora_entrada->diffInHours($hora_salida);
                                break;
                            case 4:
                                $this->fpdf->Text($x_mat + 40, $y_mat + 1, $horario);
                                $this->hcolmi += $hora_entrada->diffInHours($hora_salida);
                                break;
                            case 5:
                                $this->fpdf->Text($x_mat + 60, $y_mat + 1, $horario);
                                $this->hcolj += $hora_entrada->diffInHours($hora_salida);
                                break;
                            case 6:
                                $this->fpdf->Text($x_mat + 80, $y_mat + 1, $horario);
                                $this->hcolvi += $hora_entrada->diffInHours($hora_salida);
                                break;
                            case 7:
                                $this->fpdf->Text($x_mat + 100, $y_mat + 1, $horario);
                                $this->hcols += $hora_entrada->diffInHours($hora_salida);
                                break;
                        }
                    }
                }
                $this->fpdf->Text($x_mat + 120, $y_mat + 1, $hfr);
                $y_mat += 4;
            }
        }
        $this->fpdf->SetFont("helvetica",'b',"7");
        $total=$this->hcol+$this->hcolm+$this->hcolmi+$this->hcolj+$this->hcolvi+$this->hcols;
        $this->fpdf->Text(155,103+$this->te-$this->py,$this->hcol);
        $this->fpdf->Text(175,103+$this->te-$this->py,$this->hcolm);
        $this->fpdf->Text(195,103+$this->te-$this->py,$this->hcolmi);
        $this->fpdf->Text(215,103+$this->te-$this->py,$this->hcolj);
        $this->fpdf->Text(235,103+$this->te-$this->py,$this->hcolvi);
        $this->fpdf->Text(255,103+$this->te-$this->py,$this->hcols);
        $this->fpdf->Text(275,103+$this->te-$this->py,$total);
        return $total;
    }

    public function carga_apoyo_docencia()
    {
        //****************************************HORARIO DE APOYO A DOCENCIA*******************************************
        $this->fpdf->SetFont('Helvetica','b','8');
        $this->fpdf->Text(5,107.5+$this->te-$this->py,"II.- ACTIVIDADES DE APOYO A LA DOCENCIA:");
        $this->fpdf->Rect(5,111-$this->py,283,6,'DF');
        $this->fpdf->Rect(150.4,111-$this->py,117.8,3,'DF');
        //renglones apoyo
        $rn=0;
        for($i=1;$i<=7;$i++){
            $this->fpdf->Rect(5,117+$rn-$this->py,283,3,'D');
            $rn=$rn+3;
        }
        // crea columnas para parte de apoyo a la docencia
        $rn=0;
        for($i=1;$i<=6;$i++){
            $this->fpdf->Rect(150.4+$rn,114-$this->py,19.6,29,'D');
            $rn=$rn+19.6;
        }
        $this->fpdf->Rect(5,111-$this->py,79,27,'D');//117 en lugar del 100   mover el 2do num
        $this->fpdf->Rect(5,138-$this->py,283,5,'D');
        $this->fpdf->Rect(150.4,138-$this->py,137.6,2.5,'D');//rectangulo de subtotal apoyo
        $this->fpdf->Rect(150.4,140.5-$this->py,137.6,2.5,'D');//rectangulo de subtotal apoyo + academico
        $this->fpdf->SetFont('Helvetica','','7');
        $this->fpdf->Text(25,111+$this->te-$this->py,"NOMBRE DE LA ACTIVIDAD");
        $this->fpdf->Text(108,111+$this->te-$this->py,"METAS A ATENDER");//125
        $this->fpdf->Text(206,111+$this->te-$this->py,"HORARIO");
        $this->fpdf->Text(158,114+$this->te-$this->py,"L");
        $this->fpdf->Text(178,114+$this->te-$this->py,"M");
        $this->fpdf->Text(198,114+$this->te-$this->py,"M");
        $this->fpdf->Text(218,114+$this->te-$this->py,"J");
        $this->fpdf->Text(238,114+$this->te-$this->py,"V");
        $this->fpdf->Text(258,114+$this->te-$this->py,"S");
        $this->fpdf->SetFont('Helvetica','','5');
        $this->fpdf->Text(135,137+$this->te-$this->py,"SUBTOTAL");
        $this->fpdf->Text(135,140+$this->te-$this->py,"TOTAL");
        $this->fpdf->SetFont('Helvetica','','7');
        $this->fpdf->Text(270,111+$this->te-$this->py,"TOTAL HRS");
        $this->fpdf->Text(270,114+$this->te-$this->py,"SEMANALES");
    }

    public function mostrar_carga_apoyo_docencia($periodo,$docente,$total)
    {
        $hyfs=0;
        $hyts=0;
        //COMIENZA EL LLENADO DE HORARIO DE APOYO A LA EDUCACIÓN
        $apoyos=Horario::where('periodo',$periodo)
            ->where('docente',$docente)
            ->where('tipo_horario','Y')
            ->select('consecutivo')
            ->distinct()
            ->get();
        $linea=0;
        foreach ($apoyos as $apoyo){
            for($i=2; $i<=7; $i++){
                $horas=Horario::where('periodo',$periodo)
                    ->where('docente',$docente)
                    ->where('tipo_horario','Y')
                    ->where('consecutivo',$apoyo->consecutivo)
                    ->where('dia_semana',$i)
                    ->select(['hora_inicial','hora_final'])
                    ->first();
                if(isset($horas)){
                    $horario = date('H:i', strtotime($horas->hora_inicial)) . "-" . date('H:i',
                            strtotime($horas->hora_final));
                    $hora_salida = Carbon::parse($horas->hora_final);
                    $hora_entrada = Carbon::parse($horas->hora_inicial);
                    $hyfr = $hora_entrada->diffInHours($hora_salida);
                    switch ($i) {
                        case 2:
                            $this->fpdf->Text(153,117+$linea+$this->te-$this->py,$horario);
                            $this->hycol1 += $hora_entrada->diffInHours($hora_salida);
                            break;
                        case 3:
                            $this->fpdf->Text(173,117+$linea+$this->te-$this->py,$horario);
                            $this->hycol2 += $hora_entrada->diffInHours($hora_salida);
                            break;
                        case 4:
                            $this->fpdf->Text(193,117+$linea+$this->te-$this->py,$horario);
                            $this->hycol3 += $hora_entrada->diffInHours($hora_salida);
                            break;
                        case 5:
                            $this->fpdf->Text(212,117+$linea+$this->te-$this->py,$horario);
                            $this->hycol4 += $hora_entrada->diffInHours($hora_salida);
                            break;
                        case 6:
                            $this->fpdf->Text(232,117+$linea+$this->te-$this->py,$horario);
                            $this->hycol5 += $hora_entrada->diffInHours($hora_salida);
                            break;
                        case 7:
                            $this->fpdf->Text(255,117+$linea+$this->te-$this->py,$horario);
                            $this->hycol6 += $hora_entrada->diffInHours($hora_salida);
                            break;
                    }
                    $hyfs += $hyfr;
                }
            }
            $hyts += $hyfs;
            $this->fpdf->Text(273,117+$linea+$this->te-$this->py,$hyfs);
            $actividad=ApoyoDocencia::where('periodo',$periodo)
                ->where('docente',$docente)
                ->where('consecutivo',$apoyo->consecutivo)
                ->join('actividades_apoyo',
                    'apoyo_docencia.actividad',
                    '=',
                    'actividades_apoyo.actividad')
                ->select(['actividades_apoyo.descripcion_actividad', 'apoyo_docencia.especifica_actividad'])
                ->first();
            $this->fpdf->Text(7,117+$linea+$this->te-$this->py,
                mb_convert_encoding($actividad->descripcion_actividad, 'ISO-8859-1', 'UTF-8'));
            $this->fpdf->Text(86,117+$linea+$this->te-$this->py,
                mb_convert_encoding($actividad->especifica_actividad, 'ISO-8859-1', 'UTF-8'));//125
            $linea+=3;
            $hyfs=0;
        }
        $this->fpdf->SetFont("helvetica",'b',"7");
        $this->fpdf->Text(153,137.5+$this->te-$this->py,$this->hycol1);
        $this->fpdf->Text(173,137.5+$this->te-$this->py,$this->hycol2);
        $this->fpdf->Text(193,137.5+$this->te-$this->py,$this->hycol3);
        $this->fpdf->Text(212,137.5+$this->te-$this->py,$this->hycol4);
        $this->fpdf->Text(232,137.5+$this->te-$this->py,$this->hycol5);
        $this->fpdf->Text(255,137.5+$this->te-$this->py,$this->hycol6);
        $this->fpdf->Text(273,137.5+$this->te-$this->py,$hyts);
            //suma de horas de apoyo y academicas
        $this->fpdf->Text(153,140+$this->te-$this->py,($this->hycol1+$this->hcol));
        $this->fpdf->Text(173,140+$this->te-$this->py,($this->hycol2+$this->hcolm));
        $this->fpdf->Text(193,140+$this->te-$this->py,($this->hycol3+$this->hcolmi));
        $this->fpdf->Text(212,140+$this->te-$this->py,($this->hycol4+$this->hcolj));
        $this->fpdf->Text(232,140+$this->te-$this->py,($this->hycol5+$this->hcolvi));
        $this->fpdf->Text(255,140+$this->te-$this->py,($this->hycol6+$this->hcols));
        $this->fpdf->Text(273,140+$this->te-$this->py,($hyts+$total));

        return $hyts;
    }
    public function carga_administrativa()
    {
        //**** ***********************************HORARIO ADMINISTRATIVO***********************************************
        $this->fpdf->SetFont('Helvetica','b','8');
        $this->fpdf->Text(5,145.5+$this->te-$this->py,
            mb_convert_encoding("III.-ACTIVIDADES EN LA ADMINISTRACIÓN:", 'ISO-8859-1', 'UTF-8'));
        //renglones actividades
        $this->fpdf->Rect(5,149-$this->py,283,6,'DF');
        $this->fpdf->Rect(150.4,149-$this->py,117.8,3,'DF');
        $rn=0;
        for($i=1;$i<=5;$i++){
            $this->fpdf->Rect(5,155+$rn-$this->py,283,3,'D');
            $rn=$rn+3;
        }
        $rn=0;
        for($i=1;$i<=6;$i++){
            $this->fpdf->Rect(150.4+$rn,152-$this->py,19.6,23,'D');
            $rn=$rn+19.6;
        }
        $this->fpdf->Rect(5,149-$this->py,78,21,'D');
        $this->fpdf->Rect(150.4,170-$this->py,137.6,2.5,'D');//rectangulo de subtotal apoyo
        $this->fpdf->Rect(150.4,172.5-$this->py,137.6,2.5,'D');//rectangulo de subtotal apoyo + academico
        $this->fpdf->SetFont('Helvetica','','7');
        $this->fpdf->Text(25,150+$this->te-$this->py,"PUESTO");
        $this->fpdf->Text(94,150+$this->te-$this->py,
            mb_convert_encoding("UNIDAD ORGÁNICA DE ADSCRIPCIÓN", 'ISO-8859-1', 'UTF-8'));
        $this->fpdf->Text(206,149+$this->te-$this->py,"HORARIO");
        $this->fpdf->Text(158,152+$this->te-$this->py,"L");
        $this->fpdf->Text(178,152+$this->te-$this->py,"M");
        $this->fpdf->Text(198,152+$this->te-$this->py,"M");
        $this->fpdf->Text(218,152+$this->te-$this->py,"J");
        $this->fpdf->Text(238,152+$this->te-$this->py,"V");
        $this->fpdf->Text(258,152+$this->te-$this->py,"S");
        $this->fpdf->SetFont('Helvetica','','5');
        $this->fpdf->Text(135,169+$this->te-$this->py,"SUBTOTAL");
        $this->fpdf->Text(135,172+$this->te-$this->py,"TOTAL");
        $this->fpdf->SetFont('Helvetica','','7');
        $this->fpdf->Text(270,149+$this->te-$this->py,"TOTAL HRS");
        $this->fpdf->Text(270,152+$this->te-$this->py,"SEMANALES");
    }
    public function mostrar_carga_administrativa($periodo,$docente,$total,$hyts)
    {
        //COMIENZA EL LLENADO DE HORARIO ADMINISTRATIVO
        $horasAdministrativas=Horario::where('periodo',$periodo)
            ->where('docente',$docente)
            ->where('tipo_horario','A')
            ->select('consecutivo_admvo')
            ->distinct()
            ->get();
        $linea=0;
        $hafs=0;
        $hats=0;
        foreach ($horasAdministrativas as $administrativa) {
            for ($i = 2; $i <= 7; $i++) {
                $horas = Horario::where('periodo', $periodo)
                    ->where('docente', $docente)
                    ->where('tipo_horario', 'A')
                    ->where('consecutivo_admvo', $administrativa->consecutivo_admvo)
                    ->where('dia_semana', $i)
                    ->select(['hora_inicial', 'hora_final'])
                    ->first();

                if (isset($horas)) {
                    $horario = date('H:i', strtotime($horas->hora_inicial)) . "-" . date('H:i',
                            strtotime($horas->hora_final));
                    $hora_salida = Carbon::parse($horas->hora_final);
                    $hora_entrada = Carbon::parse($horas->hora_inicial);
                    $hafr = $hora_entrada->diffInHours($hora_salida);
                    switch ($i) {
                        case 2:
                            $this->fpdf->Text(153, 155 + $linea + $this->te - $this->py, $horario);
                            $this->hacol1 += $hora_entrada->diffInHours($hora_salida);
                            break;
                        case 3:
                            $this->fpdf->Text(173, 155 + $linea + $this->te - $this->py, $horario);
                            $this->hacol2 += $hora_entrada->diffInHours($hora_salida);
                            break;
                        case 4:
                            $this->fpdf->Text(193, 155 + $linea + $this->te - $this->py, $horario);
                            $this->hacol3 += $hora_entrada->diffInHours($hora_salida);
                            break;
                        case 5:
                            $this->fpdf->Text(212, 155 + $linea + $this->te - $this->py, $horario);
                            $this->hacol4 += $hora_entrada->diffInHours($hora_salida);
                            break;
                        case 6:
                            $this->fpdf->Text(232, 155 + $linea + $this->te - $this->py, $horario);
                            $this->hacol5 += $hora_entrada->diffInHours($hora_salida);
                            break;
                        case 7:
                            $this->fpdf->Text(255, 155 + $linea + $this->te - $this->py, $horario);
                            $this->hacol6 += $hora_entrada->diffInHours($hora_salida);
                            break;
                    }
                    $hafs += $hafr;
                }
            }
            $hats += $hafs;
            $this->fpdf->Text(273,155+$linea+$this->te-$this->py,$hafs);
            $puesto=HorarioAdministrativo::where('periodo',$periodo)
                ->where('docente',$docente)
                ->where('consecutivo_admvo',$administrativa->consecutivo_admvo)
                ->join('puestos','puestos.clave_puesto','=','horarios_administrativos.descripcion_horario')
                ->select('puestos.descripcion_puesto')
                ->first();
            $this->fpdf->SetFont('Helvetica','','6.5');
            $this->fpdf->Text(5,155+$linea+$this->te-$this->py,$puesto->descripcion_puesto);
            //$pdf->Text(94,155+$linea+$te-$py,$descripcion_area);
            $linea+=3;
        }
        $this->fpdf->Text(153,169.5+$this->te-$this->py,$this->hacol1);
        $this->fpdf->Text(173,169.5+$this->te-$this->py,$this->hacol2);
        $this->fpdf->Text(193,169.5+$this->te-$this->py,$this->hacol3);
        $this->fpdf->Text(212,169.5+$this->te-$this->py,$this->hacol4);
        $this->fpdf->Text(232,169.5+$this->te-$this->py,$this->hacol5);
        $this->fpdf->Text(255,169.5+$this->te-$this->py,$this->hycol6);
        $this->fpdf->Text(273,169.5+$this->te-$this->py,$hats);
        //suma de horas de apoyo y académicas y administrativas
        $this->fpdf->Text(153,172+$this->te-$this->py,($this->hacol1+$this->hycol1+$this->hcol));
        $this->fpdf->Text(173,172+$this->te-$this->py,($this->hacol2+$this->hycol2+$this->hcolm));
        $this->fpdf->Text(193,172+$this->te-$this->py,($this->hacol3+$this->hycol3+$this->hcolmi));
        $this->fpdf->Text(212,172+$this->te-$this->py,($this->hacol4+$this->hycol4+$this->hcolj));
        $this->fpdf->Text(232,172+$this->te-$this->py,($this->hacol5+$this->hycol5+$this->hcolvi));
        $this->fpdf->Text(255,172+$this->te-$this->py,($this->hacol6+$this->hycol6+$this->hcols));
        $this->fpdf->Text(273,172+$this->te-$this->py,($hats+$hyts+$total));
        return $hats;
    }
    public function carga_no_docente($docente)
    {
        $this->fpdf->SetFont('Helvetica','b','8');
        $cadena = "PERSONAL NO DOCENTE:                            Primaria( )                                Secundaria( )                                Preparatoria( )                                Otro( ) Especificar
         _________________________________________";
        $nivel_docente=$this->nivel_docente($docente);
        $opcion=$nivel_docente[0]->nivel;
        $descrip_nivel=$nivel_docente[0]->nombre_corto;
        if (($opcion!='A')&&($opcion!='D')&&($opcion!='E')&&($opcion!='I')&&($opcion!='J')){
            $cadena = "PERSONAL NO DOCENTE:                            Primaria( )                                Secundaria( )                                Preparatoria( )                                Otro(X) Especificar ".$descrip_nivel;
        }
        else{
            //Primaria
            if ($opcion=='A'){
                $cadena = "PERSONAL NO DOCENTE:                            Primaria(X)                                Secundaria( )                                Preparatoria( )                                Otro( ) Especificar_____________________________________________";
            }
            //Secundaria
            if ($opcion=='D'){
                $cadena = "PERSONAL NO DOCENTE:                            Primaria( )                                Secundaria(X)                            Preparatoria( )                                Otro( ) Especificar_________________________________________";
            }

            //Bachillerato
            if ($opcion=='E'){
                $cadena = "PERSONAL NO DOCENTE:                            Primaria( )                                Secundaria( )                                Preparatoria(X)                                Otro( ) Especificar_________________________________________";
            }

        }
        $this->fpdf->Text(5,177+$this->te-$this->py,$cadena);
        $this->fpdf->Rect(5,181-$this->py,283,6,'DF');
        $this->fpdf->Rect(150.4,181-$this->py,117.8,3,'DF');
        $rn=0;
        For($i=1;$i<=5;$i++){
            $this->fpdf->Rect(5,187+$rn-$this->py,283,3,'D');
            $rn=$rn+3;
        }
        $rn=0;
        For($i=1;$i<=6;$i++){
            $this->fpdf->Rect(150.4+$rn,184-$this->py,19.6,23,'D');
            $rn=$rn+19.6;
        }
        $this->fpdf->Rect(5,181-$this->py,60,21,'D');
        $this->fpdf->Rect(150.4,202-$this->py,137.6,2.5,'D');//rectangulo de subtotal apoyo
        $this->fpdf->Rect(150.4,204.5-$this->py,137.6,2.5,'D');//rectangulo de subtotal apoyo + academico
        $this->fpdf->SetFont('Helvetica','','7');
        $this->fpdf->Text(25,182+$this->te-$this->py,"PUESTO");
        $this->fpdf->Text(90,182+$this->te-$this->py, mb_convert_encoding("UNIDAD ORGÁNICA DE ADSCRIPCIÓN", 'ISO-8859-1', 'UTF-8'));
        $this->fpdf->Text(206,181+$this->te-$this->py,"HORARIO");
        $this->fpdf->Text(158,184+$this->te-$this->py,"L");
        $this->fpdf->Text(178,184+$this->te-$this->py,"M");
        $this->fpdf->Text(198,184+$this->te-$this->py,"M");
        $this->fpdf->Text(218,184+$this->te-$this->py,"J");
        $this->fpdf->Text(238,184+$this->te-$this->py,"V");
        $this->fpdf->Text(258,184+$this->te-$this->py,"S");
        $this->fpdf->SetFont('Helvetica','','5');
        $this->fpdf->Text(135,201+$this->te-$this->py,"SUBTOTAL");
        $this->fpdf->Text(135,204+$this->te-$this->py,"TOTAL");
        $this->fpdf->SetFont('Helvetica','','7');
        $this->fpdf->Text(270,181+$this->te-$this->py,"TOTAL HRS");
        $this->fpdf->Text(270,184+$this->te-$this->py,"SEMANALES");
    }

    public function pie_pagina($periodo, $docente)
    {
        $this->fpdf->Text(20,210+$this->te-$this->py,"OBSERVACIONES");
        if(HorarioObservacion::where('periodo',$periodo)
        ->where('docente',$docente)->count()>0){
            $observacion=HorarioObservacion::where('periodo',$periodo)
                ->where('docente',$docente)
                ->select('observaciones')
                ->first();

            $this->fpdf->SetXY(46, 205.5);
            $this->fpdf->MultiCell(215, 3,
                mb_convert_encoding($observacion->observaciones, 'ISO-8859-1', 'UTF-8'),
                0,'J');
        }else{
            $this->fpdf->Line(46,210+$this->te-$this->py,130,210+$this->te-$this->py);
        }
        $this->fpdf
            ->Text(15,242+$this->te-$this->py,
                mb_convert_encoding("C.c.p.- Subdirección Académica", 'ISO-8859-1', 'UTF-8'));
        $this->fpdf
            ->Text(115,242+$this->te-$this->py,
                mb_convert_encoding("C.c.p.- Departamento de Planeación, Programación y Presupuestación",
                    'ISO-8859-1', 'UTF-8'));
        $this->fpdf->Text(220,242+$this->te-$this->py,"C.c.p.- Departamento de Recursos Humanos");
        $director=Jefe::where('clave_area','=','100000')
            ->select('id_jefe')->first();
        $nombre_director=Personal::where('id','=',$director->id_jefe)
            ->select(['apellidos_empleado','nombre_empleado','sexo_empleado'])
            ->first();
        $cargo=$nombre_director->sexo_empleado=="M"?"DIRECTOR":"DIRECTORA";
        $director_nombre=trim($nombre_director->nombre_empleado)." ".trim($nombre_director->apellidos_empleado);
        $datos_personal = Personal::where('id',$docente)
            ->select(['apellidos_empleado','nombre_empleado'])
            ->first();
        $nombre_personal=trim($datos_personal->nombre_empleado)." ".trim($datos_personal->apellidos_empleado);
        $this->fpdf->Text(35,234+$this->te-$this->py, mb_convert_encoding($nombre_personal, 'ISO-8859-1', 'UTF-8'));// 193,187
        $this->fpdf->Text(237,237+$this->te-$this->py,$cargo);
        $this->fpdf->Text(215,234+$this->te-$this->py, mb_convert_encoding($director_nombre, 'ISO-8859-1', 'UTF-8'));
        $this->fpdf->Line(15,230+$this->te-$this->py,111,230+$this->te-$this->py);//Linea Director
        $this->fpdf->Line(210,230+$this->te-$this->py,280,230+$this->te-$this->py);//Linea Personal
        $this->fpdf->Text(195,227+$this->te-$this->py,"Sello");
    }
    public function crearPDF(Request $request){
        $docente=$request->personal;
        $descripcion_area=$request->descripcion_area;
        $periodo=$request->periodo;
        $ruta_sep=$request->ruta_sep;
        $ruta_escudo=$request->ruta_escudo;
        $nperiodo=PeriodoEscolar::where('periodo',$periodo)
            ->select('identificacion_corta')
            ->first();
        $nombre_periodo=$nperiodo->identificacion_corta;
        $this->fpdf= new Fpdf('L','mm',array(297,250));
        $this->fpdf->SetTopMargin(2);
        $this->fpdf->AddPage();
        $this->encabezado($ruta_sep,$ruta_escudo);
        $nombramiento=Personal::where('id',$docente)->select('nombramiento')->first();
        //$this->fpdf->Rect(5,40-$this->py,$xt-4,4,'D');
        $this->caratula($descripcion_area,$nombre_periodo,$docente);
        //Carga académica
        $this->carga_academica();
        if(in_array($nombramiento->nombramiento,array("D","X"))){
            $total=$this->mostrar_carga_academica($periodo,$docente);
        }else{
            $total=0;
        }
        //Carga de apoyo a la docencia
        $this->carga_apoyo_docencia();
        if(Horario::where('periodo',$periodo)
                ->where('docente',$docente)
                ->where('tipo_horario','Y')
                ->count()>0){
            $hyts=$this->mostrar_carga_apoyo_docencia($periodo,$docente,$total);
        }else{
            $hyts=0;
        }
        //Carga administrativa
        $this->carga_administrativa();
        if(Horario::where('periodo',$periodo)
                ->where('docente',$docente)
                ->where('tipo_horario','A')
                ->count()>0){
            $hats=$this->mostrar_carga_administrativa($periodo,$docente,$total,$hyts);
        }else{
            $hats=0;
        }
        //No docente
        $this->carga_no_docente($docente);

        //Final del horario
        $this->pie_pagina($periodo, $docente);

        $mov_mayor=0;
        $hncol1=0;
        $hncol2=0;
        $hncol3=0;
        $hncol4=0;
        $hncol5=0;
        $hncol6=0;
        $hafs_nodoc=0;




        //Salida
        $this->fpdf->Output();
    }
}
