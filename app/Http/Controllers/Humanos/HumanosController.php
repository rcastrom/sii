<?php

namespace App\Http\Controllers\Humanos;


use App\Models\Categoria;
use App\Models\EntidadesFederativa;
use App\Models\Municipio;
use App\Models\Organigrama;
use App\Models\Personal;
use App\Models\PersonalCarrera;
use App\Models\PersonalDato;
use App\Models\PersonalEstatus;
use App\Models\PersonalEstudio;
use App\Models\PersonalInstitEstudio;
use App\Models\PersonalNivelEstudio;
use App\Models\PersonalNombramiento;
use App\Models\PersonalPlaza;
use Carbon\Carbon;
use Illuminate\Contracts\Events\Dispatcher;
use App\Http\Controllers\MenuHumanosController;
use App\Http\Controllers\Acciones\AccionesController;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PersonalExport;


class HumanosController extends Controller
{
    public function __construct(Dispatcher $events){
        new MenuHumanosController($events);
    }
    public function index(): Factory|View|Application
    {
        return view('rechumanos.index');
    }
    public function alta1(): Factory|View|Application
    {
        $encabezado="Alta de personal";
        $contrataciones=PersonalNombramiento::all();
        return view('rechumanos.alta1')->with(compact('encabezado','contrataciones'));
    }
    public function datos_personal($id): Personal
    {
        return Personal::where('id',$id)->first();
    }
    public function area_personal($area): Organigrama
    {
        return Organigrama::where('clave_area',$area)->first();
    }
    public function regresar_contenido_estudios($id): Factory|View|Application
    {
        $informacion=PersonalEstudio::where('id',$id)->first();
        $carreras=PersonalCarrera::all()->sortBy('carrera');
        $escuelas=PersonalInstitEstudio::all()->sortBy('nombre');
        $niveles=PersonalNivelEstudio::all()->sortBy('descripcion');
        $nivel_estudio=PersonalCarrera::where('id',$informacion->id_carrera)->first();
        $encabezado="Actualización de datos de estudio";
        $personal_info=$this->datos_personal($informacion->id_docente);
        $nombre=$personal_info->apellido_paterno.' '.$personal_info->apellido_materno.' '.$personal_info->nombre_empleado;
        return view('rechumanos.estudios_actualizar')->with(compact('id',
            'informacion','niveles','carreras','escuelas','encabezado','nombre','nivel_estudio'));
    }
    public function regresar_listado_estudios($id): Factory|View|Application
    {
        $encabezado="Estudios del personal";
        $personal_info= $this->datos_personal($id);
        $nombre=$personal_info->apellido_paterno.' '.$personal_info->apellido_materno.' '.$personal_info->nombre_empleado;
        if(PersonalEstudio::where('id_docente',$id)->count()>0){
            $bandera=1;
            $estudios=(new AccionesController)->personal_estudios($id);
        }else{
            $bandera=0;
            $estudios=array();
        }
        return view('rechumanos.estudios_listado')
            ->with(compact('encabezado','personal_info','id','nombre','estudios','bandera'));
    }
    public function regresar_nuevo_estudio($id): Factory|View|Application
    {
        $carreras=PersonalCarrera::all()->sortBy('carrera');
        $escuelas=PersonalInstitEstudio::all()->sortBy('nombre');
        $niveles=PersonalNivelEstudio::all()->sortBy('descripcion');
        $encabezado="Alta de estudio para el personal";
        $personal_info=$this->datos_personal($id);
        $nombre=$personal_info->apellido_paterno.' '.$personal_info->apellido_materno.' '.$personal_info->nombre_empleado;
        return view('rechumanos.nuevo_estudio')->with(compact('id',
            'carreras','escuelas','encabezado','niveles','nombre'));
    }

