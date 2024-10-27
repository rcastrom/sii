<?php

namespace App\Http\Controllers\Alumnos;

use App\Http\Controllers\Acciones\AccionesController;
use App\Http\Controllers\Controller;
use App\Models\Alumno;
use App\Models\AvisoReinscripcion;
use App\Models\Grupo;
use App\Models\HistoriaAlumno;
use App\Models\Horario;
use App\Models\Materia;
use App\Models\MateriaCarrera;
use App\Models\SeleccionMateria;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Contracts\Events\Dispatcher;
use App\Http\Controllers\MenuAlumnosController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReinscripcionController extends Controller
{
    public function __construct(Dispatcher $events)
    {
        new MenuAlumnosController($events);
    }
    public function control(){
        $data=Auth::user()->email;
        $alumno=Alumno::where('correo_electronico',$data)->first();
        return $alumno->no_de_control;
    }
    public function index(){
        $control=$this->control();
        $periodo_actual=(new AccionesController)->periodo();
        $periodo=$periodo_actual[0]->periodo;
        $en_fecha=(new AccionesController)->en_fecha($periodo);
        $encabezado="Error en selección de materias";
        if($en_fecha){
            // Que aparezca en el listado de reinscripciones
            if(AvisoReinscripcion::where([
                'periodo'=>$periodo,
                'no_de_control' => $control,
            ])->count()>0){
                //Que tenga el pago registrado
                if(AvisoReinscripcion::where([
                        'periodo'=>$periodo,
                        'no_de_control' => $control,
                        'autoriza_escolar' => 'S'
                    ])->count()>0){
                    // Si es su hora de reinscripción
                    $en_tiempo=(new AccionesController)->en_tiempo_reinscripcion($periodo,$control);
                    if($en_tiempo){
                        $alumno=Alumno::findOrFail($control);
                        $carga=SeleccionMateria::where('periodo',$periodo)
                            ->join('alumnos','alumnos.no_de_control','=','seleccion_materias.no_de_control')
                            ->where('alumnos.no_de_control',$control)
                            ->join('materias','seleccion_materias.materia','=','materias.materia')
                            ->join('materias_carreras as mc','seleccion_materias.materia','=','mc.materia')
                            ->join('alumnos as al1','al1.carrera','=','mc.carrera')
                            ->join('alumnos as al2','al2.reticula','=','mc.reticula')
                            ->selectRaw('distinct(seleccion_materias.materia),grupo,nombre_abreviado_materia,creditos_materia,repeticion,global')
                            ->get();
                        $historial=(new AccionesController)->reticula($control);
                        $encabezado="Selección de materias";
                        return view('alumnos.reinscripcion')
                            ->with(compact('alumno','historial','carga','encabezado'));
                    }else{
                        $cuando=AvisoReinscripcion::where([
                            'periodo'=>$periodo,
                            'no_de_control' => $control,
                        ])->select('fecha_hora_seleccion')
                            ->first();
                        if(empty($cuando)){
                            $mensaje="NO estás en tiempo para seleccionar tus materias";
                        }else{
                            $mensaje="NO estás en tiempo para seleccionar tus materias, te corresponde a ".$cuando->fecha_hora_seleccion;
                        }
                        return view('alumnos.no')->with(compact('mensaje'));
                    }
                }else{
                    $mensaje="El pago aún no se encuentra registrado";
                    return view('alumnos.no')->with(compact('mensaje'));
                }
            }else{
                $mensaje="Requiere autorización de Servicios Escolares para continuar";
                return view('alumnos.no')->with(compact('mensaje','encabezado'));
            }
        }else{
            $mensaje="El período de reinscripción o no ha iniciado o no ha terminado";
            return view('alumnos.no')->with(compact('mensaje','encabezado'));
        }
    }
    public function adeuda($control)
    {
        return HistoriaAlumno::where('no_de_control',$control)
            ->where('calificacion','=',0)
            ->groupBy('id','materia')->get()->count();
    }
    public function seleccion_materia($materia, $tipocur)
    {
        $control=$this->control();
        $periodo_actual=(new AccionesController)->periodo();
        $periodo=$periodo_actual[0]->periodo;
        $alumno=Alumno::where('no_de_control',$control)->first();
        $encabezado="Error en la selección de la materia";
        $adeuda=$this->adeuda($control);
        if($adeuda){
            $adeudos=HistoriaAlumno::where('no_de_control',$control)
                ->where('calificacion','=',0)
                ->groupBy('materia')
                ->selectRaw('count(materia) as veces')
                ->get();
            foreach ($adeudos as $adeudo) {
                if($adeudo->veces>1){
                    $bandera_espe=1;
                }else{
                    $bandera_espe=0;
                }
                $bandera_rep=1;
            }
        }else{
            $bandera_espe=0;  // Suponer que no adeuda especiales
            $bandera_rep=0;   // Suponer que no adeuda la materia
        }
        // Que la materia se encuentre en su plan de estudios
        if(MateriaCarrera::where('materia',$materia)
                ->join('alumnos as a1','a1.carrera','=','materias_carreras.carrera')
                ->join('alumnos as a2','a2.reticula','=','materias_carreras.reticula')
                ->where('a1.no_de_control',$control)->count()>0){
            // Que la materia se esté ofertando
            if(Grupo::where(['periodo' => $periodo,'materia' => $materia])->count()>0){
                if($bandera_espe){
                    $esp_adeud=HistoriaAlumno::select('materia')
                        ->where('no_de_control',$control)
                        ->whereIn('tipo_evaluacion',['RO','RP','R1','R2'])
                        ->where('calificacion','<',70)
                        ->whereNotIn('materia',HistoriaAlumno::where('no_de_control',$control)
                            ->where('tipo_evaluacion','CE')
                            ->where('calificacion','>=',70)
                            ->select('materia')
                        )->get()
                        ->toArray();
                    if(!empty($esp_adeud)){
                        if(array_search($materia,array_column($esp_adeud,'materia'))){
                            $estatus="S";
                        }else{
                            $estatus="N";
                        }
                        $especial="S";
                    }else{
                        $estatus="N";
                        $especial="N";
                    }
                    $info_adicional=(new AccionesController)->verifica_especial($control,$periodo);
                    if($info_adicional[0]->adeudo>=2){
                        $mensaje="Tienes adeudo de 2 o mas especiales. No procede selección de esta materia";
                        return view('alumnos.no')->with(compact('mensaje','encabezado'));
                    }elseif($info_adicional[0]->adeudo==1){
                        if($info_adicional[0]->pendientes>0){
                            $mensaje="Adeudas 1 especial. Debes seleccionar la materia correspondiente";
                            return view('alumnos.no')->with(compact('mensaje','encabezado'));
                        }
                    }
                }else{
                    $especial="N";
                    $estatus="N";
                }
                if($bandera_rep){
                    $rep_adeud=HistoriaAlumno::select('materia')
                        ->where('no_de_control',$control)
                        ->whereIn('tipo_evaluacion',['OC','OO','1','2','01','02'])
                        ->where('calificacion','<',70)
                        ->whereNotIn('materia',HistoriaAlumno::where('no_de_control',$control)
                            ->whereIn('tipo_evaluacion',['RO','RC','R1','R2'])
                            ->where('calificacion','>=',70)
                            ->select('materia')
                        )->get()
                        ->toArray();
                    if(!empty($rep_adeud)){
                        if(array_search($materia,array_column($rep_adeud,'materia'))){
                            $sel="S";
                        }else{
                            $sel="N";
                        }
                    }else{
                        $sel="S";
                    }
                    if($sel=="N"){
                        $repite=(new AccionesController)->verifica_repite($control,$periodo);
                        if($repite[0]->pendientes > 0){
                            $mensaje="Adeudas materias en repetición. No procede selección de materia";
                            if($repite[0]->ofertados > $repite[0]->seleccionadas){
                                return view('alumnos.no')->with(compact('mensaje'));
                            }else{
                                return view('alumnos.no')->with(compact('mensaje','alumno'));
                            }
                        }
                    }
                }
                //Se indica el estatus de la materia
                if($tipocur == 'CR'){
                    $repeticion = 'S';
                }elseif($tipocur == 'AE'){
                    $repeticion = "E";
                }else {
                    $repeticion = 'N';
                }
                $nmateria=Materia::select('nombre_abreviado_materia')
                    ->where('materia',$materia)
                    ->first();
                $info_grupos=(new AccionesController)->grupos_materia($periodo, $control, $materia);
                $encabezado="Reinscripción";
                return view('alumnos.seleccion_materia')
                    ->with(compact('info_grupos','materia',
                        'alumno','nmateria','periodo','repeticion','estatus','especial','encabezado'));

            }else{
                $mensaje="La materia no se está ofertando para éste semestre";
                return view('alumnos.no')->with(compact('mensaje','encabezado'));
            }
        }else{
            $mensaje="La materia no concuerda con tu plan de estudios";
            return view('alumnos.no')->with(compact('mensaje','encabezado'));
        }
    }
    public function reinscribir(Request $request){
        $control=$this->control();
        $mat=$request->get('materia');
        $periodo=$request->get('periodo');
        $repeticion=$request->get('repeticion');
        $data=explode("_",$mat);
        $materia=$data[0];
        $grupo=$data[1];
        $globales="op_".$materia."_".$grupo;
        $global=$request->get($globales);
        $bandera=0;
        $encabezado="Error en selección de materia";
        for($i=2;$i<=7;$i++){
            if(Horario::where(
                [
                    'periodo'=>$periodo,
                    'materia'=>$materia,
                    'grupo'=>$grupo,
                    'dia_semana'=>$i
                ]
            )->get()->isNotEmpty()){
                $horas=Horario::where(
                    [
                        'periodo'=>$periodo,
                        'materia'=>$materia,
                        'grupo'=>$grupo,
                        'dia_semana'=>$i
                    ]
                )->select(['hora_inicial','hora_final'])
                    ->first();
                $hinicial=Carbon::parse($horas->hora_inicial);
                $hfinal=Carbon::parse($horas->hora_final);
                $cantidad=(new AccionesController)->cruce_horario($periodo,$control,$i,$hinicial,$hfinal);
                if(!empty($cantidad)){
                    $bandera+=1;
                }
            }
        }
        if($bandera>0){
            $mensaje="No fue posible realizar el movimiento porque existe empalme con otro horario ya seleccionado";
            return view('alumnos.no')->with(compact('mensaje','encabezado'));
        }else{
            if(SeleccionMateria::where(
                [
                    'periodo'=>$periodo,
                    'materia'=>$materia,
                    'no_de_control'=>$control
                ])->count()>0){
                $mensaje="La materia ya está seleccionada por lo que no es posible volver a seleccionarla";
                return view('alumnos.no')->with(compact('mensaje','encabezado'));
            }else{
                $inscritos=Grupo::where(
                    [
                        'periodo'=>$periodo,
                        'materia'=>$materia,
                        'grupo'=>$grupo
                    ]
                )->count();
                $cap=Grupo::where(
                    [
                        'periodo'=>$periodo,
                        'materia'=>$materia,
                        'grupo'=>$grupo
                    ]
                )->select('capacidad_grupo')
                    ->first();
                $capacidad=$cap->capacidad_grupo-1;
                SeleccionMateria::insert([
                    'periodo'=>$periodo,
                    'no_de_control'=>$control,
                    'materia'=>$materia,
                    'grupo'=>$grupo,
                    'calificacion'=>null,
                    'tipo_evaluacion'=>null,
                    'repeticion'=>$repeticion,
                    'nopresento'=>'N',
                    'status_seleccion'=>'E',
                    'fecha_hora_seleccion'=>Carbon::now(),
                    'global'=>$global,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>null
                ]);
                Grupo::where(
                    [
                        'periodo'=>$periodo,
                        'materia'=>$materia,
                        'grupo'=>$grupo
                    ]
                )->update([
                        'alumnos_inscritos'=>$inscritos+1,
                        'capacidad_grupo'=>$capacidad
                    ]);
                return redirect('/estudiante/reinscripcion/');
            }
        }
    }
}
