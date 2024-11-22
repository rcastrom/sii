<?php

namespace App\Http\Controllers\Academicos;

use App\Http\Controllers\Acciones\AccionesController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MenuAcademicosController;
use App\Models\Carrera;
use App\Models\EvaluacionAlumno;
use App\Models\EvaluacionAspecto;
use App\Models\Grupo;
use App\Models\Materia;
use App\Models\PeriodoEscolar;
use App\Models\Pregunta;
use App\Models\SeleccionMateria;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Http\Request;
use Amenadiel\JpGraph\Graph;
use Amenadiel\JpGraph\Plot;

class EvaluacionDocenteCarreraController extends Controller
{
    public function __construct(Dispatcher $events)
    {
        new MenuAcademicosController($events);
    }
    public function resultados_evaluacion(Request $request){
        $encabezado="Resultados evaluacion al docente por carrera";
        $periodo=$request->get('periodo');
        $nperiodo=PeriodoEscolar::where('periodo',$periodo)
            ->select('identificacion_corta')
            ->first();
        $datos_carrera=$request->get('datos_carrera');
        $datos=explode("_",$datos_carrera);
        $carrera=$datos[0];
        $reticula=$datos[1];
        $nombre_carrera=Carrera::where('carrera',$carrera)
            ->where('reticula',$reticula)
            ->select('nombre_reducido')
            ->first();
        $materias_activas=Materia::select('materias.clave_area')
            ->join('grupos','grupos.materia','=','materias.materia')
            ->join('materias_carreras','materias_carreras.materia','=','materias.materia')
            ->where('grupos.periodo',$periodo)
            ->where('materias_carreras.carrera',$carrera)
            ->where('materias_carreras.reticula',$reticula)
            ->distinct()
            ->count('materias.clave_area');
        $materias_evaluadas=Materia::select('materias.clave_area')
            ->join('evaluacion_alumnos','evaluacion_alumnos.materia','=','materias.materia')
            ->join('materias_carreras','materias_carreras.materia','=','materias.materia')
            ->where('evaluacion_alumnos.periodo',$periodo)
            ->where('evaluacion_alumnos.encuesta','=','A')
            ->where('materias_carreras.carrera',$carrera)
            ->where('materias_carreras.reticula',$reticula)
            ->where('evaluacion_alumnos.consecutivo','=',2)
            ->whereRaw('char_length(evaluacion_alumnos.respuestas)=?',[48,])
            ->distinct()
            ->count('materias.clave_area');
        $docentes_activos=Grupo::select('grupos.docente')
            ->where('grupos.periodo',$periodo)
            ->join('materias','materias.materia','=','grupos.materia')
            ->join('materias_carreras','materias_carreras.materia','=','materias.materia')
            ->where('materias_carreras.carrera',$carrera)
            ->where('materias_carreras.reticula',$reticula)
            ->distinct()
            ->count('grupos.docente');
        $docentes_evaluados=EvaluacionAlumno::select('evaluacion_alumnos.personal')
            ->where('evaluacion_alumnos.periodo',$periodo)
            ->where('evaluacion_alumnos.encuesta','=','A')
            ->where('evaluacion_alumnos.consecutivo','=',2)
            ->whereRaw('char_length(evaluacion_alumnos.respuestas)=?',[48,])
            ->join('materias','materias.materia','=','evaluacion_alumnos.materia')
            ->join('materias_carreras','materias_carreras.materia','=','materias.materia')
            ->where('materias_carreras.carrera',$carrera)
            ->where('materias_carreras.reticula',$reticula)
            ->distinct()
            ->count('evaluacion_alumnos.personal');
        $alumnos_activos=SeleccionMateria::select('seleccion_materias.no_de_control')
            ->where('seleccion_materias.periodo',$periodo)
            ->join('materias','materias.materia','=','seleccion_materias.materia')
            ->join('materias_carreras','materias_carreras.materia','=','materias.materia')
            ->where('materias_carreras.carrera',$carrera)
            ->where('materias_carreras.reticula',$reticula)
            ->distinct()
            ->count('seleccion_materias.no_de_control');
        $alumnos_evaluados=EvaluacionAlumno::select('evaluacion_alumnos.no_de_control')
            ->where('evaluacion_alumnos.periodo',$periodo)
            ->where('evaluacion_alumnos.encuesta','=','A')
            ->where('evaluacion_alumnos.consecutivo','=',2)
            ->whereRaw('char_length(evaluacion_alumnos.respuestas)=?',[48,])
            ->join('materias','materias.materia','=','evaluacion_alumnos.materia')
            ->join('materias_carreras','materias_carreras.materia','=','materias.materia')
            ->where('materias_carreras.carrera',$carrera)
            ->where('materias_carreras.reticula',$reticula)
            ->distinct()
            ->count('evaluacion_alumnos.no_de_control');
        $resultados=$this->resultados_evaluacion_docente_carrera($periodo,$carrera,$reticula);
        $valores=[];
        $i=0;
        $suma=0;
        foreach ($resultados as $key=>$value){
            $valores[$i]=$value["porcentaje"];
            $suma+=$value["porcentaje"];
            $i++;
        }
        $promedio=round($suma/$i,2);

        switch ($promedio){
            case ($promedio>=1&&$promedio<=3.24): $cal="INSUFICIENTE"; break;
            case ($promedio>=3.25&&$promedio<=3.74): $cal="SUFICIENTE"; break;
            case ($promedio>=3.75&&$promedio<=4.24): $cal="BUENO"; break;
            case ($promedio>=4.25&&$promedio<=4.74): $cal="NOTABLE"; break;
            case ($promedio>=4.75&&$promedio<=5): $cal="EXCELENTE"; break;
            default : $cal="Otros"; break;
        }
        return view('academicos.evaldocente_carrera')
            ->with(compact('encabezado','nperiodo',
                'materias_activas','materias_evaluadas','docentes_activos','docentes_evaluados',
                'carrera','reticula','alumnos_activos','alumnos_evaluados','periodo','nombre_carrera',
                'resultados','promedio','cal'));
    }