    public function buscar_plazas_personal($tipo,$seleccion):Factory|View|Application
    {
        if($seleccion==1){
            $estatus='A';
            $leyenda="actuales";
        }elseif ($seleccion==2) {
            $estatus='H';
            $leyenda="históricas";
        }else{
            $estatus='';
            $leyenda='';
        }
        if($seleccion==1 || $seleccion==2){
            if($tipo==1){
                $encabezado2="Sin categoría específica, plazas ".$leyenda;
                $plazas=PersonalPlaza::where('estatus_plaza',$estatus)
                    ->leftjoin('categorias','personal_plazas.id_categoria','=','categorias.id')
                    ->leftjoin('motivos','personal_plazas.id_motivo','=','motivos.id')
                    ->leftjoin('personal','personal_plazas.id_personal','=','personal.id')
                    ->select('personal_plazas.*','categorias.categoria',
                        'motivos.motivo','personal.apellidos_empleado','personal.nombre_empleado')
                    ->orderBy('categorias.categoria','ASC')
                    ->orderBy('personal.apellidos_empleado','ASC')
                    ->orderBy('personal.nombre_empleado','ASC')
                    ->get();
            }
            else{
                $nombre_categoria=Categoria::where('id',$tipo)->first();
                $encabezado2="Categoría ".$nombre_categoria->categoria.", plazas ".$leyenda;
                $plazas=PersonalPlaza::where('estatus_plaza',$estatus)
                    ->where('id_categoria',$tipo)
                    ->leftjoin('categorias','personal_plazas.id_categoria','=','categorias.id')
                    ->leftjoin('motivos','personal_plazas.id_motivo','=','motivos.id')
                    ->leftjoin('personal','personal_plazas.id_personal','=','personal.id')
                    ->select('personal_plazas.*','categorias.categoria',
                        'motivos.motivo','personal.apellidos_empleado','personal.nombre_empleado')
                    ->orderBy('categorias.categoria','ASC')
                    ->orderBy('personal.apellidos_empleado','ASC')
                    ->orderBy('personal.nombre_empleado','ASC')
                    ->get();
            }
        }else{
            if($tipo==1){
                $encabezado2="Sin categoría específica, plazas actuales e históricas";
                $plazas=PersonalPlaza::whereIn('estatus_plaza',['A','H'])
                    ->leftjoin('categorias','personal_plazas.id_categoria','=','categorias.id')
                    ->leftjoin('motivos','personal_plazas.id_motivo','=','motivos.id')
                    ->leftjoin('personal','personal_plazas.id_personal','=','personal.id')
                    ->select('personal_plazas.*','categorias.categoria',
                        'motivos.motivo','personal.apellidos_empleado','personal.nombre_empleado')
                    ->orderBy('categorias.categoria','ASC')
                    ->orderBy('personal.apellidos_empleado','ASC')
                    ->orderBy('personal.nombre_empleado','ASC')
                    ->get();
            }
            else{
                $nombre_categoria=Categoria::where('id',$tipo)->first();
                $encabezado2="Categoría ".$nombre_categoria->categoria.", plazas ".$leyenda;
                $plazas=PersonalPlaza::whereIn('estatus_plaza',['A','H'])
                    ->where('id_categoria',$tipo)
                    ->leftjoin('categorias','personal_plazas.id_categoria','=','categorias.id')
                    ->leftjoin('motivos','personal_plazas.id_motivo','=','motivos.id')
                    ->leftjoin('personal','personal_plazas.id_personal','=','personal.id')
                    ->select('personal_plazas.*','categorias.categoria',
                        'motivos.motivo','personal.apellidos_empleado','personal.nombre_empleado')
                    ->orderBy('categorias.categoria','ASC')
                    ->orderBy('personal.apellidos_empleado','ASC')
                    ->orderBy('personal.nombre_empleado','ASC')
                    ->get();
            }
        }
        $encabezado="Listado de plazas";
        return view('rechumanos.listado_plazas')
            ->with(compact('plazas','encabezado','encabezado2'));
    }

