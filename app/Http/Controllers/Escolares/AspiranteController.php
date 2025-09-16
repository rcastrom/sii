<?php

namespace App\Http\Controllers\Escolares;

use App\Http\Controllers\Acciones\AccionesController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MenuEscolaresController;
use App\Models\Alumno;
use App\Models\AlumnosGeneral;
use App\Models\Carrera;
use App\Models\PlanDeEstudio;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Models\PeriodoEscolar;
use App\Models\PeriodoFicha;
use App\Models\Aspirante;
use App\Models\Preficha;
use App\Models\FichaAspirante;
use LaravelIdea\Helper\App\Models\_IH_Carrera_C;

class AspiranteController extends Controller
{
    public function __construct(Dispatcher $events)
    {
        new MenuEscolaresController($events);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): Factory|View
    {
        $parametros=PeriodoFicha::where('activo',true)->first();
        $periodo_ficha=$parametros->fichas;
        $periodos = PeriodoEscolar::orderBy('periodo', 'DESC')->get();
        $encabezado="Aspirantes nuevo ingreso";
        return view('escolares.fichas_periodo')->with(compact('encabezado',
            'periodos','periodo_ficha'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): Factory|View
    {
        $identificador=$request->get("identificador");
        $datos_aspirante=(new AccionesController)->ficha_datos($identificador)[0];
        if(Aspirante::where([
            'periodo'=>$datos_aspirante->periodo,
            'ficha'=>$datos_aspirante->ficha,
        ])->count()>0){
            $mensaje="Ya existe un registro previo del aspirante.";
            $encabezado="Nueva ficha";
            return view('escolares.no')->with(compact('mensaje','encabezado'));
        }
        if($request->pago_ficha){
            $aspirante = new Aspirante();
            $aspirante->periodo=$datos_aspirante->periodo;
            $aspirante->ficha=$datos_aspirante->ficha;
            $aspirante->apellido_paterno=$datos_aspirante->apellido_paterno_aspirante;
            $aspirante->apellido_materno=$datos_aspirante->apellido_materno_aspirante;
            $aspirante->nombre_aspirante=$datos_aspirante->nombre_aspirante;
            $aspirante->fecha_nacimiento=$datos_aspirante->fecha_nacimiento;
            $aspirante->sexo=$datos_aspirante->sexo;
            $aspirante->pais=$datos_aspirante->pais;
            $aspirante->carrera=$datos_aspirante->carrera;
            foreach ($request->get('documentos') as $key => $value) {
                $aspirante[addslashes($value)]=1;
            }
            $aspirante->migratorio=$request->get('migratorio');
            $aspirante->pago_ficha=$request->get('pago_ficha');
            $aspirante->grupo=null;
            $aspirante->control=null;
            $aspirante->save();
            (new AccionesController)->pago_ficha($identificador);
            $encabezado="Ficha generada";
            $mensaje="Se generó la ficha correspondiente";
            return view('escolares.ficha_generada')
                ->with(compact('encabezado','mensaje','identificador'));
        }else{
            $mensaje="No se dió de alta la ficha al no contar con el pago correspondiente";
            $encabezado="Nueva ficha";
            return view('escolares.no')->with(compact('mensaje','encabezado'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $ficha): Factory|View
    {
        $aspirante=(new AccionesController)->ficha_datos($ficha)[0];
        $carreras = $this->getIH_Carrera_C();
        $documentos=(new AccionesController)->documentos_aspirante($ficha)[0];
        $encabezado="Datos del aspirante a ingresar";
        return view('escolares.fichas_informacion_aspirante')
            ->with(compact('aspirante','carreras','encabezado','documentos','ficha'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $ficha): Factory|View
    {
        $aspirante=(new AccionesController)->ficha_datos($ficha)[0];
        $documentos=(new AccionesController)->documentos_aspirante($ficha)[0];
        $documentos_capturados=Aspirante::where(
            [
                'ficha'=>$aspirante->ficha,
                'periodo'=>$aspirante->periodo
            ]
        )->select(['cert_prepa','const_terminacion','acta_nacimiento','curp','nss','migratorio'])
            ->first();
        $carrera_aspirante=Carrera::where(
            [
                'ofertar'=>true,
                'carrera'=>$aspirante->carrera,
            ]
        )->select('nombre_carrera')->first();
        $periodo_aspirante=PeriodoEscolar::where('periodo',$aspirante->periodo)->first();
        $encabezado="Datos del aspirante a ingresar en el período ".$periodo_aspirante->identificacion_corta;
        return view('escolares.fichas_documentos_aspirante')
            ->with(compact('encabezado','aspirante','documentos',
                'carrera_aspirante','documentos_capturados','ficha'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $ficha): Factory|View
    {
        Preficha::where('aspirante_id',$ficha)
            ->update(
                [
                    'nombre'=>$request->nombre_aspirante,
                    'apellido_paterno'=>$request->apellido_paterno,
                    'apellido_materno'=>$request->apellido_materno,
                    'carrera'=>$request->carrera,
                    'curp'=>$request->curp
                ]
            );
        $aspirante=(new AccionesController)->ficha_datos($ficha)[0];
        $carreras = $this->getIH_Carrera_C();
        $documentos=(new AccionesController)->documentos_aspirante($ficha)[0];
        $encabezado="Datos actualizados del aspirante";
        return view('escolares.fichas_informacion_aspirante')
            ->with(compact('aspirante','carreras','encabezado','documentos'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $ficha)
    {

    }

    /**
     * Listado completo de fichas basándonos en el período seleccionado
     */
    public function listado(Request $request): Factory|View
    {
        $periodo_ficha=$request->get('periodo');
        $datos_periodo=PeriodoEscolar::where('periodo',$periodo_ficha)->first();
        $datos=(new AccionesController)->listado_aspirantes($periodo_ficha,'T');
        $encabezado="Listado de Aspirantes para el período ".$datos_periodo->identificacion_corta;
        return view('escolares.fichas_listado_completo')
            ->with(compact('datos','encabezado','datos_periodo'));
    }

    public function listado_aceptados(Request $request): Factory|View
    {
        $periodo=$request->get('periodo');
        $carrera=$request->get('carrera');
        if(Aspirante::where(
            [
                'periodo'=>$periodo,
                'carrera'=>$carrera,
                'aceptado'=>true
            ]
        )
            ->whereNull('control')
            ->count()>0
        ){
            $aceptados=Aspirante::where(
                [
                    'periodo'=>$periodo,
                    'carrera'=>$carrera,
                    'aceptado'=>true
                ]
            )
                ->whereNull('control')
                ->orderBy('apellido_paterno','ASC')
                ->orderBy('apellido_materno','ASC')
                ->orderBy('nombre_aspirante','ASC')
                ->get();
            $datos_carrera=Carrera::where(
                [
                    'ofertar'=>true,
                    'carrera'=>$carrera
                ])->select(['nombre_carrera','reticula'])->first();
            $nombre_periodo=(new AccionesController)->nombre_periodo($periodo);
            $encabezado="Nuevo ingreso para el período ".$nombre_periodo->identificacion_corta;
            return view('escolares.fichas_aceptados')
                ->with(compact('aceptados','datos_carrera','encabezado','periodo'));
        }else{
            $encabezado="Sin aspirantes aceptados";
            $mensaje="No hay aspirantes aceptados para esa carrera por el momento";
            return view('escolares.no')->with(compact('encabezado','mensaje'));
        }
    }

    public function seleccionados(Request $request): Factory|View
    {
        $periodo=$request->get('periodo');
        $reticula=$request->get('reticula');
        $tec=$_ENV["NUMERO_TEC"];
        $anio=substr($periodo,2,2);
        if(Alumno::where('periodo_ingreso_it',$periodo)->count()==0){
            $ultimo_valor="0001";
        }else{
            $ultimo_control=(new AccionesController)->ultimo_control($periodo)[0];
            $ultimo_valor=(int)substr($ultimo_control->control,-4)+1;
            $ultimo_valor=strlen($ultimo_valor)==3?"0".$ultimo_valor:$ultimo_valor;
        }
        $aceptados=$request->get('aceptados');
        $controles=array();
        $datos=array();
        foreach($aceptados as $aceptado){
            $datos_aceptado=Aspirante::where('id',$aceptado)->first();
            $nombre=$datos_aceptado->apellido_paterno." ".$datos_aceptado->apellido_materno." ".$datos_aceptado->nombre_aspirante;
            $controles["ficha"]=$datos_aceptado->ficha;
            $controles["id"]=$aceptado;
            $controles["nombre"]=$nombre;
            $controles["control"]=$anio.$tec.$ultimo_valor;
            $ultimo_valor+=1;
            $ultimo_valor=strlen($ultimo_valor)==3?"0".$ultimo_valor:$ultimo_valor;
            $datos[] = $controles;
        }
        $datos=collect($datos);
        $encabezado="Inscripción a primer semestre";
        return view('escolares.fichas_aceptados_control')
            ->with(compact('encabezado','datos','reticula'));
    }

    public function estadistica(): Factory|View
    {
        list($periodo_ficha, $periodos) = $this->extracted();
        $encabezado="Aspirantes nuevo ingreso";
        return view('escolares.fichas_periodo_estadistica')->with(compact('encabezado',
            'periodos','periodo_ficha'));
    }

    public function periodo_aceptados(): Factory|View
    {
        list($periodo_ficha, $periodos) = $this->extracted();
        $encabezado="Inscripción aspirantes aceptados";
        $carreras = $this->getIH_Carrera_C();
        return view('escolares.fichas_periodo_aceptados')->with(compact('encabezado',
            'periodos','periodo_ficha','carreras'));
    }

    public function actualizar_documentos(Request $request, int $ficha): Factory|View
    {
        $aspirante=(new AccionesController)->ficha_datos($ficha)[0];
        Aspirante::where([
            'ficha'=>$aspirante->ficha,
            'periodo'=>$aspirante->periodo
        ])->update(
            [
                'cert_prepa'=>false,
                'const_terminacion'=>false,
                'acta_nacimiento'=>false,
                'curp'=>false,
                'nss'=>false,
            ]
        );
        $registro=Aspirante::where(
            [
                'ficha'=>$aspirante->ficha,
                'periodo'=>$aspirante->periodo
            ]
        )->first();
        foreach ($request->get('documentos') as $key => $value) {
            $registro[addslashes($value)]=1;
        }
        $registro->update();
        $encabezado="Documentos del aspirante actualizados";
        $mensaje="Se actualizó la información de los documentos que el aspirante ha entregado a Servicios Escolares";
        return view('escolares.si')->with(compact('encabezado','mensaje'));
    }

    public function inscripcion(Request $request): Factory|View
    {
        $reticula=$request->get('reticula');
        $aspirantes=$request->all();
        $plan=PlanDeEstudio::max('plan_de_estudio');
        $controles=array();
        $i=1;
        foreach ($aspirantes as $key=>$value) {
            if($key!="_token" and $key!="reticula"){
                $id=explode("_",$key)[1];
                $control=$value;
                $datos=(new AccionesController)->ficha_datos($id)[0];
                $aspirante=Aspirante::where(['id'=>$id])->select('grupo')->first();
                $alumno = new Alumno();
                $alumno->no_de_control=$control;
                $alumno->carrera=trim($datos->carrera);
                $alumno->reticula=$reticula;
                $alumno->especialidad=null;
                $alumno->nivel_escolar="L";
                $alumno->semestre=1;
                $alumno->estatus_alumno="ACT";
                $alumno->plan_de_estudios=$plan;
                $alumno->apellido_paterno=$datos->apellido_paterno_aspirante??null;
                $alumno->apellido_materno=$datos->apellido_materno_aspirante??null;
                $alumno->nombre_alumno=$datos->nombre_aspirante??null;
                $alumno->curp_alumno=$datos->curp??null;
                $alumno->fecha_nacimiento=$datos->fecha_nacimiento??null;
                $alumno->sexo=$datos->sexo??null;
                $alumno->estado_civil=$datos->estado_civil??null;
                $alumno->tipo_ingreso=1;
                $alumno->periodo_ingreso_it=$datos->periodo;
                $alumno->ultimo_periodo_inscrito=$datos->periodo;
                $alumno->promedio_periodo_anterior=null;
                $alumno->promedio_aritmetico_acumulado=null;
                $alumno->creditos_aprobados=null;
                $alumno->creditos_cursados=null;
                $alumno->promedio_final_alcanzado=null;
                $alumno->escuela_procedencia=trim($datos->preparatoria)??null;
                $alumno->entidad_procedencia=substr($datos->edo_preparatoria,0,2)??null;
                $alumno->ciudad_procedencia=trim($datos->mun_preparatoria)??null;
                $alumno->correo_electronico=trim($datos->correo_electronico)??null;
                $alumno->periodos_revalidacion=null;
                $alumno->becado_por=null;
                $alumno->nip=rand(1000,9999);
                $alumno->fecha_titulacion=null;
                $alumno->opcion_titulacion=null;
                $alumno->nss=null;
                $alumno->save();
                $generales=new AlumnosGeneral();
                $generales->no_de_control=$control;
                $generales->domicilio_calle=trim($datos->calle_numero)??null;
                $generales->domicilio_colonia=trim($datos->colonia)??null;
                $generales->codigo_postal=trim($datos->codigo_postal)??null;
                $generales->telefono=trim($datos->telefono)??null;
                $generales->facebook=trim($datos->facebook)??null;
                $generales->save();
                Aspirante::where(['id'=>$id])->update(['control'=>$control]);
                FichaAspirante::where('id',$request->get('id'))->update(['control'=>$control]);
                (new AccionesController)->inscripcion($datos->periodo,$control,trim($datos->carrera),$reticula,$aspirante->grupo);
                $controles[$i]=$control;
                $i++;
            }
        }
        $encabezado="Impresión de carga horario nuevo ingreso";
        return view('escolares.fichas_impresion_horarios')
            ->with(compact('encabezado','controles'));
    }

    /**
     * @return array
     */
    public function extracted(): array
    {
        $parametros = PeriodoFicha::where('activo', true)->first();
        $periodo_ficha = $parametros->fichas;
        $periodos = PeriodoEscolar::orderBy('periodo', 'DESC')->get();
        return array($periodo_ficha, $periodos);
    }

    /**
     * @return Carrera[]|Collection|_IH_Carrera_C
     */
    public function getIH_Carrera_C(): array|_IH_Carrera_C|Collection
    {
        return Carrera::where('ofertar', '=', true)
            ->select(['nombre_carrera', 'carrera'])
            ->orderBy('carrera', 'ASC')
            ->get();
    }

}
