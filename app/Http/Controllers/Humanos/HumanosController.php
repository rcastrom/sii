<?php

namespace App\Http\Controllers\Humanos;


use App\Models\Organigrama;
use App\Models\Personal;
use App\Models\PersonalCarrera;
use App\Models\PersonalDato;
use App\Models\PersonalEstudio;
use App\Models\PersonalInstitEstudio;
use App\Models\PersonalNombramiento;
use Illuminate\Contracts\Events\Dispatcher;
use App\Http\Controllers\MenuHumanosController;
use App\Http\Controllers\Acciones\AccionesController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HumanosController extends Controller
{
    public function __construct(Dispatcher $events){
        new MenuHumanosController($events);
    }
    public function index(){
        return view('rechumanos.index');
    }
    public function alta1(){
        $encabezado="Alta de personal";
        $contrataciones=PersonalNombramiento::all();
        return view('rechumanos.alta1')->with(compact('encabezado','contrataciones'));
    }
    public function datos_personal($id){
        return Personal::where('id',$id)->first();
    }
    public function area_personal($area){
        return Organigrama::where('clave_area',$area)->first();
    }
    public function alta_personal1(Request $request){
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
    public function alta_personal2(Request $request){
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
    public function listado(){
        $personal=Personal::select('id','apellido_paterno',
            'apellido_materno','nombre_empleado','no_tarjeta')
            ->orderBy('apellido_paterno')
            ->orderBy('apellido_materno')
            ->orderBy('nombre_empleado')
            ->get();
        $encabezado="Listado del personal";
        return view('rechumanos.listado')->with(compact('personal','encabezado'));
    }
    public function listado2(Request $request){
        $id=base64_decode($request->personal);
        $personal_info= $this->datos_personal($id);
        $depto=$this->area_personal($personal_info->clave_area);
        $campos=PersonalDato::select('id','campo','lectura')->get();
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
    public function edicion(Request $request){
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
    public function actualizar(Request $request){
        $id=($request->personal)/161918;
        $campo=$request->campo;
        $dato_por_editar=PersonalDato::where('id',$campo)->first();
        $personal=Personal::where('id',$id)->first();
        $temp=$dato_por_editar->campo;
        $personal->$temp=$request->new;
        $personal->save();
        $personal_info= $this->datos_personal($id);
        $depto=$this->area_personal($personal_info->clave_area);
        $campos=PersonalDato::select('id','campo','lectura')->get();
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
    public function estudios_personal(Request $request){
        $id=base64_decode($request->personal);
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

    public function estudios_editar(Request $request){
        $id=$request->estudio;
        $informacion=PersonalEstudio::where('id',$id)->first();
        $carreras=PersonalCarrera::all()->sortBy('carrera');
        $escuelas=PersonalInstitEstudio::all()->sortBy('nombre');
        $encabezado="Actualización de datos de estudio";
        $personal_info=$this->datos_personal($informacion->id_docente);
        $nombre=$personal_info->apellido_paterno.' '.$personal_info->apellido_materno.' '.$personal_info->nombre_empleado;
        return view('rechumanos.estudios_actualizar')->with(compact('id',
            'informacion','carreras','escuelas','encabezado','nombre'));
    }
    public function estudios_actualizar(Request $request){
        $id=$request->id;
        $estudios_actualizar=PersonalEstudio::where('id',$id)->first();
        $estudios_actualizar->id_carrera=$request->carrera;
        $estudios_actualizar->id_escuela=$request->escuela;
        $estudios_actualizar->cedula=$request->cedula;
        $estudios_actualizar->fecha_inicio=$request->fecha_inicio;
        $estudios_actualizar->fecha_final=$request->fecha_final;
        $estudios_actualizar->save();
        $personal=Personal::select('id','apellido_paterno',
            'apellido_materno','nombre_empleado','no_tarjeta')
            ->orderBy('apellido_paterno')
            ->orderBy('apellido_materno')
            ->orderBy('nombre_empleado')
            ->get();
        $encabezado="Listado del personal actualizado";
        return view('rechumanos.listado')->with(compact('personal','encabezado'));
    }
    public function alta_carrera(Request $request){
        $estudio=$request->estudio;
        $encabezado="Alta de estudio de personal";
        return view('rechumanos.alta_carrera')->with(compact('estudio','encabezado'));
    }
    public function alta_carrera2(Request $request){
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
        $informacion=PersonalEstudio::where('id',$id)->first();
        $carreras=PersonalCarrera::all()->sortBy('carrera');
        $escuelas=PersonalInstitEstudio::all()->sortBy('nombre');
        $encabezado="Actualización de datos de estudio";
        $personal_info=$this->datos_personal($informacion->id_docente);
        $nombre=$personal_info->apellido_paterno.' '.$personal_info->apellido_materno.' '.$personal_info->nombre_empleado;
        return view('rechumanos.estudios_actualizar')->with(compact('id',
            'informacion','carreras','escuelas','encabezado','nombre'));

    }
}