    public function alta_personal1(Request $request): Factory|View|Application
    {
        request()->validate([
            'apellido_materno'=>'required',
            'nombre_empleado'=>'required',
            'curp_empleado'=>'required|size:18|unique:personal,curp_empleado',
            'rfc'=>'required|size:13|unique:personal,rfc',
            'correo_electronico'=>'required',
            'no_tarjeta'=>'required|unique:personal,no_tarjeta'

        ],[
            'apellido_materno.required'=>'Debe especificar el apellido',
            'nombre_empleado.required'=>'Debe especificar el nombre',
            'curp_empleado.required'=>'Debe especificar el CURP',
            'curp_empleado.size'=>'El CURP debe constar de 18 caracteres',
            'curp_empleado.unique'=>'El CURP debe ser único y ya existe en la BD',
            'rfc.required'=>'Debe especificar el RFC',
            'rfc.size'=>'El RFC debe constar de 13 caracteres',
            'rfc.unique'=>'El RFC del empleado debe ser único, y ya existe un registro en la BD',
            'correo_electronico.required'=>'Debe indicar el correo electrónico',
            'no_tarjeta.required'=>'Debe especificar el número de empleado o tarjeta de la persona',
            'no_tarjeta.unique'=>'El número de empleado por asignar ya existe en la BD'
        ]);
        $appat=$request->apellido_paterno;
        $apmat=$request->apellido_materno;
        $nombre=$request->nombre_empleado;
        $apellidos=$appat." ".$apmat;
        $alta=new Personal();
        $alta->rfc=$request->rfc;
        $alta->curp_empleado=$request->curp_empleado;
        $alta->no_tarjeta=$request->no_tarjeta;
        $alta->apellidos_empleado=$apellidos;
        $alta->nombre_empleado=$nombre;
        $alta->sexo_empleado=$request->sexo_empleado;
        $alta->estado_civil=$request->estado_civil;
        $alta->correo_electronico=$request->correo_electronico;
        $alta->apellido_paterno=$appat;
        $alta->apellido_materno=$apmat;
        $alta->nombramiento=$request->nombramiento;
        $alta->status_empleado=$request->status_empleado;
        $alta->save();
        $id=$alta->id;
        $encabezado="Continuación alta";
        $deptos=Organigrama::orderBy('descripcion_area')->get();
        return view('rechumanos.alta2')->with(compact('nombre','apellidos',
            'id','encabezado','deptos'));
    }
    public function alta_personal2(Request $request): Factory|View|Application
    {
        request()->validate([
            'domicilio'=>'required',
            'colonia'=>'required',
            'cp'=>'required|size:5',
            'telefono'=>'required'
        ],[
            'domicilio.required'=>'Debe especificar el domicilio (calle y número)',
            'colonia.required'=>'Debe especificar la colonia',
            'cp.required'=>'Debe indicar el código postal',
            'cp.size'=>'El código postal debe constar de 5 caracteres',
            'telefono.required'=>'Debe especificar el teléfono de contacto'
        ]);
        $id=$request->id;
        $rama=$request->rama ?? null;
        $gob= $request->gob ?? null;
        $sep= $request->sep ?? null;
        $siglas= $request->siglas ?? null;
        Personal::where('id',$id)->update([
            'clave_area'=>$request->area,
            'ingreso_rama'=>$rama,
            'inicio_gobierno'=>$gob,
            'inicio_sep'=>$sep,
            'domicilio_empleado'=>$request->domicilio,
            'colonia_empleado'=>$request->colonia,
            'codigo_postal_empleado'=>$request->cp,
            'telefono_empleado'=>$request->telefono,
            'siglas'=>$siglas
        ]);
        $encabezado='Proceso concluido';
        $mensaje="Se dió de alta al personal, por lo que puede continuar con el proceso";
        return view('rechumanos.si')->with(compact('encabezado','mensaje'));
    }

    public function listado1(): Factory|View|Application
    {
        $encabezado="Consulta de personal";
        $situaciones=PersonalEstatus::all();
        return view('rechumanos.listado_personal')
            ->with(compact('situaciones','encabezado'));
    }

