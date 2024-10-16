<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Acciones\AccionesController;
use App\Http\Controllers\Controller;
use App\Models\Carrera;
use App\Models\Categoria;
use App\Models\Grupo;
use App\Models\Horario;
use App\Models\Motivo;
use App\Models\PeriodoEscolar;
use App\Models\Personal;
use App\Models\PersonalPlaza;
use App\Models\SeleccionMateria;
use Illuminate\Http\Request;
use Codedge\Fpdf\Fpdf\Fpdf;

class HorarioPDFController extends Controller
{
    private $fpdf;

    public function __construct(){

    }

    public function encabezado()
    {
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->Image('/var/www/html/sii/public/img/educacion.jpg',8,13,55);
        $this->fpdf->Image('/var/www/html/sii/public/img/escudo.jpg',260,11,18);
        $x = 62;
        $y = 12;
        $w = 175;
        $h = 4;

        $this->fpdf->SetXY($x+20, $y+5);
        $this->fpdf->SetFont('Arial','b','14');
        $this->fpdf->Cell($w, $h, mb_convert_encoding("Tecnológico Nacional de México", 'ISO-8859-1', 'UTF-8'), 0, 2);
        $this->fpdf->SetFont('Arial','b','11');
        $xt=$this->fpdf->Cell($w, $h, mb_convert_encoding("INSTITUTO TECNOLÓGICO DE ENSENADA", 'ISO-8859-1', 'UTF-8'), 0, 2);

        $x = 5;
        $y = 30;
        $this->fpdf->SetXY($x, $y);
        $this->fpdf->SetFont('Arial','b','5');
        //$pdf->Cell($w, $h, "SUBSECRETARIA DE EDUCACION SUPERIOR", 0, 2);

        $this->fpdf->SetFillColor(0,0,128);
        $this->fpdf->Rect(5, 35, 283, 1, 'F');
        return $xt;
    }

    public function nivel_docente($id){
        return (new AccionesController)->nivel_academico_docente($id);
    }