    public function resultados_evaluacion_docente_carrera($periodo,$carrera,$reticula)
    {
        $resultados = array();
        $i = 0; //Este es el contador inicial para el registro de información
        $consecutivo = EvaluacionAlumno::where('encuesta', 'A')
            ->max('consecutivo');
        $aspectos = EvaluacionAspecto::where('encuesta', 'A')
            ->where('consecutivo', $consecutivo)
            ->orderBy('aspecto')
            ->get();
        $valor_resp = [];
        $valor_resp[0] = 0;
        $valor_resp[1] = 0;
        $valor_resp[2] = 0;
        $valor_resp[3] = 0;
        $valor_resp[4] = 0;
        $valor_resp[5] = 0;
        $suma = 0;
        $num_res = 0;
        foreach ($aspectos as $aspecto) {
            $preguntas = Pregunta::where('encuesta', 'A')
                ->where('consecutivo', $consecutivo)
                ->where('aspecto', $aspecto->aspecto)
                ->select('no_pregunta')
                ->get();
            foreach ($preguntas as $pregunta) {
                $obtenido = (new AccionesController)->resultados_carrera_evaluacion_al_docente($periodo, $pregunta->no_pregunta, $carrera, $reticula);
                switch ($obtenido[0]->respuesta) {
                    case '1':
                    case 'A':
                        $valor_resp[0] += $obtenido[0]->cantidad;
                        break;
                    case '2':
                    case 'B':
                        $valor_resp[1] += $obtenido[0]->cantidad;
                        break;
                    case '3':
                    case 'C':
                        $valor_resp[2] += $obtenido[0]->cantidad;
                        break;
                    case '4':
                    case 'D':
                        $valor_resp[3] += $obtenido[0]->cantidad;
                        break;
                    case '5':
                    case 'E':
                        $valor_resp[4] += $obtenido[0]->cantidad;
                        break;
                    default:
                        $valor_resp[5] += $obtenido[0]->cantidad;
                        break;
                }
            }
            for ($a = 0; $a < 5; $a++) {
                $suma += $valor_resp[$a] * ($a + 1);
                $num_res += $valor_resp[$a];
            }
            $porcentaje = round($suma / $num_res, 2);
            switch ($porcentaje) {
                case ($porcentaje >= 1 && $porcentaje <= 3.24):
                    $cal = "INSUFICIENTE";
                    break;
                case ($porcentaje >= 3.25 && $porcentaje <= 3.74):
                    $cal = "SUFICIENTE";
                    break;
                case ($porcentaje >= 3.75 && $porcentaje <= 4.24):
                    $cal = "BUENO";
                    break;
                case ($porcentaje >= 4.25 && $porcentaje <= 4.74):
                    $cal = "NOTABLE";
                    break;
                case ($porcentaje >= 4.75 && $porcentaje <= 5):
                    $cal = "EXCELENTE";
                    break;
                default:
                    $cal = "S/D";
                    break;
            }
            $resultados[$i]["aspecto"] = $aspecto->aspecto;
            $resultados[$i]["descripcion"] = $aspecto->descripcion;
            $resultados[$i]["porcentaje"] = $porcentaje;
            $resultados[$i]["calificacion"] = $cal;
            $i++;
        }
        return $resultados;
    }
    public function grafica_evaluacion_carrera($periodo,$carrera,$reticula,$promedio)
    {
        // Create the graph and setup the basic parameters
        $__width  = 460;
        $__height = 200;
        $resultados=$this->resultados_evaluacion_docente_carrera($periodo,$carrera,$reticula);
        $valores=[];
        $i=0;
        foreach ($resultados as $key=>$value){
            $valores[$i]=$value["porcentaje"];
            $i++;
        }
        $valores[] = $promedio;
        $graph = new Graph\Graph($__width, $__height, 'auto');
        $graph->img->SetMargin(40, 30, 30, 40);
        $graph->SetScale('textint',1,5);
        $graph->SetShadow();
        $graph->SetFrame(false); // No border around the graph
        // Add some grace to the top so that the scale doesn't
        // end exactly at the max value.
        $graph->yaxis->scale->SetGrace(7);
        // Setup X-axis labels
        $a = ["A","B","C","D","E","F","G","H","I","J","Prom"];
        $graph->xaxis->SetTickLabels($a);
        $graph->xaxis->SetFont(FF_FONT2);
        // Setup graph title ands fonts
        $graph->title->Set(mb_convert_encoding('Evaluación Docente por carrera', 'ISO-8859-1', 'UTF-8'));
        $graph->title->SetFont(FF_FONT2, FS_BOLD);
        // Create a bar pot
        $bplot = new Plot\BarPlot($valores);
        $bplot->SetFillColor('orange');
        foreach ($valores as $key=>$value){
            if($key<=9){
                switch ($value){
                    case ($value>=1&&$value<=3.24): $barcolors[]="#FF0000"; break;
                    case ($value>=3.25&&$value<=3.74): $barcolors[]="#FFCC00"; break;
                    case ($value>=3.75&&$value<=4.24): $barcolors[]="#FF6321"; break;
                    case ($value>=4.25&&$value<=4.74): $barcolors[]="#FF42FF"; break;
                    case ($value>=4.75&&$value<=5): $barcolors[]="#0042FF"; break;
                }
            }else{
                $barcolors[]="green";
            }
        }
        $bplot->SetFillColor($barcolors);
        $bplot->SetValuePos('center');
        $bplot->SetWidth(0.55);
        $bplot->SetShadow();
        $bplot->value->format='%1.2f';
        // Setup the values that are displayed on top of each bar
        $bplot->value->Show();
        // Must use TTF fonts if we want text at an arbitrary angle
        $bplot->value->SetFont(FF_COURIER, FS_NORMAL,7);
        $bplot->value->SetAngle(90);
        $bplot->value->valign='center';
        // Black color for positive values and darkred for negative values
        $bplot->value->SetColor('white', 'darkred');
        $graph->Add($bplot);
        $graph->Stroke();
    }
}