    public function listado(Request $request): Factory|View|Application
    {
        if($request->estatus!="T"){
            $personal=Personal::select(['id','apellido_paterno',
                'apellido_materno','nombre_empleado','no_tarjeta'])
                ->where('status_empleado',$request->estatus)
                ->orderBy('apellido_paterno')
                ->orderBy('apellido_materno')
                ->orderBy('nombre_empleado')
                ->get();
        }else{
            $personal=Personal::select(['id','apellido_paterno',
                'apellido_materno','nombre_empleado','no_tarjeta'])
                ->orderBy('apellido_paterno')
                ->orderBy('apellido_materno')
                ->orderBy('nombre_empleado')
                ->get();
        }
        $encabezado="Listado del personal";
        return view('rechumanos.listado')->with(compact('personal','encabezado'));
    }
    public function listado2(Request $request): Factory|View|Application
    {
        $id=base64_decode($request->personal);
        $personal_info= $this->datos_personal($id);
        $depto=$this->area_personal($personal_info->clave_area);
        $campos=PersonalDato::select(['id','campo','lectura'])->get();
        $datos=array();
        $i=0;
        foreach ($campos as $campo){
            if($campo["id"]!=7){
                if($campo["id"]==9){
                       $tipo_contratacion=PersonalNombramiento::where('letra',$personal_info->nombramiento)
                           ->first();
                       $valor=$tipo_contratacion->descripcion;
                }else {
                    $temp=$campo["campo"];
                    $valor = $personal_info->$temp;
                }
            }else{
                $valor= $depto->descripcion_area ?? "SIN ÁREA ESPECIFICADA";
            }
            $datos[$i]=array($campo["id"],$valor,$campo["lectura"]);
            $i++;
        }
        $encabezado="Consulta o modificación de datos del personal";
        return view('rechumanos.informacion')->with(compact('id',
            'datos','personal_info','encabezado'));
    }
    public function edicion(Request $request): Factory|View|Application
    {
        $campo_editar=$request->campo;
        $id=base64_decode($request->personal);
        $personal_info= $this->datos_personal($id);
        $dato_por_editar=PersonalDato::where('id',$campo_editar)->first();
        if($campo_editar!=7){
            if($campo_editar==9){
                $tipo_contratacion=PersonalNombramiento::where('letra',$personal_info->nombramiento)
                    ->first();
                $valor=$tipo_contratacion->descripcion;
            }else {
                $temp = $dato_por_editar->campo;
                $valor = $personal_info->$temp;
            }
        }else{
            $depto=$this->area_personal($personal_info->clave_area);
            $valor=$depto->descripcion_area ?? "SIN ÁREA ESPECIFICADA";
        }
        $titulo=$dato_por_editar->lectura;
        $areas=Organigrama::orderBy('descripcion_area')->get();
        $nombramientos=PersonalNombramiento::all();
        $encabezado="Actualización de datos del personal";
        $id_temp=$id * 161918;
        return view('rechumanos.edicion')->with(compact('id_temp', 'valor',
            'campo_editar','personal_info','titulo','nombramientos','areas','encabezado'));
    }
    public function actualizar(Request $request): Factory|View|Application
    {
        $id=($request->personal)/161918;
        $campo=$request->campo;
        $dato_por_editar=PersonalDato::where('id',$campo)->first();
        $personal=Personal::where('id',$id)->first();
        $temp=$dato_por_editar->campo;
        $personal->$temp=$request->new;
        $personal->save();
        $personal_info= $this->datos_personal($id);
        $depto=$this->area_personal($personal_info->clave_area);
        $campos=PersonalDato::select(['id','campo','lectura'])->get();
        $datos=array();
        $i=0;
        foreach ($campos as $campo){
            if($campo["id"]!=7){
                $temp=$campo["campo"];
                $valor=$personal_info->$temp;
            }else{
                $valor=$depto->descripcion_area;
            }
            $datos[$i]=array($campo["id"],$valor,$campo["lectura"]);
            $i++;
        }
        $encabezado="Consulta o modificación de datos del personal";
        return view('rechumanos.informacion')->with(compact('id',
            'datos','personal_info','encabezado'));
    }
    public function estudios_personal(Request $request): Factory|View|Application
    {
        $id=base64_decode($request->personal);
        return $this->regresar_listado_estudios($id);
    }