    public function crearPDF(Request $request){
        $docente=$request->personal;
        $descripcion_area=$request->descripcion_area;
        $datos_personal = Personal::where('id',$docente)->first();
        $periodo=$request->periodo;
        $nperiodo=PeriodoEscolar::where('periodo',$periodo)
            ->select('identificacion_corta')
            ->first();
        $nombre_periodo=$nperiodo->identificacion_corta;
        $nivel_docente =$this->nivel_docente($docente);
        $this->fpdf= new Fpdf('L','mm',array(297,250));
        $this->fpdf->SetTopMargin(2);
        $this->fpdf->AddPage();
        $xt=$this->encabezado();
        $py=5;
        //$this->fpdf->Rect(5,40-$py,$xt-4,4,'D');
        $this->fpdf->Text($xt+76,43-$py,"C.C.T.:");
        $this->fpdf->Rect($xt+75,40-$py,15,4,'D');
        $this->fpdf->Text($xt+111,43-$py,"PERIODO ESCOLAR");
        $this->fpdf->Rect($xt+110,40-$py,35,4,'D');
        $this->fpdf->Text($xt+170,43-$py,"RFC");
        $this->fpdf->Rect($xt+169,40-$py,12,4,'D');
        $this->fpdf->SetFont('Helvetica','b','8');
        $this->fpdf->SetLineWidth(.55);
        $this->fpdf->Rect(5,44-$py,283,21,'D');
        $this->fpdf->SetLineWidth(.4);
        $te=2.7;
        $this->fpdf->Rect(5,44-$py,165,21,'D');
        $this->fpdf->Rect(5,44-$py,165,3,'D'); //<-
        $this->fpdf->Text(7,44+$te-$py,"NOMBRE COMPLETO:");
        $this->fpdf->Rect(5,47-$py,165,3,'D');
        $this->fpdf->Rect(5,50-$py,165,3,'D');
        $this->fpdf->Rect(5,53-$py,165,3,'D');
        $this->fpdf->Rect(5,59-$py,165,3,'D');
        $this->fpdf->Rect(5,62-$py,165,3,'D');
        $this->fpdf->Rect(170,44-$py,118,9,'D');
        $this->fpdf->Rect(170,53-$py,118,6,'D');
        $this->fpdf->Rect(170,53-$py,59,12,'D');
        $this->fpdf->Rect(139,47-$py,15,12,'D');//cuadro pasante o titulado
        $this->fpdf->Rect(154,47-$py,16,12,'D');//cuadro pasante o titulado
        $this->fpdf->SetLineWidth(.35);
        $this->fpdf->Rect($xt+170,47-$py,59.3,3,'D');//cuadros de plazas
        $this->fpdf->Rect($xt+170,50-$py,59.3,3,'D');//cuadros de plazas
        $this->fpdf->Rect($xt+229.3,47-$py,59.3,3,'D');//cuadros de plazas
        //$this->fpdf->Rect($xt+106,47-$py,59.5,3,'D');//cuadros de plazas

        $this->fpdf->SetLineWidth(.4);
        $this->fpdf->SetFont('Helvetica','b','8');
        $this->fpdf->Text(172,44+$te-$py,"CLAVE COMPLETA DE LA(S) PLAZA(S):");
        $this->fpdf->Text(172,59+$te-$py,"NO. DE TARJETA DE CONTROL:");
        $this->fpdf->Text(172,53+$te-$py,"CURP");
        $this->fpdf->Text(232,53+$te-$py,"FECHA DE INGRESO A LA S.E.P.:");
        $this->fpdf->Text(232,59+$te-$py,"FECHA DE INGRESO A LA INSTITUCION:");
        $this->fpdf->Text(140,47+$te-$py,"HORAS");
        $this->fpdf->Text(155,47+$te-$py,"TITULADO");
        $this->fpdf->Text(7,47+$te-$py,"ESCOLARIDAD DEL PERSONAL");
        $this->fpdf->Text(7,50+$te-$py,"LICENCIATURA EN:");
        $this->fpdf->Text(7,53+$te-$py,"ESPECIALIZACION EN:");
        $this->fpdf->Text(7,56+$te-$py,"MAESTRIA EN:");
        $this->fpdf->Text(7,59+$te-$py,"DOCTORADO EN:");
        $this->fpdf->Text(7,62+$te-$py, mb_convert_encoding("UNIDAD ORGÁNICA DE ADSCRIPCIÓN: " . $descripcion_area, 'ISO-8859-1', 'UTF-8'));
        $this->fpdf->Text($xt+185,43.5-$py,strtoupper($datos_personal->rfc));// agrega el rfc
        $this->fpdf->Text($xt+147,43.5-$py,$nombre_periodo);
        $this->fpdf->Text($xt+92,43.5-$py,"02DIT0023K");
        $hrs=PersonalPlaza::where('id_personal',$docente)
            ->where('estatus_plaza','A')
            ->sum('horas');
        $this->fpdf->Text(144,50+$te-$py,$hrs);
        if ($nivel_docente[0]->nivel=='I') {
            $this->fpdf->Text(43,50+$te-$py,strtoupper($nivel_docente[0]->nombre_corto));
            $gr=50;
        }elseif ($nivel_docente[0]->nivel=='J') {
            $this->fpdf->Text(43,56+$te-$py,strtoupper($nivel_docente[0]->nombre_corto));
            $gr=56;
        }elseif ($nivel_docente[0]->nivel=='K') {
            $this->fpdf->Text(43,59+$te-$py,strtoupper($nivel_docente[0]->nombre_corto));
            $gr=59;
        }elseif ($nivel_docente[0]->nivel=='H') {
            $gr=50;
        }else{
            $gr=0;
        }
        if($gr!=0){
            $this->fpdf->Text(159,$gr+$te-$py,"X");
        }else{
            $this->fpdf->Text(145,$gr+$te-$py,"X");
        }
        //nombre
        $this->fpdf->Text(38,44+$te-$py,trim($datos_personal->apellidos_empleado)." ".trim($datos_personal->nombre_empleado));
        //curp
        $this->fpdf->Text(172,56+$te-$py,$datos_personal->curp_empleado);
        //tarjeta
        $this->fpdf->Text(172,62+$te-$py,$datos_personal->no_tarjeta);
        //INICIO PLANTEL Y SEP
        $this->fpdf->Text(232,56+$te-$py,$datos_personal->ingreso_rama);//tec
        $this->fpdf->Text(232,62+$te-$py,$datos_personal->inicio_sep);
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
            $this->fpdf->Text($xplaz,$yplaz+$te-$py,$plaza_asignada);
            $contp+=1;
            $yplaz+=3;
            if ($contp==3){
                $contp=1;
                $xplaz=232;
                $yplaz=47;
            }
        }
        //Carga académica
        $mov_mayor=0;
        $hcolm=0;
        $hcol=0;
        $hcolmi=0;
        $hcolj=0;
        $hcolvi=0;
        $hcols=0;
        $hycol1=0;
        $hycol2=0;
        $hycol3=0;
        $hycol4=0;
        $hycol5=0;
        $hycol6=0;
        $hacol1=0;
        $hacol2=0;
        $hacol3=0;
        $hacol4=0;
        $hacol5=0;
        $hacol6=0;
        $hncol1=0;
        $hncol2=0;
        $hncol3=0;
        $hncol4=0;
        $hncol5=0;
        $hncol6=0;
        $hafs_nodoc=0;
        $hats=0;
        $hyts=0;
        $hyfs=0;
        $dias=array("lunes","martes","miercoles","jueves","viernes","sabado","domingo");
        foreach($dias as $value){
            $txt="min_".$value;
            $$txt=0;
        }
        $this->fpdf->SetFont('Helvetica','b','8');
        $this->fpdf->Text(5,65.5+$te-$py, mb_convert_encoding("I.-CARGA ACADÉMICA:", 'ISO-8859-1','UTF-8',));
        $this->fpdf->SetFillColor(192,192,192);
        $this->fpdf->Rect(5,69-$py,283,6,'DF');
        $this->fpdf->Rect(150.4,69-$py,117.8,3,'DF');
        // crea columnas para parte académica
        $rn=0;
        for($i=1;$i<=6;$i++){
            $this->fpdf->Rect(150.4+$rn,72-$py,19.6,35,'D');
            $rn=$rn+19.6;
        }
        //crea renglones para parte de carga académica
        $rn=0;
        for($i=1;$i<=8;$i++){
            $this->fpdf->Rect(5,75+$rn-$py,283,4,'D');
            $rn=$rn+4;
        }
        $this->fpdf->SetFont('Helvetica','','7');
        $this->fpdf->Text(206,69+$te-$py,"HORARIO");
        $this->fpdf->Text(158,72+$te-$py,"L");
        $this->fpdf->Text(178,72+$te-$py,"M");
        $this->fpdf->Text(198,72+$te-$py,"M");
        $this->fpdf->Text(218,72+$te-$py,"J");
        $this->fpdf->Text(238,72+$te-$py,"V");
        $this->fpdf->Text(258,72+$te-$py,"S");
        $this->fpdf->SetFont('Helvetica','','7');
        $this->fpdf->Rect(5,69-$py,52,34,'D'); //rect de asignatura
        $this->fpdf->Text(25,70+$te-$py,"ASIGNATURA");
        $this->fpdf->Rect(57,69-$py,16,34,'D');// rect de grupo
        $this->fpdf->Text(60,70+$te-$py,"GRUPO");
        $this->fpdf->Rect(73,69-$py,16,34,'D');// rect de alumnos
        $this->fpdf->Text(75,70+$te-$py,"ALUMNOS");
        $this->fpdf->Rect(89,69-$py,12,34,'D');// rect de  aula
        $this->fpdf->Text(92,70+$te-$py,"AULA");
        $this->fpdf->Rect(101,69-$py,16,34,'D');// rect de  nivel
        $this->fpdf->Text(105,70+$te-$py,"NIVEL");
        $this->fpdf->SetFont('Helvetica','','6.5');
        $this->fpdf->Rect(117,69-$py,17,34,'D');// rect de  modalidad
        $this->fpdf->Text(119,70+$te-$py,"MODALIDAD");
        $this->fpdf->Text(135,70+$te-$py,"CARRERA(S)");
        $this->fpdf->Text(270,69+$te-$py,"TOTAL HRS");
        $this->fpdf->Text(270,72+$te-$py,"SEMANALES");
        $this->fpdf->SetFont('Helvetica','','8');
        $this->fpdf->Text(7,103+$te-$py, mb_convert_encoding("PREPARACIÓN, CONTROL Y EVALUACIÓN DE MATERIAS QUE IMPARTE", 'ISO-8859-1', 'UTF-8'));
        $this->fpdf->Text(135,103+$te-$py,"SUBTOTAL");
        // AQUI COMIENZA EL LLENADO DE HORARIO ACADEMICO
        $linea=0;
        $x_mat=152;
        $y_mat=72;
        $hts=0;
        $grupos=Grupo::where('periodo',$periodo)
            ->where('docente',$datos_personal->id)
            ->whereNull('paralelo_de')
            ->join('materias','materias.materia','=','grupos.materia')
            ->select(['materias.nombre_abreviado_materia','grupos.materia','grupos.grupo',
                'grupos.tipo_personal','materias.nivel_escolar','grupos.carrera','grupos.reticula'])
            ->get();
        foreach ($grupos as $grupo) {
            if($grupo->tipo_personal=='B'){
                $hts=0;
                $this->fpdf->SetFont('Helvetica','','6');
                if(strncasecmp($grupo->nombre_abreviado_materia,"RESID",5)!=0){
                    $this->fpdf->Text(7,75+$linea+$te-$py, mb_convert_encoding($grupo->materia . "|" . $grupo->nombre_abreviado_materia, 'ISO-8859-1', 'UTF-8'));
                    $this->fpdf->SetFont('Helvetica','','8');
                    $this->fpdf->Text(60,75+$linea+$te-$py,$grupo->grupo);
                    $total_alumnos=SeleccionMateria::where('grupo',$grupo->grupo)
                        ->where('periodo',$periodo)
                        ->where('materia',$grupo->materia)
                        ->count();
                    $this->fpdf->Text(78,75+$linea+$te-$py,$total_alumnos);
                    $aulas=Horario::where('grupo',$grupo->grupo)
                        ->where('periodo',$periodo)
                        ->where('materia',$grupo->materia)
                        ->select('aula')
                        ->first();
                    $this->fpdf->Text(92,75+$linea+$te-$py,$aulas->aula);
                    $letra=$grupo->nivel_escolar=='L'? "L":"P";
                    $this->fpdf->Text(108,75+$linea+$te-$py,$letra);
                    $datos_carrera=Carrera::where('carrera',$grupo->carrera)
                        ->where('reticula',$grupo->reticula)
                        ->select(['siglas','modalidad'])
                        ->first();
                    $this->fpdf->Text(124,75+$linea+$te-$py,$datos_carrera->modalidad);//Distancia
                    $this->fpdf->Text(136,75+$linea+$te-$py,$datos_carrera->siglas);
                    $linea+=4;
                    for($i=2;$i<=7;$i++){
                        if(Horario::where('grupo',$grupo->grupo)
                            ->where('periodo',$periodo)
                            ->where('materia',$grupo->materia)
                            ->where('docente',$docente)
                            ->where('tipo_horario','D')
                            ->where('dia_semana',$i)
                            ->count()>0){
                            $horas=Horario::where('grupo',$grupo->grupo)
                                ->where('periodo',$periodo)
                                ->where('materia',$grupo->materia)
                                ->where('tipo_horario','D')
                                ->where('docente',$docente)
                                ->where('dia_semana',$i)
                                ->select(['hora_inicial','hora_final'])
                                ->first();
                            $this->fpdf->SetFont('Helvetica','','6');
                            $horario=date('H:i',strtotime($horas->hora_inicial))."-".date('H:i',strtotime($horas->hora_final));
                            switch ($i){
                                case 2: $this->fpdf->Text($x_mat,$y_mat+1,$horario); break;
                                case 3: $this->fpdf->Text($x_mat+20,$y_mat+1,$horario); break;
                                case 4: $this->fpdf->Text($x_mat+40,$y_mat+1,$horario); break;
                                case 5: $this->fpdf->Text($x_mat+60,$y_mat+1,$horario); break;
                                case 6: $this->fpdf->Text($x_mat+80,$y_mat+1,$horario); break;
                                case 7: $this->fpdf->Text($x_mat+100,$y_mat+1,$horario); break;
                            }
                        }
                    }
                }
            }else{
                $carre="";
                $modalidad="";
            }
        }
        //Salida
        $this->fpdf->Output();
    }
}
