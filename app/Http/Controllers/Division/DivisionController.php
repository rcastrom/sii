<?php

namespace App\Http\Controllers\Division;

use App\Http\Controllers\Acciones\AccionesController;
use App\Http\Controllers\Controller;
use App\Models\Alumno;
use App\Models\Aula;
use App\Models\AvisoReinscripcion;
use App\Models\Carrera;
use App\Models\Grupo;
use App\Models\HistoriaAlumno;
use App\Models\Horario;
use App\Models\Materia;
use App\Models\MateriaCarrera;
use App\Models\PeriodoEscolar;
use App\Models\PermisosCarrera;
use App\Models\Personal;
use App\Models\SeleccionMateria;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Contracts\Events\Dispatcher;
use App\Http\Controllers\MenuDivisionController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DivisionController extends Controller
{
    public function __construct(Dispatcher $events)
    {
        new MenuDivisionController($events);
    }
    public function index(){
        return view('division.index');
    }
    public function altagrupo(){
        $data=Auth::user()->email;
        $carreras=PermisosCarrera::where('email',$data)
            ->orderBy('nombre_carrera','asc')
            ->orderBy('reticula','asc')->get();
        $periodos=PeriodoEscolar::orderBy('periodo','desc')->get();
        $periodo_actual = (new AccionesController)->periodo();
        $encabezado="Creación de grupo";
        return view('division.selcarrera')->with(compact('carreras',
            'periodos','periodo_actual','encabezado'));
    }
    public function listado2(Request $request){
        $periodo=$request->get('periodo');
        $carr=$request->get('carrera');
        $data=explode('_',$carr);
        $carrera=$data[0]; $ret=$data[1];
        $ncarrera=Carrera::where('carrera',$carrera)->where('reticula',$ret)->first();
        $listado=MateriaCarrera::where('carrera',$carrera)
            ->where('reticula',$ret)
            ->join('materias','materias_carreras.materia','=','materias.materia')
            ->where('nombre_completo_materia','not like','%RESIDENCIA%')
            ->where('nombre_completo_materia','not like','%SERVICIO SOC%')
            ->where('nombre_completo_materia','not like','%COMPLEMENT%')
            ->orderBy('semestre_reticula','asc')
            ->orderBy('nombre_completo_materia','asc')
            ->select('semestre_reticula','materias.materia as mater','nombre_abreviado_materia')
            ->get();
        $encabezado="Creación de grupo";
        return view('division.listado3')->with(compact('listado',
            'ncarrera','carrera','ret','periodo','encabezado'));
    }
    public function creargrupo1($periodo,$materia,$carrera,$ret){
        $ncarrera=Carrera::where('carrera',$carrera)->where('reticula',$ret)->first();
        $nmateria=MateriaCarrera::where('carrera',$carrera)->where('reticula',$ret)
            ->where('materias_carreras.materia',$materia)
            ->join('materias','materias_carreras.materia','=','materias.materia')
            ->select('nombre_abreviado_materia','creditos_materia')->first();
        $aulas=DB::table('aulas')->where('estatus','A')->get();
        $encabezado="Creación de grupo";
        return view('division.crear_grupo')->with(compact('materia',
            'carrera','ncarrera','ret','nmateria','aulas','periodo','encabezado'));
    }
    public function creargrupo2(Request $request){
        request()->validate([
            'grupo'=>'required',
            'capacidad'=>'required',
            'slunes'=>'required_with:elunes',
            'smartes'=>'required_with:emartes',
            'smiercoles'=>'required_with:emiercoles',
            'sjueves'=>'required_with:ejueves',
            'sviernes'=>'required_with:eviernes',
            'ssabado'=>'required_with:esabado',
        ],[
            'grupo.required'=>'Debe indicar la clave del grupo',
            'capacidad.required'=>'Debe indicar la capacidad del grupo',
            'slunes.required_with'=>'Debe indicar la hora de salida para el lunes',
            'smartes.required_with'=>'Debe indicar la hora de salida para el martes',
            'smiercoles.required_with'=>'Debe indicar la hora de salida para el miércoles',
            'sjueves.required_with'=>'Debe indicar la hora de salida para el jueves',
            'sviernes.required_with'=>'Debe indicar la hora de salida para el viernes',
            'ssabado.required_with'=>'Debe indicar la hora de salida para el sabado',
        ]);
        $periodo=$request->get('periodo');
        $carrera=$request->get('carrera');
        $ret=$request->get('reticula');
        $materia=$request->get('materia');
        $grupo=$request->get('grupo');
        $creditos=$request->get('creditos');
        $capacidad=$request->get('capacidad');
        $elunes=$request->get('elunes'); if(!empty($elunes)){$elunes=Carbon::parse($elunes);}
        $emartes=$request->get('emartes'); if(!empty($emartes)){$emartes=Carbon::parse($emartes);}
        $emiercoles=$request->get('emiercoles'); if(!empty($emiercoles)){$emiercoles=Carbon::parse($emiercoles);}
        $ejueves=$request->get('ejueves'); if(!empty($ejueves)){$ejueves=Carbon::parse($ejueves);}
        $eviernes=$request->get('eviernes'); if(!empty($eviernes)){$eviernes=Carbon::parse($eviernes);}
        $esabado=$request->get('esabado'); if(!empty($esabado)){$esabado=Carbon::parse($esabado);}
        $slunes=$request->get('slunes'); if(!empty($slunes)){$slunes=Carbon::parse($slunes);}
        $smartes=$request->get('smartes'); if(!empty($smartes)){$smartes=Carbon::parse($smartes);}
        $smiercoles=$request->get('smiercoles'); if(!empty($smiercoles)){$smiercoles=Carbon::parse($smiercoles);}
        $sjueves=$request->get('sjueves'); if(!empty($sjueves)){$sjueves=Carbon::parse($sjueves);}
        $sviernes=$request->get('sviernes'); if(!empty($sviernes)){$sviernes=Carbon::parse($sviernes);}
        $ssabado=$request->get('ssabado'); if(!empty($ssabado)){$ssabado=Carbon::parse($ssabado);}
        $aula_l=$request->get('aula_l');
        $aula_m=$request->get('aula_m');
        $aula_mm=$request->get('aula_mm');
        $aula_j=$request->get('aula_j');
        $aula_v=$request->get('aula_v');
        $aula_s=$request->get('aula_s');
        if(!empty($elunes)){$hl=$elunes->diff($slunes)->format('%h');}else{$hl=0;}
        if(!empty($emartes)){$hm=$emartes->diff($smartes)->format('%h');}else{$hm=0;}
        if(!empty($emiercoles)){$hmm=$emiercoles->diff($smiercoles)->format('%h');}else{$hmm=0;}
        if(!empty($ejueves)){$hj=$ejueves->diff($sjueves)->format('%h');}else{$hj=0;}
        if(!empty($eviernes)){$hv=$eviernes->diff($sviernes)->format('%h');}else{$hv=0;}
        if(!empty($esabado)){$hs=$esabado->diff($ssabado)->format('%h');}else{$hs=0;}
        $total_horas=$hl+$hm+$hmm+$hj+$hv+$hs;
        $bandera=0;
        if($total_horas==$creditos){
            //Que no sea un grupo repetido
            if(Grupo::where([
                    'periodo'=>$periodo,
                    'materia'=>$materia,
                    'grupo'=>$grupo
                ])->count()>0){
                $encabezado="Error de alta de grupo";
                $mensaje="Ya existe la materia y grupo dados de alta previamente,
                por lo que no se volvió a crear el grupo";
                return view('division.no')->with(compact('mensaje','encabezado'));
            }else{
                if(!empty($elunes)){
                    try{
                        $alta=new Horario();
                        $alta->periodo=$periodo;
                        $alta->rfc=null;
                        $alta->tipo_horario='D';
                        $alta->dia_semana=2;
                        $alta->hora_inicial=$elunes;
                        $alta->hora_final=$slunes;
                        $alta->materia=$materia;
                        $alta->grupo=$grupo;
                        $alta->aula=$aula_l;
                        $alta->actividad=null;
                        $alta->consecutivo=null;
                        $alta->vigencia_inicio=null;
                        $alta->vigencia_fin=null;
                        $alta->consecutivo_admvo=null;
                        $alta->tipo_personal=null;
                        $alta->save();
                        $bandera++;
                    }catch (QueryException $e){
                        $encabezado="Error de alta de grupo";
                        $mensaje="El aula se encuentra ocupada el día lunes";
                        return view('division.no')->with(compact('mensaje','encabezado'));
                    }
                }
                if(!empty($emartes)){
                    try{
                        $alta=new Horario();
                        $alta->periodo=$periodo;
                        $alta->rfc=null;
                        $alta->tipo_horario='D';
                        $alta->dia_semana=3;
                        $alta->hora_inicial=$emartes;
                        $alta->hora_final=$smartes;
                        $alta->materia=$materia;
                        $alta->grupo=$grupo;
                        $alta->aula=$aula_m;
                        $alta->actividad=null;
                        $alta->consecutivo=null;
                        $alta->vigencia_inicio=null;
                        $alta->vigencia_fin=null;
                        $alta->consecutivo_admvo=null;
                        $alta->tipo_personal=null;
                        $alta->save();
                        $bandera++;
                    }catch (QueryException $e){
                        $encabezado="Error de alta de grupo";
                        $mensaje="El aula se encuentra ocupada el día martes";
                        return view('division.no')->with(compact('mensaje','encabezado'));
                    }
                }
                if(!empty($emiercoles)){
                    try{
                        $alta=new Horario();
                        $alta->periodo=$periodo;
                        $alta->rfc=null;
                        $alta->tipo_horario='D';
                        $alta->dia_semana=4;
                        $alta->hora_inicial=$emiercoles;
                        $alta->hora_final=$smiercoles;
                        $alta->materia=$materia;
                        $alta->grupo=$grupo;
                        $alta->aula=$aula_mm;
                        $alta->actividad=null;
                        $alta->consecutivo=null;
                        $alta->vigencia_inicio=null;
                        $alta->vigencia_fin=null;
                        $alta->consecutivo_admvo=null;
                        $alta->tipo_personal=null;
                        $alta->save();
                        $bandera++;
                    }catch(QueryException $e){
                        $encabezado="Error de alta de grupo";
                        $mensaje="El aula se encuentra ocupada el día miércoles";
                        return view('division.no')->with(compact('mensaje','encabezado'));
                    }
                }
                if(!empty($ejueves)){
                    try{
                        $alta=new Horario();
                        $alta->periodo=$periodo;
                        $alta->rfc=null;
                        $alta->tipo_horario='D';
                        $alta->dia_semana=5;
                        $alta->hora_inicial=$ejueves;
                        $alta->hora_final=$sjueves;
                        $alta->materia=$materia;
                        $alta->grupo=$grupo;
                        $alta->aula=$aula_j;
                        $alta->actividad=null;
                        $alta->consecutivo=null;
                        $alta->vigencia_inicio=null;
                        $alta->vigencia_fin=null;
                        $alta->consecutivo_admvo=null;
                        $alta->tipo_personal=null;
                        $alta->save();
                        $bandera++;
                    }catch (QueryException $e){
                        $encabezado="Error de alta de grupo";
                        $mensaje="El aula se encuentra ocupada el día jueves";
                        return view('division.no')->with(compact('mensaje','encabezado'));
                    }
                }
                if(!empty($eviernes)){
                    try{
                        $alta=new Horario();
                        $alta->periodo=$periodo;
                        $alta->rfc=null;
                        $alta->tipo_horario='D';
                        $alta->dia_semana=6;
                        $alta->hora_inicial=$eviernes;
                        $alta->hora_final=$sviernes;
                        $alta->materia=$materia;
                        $alta->grupo=$grupo;
                        $alta->aula=$aula_v;
                        $alta->actividad=null;
                        $alta->consecutivo=null;
                        $alta->vigencia_inicio=null;
                        $alta->vigencia_fin=null;
                        $alta->consecutivo_admvo=null;
                        $alta->tipo_personal=null;
                        $alta->save();
                        $bandera++;
                    }catch(QueryException $e){
                        $encabezado="Error de alta de grupo";
                        $mensaje="El aula se encuentra ocupada el día viernes";
                        return view('division.no')->with(compact('mensaje','encabezado'));
                    }
                }
                if(!empty($esabado)){
                    try{
                        $alta=new Horario();
                        $alta->periodo=$periodo;
                        $alta->rfc=null;
                        $alta->tipo_horario='D';
                        $alta->dia_semana=7;
                        $alta->hora_inicial=$esabado;
                        $alta->hora_final=$ssabado;
                        $alta->materia=$materia;
                        $alta->grupo=$grupo;
                        $alta->aula=$aula_s;
                        $alta->actividad=null;
                        $alta->consecutivo=null;
                        $alta->vigencia_inicio=null;
                        $alta->vigencia_fin=null;
                        $alta->consecutivo_admvo=null;
                        $alta->tipo_personal=null;
                        $alta->save();
                        $bandera++;
                    }catch (QueryException $e){
                        $encabezado="Error de alta de grupo";
                        $mensaje="El aula se encuentra ocupada el día sábado";
                        return view('division.no')->with(compact('mensaje','encabezado'));
                    }
                }
                if($bandera>0){
                    $grupo_nuevo=new Grupo();
                    $grupo_nuevo->periodo=$periodo;
                    $grupo_nuevo->materia=$materia;
                    $grupo_nuevo->grupo=$grupo;
                    $grupo_nuevo->estatus_grupo=null;
                    $grupo_nuevo->capacidad_grupo=$capacidad;
                    $grupo_nuevo->alumnos_inscritos=0;
                    $grupo_nuevo->folio_acta=null;
                    $grupo_nuevo->paralelo_de=null;
                    $grupo_nuevo->exclusivo_carrera=$carrera;
                    $grupo_nuevo->exclusivo_reticula=$ret;
                    $grupo_nuevo->rfc=null;
                    $grupo_nuevo->tipo_personal='B';
                    $grupo_nuevo->exclusivo='no';
                    $grupo_nuevo->entrego=0;
                    $grupo_nuevo->save();
                    $ncarrera=Carrera::where('carrera',$carrera)->where('reticula',$ret)->first();
                    $nmateria=MateriaCarrera::where('carrera',$carrera)->where('reticula',$ret)
                        ->where('materias_carreras.materia',$materia)
                        ->join('materias','materias_carreras.materia','=','materias.materia')
                        ->select('nombre_abreviado_materia','creditos_materia')->first();
                    $encabezado="Alta de grupo";
                    $mensaje="Se dió de alta la materia ".$nmateria->nombre_abreviado_materia." para la
                    carrera ".$ncarrera->nombre_reducido." retícula ".$ret;
                    return view('division.si')->with(compact('encabezado','mensaje'));
                }else{
                    $encabezado="Error de alta de grupo";
                    $mensaje="No se pudo generar el alta de la materia (-1)";
                    return view('division.no')->with(compact('mensaje','encabezado'));
                }
            }
        }else{
            $encabezado="Error de alta de grupo";
            $mensaje="No se pudo realizar la acción porque no concuerda el número de horas a
            impartir contra las que debe tener la materia";
            return view('division.no')->with(compact('mensaje','encabezado'));
        }
    }
    public function paralelo1(){
        $data=Auth::user()->email;
        $carrera_origen=PermisosCarrera::where('email',$data)
            ->orderBy('nombre_carrera','asc')
            ->orderBy('reticula','asc')->get();
        $periodos=PeriodoEscolar::orderBy('periodo','desc')->get();
        $periodo_actual=(new AccionesController)->periodo();
        $periodo=$periodo_actual[0]->periodo;
        $carreras=Carrera::orderBy('nombre_carrera','asc')->orderBy('reticula','asc')->get();
        $encabezado="Creación de Grupos Paralelos";
        return view('division.altaparalelo1')->with(compact('carreras',
            'carrera_origen','periodos','periodo','encabezado'));
    }
    public function paralelo2(Request $request){
        $origen=$request->get('carrerao');
        $destino=$request->get('carrerap');
        $periodo=$request->get('periodo');
        $datos_o=explode("_",$origen);
        $carrera_o=$datos_o[0]; $ret_o=$datos_o[1];
        $datos_p=explode("_",$destino);
        $carrera_p=$datos_p[0]; $ret_p=$datos_p[1];
        $listado_o=MateriaCarrera::where('carrera',$carrera_o)
            ->where('reticula',$ret_o)
            ->join('grupos','materias_carreras.materia','=','grupos.materia')
            ->where('grupos.periodo',$periodo)
            ->whereNull('grupos.paralelo_de')
            ->join('materias','materias_carreras.materia','=','materias.materia')
            ->select('materias_carreras.materia as mater','semestre_reticula','nombre_abreviado_materia','nombre_completo_materia','grupo')
            ->orderBy('semestre_reticula','asc')
            ->orderBy('nombre_completo_materia','asc')
            ->get();
        $listado_p=MateriaCarrera::where('carrera',$carrera_p)
            ->where('reticula',$ret_p)
            ->join('materias','materias_carreras.materia','=','materias.materia')
            ->where('nombre_completo_materia','not like','%RESIDENCIA%')
            ->where('nombre_completo_materia','not like','%SERVICIO SOC%')
            ->where('nombre_completo_materia','not like','%COMPLEMENT%')
            ->select('materias_carreras.materia as mater','semestre_reticula','nombre_abreviado_materia','nombre_abreviado_materia')
            ->orderBy('nombre_completo_materia','asc')
            ->get();
        $encabezado="Creación de Grupos Paralelos";
        return view('division.altaparalelo2')->with(compact('listado_o',
            'listado_p','carrera_o','ret_o','carrera_p','ret_p','periodo','encabezado'));
    }
    public function paralelo3(Request $request){
        request()->validate([
            'gpo_p'=>'required',
            'cap_n'=>'required'
        ],[
            'gpo_p.required'=>'Debe indicar la clave del grupo',
            'cap_n.required'=>'Debe indicar la capacidad del grupo'
        ]);
        $car_p=$request->get('carrera_p');
        $ret_p=$request->get('ret_p');
        $gpo_p=$request->get('gpo_p');
        $cap_n=$request->get('cap_n');
        $periodo=$request->get('periodo');
        $origenn=$request->get('mat_o');
        $datos_o=explode("_",$origenn);
        $mat_o=$datos_o[0];
        $gpo_o=$datos_o[1];
        $mat_p=$request->get('matp');
        //Se checa si existe el docente
        $doc=Grupo::where('periodo',$periodo)->where('materia',$mat_o)->where('grupo',$gpo_o)
            ->select('rfc')->first();
        if(!empty($doc)){
            $rfc=$doc->rfc;
        }else{
            $rfc=null;
        }
        //
        try{
            $alta=new Grupo();
            $alta->periodo=$periodo;
            $alta->materia=$mat_p;
            $alta->grupo=$gpo_p;
            $alta->estatus_grupo=null;
            $alta->capacidad_grupo=$cap_n;
            $alta->alumnos_inscritos=0;
            $alta->folio_acta=null;
            $alta->paralelo_de=$mat_o.$gpo_o;
            $alta->exclusivo_carrera=$car_p;
            $alta->exclusivo_reticula=$ret_p;
            $alta->rfc=$rfc;
            $alta->tipo_personal='B';
            $alta->exclusivo='no';
            $alta->entrego=0;
            $alta->save();
        }catch(QueryException $e){
            $encabezado="Error de alta de grupo paralelo";
            $mensaje="El grupo ya existe, por lo que no es posible duplicarlo";
            return view('division.no')->with(compact('mensaje','encabezado'));
        }
        //Ahora, el horario
        for($i=2;$i<=7;$i++){
            $info=Horario::where('periodo',$periodo)->where('materia',$mat_o)
                ->where('grupo',$gpo_o)->where('dia_semana',$i)->first();
            if(!empty($info->hora_inicial)){
                $horario=new Horario();
                $horario->periodo=$periodo;
                $horario->rfc=$rfc;
                $horario->tipo_horario='D';
                $horario->dia_semana=$i;
                $horario->hora_inicial=$info->hora_inicial;
                $horario->hora_final=$info->hora_final;
                $horario->materia=$mat_p;
                $horario->grupo=$gpo_p;
                $horario->aula=$info->aula;
                $horario->actividad=null;
                $horario->consecutivo=0;
                $horario->vigencia_inicio=null;
                $horario->vigencia_fin=null;
                $horario->consecutivo_admvo=0;
                $horario->tipo_personal='B';
                $horario->save();
            }
        }
        $encabezado="Alta de grupo paralelo";
        $mensaje="Se generó el grupo paralelo correspondiente";
        return view('division.si')->with(compact('mensaje','encabezado'));
    }
    public function existentes(){
        $data=Auth::user()->email;
        $carreras=Carrera::orderBy('nombre_carrera','asc')
            ->orderBy('reticula','asc')->get();
        $periodos=PeriodoEscolar::orderBy('periodo','desc')->get();
        $periodo_actual=(new AccionesController)->periodo();
        $periodo=$periodo_actual[0]->periodo;
        $encabezado="Grupos del semestre";
        return view('division.listado')->with(compact('carreras',
            'periodos','periodo','encabezado'));
    }
    public function listado(Request $request){
        $periodo=$request->get('periodo');
        $carr=$request->get('carrera');
        $data=explode('_',$carr);
        $carrera=$data[0]; $ret=$data[1];
        $ncarrera=Carrera::where('carrera',$carrera)->where('reticula',$ret)->first();
        $listado=MateriaCarrera::where('carrera',$carrera)
            ->where('reticula',$ret)
            ->join('grupos','materias_carreras.materia','=','grupos.materia')
            ->where('grupos.periodo',$periodo)
            ->join('materias','materias_carreras.materia','=','materias.materia')
            ->select('materias_carreras.materia as mater','semestre_reticula',
                'nombre_abreviado_materia','nombre_completo_materia','grupo','paralelo_de','alumnos_inscritos')
            ->orderBy('semestre_reticula','asc')
            ->orderBy('nombre_completo_materia','asc')
            ->get();
        $encabezado="Grupos del semestre";
        return view('division.listado2')->with(compact('listado','ncarrera','periodo','encabezado'));
    }
    public function info($periodo,$materia,$grupo){
        $personal=Grupo::select('rfc')->where('periodo',$periodo)
            ->where('materia',$materia)->where('grupo',$grupo)->first();
        if(is_null($personal->rfc)){
            $docente="Pendiente por asignar";
        }else{
            $datos_doc=Personal::where('rfc',$personal->rfc)->first();
            $docente=$datos_doc->apellidos_empleado." ".$datos_doc->nombre_empleado;
        }
        $nmateria=Materia::where('materia',$materia)->first();
        $alumnos=SeleccionMateria::where('periodo',$periodo)
            ->where('materia',$materia)
            ->where('grupo',$grupo)
            ->join('alumnos','seleccion_materias.no_de_control','=','alumnos.no_de_control')
            ->orderBy('apellido_paterno','asc')
            ->orderBy('apellido_materno','asc')
            ->orderBy('nombre_alumno','asc')
            ->get();
        $encabezado="Información sobre grupos existentes";
        return view('division.informacion_grupo')->with(compact('docente',
            'materia','grupo','nmateria','periodo','alumnos','encabezado'));
    }
    public function acciones(Request $request){
        $materia=$request->get('materia');
        $grupo=$request->get('grupo');
        $periodo=$request->get('periodo');
        $accion=$request->get('accion');
        $nmateria=Materia::where('materia',$materia)->first();
        if($accion==1){
            $encabezado="Alta de estudiante a grupo";
            return view('division.alta_a_grupo')->with(compact('materia',
                'grupo','nmateria','periodo','encabezado'));
        }elseif($accion==2){
            $encabezado="Baja de estudiante a grupo";
            $alumnos=SeleccionMateria::where('periodo',$periodo)
                ->where('materia',$materia)
                ->where('grupo',$grupo)
                ->join('alumnos','seleccion_materias.no_de_control','=','alumnos.no_de_control')
                ->orderBy('apellido_paterno','asc')
                ->orderBy('apellido_materno','asc')
                ->orderBy('nombre_alumno','asc')
                ->get();
            return view('division.baja_a_grupo')->with(compact('materia',
                'grupo','nmateria','alumnos','periodo','encabezado'));
        }elseif($accion==3){
            //Primero verifico, si tiene inscritos, no puede modificar
            if(SeleccionMateria::where('periodo',$periodo)->where('materia',$materia)->where('grupo',$grupo)->count()>0){
                $encabezado="Error para modificación en grupo";
                $mensaje="No puede modificar el horario a una materia que tiene alumnos inscritos";
                return view('division.no')->with(compact('mensaje','encabezado'));
                //Ahora, si la materia es paralela, no se puede modificar su horario
            }elseif (Grupo::where('periodo',$periodo)->where('materia',$materia)
                    ->where('grupo',$grupo)->whereNotNull('paralelo_de')->count()>0){
                $encabezado="Error para modificación en grupo";
                $mensaje="No puede modificar el horario a una materia que es paralela de otra";
                return view('division.no')->with(compact('mensaje','encabezado'));
            }else{
                $lunes=Horario::where('periodo',$periodo)
                    ->where('materia',$materia)->where('grupo',$grupo)
                    ->where('dia_semana',2)->select('hora_inicial','hora_final','aula')
                    ->get();
                $martes=Horario::where('periodo',$periodo)
                    ->where('materia',$materia)->where('grupo',$grupo)
                    ->where('dia_semana',3)->select('hora_inicial','hora_final','aula')
                    ->get();
                $miercoles=Horario::where('periodo',$periodo)
                    ->where('materia',$materia)->where('grupo',$grupo)
                    ->where('dia_semana',4)->select('hora_inicial','hora_final','aula')
                    ->get();
                $jueves=Horario::where('periodo',$periodo)
                    ->where('materia',$materia)->where('grupo',$grupo)
                    ->where('dia_semana',5)->select('hora_inicial','hora_final','aula')
                    ->get();
                $viernes=Horario::where('periodo',$periodo)
                    ->where('materia',$materia)->where('grupo',$grupo)
                    ->where('dia_semana',6)->select('hora_inicial','hora_final','aula')
                    ->get();
                $sabado=Horario::where('periodo',$periodo)
                    ->where('materia',$materia)->where('grupo',$grupo)
                    ->where('dia_semana',7)->select('hora_inicial','hora_final','aula')
                    ->get();
                $grupo_existente=Grupo::where('periodo',$periodo)
                    ->where('materia',$materia)->where('grupo',$grupo)->get();
                $aulas=Aula::where('estatus','A')->get();
                $mater=Grupo::where('periodo',$periodo)
                    ->where('grupos.materia',$materia)->where('grupo',$grupo)
                    ->join('materias_carreras as a1','grupos.materia','=','a1.materia')
                    ->join('materias_carreras as a2','grupos.exclusivo_carrera','=','a2.carrera')
                    ->join('materias_carreras as a3','grupos.exclusivo_reticula','=','a3.reticula')
                    ->join('materias','materias.materia','=','grupos.materia')
                    ->select('nombre_abreviado_materia','a1.creditos_materia')
                    ->first();
                $encabezado="Actualización de horario";
                return view('division.modificar_grupo')
                    ->with(compact('grupo','materia',
                    'mater','aulas','grupo_existente','lunes','martes',
                    'miercoles','jueves','viernes','sabado','periodo','encabezado'));
            }
        }elseif ($accion==4) {
            $cap=DB::table('grupos')->where('periodo',$periodo)->where('materia',$materia)
                ->where('grupo',$grupo)->select('capacidad_grupo')->first();
            return view('dep.capgrupo')->with(compact('materia','grupo','nmateria','periodo','cap'));
        }elseif ($accion==5){
            //Si tiene estudiantes, no puedo borrar
            if(DB::table('seleccion_materias')->where('periodo',$periodo)
                    ->where('materia',$materia)->where('grupo',$grupo)->count()>0){
                $mensaje="La materia cuenta con estudiantes inscritos, por lo que no es posible borrar el grupo";
                return view('dep.no')->with(compact('mensaje'));
            }else{
                //Si tiene grupos paralelas en la misma, no se puede
                $pos_paralela=$materia.$grupo;
                if(DB::table('grupos')->where('periodo',$periodo)->where('paralelo_de',$pos_paralela)->count()>0){
                    $mensaje="La materia tiene grupos paralelos, debe eliminar los dependientes primero para poder eliminar al grupo";
                    return view('dep.no')->with(compact('mensaje'));
                }else{
                    DB::table('grupos')->where('periodo',$periodo)
                        ->where('materia',$materia)->where('grupo',$grupo)->delete();
                    DB::table('horarios')->where('periodo',$periodo)
                        ->where('materia',$materia)->where('grupo',$grupo)->delete();
                    return view('dep.si');
                }
            }
        }
    }
    public function altacontrol(Request $request){
        request()->validate([
            'control'=>'required',
        ],[
            'control.required'=>'Debe indicar un dato para ser buscado'
        ]);
        $control=$request->get('control');
        $global=$request->get('global');
        $materia=$request->get('materia');
        $grupo=$request->get('grupo');
        $periodo=$request->get('periodo');
        //Se verifica primero que es estudiante
        Alumno::findorFail($control);
        //Determinar si están en permiso de reinscribir
        $permiso=PeriodoEscolar::where('periodo',$periodo)->select('cierre_seleccion')->first();
        if($permiso->cierre_seleccion=="N"){
            $encabezado="Error de asignación a grupo";
            $mensaje="El período de reinscripciones o no ha iniciado o ha concluido";
            return view('division.no')->with(compact('mensaje','encabezado'));
        }else{
            //Ahora, si la materia es de su plan de estudios
            if(Alumno::where('no_de_control',$control)
                    ->join('materias_carreras as a1','a1.carrera','=','alumnos.carrera')
                    ->join('materias_carreras as a2','a2.reticula','=','alumnos.reticula')
                    ->where('a1.materia',$materia)
                    ->count()>0
            ){
                //Ver si cuenta con pago registrado
                if(AvisoReinscripcion::where('periodo',$periodo)
                        ->where('no_de_control',$control)
                        ->where('autoriza_escolar','S')
                        ->count()>0){
                    //No repetir al estudiante en la misma materia
                    if(SeleccionMateria::where('periodo',$periodo)
                            ->where('no_de_control',$control)
                            ->where('materia',$materia)
                            ->count()>0){
                        $encabezado="Error de asignación a grupo";
                        $mensaje="El estudiante ya está inscrito previamente en la materia";
                        return view('division.no')->with(compact('mensaje','encabezado'));
                    }else{
                        //No darlo de alta en una materia que ya acreditó.
                        if(HistoriaAlumno::where('no_de_control',$control)
                                ->where('materia',$materia)->where('calificacion','>=',70)
                                ->count()>0){
                            $encabezado="Error de asignación a grupo";
                            $mensaje="El estudiante ya tiene acreditada la materia";
                            return view('division.no')->with(compact('mensaje','encabezado'));
                        }else{
                            //Inscribo
                            if(HistoriaAlumno::where('no_de_control',$control)->where('materia',$materia)->count()>0){
                                $rep="S";
                            }else{
                                $rep="N";
                            }
                            $alta_materia=new SeleccionMateria();
                            $alta_materia->periodo=$periodo;
                            $alta_materia->no_de_control=$control;
                            $alta_materia->materia=$materia;
                            $alta_materia->grupo=$grupo;
                            $alta_materia->calificacion=null;
                            $alta_materia->tipo_evaluacion=null;
                            $alta_materia->repeticion=$rep;
                            $alta_materia->nopresento='N';
                            $alta_materia->status_seleccion='C';
                            $alta_materia->fecha_hora_seleccion=Carbon::now();
                            $alta_materia->global=$global;
                            $alta_materia->save();
                            $quien=Auth::user()->email;
                            DB::table('seleccion_materias_log')->insert([
                                'periodo'=>$periodo,
                                'no_de_control'=>$control,
                                'materia'=>$materia,
                                'grupo'=>$grupo,
                                'movimiento'=>'A',
                                'cuando'=>Carbon::now(),
                                'responsable'=>$quien
                            ]);
                            //Cantidad de inscritos
                            $cant=Grupo::where([
                                'periodo'=>$periodo,
                                'materia'=>$materia,
                                'grupo'=>$grupo
                            ])->select('alumnos_inscritos','capacidad_grupo')->first();
                            $inscritos=$cant->alumnos_inscritos+1;
                            $capacidad=$cant->capacidad_grupo-1;
                            Grupo::where([
                                'periodo'=>$periodo,
                                'materia'=>$materia,
                                'grupo'=>$grupo
                            ])->update([
                                'alumnos_inscritos'=>$inscritos,
                                'capacidad_grupo'=>$capacidad
                            ]);
                            $encabezado="Información sobre grupos existentes";
                            return redirect()->route('dep_info',
                                [
                                    'periodo'=>$periodo,
                                    'materia'=>$materia,
                                    'gpo'=>$grupo,
                                    'encabezado'=>$encabezado
                                ]);
                        }
                    }
                }else{
                    $encabezado="Error de asignación a grupo";
                    $mensaje="No existe pago registrado";
                    return view('division.no')->with(compact('mensaje','encabezado'));
                }
            }else{
                $encabezado="Error de asignación a grupo";
                $mensaje="La materia no pertenece al plan de estudios del estudiante";
                return view('division.no')->with(compact('mensaje','encabezado'));
            }
        }
    }
    public function bajacontrol(Request $request){
        $control=$request->get('control');
        $materia=$request->get('materia');
        $grupo=$request->get('grupo');
        $periodo=$request->get('periodo');
        SeleccionMateria::where([
            'periodo'=>$periodo,
            'materia'=>$materia,
            'grupo'=>$grupo,
            'no_de_control'=>$control
        ])->delete();
        $quien=Auth::user()->email;
        DB::table('seleccion_materias_log')->insert([
            'periodo'=>$periodo,
            'no_de_control'=>$control,
            'materia'=>$materia,
            'grupo'=>$grupo,
            'movimiento'=>'B',
            'cuando'=>Carbon::now(),
            'responsable'=>$quien
        ]);
        //Cantidad de inscritos
        $cant=Grupo::where([
            'periodo'=>$periodo,
            'materia'=>$materia,
            'grupo'=>$grupo
        ])->select('alumnos_inscritos','capacidad_grupo')->first();
        $inscritos=$cant->alumnos_inscritos-1;
        $capacidad=$cant->capacidad_grupo+1;
        Grupo::where([
            'periodo'=>$periodo,
            'materia'=>$materia,
            'grupo'=>$grupo
        ])->update(
            [
            'alumnos_inscritos'=>$inscritos,
            'capacidad_grupo'=>$capacidad
            ]
        );
        $encabezado="Información sobre grupos existentes";
        return redirect()->route('dep_info',[
            'periodo'=>$periodo,
            'materia'=>$materia,
            'gpo'=>$grupo,
            'encabezado'=>$encabezado
            ]);
    }

}