    public function estudios_editar(Request $request): Factory|View|Application
    {
        $id=$request->estudio;
        return $this->regresar_contenido_estudios($id);
    }
    public function estudios_actualizar(Request $request): Factory|View|Application
    {
        $id=$request->id;
        $estudios_actualizar=PersonalEstudio::where('id',$id)->first();
        $estudios_actualizar->id_carrera=$request->carrera;
        $estudios_actualizar->id_escuela=$request->escuela;
        $estudios_actualizar->cedula=$request->cedula;
        $estudios_actualizar->fecha_inicio=$request->fecha_inicio;
        $estudios_actualizar->fecha_final=$request->fecha_final;
        $estudios_actualizar->save();
        //Para el nivel de estudios
        $nivel_estudios=PersonalCarrera::where('id',$request->carrera)->first();
        $nivel_estudios->nivel=$request->nivel;
        $nivel_estudios->save();
        //
        $personal=Personal::select(['id','apellido_paterno',
            'apellido_materno','nombre_empleado','no_tarjeta'])
            ->orderBy('apellido_paterno')
            ->orderBy('apellido_materno')
            ->orderBy('nombre_empleado')
            ->get();
        $encabezado="Listado del personal actualizado";
        return view('rechumanos.listado')->with(compact('personal','encabezado'));
    }
    public function alta_carrera(Request $request): Factory|View|Application
    {
        $estudio=$request->estudio;
        $bandera=$request->bandera;
        $niveles=PersonalNivelEstudio::all()->sortBy('descripcion');
        $encabezado="Alta de estudio de personal";
        return view('rechumanos.alta_carrera')->with(compact('estudio',
            'bandera','niveles','encabezado'));
    }
    public function alta_carrera2(Request $request): View|Factory|Application
    {
        $request->validate([
            'carrera'=>'required',
            'nombre_corto'=>'required',
            'siglas'=>'required'
        ],[
            'carrera.required'=>'Debe escribir el nombre de la carrera ha ser dada de alta',
            'nombre_corto.required'=>'Debe indicar el nombre abreviado de la carrera',
            'siglas.required'=>'Indica las siglas correspondiente a la carrera'
        ]);
        $carrera = new PersonalCarrera();
        $carrera->carrera=$request->carrera;
        $carrera->nombre_corto=$request->nombre_corto;
        $carrera->siglas=$request->siglas;
        $carrera->nivel=$request->nivel;
        $carrera->save();
        $id=$request->estudios;
        if($request->bandera){
            $id=base64_decode($id);
            return $this->regresar_nuevo_estudio($id);
            //return $this->regresar_listado_estudios($id);
        }else{
            return $this->regresar_contenido_estudios($id);
        }
    }
    public function alta_escuela(Request $request): Factory|View|Application
    {
        $estudio=$request->estudio;
        $bandera=$request->bandera;
        $encabezado="Alta de institución educativa de personal";
        $estados=EntidadesFederativa::all()->sortBy('nombre_entidad');
        return view('rechumanos.alta_escuela')->with(compact('estudio',
            'estados','bandera','encabezado'));
    }
    public function municipios(Request $request): JsonResponse
    {
        $estado = $request->id;
        $municipios['data'] =Municipio::where('id_estado',$estado)
            ->select(['id','municipio'])
            ->orderBy('municipio')
            ->get();
        return response()->json($municipios);
    }
    public function alta_escuela2(Request $request): View|Factory|Application
    {
        $request->validate([
            'nombre'=>'required'
        ],[
            'nombre.required'=>'Por favor, indique el nombre de la institución'
        ]);
        $escuela = new PersonalInstitEstudio();
        $escuela->id_escuela=666;
        $escuela->id_estado=$request->estado;
        $escuela->id_municipio=$request->municipio;
        $escuela->nombre=$request->nombre;
        $escuela->save();
        $id=$request->estudios;
        if($request->bandera){
            $id=base64_decode($id);
            return $this->regresar_nuevo_estudio($id);
        }else{
            return $this->regresar_contenido_estudios($id);
        }
    }
    public function alta_municipio(Request $request): Factory|View|Application
    {
        $estudio=$request->estudio;
        $encabezado="Alta de institución educativa de personal";
        $estados=EntidadesFederativa::all()->sortBy('nombre_entidad');
        return view('rechumanos.alta_municipio')->with(compact('estudio',
            'estados','encabezado'));
    }
    public function alta_municipio2(Request $request): Factory|View|Application
    {
        $request->validate([
            'municipio'=>'required'
        ],[
            'municipio.required'=>'Por favor, indique el nombre del municipio a ser dado de alta'
        ]);
        $municipio = new Municipio();
        $municipio->id_estado=$request->estado;
        $municipio->id_municipio=666;
        $municipio->municipio=$request->municipio;
        $municipio->save();
        $estudio=$request->estudio;
        $encabezado="Alta de institución educativa de personal";
        $estados=EntidadesFederativa::all()->sortBy('nombre_entidad');
        return view('rechumanos.alta_escuela')->with(compact('estudio',
            'estados','encabezado'));
    }
    public function nuevo_estudio(Request $request): Factory|View|Application
    {
        $id=base64_decode($request->personal);
        return $this->regresar_nuevo_estudio($id);
    }
    public function nuevo_estudio2(Request $request): Factory|View|Application
    {
        $id=base64_decode($request->id);
        $estudio_nuevo=new PersonalEstudio();
        $estudio_nuevo->id_docente=$id;
        $estudio_nuevo->fecha_inicio=$request->fecha_inicio??NULL;
        $estudio_nuevo->fecha_final=$request->fecha_final??NULL;
        $estudio_nuevo->id_carrera=$request->carrera;
        $estudio_nuevo->id_escuela=$request->escuela;
        $estudio_nuevo->cedula=$request->cedula??NULL;
        $estudio_nuevo->save();
        return $this->regresar_listado_estudios($id);
    }
    public function eliminar_estudio(Request $request): Factory|View|Application
    {
        $id=$request->estudio;
        $informacion=PersonalEstudio::where('id',$id)->first();
        $carrera=PersonalCarrera::select('carrera')->where('id',$informacion->id_carrera)->first();
        $escuela=PersonalInstitEstudio::select('nombre')->where('id',$informacion->id_escuela)->first();
        $encabezado="Eliminar estudio del personal";
        $personal_info=$this->datos_personal($informacion->id_docente);
        $estudios=(new AccionesController)->personal_estudios($informacion->id_docente);
        $nivel="";
        foreach ($estudios as $estudio) {
            if ($estudio->id == $id) {
                $nivel = $estudio->nivel;
            }
        }
        $nombre=$personal_info->apellido_paterno.' '.$personal_info->apellido_materno.' '.$personal_info->nombre_empleado;
        return view('rechumanos.estudio_eliminar')->with(compact('id',
            'nivel','carrera','escuela','encabezado','informacion','nombre'));
    }
    public function eliminar_estudio2(Request $request): Factory|View|Application
    {
        $id=$request->id;
        $informacion=PersonalEstudio::where('id',$id)->first();
        $personal=$informacion->id_docente;
        if($request->confirmar==1){
            $informacion->delete();
        }
        return $this->regresar_listado_estudios($personal);
    }

