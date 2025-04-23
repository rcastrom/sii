<?php

namespace App\Http\Controllers\Desarrollo;

use App\Models\Aula;
use App\Models\GrupoPropedeutico;
use App\Models\PeriodoEscolar;
use App\Models\PeriodoFicha;
use App\Models\InscripcionPropedeutico;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MenuDesarrolloController;
use App\Models\Personal;
use Carbon\Carbon;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Http\Request;

class PropedeuticoController extends Controller
{
    public function __construct(Dispatcher $events)
    {
        new MenuDesarrolloController($events);
    }

    public function grupos(){
        $parametros=PeriodoFicha::where('activo',true)->first();
        $periodo=$parametros->fichas;
        $nombre_periodo = $this->getPeriodo($periodo);
        $encabezado="Alta de grupos de propedéutico";
        $bandera=GrupoPropedeutico::where('periodo',$periodo)->count();
        if($bandera>0){
            $grupos=GrupoPropedeutico::where('periodo',$periodo)
                ->leftJoin('aulas','aulas.id','=','grupos_propedeuticos.aula_id')
                ->leftJoin('personal','personal.id','=','grupos_propedeuticos.docente')
                ->select(['grupos_propedeuticos.*','aulas.aula',
                    'personal.nombre_empleado','personal.apellidos_empleado'])
                ->get();
        }else{
            $grupos=null;
        }
        return view('desarrollo.propedeutico_grupos1')
            ->with(compact('nombre_periodo',
                'encabezado','bandera','grupos','periodo'));
    }
    public function alta_grupo(Request $request)
    {
        request()->validate(
            [
                'materia'=>'required',
                'nombre_corto'=>'required',
                'grupo'=>'required',
            ],
            [
                'materia.required'=>'Debe proporcionarle un nombre a la materia',
                'nombre_corto.required'=>'Debe proporcionarle un nombre corto a la materia',
                'grupo.required'=>'Debe proporcionarle un grupo a la materia',
            ]
        );
        $periodo=$request->get('periodo');
        $grupo = new GrupoPropedeutico;
        $grupo->periodo=$periodo;
        $grupo->materia=$request->get('materia');
        $grupo->nombre_corto=$request->get('nombre_corto');
        $grupo->grupo=$request->get('grupo');
        $grupo->save();
        $encabezado="Alta de grupo propedéutico";
        $mensaje="El grupo ha sido creado correctamente";
        return view('desarrollo.si')->with(compact('encabezado','mensaje'));
    }

    public function informe_grupo($id,$periodo)
    {
        $grupo=GrupoPropedeutico::where('id','=',$id)->first();
        $nombre_periodo=$this->getPeriodo($periodo);
        $encabezado="Edición de grupo propedéutico del periodo $nombre_periodo";
        return view('desarrollo.propedeutico_grupos2')
            ->with(compact('grupo','encabezado','periodo'));
    }
    public function aula_grupo($id,$periodo)
    {
        $grupo=GrupoPropedeutico::where('id','=',$id)->first();
        $aulas=Aula::where('estatus',1)->get();
        $nombre_periodo=$this->getPeriodo($periodo);
        $encabezado="Asignación de aula en el período $nombre_periodo";
        if(GrupoPropedeutico::where('periodo',$periodo)
        ->select('aula_id')
        ->count()>0)
        {
            $bandera=1;
            $salones=GrupoPropedeutico::where('periodo','=',$periodo)
                ->leftJoin('aulas','aulas.id','=','grupos_propedeuticos.aula_id')
                ->select('grupos_propedeuticos.*','aulas.aula')
                ->get();
        }else{
            $bandera=0;
            $salones=null;
        }
        return view('desarrollo.propedeutico_grupos_aulas')
            ->with(compact('grupo','encabezado','bandera'
                ,'periodo','aulas','salones'));
    }
    public function docente_grupo($id,$periodo)
    {
        $grupo=GrupoPropedeutico::where('id','=',$id)->first();
        $nombre_periodo=$this->getPeriodo($periodo);
        $maestros=Personal::where('status_empleado','=',2)
            ->whereIn('nombramiento' ,['D','X'])
            ->select(['id','nombre_empleado','apellidos_empleado'])
            ->orderBy('apellidos_empleado','ASC')
            ->orderBy('nombre_empleado','ASC')
            ->get();
        $encabezado="Asignación de aula en el período $nombre_periodo";
        return view('desarrollo.propedeutico_grupos_aulas2')
            ->with(compact('grupo','encabezado','periodo','maestros'));
    }

