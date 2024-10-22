<?php

namespace App\Http\Controllers\Academicos;

use App\Http\Controllers\Acciones\AccionesController;
use App\Http\Controllers\MenuAcademicosController;
use App\Http\Controllers\Controller;
use App\Models\Horario;
use App\Models\HorarioNoDocente;
use App\Models\Organigrama;
use App\Models\PeriodoEscolar;
use App\Models\Personal;
use App\Models\Puesto;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\View\View;


class HorarioNoDocenteController extends Controller
{
    public function __construct(Dispatcher $events)
    {
        new MenuAcademicosController($events);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $administrativos=Personal::whereIn('nombramiento',['A','X'])
            ->where('status_empleado','2')
            ->orderBy('apellidos_empleado','ASC')
            ->orderBy('nombre_empleado','ASC')->get();
        $periodos=PeriodoEscolar::orderBy('periodo','DESC')->get();
        $periodo_actual=(new AccionesController)->periodo();
        $periodo=$periodo_actual[0]->periodo;
        $encabezado="Asignación de horario para personal administrativo / docente";
        return view('academicos.administrativos')
            ->with(compact('administrativos',
                'periodo','periodos','encabezado'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /*
     * Borrado de horario administrativo
     */
    public function borrado($periodo, $personal,$tipo,$consecutivo)
    {
        Horario::where('periodo',$periodo)
            ->where('docente',$personal)
            ->where('tipo_horario',$tipo)
            ->where('consecutivo_admvo',$consecutivo)
            ->delete();
        HorarioNoDocente::where('id',$consecutivo)->delete();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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
        $personal=$request->get('personal');
        $area_adscripcion=$request->get('unidad');
        $observacion= $request->get('observacion') !== null ?$request->get('observacion'):NULL;
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

        //Primero, que no sobrepase las 8 h al día
        $encabezado="Error de alta de horario de personal no docente";
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
        //No sobrepase a las 36 horas
        $suma_total=$hl + $hm + $hmm + $hj + $hv + $hs;
        if($suma_total>36){
            $mensaje="La suma total de horas, no puede ser superior a 36 horas";
            return view('academicos.no')->with(compact('mensaje','encabezado'));
        }
        $registro=HorarioNoDocente::create([
            'periodo'=>$periodo,
            'personal'=>$personal,
            'descripcion_horario'=>$actividad,
            'area_adscripcion'=>$area_adscripcion,
            'observacion'=>$observacion,
        ]);
        $cant=$registro->id;
        if(!empty($elunes)){
            try{
                Horario::insert([
                    'periodo'=>$periodo,
                    'docente'=>$personal,
                    'tipo_horario'=>'Z',
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
                    'consecutivo_admvo'=>$cant,
                    'tipo_personal'=>null
                ]);
            }catch (QueryException){
                $this->borrado($periodo,$personal,'Z',$cant);
                $mensaje="La persona se encuentra ocupada en ese horario el lunes";
                return view('academicos.no')->with(compact('mensaje','encabezado'));
            }
        }
        if(!empty($emartes)){
            try{
                Horario::insert([
                    'periodo'=>$periodo,
                    'docente'=>$personal,
                    'tipo_horario'=>'Z',
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
                    'consecutivo_admvo'=>$cant,
                    'tipo_personal'=>null
                ]);
            }catch (QueryException){
                $this->borrado($periodo,$personal,'Z',$cant);
                $mensaje="La persona se encuentra ocupada en ese horario el martes";
                return view('academicos.no')->with(compact('mensaje','encabezado'));
            }
        }
        if(!empty($emiercoles)){
            try{
                Horario::insert([
                    'periodo'=>$periodo,
                    'docente'=>$personal,
                    'tipo_horario'=>'Z',
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
                    'consecutivo_admvo'=>$cant,
                    'tipo_personal'=>null
                ]);
            }catch (QueryException){
                $this->borrado($periodo,$personal,'Z',$cant);
                $mensaje="La persona se encuentra ocupada en ese horario el miércoles";
                return view('academicos.no')->with(compact('mensaje','encabezado'));
            }
        }
        if(!empty($ejueves)){
            try{
                Horario::insert([
                    'periodo'=>$periodo,
                    'docente'=>$personal,
                    'tipo_horario'=>'Z',
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
                    'consecutivo_admvo'=>$cant,
                    'tipo_personal'=>null
                ]);
            }catch (QueryException){
                $this->borrado($periodo,$personal,'Z',$cant);
                $mensaje="La persona se encuentra ocupada en ese horario el jueves";
                return view('academicos.no')->with(compact('mensaje','encabezado'));
            }
        }
        if(!empty($eviernes)){
            try{
                Horario::insert([
                    'periodo'=>$periodo,
                    'docente'=>$personal,
                    'tipo_horario'=>'Z',
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
                    'consecutivo_admvo'=>$cant,
                    'tipo_personal'=>null
                ]);
            }catch (QueryException){
                $this->borrado($periodo,$personal,'Z',$cant);
                $mensaje="La persona se encuentra ocupada en ese horario el viernes";
                return view('academicos.no')->with(compact('mensaje','encabezado'));
            }
        }
        if(!empty($esabado)){
            try{
                Horario::insert([
                    'periodo'=>$periodo,
                    'docente'=>$personal,
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
                    'consecutivo_admvo'=>$cant,
                    'tipo_personal'=>null,
                    'created_at'=>Carbon::now()
                ]);
            }catch (QueryException){
                $this->borrado($periodo,$personal,'Z',$cant);
                $mensaje="La persona se encuentra ocupada en ese horario el sábado";
                return view('academicos.no')->with(compact('mensaje','encabezado'));
            }
        }

        $encabezado="Proceso realizado";
        $mensaje="Horario registrado correctamente";
        return view('academicos.si')->with(compact('encabezado','mensaje'));
    }

    /**
     * Display the specified resource.
     */
    public function show(HorarioNoDocente $nodocente)
    {
        $puesto=Puesto::where('clave_puesto',$nodocente->descripcion_horario)
        ->select('descripcion_puesto')
        ->first();
        $area=Organigrama::where('clave_area',$nodocente->area_adscripcion)
            ->select('descripcion_area')
            ->first();
        $horarios=Horario::where('periodo',$nodocente->periodo)
            ->where('docente',$nodocente->personal)
            ->where('tipo_horario','Z')
            ->where('consecutivo_admvo',$nodocente->id)
            ->get();
        $encabezado="Eliminación de horario para personal no docente";
        return view('academicos.confirmar_borrado_hno_docente')
            ->with(compact('nodocente','encabezado','puesto','area','horarios'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HorarioNoDocente $nodocente)
    {
        $puestos=Puesto::get();
        $areas=Organigrama::select(['clave_area','descripcion_area'])
            ->orderBy('descripcion_area','ASC')
            ->get();
        $horarios=Horario::where('periodo',$nodocente->periodo)
            ->where('docente',$nodocente->personal)
            ->where('tipo_horario','Z')
            ->where('consecutivo_admvo',$nodocente->id)
            ->get();
        $encabezado="Actualización de horario para personal no docente";
        return view('academicos.mod_hno_docente')
            ->with(compact('nodocente','encabezado','puestos','areas','horarios'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HorarioNoDocente $nodocente)
    {
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
        $periodo=$nodocente->periodo;
        $actividad=$request->get('puesto');
        $personal=$nodocente->personal;
        $area_adscripcion=$request->get('unidad');
        $observacion= $request->get('observacion') !== null ?$request->get('observacion'):NULL;
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

        //Primero, que no sobrepase las 8 h al día
        $encabezado="Error de alta de horario de personal no docente";
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
        //No sobrepase a las 36 horas
        $suma_total=$hl + $hm + $hmm + $hj + $hv + $hs;
        if($suma_total>36){
            $mensaje="La suma total de horas, no puede ser superior a 36 horas";
            return view('academicos.no')->with(compact('mensaje','encabezado'));
        }
        HorarioNoDocente::where('id',$nodocente->id)
            ->update([
                'descripcion_horario'=>$actividad,
                'area_adscripcion'=>$area_adscripcion,
                'observacion'=>$observacion,
            ]);
        if(!empty($elunes)){
            try{
                Horario::where(
                    [
                        'periodo'=>$periodo,
                        'docente'=>$personal,
                        'tipo_horario'=>'Z',
                        'dia_semana'=>2,
                        'consecutivo_admvo'=>$nodocente->id
                    ]
                )->update(
                    [
                        'hora_inicial'=>$elunes,
                        'hora_final'=>$slunes,
                    ]
                );
            }catch (QueryException){
                $mensaje="La persona se encuentra ocupada en ese horario el lunes";
                return view('academicos.no')->with(compact('mensaje','encabezado'));
            }
        }
        if(!empty($emartes)){
            try{
                Horario::where(
                    [
                    'periodo'=>$periodo,
                    'docente'=>$personal,
                    'tipo_horario'=>'Z',
                    'dia_semana'=>3,
                    'consecutivo_admvo'=>$nodocente->id
                    ]
                )->update(
                    [
                        'hora_inicial'=>$emartes,
                        'hora_final'=>$smartes,
                    ]
                );
            }catch (QueryException){
                $mensaje="La persona se encuentra ocupada en ese horario el martes";
                return view('academicos.no')->with(compact('mensaje','encabezado'));
            }
        }
        if(!empty($emiercoles)){
            try{
                Horario::where(
                    [
                        'periodo'=>$periodo,
                        'docente'=>$personal,
                        'tipo_horario'=>'Z',
                        'dia_semana'=>4,
                        'consecutivo_admvo'=>$nodocente->id
                    ]
                )->update(
                    [
                        'hora_inicial'=>$emiercoles,
                        'hora_final'=>$smiercoles,
                    ]
                );
            }catch (QueryException){
                $mensaje="La persona se encuentra ocupada en ese horario el miércoles";
                return view('academicos.no')->with(compact('mensaje','encabezado'));
            }
        }
        if(!empty($ejueves)){
            try{
                Horario::where(
                    [
                        'periodo'=>$periodo,
                        'docente'=>$personal,
                        'tipo_horario'=>'Z',
                        'dia_semana'=>5,
                        'consecutivo_admvo'=>$nodocente->id
                    ]
                )->update(
                    [
                        'hora_inicial'=>$ejueves,
                        'hora_final'=>$sjueves,
                    ]
                );
            }catch (QueryException){
                $mensaje="La persona se encuentra ocupada en ese horario el jueves";
                return view('academicos.no')->with(compact('mensaje','encabezado'));
            }
        }
        if(!empty($eviernes)){
            try{
                Horario::where(
                    [
                        'periodo'=>$periodo,
                        'docente'=>$personal,
                        'tipo_horario'=>'Z',
                        'dia_semana'=>6,
                        'consecutivo_admvo'=>$nodocente->id
                    ]
                )->update(
                    [
                        'hora_inicial'=>$eviernes,
                        'hora_final'=>$sviernes,
                    ]
                );
            }catch (QueryException){
                $mensaje="La persona se encuentra ocupada en ese horario el viernes";
                return view('academicos.no')->with(compact('mensaje','encabezado'));
            }
        }
        if(!empty($esabado)){
            try{
                Horario::where(
                    [
                        'periodo'=>$periodo,
                        'docente'=>$personal,
                        'tipo_horario'=>'Z',
                        'dia_semana'=>7,
                        'consecutivo_admvo'=>$nodocente->id
                    ]
                )->update(
                    [
                        'hora_inicial'=>$esabado,
                        'hora_final'=>$ssabado,
                    ]
                );
            }catch (QueryException){
                $mensaje="La persona se encuentra ocupada en ese horario el sábado";
                return view('academicos.no')->with(compact('mensaje','encabezado'));
            }
        }
        $encabezado="Proceso realizado";
        $mensaje="Horario actualizado correctamente";
        return view('academicos.si')->with(compact('encabezado','mensaje'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HorarioNoDocente $nodocente)
    {
        Horario::where(
            [
                'periodo'=>$nodocente->periodo,
                'docente'=>$nodocente->personal,
                'tipo_horario'=>'Z',
                'consecutivo_admvo'=>$nodocente->id
            ]
        )->delete();
        HorarioNoDocente::destroy($nodocente->id);
        $encabezado="Proceso realizado";
        $mensaje="Horario no docente eliminado correctamente";
        return view('academicos.si')->with(compact('encabezado','mensaje'));
    }

    /**
     * Empleado para desplegar más información en la selección del horario
     */
    public function alta(Request $request): View
    {
        $personal = $request->get('admin');
        $periodo = $request->get('periodo');
        if($request->get('accion')==1){
         if(HorarioNoDocente::where('periodo',$periodo)
         ->where('personal',$personal)->count()>0){
             $apoyos=HorarioNoDocente::where('periodo',$periodo)
                 ->where('personal',$personal)
                 ->join('puestos','horario_no_docentes.descripcion_horario','=','puestos.clave_puesto')
                 ->distinct()
                 ->get();
             $encabezado="Consulta de horario";
             return view('academicos.modificar_hnodocente')
                 ->with(compact('encabezado','periodo','personal','apoyos'));
         }else{
             $encabezado="Error de consulta";
             $mensaje="El personal no cuenta con horario no docente asignado";
             return view('academicos.no')->with(compact('mensaje','encabezado'));
         }
        }elseif($request->get('accion')==2){
            $puestos=Puesto::get();
            $areas=Organigrama::select(['clave_area','descripcion_area'])
                ->orderBy('descripcion_area','ASC')
                ->get();
            $encabezado="Alta de horario para personal administrativo / docente";
            return view('academicos.alta_nodocente')
                ->with(compact('personal','periodo','encabezado','puestos','areas'));
        }else{
            $puestos=Puesto::get();
            $areas=Organigrama::select(['clave_area','descripcion_area'])
                ->orderBy('descripcion_area','ASC')
                ->get();
            $encabezado="Alta de horario para personal administrativo / docente";
            return view('academicos.alta_nodocente')
                ->with(compact('personal','periodo','encabezado','puestos','areas'));
        }
    }
}
