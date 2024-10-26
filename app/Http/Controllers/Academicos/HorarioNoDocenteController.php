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
use Carbon\CarbonInterval;
use Carbon\CarbonPeriod;
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

    public function calculatePeriodsOverlap(CarbonPeriod $periodA, CarbonPeriod $periodB): CarbonInterval
    {
        if (!$periodA->overlaps($periodB)) {
            return new CarbonInterval(0);
        }

        $firstEndDate = min($periodA->calculateEnd(), $periodB->calculateEnd());
        $latestStartDate = max($periodA->getStartDate(), $periodB->getStartDate());

        return CarbonInterval::make($firstEndDate->diff($latestStartDate));
    }
    public function cruce($periodo,$personal,$dia,$hora_entrada,$hora_salida)
    {
        $bandera=0;
        $horas=Horario::where('periodo',$periodo)
            ->where('docente',$personal)
            ->where('dia_semana',$dia)
            ->select(['hora_inicial','hora_final'])
            ->get();
        foreach($horas as $hora){
            $periodo1=new CarbonPeriod($hora->hora_inicial,$hora->hora_final);
            $periodo2=new CarbonPeriod($hora_entrada,$hora_salida);
            $dif=$this->calculatePeriodsOverlap($periodo1,$periodo2);
            if($dif->h>=1){
                $bandera+=1;
            }
        }
        return $bandera;
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
        $entradas=array('elunes','emartes','emiercoles','ejueves','eviernes','esabado');
        foreach ($entradas as $key){
            if(!empty($request->get($key))){
                $$key=Carbon::parse($request->get($key));
            }else{
                $$key=NULL;
            }
        }
        $salidas=array('slunes','smartes','smiercoles','sjueves','sviernes','ssabado');
        foreach ($salidas as $key){
            if(!empty($request[$key])){
                $$key=Carbon::parse($request[$key]);
            }else{
                $$key=NULL;
            }
        }

        $hl=!is_null($elunes)?$elunes->diff($slunes)->format('%h'):0;
        $hm=!is_null($emartes)?$emartes->diff($smartes)->format('%h'):0;
        $hmm=!is_null($emiercoles)?$emiercoles->diff($smiercoles)->format('%h'):0;
        $hj=!is_null($ejueves)?$ejueves->diff($sjueves)->format('%h'):0;
        $hv=!is_null($eviernes)?$eviernes->diff($sviernes)->format('%h'):0;
        $hs=!is_null($esabado)?$esabado->diff($ssabado)->format('%h'):0;

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
        //Que no exista cruce
        if($hl){
            $cruce=$this->cruce($periodo,$personal,2,$elunes,$slunes);
            if($cruce>0){
                $mensaje="Existe un empalme de horario para el día lunes con alguna otra actividad";
                return view('academicos.no')->with(compact('mensaje','encabezado'));
            }
        }
        if($hm){
            $cruce=$this->cruce($periodo,$personal,3,$emartes,$smartes);
            if($cruce>0){
                $mensaje="Existe un empalme de horario para el día martes con alguna otra actividad";
                return view('academicos.no')->with(compact('mensaje','encabezado'));
            }
        }
        if($hmm){
            $cruce=$this->cruce($periodo,$personal,4,$emiercoles,$smiercoles);
            if($cruce>0){
                $mensaje="Existe un empalme de horario para el día miércoles con alguna otra actividad";
                return view('academicos.no')->with(compact('mensaje','encabezado'));
            }
        }
        if($hj){
            $cruce=$this->cruce($periodo,$personal,5,$ejueves,$sjueves);
            if($cruce>0){
                $mensaje="Existe un empalme de horario para el día jueves con alguna otra actividad";
                return view('academicos.no')->with(compact('mensaje','encabezado'));
            }
        }
        if($hv){
            $cruce=$this->cruce($periodo,$personal,6,$eviernes,$sviernes);
            if($cruce>0){
                $mensaje="Existe un empalme de horario para el día viernes con alguna otra actividad";
                return view('academicos.no')->with(compact('mensaje','encabezado'));
            }
        }
        if($hs){
            $cruce=$this->cruce($periodo,$personal,7,$esabado,$ssabado);
            if($cruce>0){
                $mensaje="Existe un empalme de horario para el día sábado con alguna otra actividad";
                return view('academicos.no')->with(compact('mensaje','encabezado'));
            }
        }
        //

        $registro=HorarioNoDocente::create([
            'periodo'=>$periodo,
            'personal'=>$personal,
            'descripcion_horario'=>$actividad,
            'area_adscripcion'=>$area_adscripcion,
            'observacion'=>$observacion,
        ]);
        $cant=$registro->id;
        if(!is_null($elunes)){
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
        if(!is_null($emartes)){
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
        if(!is_null($emiercoles)){
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
        if(!is_null($ejueves)){
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
        if(!is_null($eviernes)){
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
        if(!is_null($esabado)){
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
        $entradas=array('elunes','emartes','emiercoles','ejueves','eviernes','esabado');
        foreach ($entradas as $key){
            if(!empty($request->get($key))){
                $$key=Carbon::parse($request->get($key));
            }else{
                $$key=NULL;
            }
        }
        $salidas=array('slunes','smartes','smiercoles','sjueves','sviernes','ssabado');
        foreach ($salidas as $key){
            if(!empty($request[$key])){
                $$key=Carbon::parse($request[$key]);
            }else{
                $$key=NULL;
            }
        }

        $hl=!is_null($elunes)?$elunes->diff($slunes)->format('%h'):0;
        $hm=!is_null($emartes)?$emartes->diff($smartes)->format('%h'):0;
        $hmm=!is_null($emiercoles)?$emiercoles->diff($smiercoles)->format('%h'):0;
        $hj=!is_null($ejueves)?$ejueves->diff($sjueves)->format('%h'):0;
        $hv=!is_null($eviernes)?$eviernes->diff($sviernes)->format('%h'):0;
        $hs=!is_null($esabado)?$esabado->diff($ssabado)->format('%h'):0;

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
        //Que no exista cruce
        if($hl){
            $cruce=$this->cruce($periodo,$personal,2,$elunes,$slunes);
            if($cruce>0){
                $mensaje="Existe un empalme de horario para el día lunes con alguna otra actividad";
                return view('academicos.no')->with(compact('mensaje','encabezado'));
            }
        }
        if($hm){
            $cruce=$this->cruce($periodo,$personal,3,$emartes,$smartes);
            if($cruce>0){
                $mensaje="Existe un empalme de horario para el día martes con alguna otra actividad";
                return view('academicos.no')->with(compact('mensaje','encabezado'));
            }
        }
        if($hmm){
            $cruce=$this->cruce($periodo,$personal,4,$emiercoles,$smiercoles);
            if($cruce>0){
                $mensaje="Existe un empalme de horario para el día miércoles con alguna otra actividad";
                return view('academicos.no')->with(compact('mensaje','encabezado'));
            }
        }
        if($hj){
            $cruce=$this->cruce($periodo,$personal,5,$ejueves,$sjueves);
            if($cruce>0){
                $mensaje="Existe un empalme de horario para el día jueves con alguna otra actividad";
                return view('academicos.no')->with(compact('mensaje','encabezado'));
            }
        }
        if($hv){
            $cruce=$this->cruce($periodo,$personal,6,$eviernes,$sviernes);
            if($cruce>0){
                $mensaje="Existe un empalme de horario para el día viernes con alguna otra actividad";
                return view('academicos.no')->with(compact('mensaje','encabezado'));
            }
        }
        if($hs){
            $cruce=$this->cruce($periodo,$personal,7,$esabado,$ssabado);
            if($cruce>0){
                $mensaje="Existe un empalme de horario para el día sábado con alguna otra actividad";
                return view('academicos.no')->with(compact('mensaje','encabezado'));
            }
        }
        //
        HorarioNoDocente::where('id',$nodocente->id)
            ->update([
                'descripcion_horario'=>$actividad,
                'area_adscripcion'=>$area_adscripcion,
                'observacion'=>$observacion,
            ]);
        if(!is_null($elunes)){
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
        if(!is_null($emartes)){
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
        if(!is_null($emiercoles)){
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
        if(!is_null($ejueves)){
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
        if(!is_null($eviernes)){
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
        if(!is_null($esabado)){
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
        $noDocente = $request->get('admin');
        $periodo = $request->get('periodo');
        if($request->get('accion')==1){
         if(HorarioNoDocente::where('periodo',$periodo)
         ->where('personal',$noDocente)->count()>0){
             $apoyos=HorarioNoDocente::where('periodo',$periodo)
                 ->where('personal',$noDocente)
                 ->join('puestos','horario_no_docentes.descripcion_horario','=','puestos.clave_puesto')
                 ->distinct()
                 ->get();
             $encabezado="Consulta de horario";
             return view('academicos.modificar_hnodocente')
                 ->with(compact('encabezado','periodo','noDocente','apoyos'));
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
                ->with(compact('noDocente','periodo','encabezado','puestos','areas'));
        }else{
            $encabezado="Impresión de horario no docente";
            $personal=Personal::where('id',$noDocente)
                ->first();
            $descripcion_area=Organigrama::where('clave_area',$personal->clave_area)
                ->first();
            return view('academicos.imprimir_horario')
                ->with(compact('encabezado',
                    'personal','descripcion_area','periodo'));
        }
    }
}