    public function asignar_aula_propedeutico(Request $request)
    {
        $grupo=$request->get('id');
        GrupoPropedeutico::where('id','=',$grupo)->update([
            'aula_id'=>$request->get('aula')
        ]);
        $encabezado="Aula para grupo propedéutico";
        $mensaje="La asignación del aula para el grupo propedéutico se ha realizado de forma correcta";
        return view('desarrollo.si')->with(compact('encabezado','mensaje'));
    }

    public function asignar_maestro_propedeutico(Request $request)
    {
        $grupo=$request->get('id');
        GrupoPropedeutico::where('id','=',$grupo)->update([
            'docente'=>$request->get('docente')
        ]);
        $encabezado="Docente para grupo propedéutico";
        $mensaje="La asignación del maestro para el grupo propedéutico se ha realizado de forma correcta";
        return view('desarrollo.si')->with(compact('encabezado','mensaje'));
    }
    public function grupos_editar(Request $request)
    {
        request()->validate(
            [
                'materia'=>'required',
                'nombre_corto'=>'required',
                'grupo'=>'required',
            ],
            [
                'materia.required'=>'Debe proporcionarle un nombre a la materia',
                'nombre_corto.required'=>'Debe proporcionarle un nombre corto a la materia',
                'grupo.required'=>'Debe proporcionarle un grupo a la materia',
            ]
        );

        $entradas=array('entrada_1','entrada_2','entrada_3','entrada_4','entrada_5');
        foreach ($entradas as $key){
            if(!empty($request->get($key))){
                $$key=Carbon::parse($request->get($key));
            }else{
                $$key=NULL;
            }
        }
        $salidas=array('salida_1','salida_2','salida_3','salida_4','salida_5');
        foreach ($salidas as $key){
            if(!empty($request[$key])){
                $$key=Carbon::parse($request[$key]);
            }else{
                $$key=NULL;
            }
        }
        try{
            GrupoPropedeutico::where('id','=',$request->get('id'))->update([
                'materia'=>$request->get('materia'),
                'nombre_corto'=>$request->get('nombre_corto'),
                'grupo'=>$request->get('grupo'),
                'entrada_1'=> is_null($entrada_1) ? NULL : $entrada_1->format('H:i') ,
                'entrada_2'=>is_null($entrada_2) ? NULL : $entrada_2->format('H:i'),
                'entrada_3'=>is_null($entrada_3) ? NULL : $entrada_3->format('H:i'),
                'entrada_4'=>is_null($entrada_4) ? NULL : $entrada_4->format('H:i'),
                'entrada_5'=>is_null($entrada_5) ? NULL : $entrada_5->format('H:i'),
                'salida_1'=>is_null($salida_1) ? NULL : $salida_1->format('H:i'),
                'salida_2'=>is_null($salida_2) ? NULL : $salida_2->format('H:i'),
                'salida_3'=>is_null($salida_3) ? NULL : $salida_3->format('H:i'),
                'salida_4'=>is_null($salida_4) ? NULL : $salida_4->format('H:i'),
                'salida_5'=>is_null($salida_5) ? NULL : $salida_5->format('H:i'),
            ]);
            $encabezado="Grupo de propedéutico editado";
            $mensaje="La materia ".$request->get('materia')." del grupo ".$request->get('grupo')." ha sido editado correctamente";
            return view('desarrollo.si')->with(compact('encabezado','mensaje'));
        }catch (\Exception $e){
            dd($e);
        }
    }

    public function grupo_eliminar($id,$periodo)
    {
        $grupo=GrupoPropedeutico::where('id',$id)->first();
        $nombre_periodo=$this->getPeriodo($periodo);
        $encabezado="Baja de grupo propedéutico del periodo $nombre_periodo";
        return view('desarrollo.propedeutico_grupos3')
            ->with(compact('grupo','encabezado','periodo'));
    }

    public function eliminar_grupo(Request $request)
    {
        $id=$request->get('id');
        $periodo=$request->get('periodo');
        if(InscripcionPropedeutico::where(
            [
                'grupo_id'=>$id,
                'periodo'=>$periodo,
            ])->count()>0){
            InscripcionPropedeutico::where(
                [
                    'grupo_id'=>$id,
                    'periodo'=>$periodo,
                ])->delete();
        }
        GrupoPropedeutico::where('id','=',$id)->delete();
        $encabezado="Grupo de propedéutico eliminado";
        $mensaje="Se ha eliminado a la materia dentro del registro de grupos";
        return view('desarrollo.si')->with(compact('encabezado','mensaje'));
    }



    /**
     * @param $periodo
     * @return mixed|string
     */
    public function getPeriodo($periodo): mixed
    {
        $datos_periodo = PeriodoEscolar::where('periodo', $periodo)->first();
        $nombre_periodo = $datos_periodo->identificacion_larga;
        return $nombre_periodo;
    }
}
