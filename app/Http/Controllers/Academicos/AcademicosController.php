<?php

namespace App\Http\Controllers\Academicos;

use App\Http\Controllers\Acciones\AccionesController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MenuAcademicosController;
use App\Models\ActividadesApoyo;
use App\Models\ApoyoDocencia;
use App\Models\Aula;
use App\Models\Carrera;
use App\Models\Grupo;
use App\Models\Horario;
use App\Models\HorarioAdministrativo;
use App\Models\Materia;
use App\Models\PeriodoEscolar;
use App\Models\Personal;
use App\Models\Puesto;
use App\Models\SeleccionMateria;
use Carbon\Carbon;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AcademicosController extends Controller
{
    public function __construct(Dispatcher $events)
    {
        new MenuAcademicosController($events);
    }
    public function index(){
        return view('academicos.index');
    }

    public function existentes(){
        $encabezado="Grupos del período";
        $carreras = Carrera::select('carrera', 'reticula', 'nombre_reducido')
            ->orderBy('carrera')
            ->orderBy('reticula')->get();
        $periodo_semestre = (new AccionesController)->periodo();
        $periodo_actual=$periodo_semestre[0]->periodo;
        $periodos = PeriodoEscolar::orderBy('periodo','DESC')->get();
        return view('academicos.listado')
            ->with(compact('encabezado','carreras','periodo_actual','periodos'));
    }
    public function listado(Request $request){
        $periodo=$request->get('periodo');
        $carr=$request->get('carrera');
        $data=explode('_',$carr);
        $carrera=$data[0]; $ret=$data[1];
        $ncarrera=(new AccionesController)->ncarrera($carrera, $ret);
        $listado=(new AccionesController)->listado_materias($carrera, $ret, $request->get('periodo'));
        $encabezado="Grupos del período";
        return view('academicos.listado2')
            ->with(compact('listado','ncarrera','periodo','encabezado'));
    }
    public function info($periodo,$materia,$grupo){
        $maestro=(new AccionesController)->nombre_docente($periodo,$materia,$grupo);
        if(is_null($maestro->docente)){
            $docente="Pendiente por asignar";
        }else{
            $datos_doc=Personal::where('id',$maestro->docente)->first();
            $docente=isset($datos_doc)?$datos_doc->apellidos_empleado." ".$datos_doc->nombre_empleado:"Por ser asignado";
        }
        $nmateria=Materia::where('materia',$materia)->first();
        $alumnos=(new AccionesController)->listado_alumnos($periodo,$materia,$grupo);
        $encabezado="Información sobre grupos existentes";
        return view('academicos.informacion_grupo')->with(compact('docente',
            'materia','grupo','nmateria','periodo','alumnos','encabezado'));
    }
    public function acciones(Request $request){
        $materia=$request->get('materia');
        $grupo=$request->get('grupo');
        $periodo=$request->get('periodo');
        $nmateria=Materia::where('materia',$materia)->first();
        $personal=Personal::where('nombramiento','D')
            ->where('status_empleado','2')
            ->orderBy('apellidos_empleado','ASC')
            ->orderBy('nombre_empleado','ASC')
            ->get();
        $encabezado="Asignación de docente";
        return view('academicos.alta_docente')
            ->with(compact('materia','grupo','nmateria','personal','periodo','encabezado'));
    }
    public function altadocente(Request $request){
        $materia=$request->get('materia');
        $grupo=$request->get('grupo');
        $periodo=$request->get('periodo');
        //Si la materia es paralela, no se puede modificar, solo la base
        if(Grupo::where('periodo',$periodo)
                ->where('materia',$materia)
                ->where('grupo',$grupo)
                ->whereNotNull('paralelo_de')
                ->count()>0){
            $encabezado="Materia paralela";
            $mensaje="No puede asignarle docente a una materia paralela";
            return view('academicos.no')
                ->with(compact('mensaje','encabezado'));
        }else{
            $docente=$request->get('docente');
            $dias=Horario::select('dia_semana')
                ->where('periodo',$periodo)
                ->where('materia',$materia)
                ->where('grupo',$grupo)
                ->get();
            $mensaje="";
            $suma=0;
            $dia_semana='';
            foreach ($dias as $dia){
                $pcruce=(new AccionesController)->cruce($periodo,$materia,$grupo,$docente,$dia->dia_semana);
                switch ($dia->dia_semana){
                    case 2: $dia_semana="lunes"; break;
                    case 3: $dia_semana="martes"; break;
                    case 4: $dia_semana="miércoles"; break;
                    case 5: $dia_semana="jueves"; break;
                    case 6: $dia_semana="viernes"; break;
                    case 7: $dia_semana="sábado"; break;
                }
                if($pcruce==1){
                    $mensaje.="El docente tiene cruce de horario el día ".$dia_semana."\n";
                    $suma++;
                }
            }
            if($suma>0){
                $encabezado="Cruce de horario para el docente";
                return view('academicos.no')
                    ->with(compact('mensaje','encabezado'));
            }else{
                Horario::where('periodo',$periodo)
                    ->where('materia',$materia)
                    ->where('grupo',$grupo)
                    ->update(
                        [
                            'docente'=>$docente,
                            'updated_at'=>Carbon::now()
                        ]);
                Grupo::where('periodo',$periodo)
                    ->where('materia',$materia)
                    ->where('grupo',$grupo)
                    ->update([
                        'docente'=>$docente,
                        'updated_at'=>Carbon::now()
                    ]);
                //Si tiene materia paralela, se le debe asignar el docente también
                $materia_paralela=$materia.$grupo;
                if(Grupo::where('periodo',$periodo)
                        ->where('paralelo_de',$materia_paralela)
                        ->count()>0){
                    $datos=Grupo::where('periodo',$periodo)
                        ->where('paralelo_de',$materia_paralela)
                        ->select('materia','grupo')
                        ->get();
                    foreach ($datos as $dato){
                        $mat_p=$dato->materia;
                        $gpo_p=$dato->grupo;
                        Horario::where('periodo',$periodo)
                            ->where('materia',$mat_p)
                            ->where('grupo',$gpo_p)
                            ->update([
                                'docente'=>$docente,
                                'updated_at'=>Carbon::now()
                            ]);
                        Grupo::where('periodo',$periodo)
                            ->where('materia',$mat_p)
                            ->where('grupo',$gpo_p)
                            ->update([
                                'docente'=>$docente,
                                'updated_at'=>Carbon::now()
                            ]);
                    }
                }
                return redirect()->route('academicos.info',['periodo'=>$periodo,'materia'=>$materia,'gpo'=>$grupo]);
            }
        }
    }
    public function prepoblacion(){
        $periodos=PeriodoEscolar::orderBy('periodo','desc')->get();
        $periodo_actual=(new AccionesController)->periodo();
        $periodo=$periodo_actual[0]->periodo;
        $encabezado="Población Escolar";
        return view('academicos.prepoblacion')->with(compact('periodos',
            'periodo','encabezado'));
    }
    public function poblacion(Request $request){
        $periodo=$request->get('periodo');
        $inscritos=(new AccionesController)->inscritos($periodo);
        $nperiodo=PeriodoEscolar::where('periodo',$periodo)->first();
        $encabezado="Población Escolar";
        return view('academicos.poblacion')->with(compact('inscritos',
            'periodo','nperiodo','encabezado'));
    }
    public function pobxcarrera($periodo,$carrera,$reticula){
        $nperiodo=PeriodoEscolar::where('periodo',$periodo)->first();
        $ncarrera=Carrera::where('carrera',$carrera)->where('reticula',15)->first();
        $cantidad=SeleccionMateria::where('periodo',$periodo)
            ->join('alumnos','alumnos.no_de_control','=','seleccion_materias.no_de_control')
            ->where('carrera',$carrera)
            ->where('reticula',$reticula)
            ->selectRaw('COUNT(DISTINCT(seleccion_materias.no_de_control)) AS inscritos, semestre')
            ->groupByRaw('semestre')
            ->get();
        $data=array();
        $i=0;
        foreach ($cantidad as $cant){
            $pob_masc=SeleccionMateria::where('periodo',$periodo)
                ->join('alumnos','alumnos.no_de_control','=','seleccion_materias.no_de_control')
                ->where('carrera',$carrera)
                ->where('reticula',$reticula)
                ->where('sexo','M')
                ->where('semestre',$cant->semestre)
                ->selectRaw('COUNT(DISTINCT(seleccion_materias.no_de_control)) AS inscritos')
                ->first();
            $pob_fem=SeleccionMateria::where('periodo',$periodo)
                ->join('alumnos','alumnos.no_de_control','=','seleccion_materias.no_de_control')
                ->where('carrera',$carrera)
                ->where('reticula',$reticula)
                ->where('sexo','F')
                ->where('semestre',$cant->semestre)
                ->selectRaw('COUNT(DISTINCT(seleccion_materias.no_de_control)) AS inscritos')
                ->first();
            $data[$i]["semestre"]=$cant->semestre;
            $data[$i]["hombres"]=$pob_masc->inscritos;
            $data[$i]["mujeres"]=$pob_fem->inscritos;
            $data[$i]["total"]=$pob_masc->inscritos+$pob_fem->inscritos;
            $i++;
        }
        $poblacion=collect($data);
        $encabezado="Población Escolar";
        return view('academicos.poblacion2')
            ->with(compact('poblacion', 'ncarrera',
            'reticula','nperiodo','encabezado'));
    }
    public function pobxaulas(){
        $periodos=PeriodoEscolar::orderBy('periodo','desc')->get();
        $periodo_actual=(new AccionesController)->periodo();
        $periodo=$periodo_actual[0]->periodo;
        $aulas=Aula::where('estatus','=', True)->get();
        $encabezado="Uso de aulas";
        return view('academicos.aulas')->with(compact('aulas',
            'periodos','periodo','encabezado'));
    }
    public function pobxaulas2(Request $request){
        $aula=$request->get('salon');
        $periodo=$request->get('periodo');
        $nperiodo=PeriodoEscolar::where('periodo',$periodo)->first();
        $lunes=Horario::where('periodo',$periodo)->where('dia_semana',2)
            ->where('aula',$aula)
            ->join('materias','materias.materia','=','horarios.materia')
            ->join('materias_carreras','materias_carreras.materia','=','materias.materia')
            ->join('carreras','carreras.carrera','=','materias_carreras.carrera')
            ->select('hora_inicial','hora_final','nombre_abreviado_materia','horarios.materia','grupo','docente')
            ->distinct()
            ->get();
        $martes=Horario::where('periodo',$periodo)->where('dia_semana',3)
            ->where('aula',$aula)
            ->join('materias','materias.materia','=','horarios.materia')
            ->join('materias_carreras','materias_carreras.materia','=','materias.materia')
            ->join('carreras','carreras.carrera','=','materias_carreras.carrera')
            ->select('hora_inicial','hora_final','nombre_abreviado_materia','horarios.materia','grupo','docente')
            ->distinct()
            ->get();
        $miercoles=Horario::where('periodo',$periodo)->where('dia_semana',4)
            ->where('aula',$aula)
            ->join('materias','materias.materia','=','horarios.materia')
            ->join('materias_carreras','materias_carreras.materia','=','materias.materia')
            ->join('carreras','carreras.carrera','=','materias_carreras.carrera')
            ->select('hora_inicial','hora_final','nombre_abreviado_materia','horarios.materia','grupo','docente')
            ->distinct()
            ->get();
        $jueves=Horario::where('periodo',$periodo)->where('dia_semana',5)
            ->where('aula',$aula)
            ->join('materias','materias.materia','=','horarios.materia')
            ->join('materias_carreras','materias_carreras.materia','=','materias.materia')
            ->join('carreras','carreras.carrera','=','materias_carreras.carrera')
            ->select('hora_inicial','hora_final','nombre_abreviado_materia','horarios.materia','grupo','docente')
            ->distinct()
            ->get();
        $viernes=Horario::where('periodo',$periodo)->where('dia_semana',6)
            ->where('aula',$aula)
            ->join('materias','materias.materia','=','horarios.materia')
            ->join('materias_carreras','materias_carreras.materia','=','materias.materia')
            ->join('carreras','carreras.carrera','=','materias_carreras.carrera')
            ->select('hora_inicial','hora_final','nombre_abreviado_materia','horarios.materia','grupo','docente')
            ->distinct()
            ->get();
        $sabado=Horario::where('periodo',$periodo)->where('dia_semana',7)
            ->where('aula',$aula)
            ->join('materias','materias.materia','=','horarios.materia')
            ->join('materias_carreras','materias_carreras.materia','=','materias.materia')
            ->join('carreras','carreras.carrera','=','materias_carreras.carrera')
            ->select('hora_inicial','hora_final','nombre_abreviado_materia','horarios.materia','grupo','docente')
            ->distinct()
            ->get();
        $encabezado="Uso de aulas";
        return view('academicos.aulas2')->with(compact('nperiodo','aula',
            'lunes','martes','miercoles','jueves','viernes','sabado','periodo','encabezado'));
    }
    public function predocentes(){
        $maestros=Personal::where('nombramiento','D')
            ->where('status_empleado','2')
            ->orderBy('apellidos_empleado','asc')->orderBy('nombre_empleado','asc')->get();
        $periodos=PeriodoEscolar::orderBy('periodo','desc')->get();
        $periodo_actual=(new AccionesController)->periodo();
        $periodo=$periodo_actual[0]->periodo;
        $encabezado="Listado por docentes";
        return view('academicos.docentes')
            ->with(compact('maestros','periodo','periodos','encabezado'));
    }
    public function docente(Request $request){
        $nperiodo=PeriodoEscolar::where('periodo',$request->get('periodo'))
            ->select('identificacion_larga')
            ->first();
        $info=Grupo::where('periodo',$request->get('periodo'))
            ->where('docente',$request->get('docente'))
            ->whereNull('paralelo_de')
            ->join('materias_carreras as mc','mc.materia','=','grupos.materia')
            ->join('materias','mc.materia','=','materias.materia')
            ->select('grupos.materia','grupo','nombre_abreviado_materia')
            ->distinct('grupos.materia')
            ->get();
        $admin=HorarioAdministrativo::where('periodo',$request->get('periodo'))
            ->where('docente',$request->get('docente'))
            ->join('puestos','horarios_administrativos.descripcion_horario','=','puestos.clave_puesto')
            ->distinct('consecutivo_admvo')
            ->select('consecutivo_admvo','descripcion_puesto')
            ->get();
        $apoyo=ApoyoDocencia::where('periodo',$request->get('periodo'))
            ->where('docente',$request->get('docente'))
            ->join('actividades_apoyo','apoyo_docencia.actividad','=','actividades_apoyo.actividad')
            ->distinct('consecutivo')
            ->get();
        $periodo=$request->get('periodo');
        $docente=$request->get('docente');
        $encabezado="Horario del personal docente";
        return view('academicos.horario')
            ->with(compact('docente','periodo','info',
                'nperiodo','admin','apoyo','encabezado'));
    }
    public function otroshorariosaccion(Request $request){
        $periodo=$request->get('periodo');
        $accion=$request->get('accion');
        $docente=$request->get('docente');
        $personal=Personal::where('id',$docente)
            ->select('apellidos_empleado','nombre_empleado')
            ->first();
        $puestos=Puesto::get();
        $nperiodo=PeriodoEscolar::where('periodo',$periodo)
            ->select('identificacion_larga')
            ->first();
        $info=Grupo::where('periodo',$periodo)
            ->where('docente',$docente)
            ->whereNull('paralelo_de')
            ->join('materias_carreras as mc','mc.materia','=','grupos.materia')
            ->join('materias','mc.materia','=','materias.materia')
            ->select('grupos.materia','grupo','nombre_abreviado_materia')
            ->distinct('grupos.materia')
            ->get();
        $admin=HorarioAdministrativo::where('periodo',$periodo)
            ->where('docente',$docente)
            ->join('puestos','horarios_administrativos.descripcion_horario','=','puestos.clave_puesto')
            ->distinct('consecutivo_admvo')
            ->select('consecutivo_admvo','descripcion_puesto')
            ->get();
        $apoyo=ApoyoDocencia::where('periodo',$periodo)
            ->where('docente',$docente)
            ->join('actividades_apoyo','apoyo_docencia.actividad','=','actividades_apoyo.actividad')
            ->distinct('consecutivo')
            ->get();
        if($accion==1){
            $encabezado="Alta acción administrativa para docentes";
            return view('academicos.alta_hadmvo')
                ->with(compact('periodo','puestos',
                    'docente','info','nperiodo','admin','encabezado'));
        }elseif($accion==2){
            $encabezado="Modificación de horario administrativo para docentes";
            return view('academicos.modificar_hadmvo')
                ->with(compact('periodo','puestos','docente','nperiodo',
                    'admin','encabezado'));
        }/*elseif($accion==3){
            $apoyos=ActividadesApoyo::get();
            return view('acad.alta_apoyo')->with(compact('periodo','puestos','rfc','nperiodo','admin','apoyos','info'));
        }elseif($accion==4){
            return view('acad.modificar_hapoyo')->with(compact('periodo','rfc','nperiodo','apoyo'));
        }elseif ($accion==5){
            return view('acad.alta_obs')->with(compact('periodo','rfc','personal'));
        }elseif ($accion==6){
            if(DB::table('horario_observaciones')->where('periodo',$periodo)->where('rfc',$rfc)->count()>0){
                $obs=DB::table('horario_observaciones')->where('periodo',$periodo)->where('rfc',$rfc)
                    ->select('observaciones')->first();
                return view('acad.modificar_obs')->with(compact('periodo','rfc','personal','obs'));
            }else{
                $mensaje="El docente no cuenta con observaciones en el horario, por lo que no es posible modificar nada";
                return view('acad.no')->with(compact('mensaje'));
            }
        }*/
    }
    public function procesaadmvoalta(Request $request){
        request()->validate([
            'slunes'=>'required_with:elunes',
            'smartes'=>'required_with:emartes',
            'smiercoles'=>'required_with:emiercoles',
            'sjueves'=>'required_with:ejueves',
            'sviernes'=>'required_with:eviernes',
            'ssabado'=>'required_with:esabado',
        ],[
            'slunes.required_with'=>'Debe indicar la hora de salida para el lunes',
            'smartes.required_with'=>'Debe indicar la hora de salida para el martes',
            'smiercoles.required_with'=>'Debe indicar la hora de salida para el miércoles',
            'sjueves.required_with'=>'Debe indicar la hora de salida para el jueves',
            'sviernes.required_with'=>'Debe indicar la hora de salida para el viernes',
            'ssabado.required_with'=>'Debe indicar la hora de salida para el sabado',
        ]);
        $periodo=$request->get('periodo');
        $actividad=$request->get('puesto');
        $docente=$request->get('docente');
        $lun=$request->get('hl');
        $mar=$request->get('hm');
        $mie=$request->get('hmm');
        $jue=$request->get('hj');
        $vie=$request->get('hv');
        $sab=$request->get('hs');
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

        if(!empty($elunes)){$hl=$elunes->diff($slunes)->format('%h');}else{$hl=0;}
        if(!empty($emartes)){$hm=$emartes->diff($smartes)->format('%h');}else{$hm=0;}
        if(!empty($emiercoles)){$hmm=$emiercoles->diff($smiercoles)->format('%h');}else{$hmm=0;}
        if(!empty($ejueves)){$hj=$ejueves->diff($sjueves)->format('%h');}else{$hj=0;}
        if(!empty($eviernes)){$hv=$eviernes->diff($sviernes)->format('%h');}else{$hv=0;}
        if(!empty($esabado)){$hs=$esabado->diff($ssabado)->format('%h');}else{$hs=0;}

        //Primero, que no sobrepase las 8 hrs al dia
        $encabezado="Error de alta de horario administrativo";
        if($hl+$lun>8){
            $mensaje="No fue posible procesar el horario ya que el lunes sobrepasa las 8 horas al día";
            return view('academicos.no')->with(compact('mensaje','encabezado'));
        }
        if($hm+$mar>8){
            $mensaje="No fue posible procesar el horario ya que el martes sobrepasa las 8 horas al día";
            return view('academicos.no')->with(compact('mensaje','encabezado'));
        }
        if($hmm+$mie>8){
            $mensaje="No fue posible procesar el horario ya que el miércoles sobrepasa las 8 horas al día";
            return view('academicos.no')->with(compact('mensaje','encabezado'));
        }
        if($hj+$jue>8){
            $mensaje="No fue posible procesar el horario ya que el jueves sobrepasa las 8 horas al día";
            return view('academicos.no')->with(compact('mensaje','encabezado'));
        }
        if($hv+$vie>8){
            $mensaje="No fue posible procesar el horario ya que el viernes sobrepasa las 8 horas al día";
            return view('academicos.no')->with(compact('mensaje','encabezado'));
        }
        if($hs+$sab>8){
            $mensaje="No fue posible procesar el horario ya que el sábado sobrepasa las 8 horas al día";
            return view('academicos.no')->with(compact('mensaje','encabezado'));
        }

        //Sólo por si acaso, las horas no pueden ser negativas
        if(($hl<0)||($hm<0)||($hmm<0)||($hj<0)||($hv<0)||($hs<0)){
            $mensaje="La hora de salida no puede ser mayor a la de entrada";
            return view('academicos.no')->with(compact('mensaje','encabezado'));
        }
        //Que no exista cruce
        $cant=HorarioAdministrativo::where('periodo',$periodo)
            ->where('docente',$docente)->count();
        HorarioAdministrativo::insert([
            'periodo'=>$periodo,
            'docente'=>$docente,
            'consecutivo_admvo'=>$cant+1,
            'descripcion_horario'=>$actividad
        ]);
        if(!empty($elunes)){
            try{
                Horario::insert([
                    'periodo'=>$periodo,
                    'docente'=>$docente,
                    'tipo_horario'=>'A',
                    'dia_semana'=>2,
                    'hora_inicial'=>$elunes,
                    'hora_final'=>$slunes,
                    'materia'=>null,
                    'grupo'=>null,
                    'aula'=>null,
                    'actividad'=>null,
                    'consecutivo'=>null,
                    'vigencia_inicio'=>null,
                    'vigencia_fin'=>null,
                    'consecutivo_admvo'=>$cant+1,
                    'tipo_personal'=>null
                ]);
            }catch (QueryException){
                $mensaje="La persona se encuentra ocupada en ese horario el lunes";
                return view('academicos.no')->with(compact('mensaje','encabezado'));
            }
        }
        if(!empty($emartes)){
            try{
                Horario::insert([
                    'periodo'=>$periodo,
                    'docente'=>$docente,
                    'tipo_horario'=>'A',
                    'dia_semana'=>3,
                    'hora_inicial'=>$emartes,
                    'hora_final'=>$smartes,
                    'materia'=>null,
                    'grupo'=>null,
                    'aula'=>null,
                    'actividad'=>null,
                    'consecutivo'=>null,
                    'vigencia_inicio'=>null,
                    'vigencia_fin'=>null,
                    'consecutivo_admvo'=>$cant+1,
                    'tipo_personal'=>null
                ]);
            }catch (QueryException){
                $mensaje="La persona se encuentra ocupada en ese horario el martes";
                return view('academicos.no')->with(compact('mensaje','encabezado'));
            }
        }
        if(!empty($emiercoles)){
            try{
                Horario::insert([
                    'periodo'=>$periodo,
                    'docente'=>$docente,
                    'tipo_horario'=>'A',
                    'dia_semana'=>4,
                    'hora_inicial'=>$emiercoles,
                    'hora_final'=>$smiercoles,
                    'materia'=>null,
                    'grupo'=>null,
                    'aula'=>null,
                    'actividad'=>null,
                    'consecutivo'=>null,
                    'vigencia_inicio'=>null,
                    'vigencia_fin'=>null,
                    'consecutivo_admvo'=>$cant+1,
                    'tipo_personal'=>null
                ]);
            }catch (QueryException){
                $mensaje="La persona se encuentra ocupada en ese horario el miércoles";
                return view('academicos.no')->with(compact('mensaje','encabezado'));
            }
        }
        if(!empty($ejueves)){
            try{
                Horario::insert([
                    'periodo'=>$periodo,
                    'docente'=>$docente,
                    'tipo_horario'=>'A',
                    'dia_semana'=>5,
                    'hora_inicial'=>$ejueves,
                    'hora_final'=>$sjueves,
                    'materia'=>null,
                    'grupo'=>null,
                    'aula'=>null,
                    'actividad'=>null,
                    'consecutivo'=>null,
                    'vigencia_inicio'=>null,
                    'vigencia_fin'=>null,
                    'consecutivo_admvo'=>$cant+1,
                    'tipo_personal'=>null
                ]);
            }catch (QueryException){
                $mensaje="La persona se encuentra ocupada en ese horario el jueves";
                return view('academicos.no')->with(compact('mensaje','encabezado'));
            }
        }
        if(!empty($eviernes)){
            try{
                Horario::insert([
                    'periodo'=>$periodo,
                    'docente'=>$docente,
                    'tipo_horario'=>'A',
                    'dia_semana'=>6,
                    'hora_inicial'=>$eviernes,
                    'hora_final'=>$sviernes,
                    'materia'=>null,
                    'grupo'=>null,
                    'aula'=>null,
                    'actividad'=>null,
                    'consecutivo'=>null,
                    'vigencia_inicio'=>null,
                    'vigencia_fin'=>null,
                    'consecutivo_admvo'=>$cant+1,
                    'tipo_personal'=>null
                ]);
            }catch (QueryException){
                $mensaje="La persona se encuentra ocupada en ese horario el viernes";
                return view('academicos.no')->with(compact('mensaje','encabezado'));
            }
        }
        if(!empty($esabado)){
            try{
                Horario::insert([
                    'periodo'=>$periodo,
                    'docente'=>$docente,
                    'tipo_horario'=>'A',
                    'dia_semana'=>7,
                    'hora_inicial'=>$esabado,
                    'hora_final'=>$ssabado,
                    'materia'=>null,
                    'grupo'=>null,
                    'aula'=>null,
                    'actividad'=>null,
                    'consecutivo'=>null,
                    'vigencia_inicio'=>null,
                    'vigencia_fin'=>null,
                    'consecutivo_admvo'=>$cant+1,
                    'tipo_personal'=>null,
                    'created_at'=>Carbon::now()
                ]);
            }catch (QueryException){
                $mensaje="La persona se encuentra ocupada en ese horario el sábado";
                return view('academicos.no')->with(compact('mensaje','encabezado'));
            }
        }
        $encabezado="Alta de horario administrativo";
        $mensaje="Se realizó el proceso con éxito";
        return view('academicos.si')->with(compact('mensaje','encabezado'));
    }
    public function modificaadmvo($periodo,$docente,$consecutivo){
        $consecutivo=(int)$consecutivo;
        $puestos=Puesto::get();
        $puesto=HorarioAdministrativo::where('periodo',$periodo)
            ->where('docente',$docente)
            ->where('consecutivo_admvo',$consecutivo)
            ->select('descripcion_horario')
            ->first();
        $info=Grupo::where('periodo',$periodo)
            ->where('docente',$docente)
            ->whereNull('paralelo_de')
            ->join('materias_carreras as mc','mc.materia','=','grupos.materia')
            ->join('materias','mc.materia','=','materias.materia')
            ->select('grupos.materia','grupo','nombre_abreviado_materia')
            ->distinct('grupos.materia')
            ->get();
        $nperiodo=PeriodoEscolar::where('periodo',$periodo)->first();
        $encabezado="Actualización de horario administrativo docente";
        return view('academicos.mod_hadmvo')
            ->with(compact('periodo','docente','consecutivo',
                'puestos','puesto','info','nperiodo','encabezado'));
    }
    public function eliminaadmvo($periodo,$docente,$consecutivo){
        HorarioAdministrativo::where('periodo',$periodo)
            ->where('docente',$docente)
            ->where('consecutivo_admvo',$consecutivo)
            ->delete();
        Horario::where('periodo',$periodo)
            ->where('docente',$docente)
            ->where('tipo_horario','A')
            ->where('consecutivo_admvo',$consecutivo)
            ->delete();
        $encabezado="Horario administrativo docente";
        $mensaje="Se eliminó la actividad administrativa que tenía asignada";
        return view('academicos.si')->with(compact('encabezado','mensaje'));
    }
    public function procesoadmvoupdate(Request $request){
        request()->validate([
            'slunes'=>'required_with:elunes',
            'smartes'=>'required_with:emartes',
            'smiercoles'=>'required_with:emiercoles',
            'sjueves'=>'required_with:ejueves',
            'sviernes'=>'required_with:eviernes',
            'ssabado'=>'required_with:esabado',
        ],[
            'slunes.required_with'=>'Debe indicar la hora de salida para el lunes',
            'smartes.required_with'=>'Debe indicar la hora de salida para el martes',
            'smiercoles.required_with'=>'Debe indicar la hora de salida para el miércoles',
            'sjueves.required_with'=>'Debe indicar la hora de salida para el jueves',
            'sviernes.required_with'=>'Debe indicar la hora de salida para el viernes',
            'ssabado.required_with'=>'Debe indicar la hora de salida para el sabado',
        ]);
        $periodo=$request->get('periodo');
        $actividad=$request->get('puesto');
        $docente=$request->get('docente');
        $cant=$request->get('consecutivo');
        $lun=$request->get('hl');
        $mar=$request->get('hm');
        $mie=$request->get('hmm');
        $jue=$request->get('hj');
        $vie=$request->get('hv');
        $sab=$request->get('hs');
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

        if(!empty($elunes)){$hl=$elunes->diff($slunes)->format('%h');}else{$hl=0;}
        if(!empty($emartes)){$hm=$emartes->diff($smartes)->format('%h');}else{$hm=0;}
        if(!empty($emiercoles)){$hmm=$emiercoles->diff($smiercoles)->format('%h');}else{$hmm=0;}
        if(!empty($ejueves)){$hj=$ejueves->diff($sjueves)->format('%h');}else{$hj=0;}
        if(!empty($eviernes)){$hv=$eviernes->diff($sviernes)->format('%h');}else{$hv=0;}
        if(!empty($esabado)){$hs=$esabado->diff($ssabado)->format('%h');}else{$hs=0;}

        //Primero, que no sobrepase las 8 hrs al dia
        $encabezado="Error en la modificación de horario administrativo del personal docente";
        if($hl+$lun>8){
            $mensaje="No fue posible procesar el horario ya que el lunes sobrepasa las 8 horas al día";
            return view('academicos.no')->with(compact('mensaje','encabezado'));
        }
        if($hm+$mar>8){
            $mensaje="No fue posible procesar el horario ya que el martes sobrepasa las 8 horas al día";
            return view('academicos.no')->with(compact('mensaje','encabezado'));
        }
        if($hmm+$mie>8){
            $mensaje="No fue posible procesar el horario ya que el miércoles sobrepasa las 8 horas al día";
            return view('academicos.no')->with(compact('mensaje','encabezado'));
        }
        if($hj+$jue>8){
            $mensaje="No fue posible procesar el horario ya que el jueves sobrepasa las 8 horas al día";
            return view('academicos.no')->with(compact('mensaje','encabezado'));
        }
        if($hv+$vie>8){
            $mensaje="No fue posible procesar el horario ya que el viernes sobrepasa las 8 horas al día";
            return view('academicos.no')->with(compact('mensaje','encabezado'));
        }
        if($hs+$sab>8){
            $mensaje="No fue posible procesar el horario ya que el sábado sobrepasa las 8 horas al día";
            return view('academicos.no')->with(compact('mensaje','encabezado'));
        }
        //Solo por si acaso, las horas no pueden ser negativas
        if(($hl<0)||($hm<0)||($hmm<0)||($hj<0)||($hv<0)||($hs<0)){
            $mensaje="La hora de salida no puede ser mayor a la de entrada";
            return view('academicos.no')->with(compact('mensaje','encabezado'));
        }
        HorarioAdministrativo::where('periodo',$periodo)
            ->where('docente',$docente)
            ->where('consecutivo_admvo',$cant)->update([
                'descripcion_horario'=>$actividad
            ]);
        //Que no exista cruce
        if(!empty($elunes)){
            try{
               Horario::where('periodo',$periodo)
                   ->where('docente',$docente)
                   ->where('tipo_horario','A')
                   ->where('consecutivo_admvo',$cant)
                   ->where('dia_semana',2)
                   ->update([
                        'hora_inicial'=>$elunes,
                        'hora_final'=>$slunes
                   ]);
            }catch (QueryException){
                $mensaje="La persona se encuentra ocupada en ese horario el lunes";
                return view('academicos.no')->with(compact('mensaje','encabezado'));
            }
        }
        if(!empty($emartes)){
            try{
                Horario::where('periodo',$periodo)
                    ->where('docente',$docente)
                    ->where('tipo_horario','A')
                    ->where('consecutivo_admvo',$cant)
                    ->where('dia_semana',3)
                    ->update([
                        'hora_inicial'=>$emartes,
                        'hora_final'=>$smartes
                    ]);
            }catch (QueryException){
                $mensaje="La persona se encuentra ocupada en ese horario el martes";
                return view('academicos.no')->with(compact('mensaje','encabezado'));
            }
        }
        if(!empty($emiercoles)){
            try{
                Horario::where('periodo',$periodo)
                    ->where('docente',$docente)
                    ->where('tipo_horario','A')
                    ->where('consecutivo_admvo',$cant)
                    ->where('dia_semana',4)
                    ->update([
                        'hora_inicial'=>$emiercoles,
                        'hora_final'=>$smiercoles
                    ]);
            }catch (QueryException){
                $mensaje="La persona se encuentra ocupada en ese horario el miércoles";
                return view('academicos.no')->with(compact('mensaje','encabezado'));
            }
        }
        if(!empty($ejueves)){
            try{
                Horario::where('periodo',$periodo)
                    ->where('docente',$docente)
                    ->where('tipo_horario','A')
                    ->where('consecutivo_admvo',$cant)
                    ->where('dia_semana',5)
                    ->update([
                        'hora_inicial'=>$ejueves,
                        'hora_final'=>$sjueves
                    ]);
            }catch (QueryException){
                $mensaje="La persona se encuentra ocupada en ese horario el jueves";
                return view('academicos.no')->with(compact('mensaje','encabezado'));
            }
        }
        if(!empty($eviernes)){
            try{
                Horario::where('periodo',$periodo)
                    ->where('docente',$docente)
                    ->where('tipo_horario','A')
                    ->where('consecutivo_admvo',$cant)
                    ->where('dia_semana',6)
                    ->update([
                        'hora_inicial'=>$eviernes,
                        'hora_final'=>$sviernes
                    ]);
            }catch (QueryException){
                $mensaje="La persona se encuentra ocupada en ese horario el viernes";
                return view('academicos.no')->with(compact('mensaje','encabezado'));
            }
        }
        if(!empty($esabado)){
            try{
                Horario::where('periodo',$periodo)
                    ->where('docente',$docente)
                    ->where('tipo_horario','A')
                    ->where('consecutivo_admvo',$cant)
                    ->where('dia_semana',7)
                    ->update([
                        'hora_inicial'=>$esabado,
                        'hora_final'=>$ssabado
                    ]);
            }catch (QueryException){
                $mensaje="La persona se encuentra ocupada en ese horario el sábado";
                return view('academicos.no')->with(compact('mensaje','encabezado'));
            }
        }
        $encabezado="Actualización de horario administrativo";
        $mensaje="Se modificó con éxito el horario administrativo del docente";
        return view('academicos.si')->with(compact('mensaje','encabezado'));
    }
}