    public function estatus_personal_editar(Request $request): Factory|View|Application
    {
        $id=base64_decode($request->personal);
        $personal_info= $this->datos_personal($id);
        $estatus=PersonalEstatus::get();
        $encabezado="Personal: ".$personal_info->apellidos_empleado.' '.$personal_info->nombre_empleado;
        return view('rechumanos.estatus_actualizar')
            ->with(compact('id','personal_info','encabezado','estatus'));
    }

    public function estatus_personal_editar2(Request $request): Factory|View|Application
    {
        $id=$request->id;
        Personal::where('id',$id)->update(['status_empleado'=>$request->estatus]);
        $encabezado="Datos actualizados ";
        $mensaje="Se modificó el estatus del personal";
        return view('rechumanos.si')
            ->with(compact('encabezado','mensaje'));
    }

    public function listado_plazas_personal(Request $request): Factory|View|Application
    {
        $id=base64_decode($request->personal);
        $tipo=$request->tipo;
        $encabezado="Plazas personal";
        $personal_info= $this->datos_personal($id);
        $nombre=$personal_info->apellido_paterno.' '.$personal_info->apellido_materno.' '.$personal_info->nombre_empleado;
        $estatus=$tipo==1?'A':'H';
        if(PersonalPlaza::where('id_personal',$id)->where('estatus_plaza', $estatus)
                ->count()>0){
            $plazas=PersonalPlaza::where('id_personal',$id)
                ->where('estatus_plaza',$estatus)
                ->leftjoin('categorias','personal_plazas.id_categoria','=','categorias.id')
                ->leftjoin('motivos','personal_plazas.id_motivo','=','motivos.id')
                ->select('personal_plazas.*','categorias.categoria','motivos.motivo')
                ->get();
            $bandera=1;
        }else{
            $bandera=0;
            $plazas='';
        }
        return view('rechumanos.plazas_listado')
            ->with(compact('id','encabezado','bandera','nombre','plazas','tipo'));

    }
    public function listado_plazas_uno(): Factory|View|Application
    {
        $encabezado="Listado de plazas del personal";
        return view('rechumanos.listado_plazas_1')->with(compact('encabezado'));
    }

    public function listado_plazas_dos(Request $request)
    {
        $estatus=$request->get('estatus');
        $categoria=$request->get('categoria');
        return $this->buscar_plazas_personal($categoria,$estatus);
    }

    public function listado_plazas(Request $request): Factory|View|Application
    {
        $estatus=$request->get('estatus');
        if($request->get('busqueda')==1){
            $categorias=Categoria::select(['descripcion','id','categoria'])
                ->distinct('categoria')
                ->orderBy('categoria','ASC')
                ->get();
            $encabezado="Listado de plazas en base a una categoría específica";
            return view('rechumanos.listado_plazas_2')
                ->with(compact('encabezado','categorias','estatus'));
        }else{
            return $this->buscar_plazas_personal(1,$estatus);
        }
    }
    public function exportar()
    {
        $personal=Personal::select([
            'apellido_paterno','apellido_materno','nombre_empleado','rfc','curp_empleado',
            'no_tarjeta','inicio_gobierno','inicio_sep','ingreso_rama','status_empleado',
            'correo_electronico','correo_institucion'
        ])
            ->orderBy('apellido_paterno','ASC')
            ->orderBy('apellido_materno','ASC')
            ->orderBy('nombre_empleado','ASC')
            ->get();
        return Excel::download(new PersonalExport($personal), 'personal.xlsx');
    }

    public function contrasenia()
    {
        $encabezado = 'Cambio de contraseña';

        return view('escolares.contrasenia')->with(compact('encabezado'));
    }

    public function ccontrasenia(Request $request)
    {
        request()->validate([
            'contra' => 'required|required_with:verifica|same:verifica',
            'verifica' => 'required',
        ], [
            'contra.required' => 'Debe escribir la nueva contraseña',
            'contra.required_with' => 'Debe confirmar la contraseña',
            'contra.same' => 'No concuerda con la verificación',
            'verifica.required' => 'Debe confirmar la nueva contraseña',
        ]);
        $ncontra = bcrypt($request->get('contra'));
        $data = Auth::user()->email;
        User::where('email', $data)->update([
            'password' => $ncontra,
            'updated_at' => Carbon::now(),
        ]);

        return redirect('inicio_rechumanos');
    }

}
