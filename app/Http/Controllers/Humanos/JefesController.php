<?php

namespace App\Http\Controllers\Humanos;

use App\Http\Controllers\Controller;
use App\Models\Jefe;
use App\Models\Organigrama;
use App\Models\Personal;
use Illuminate\Http\Request;

class JefesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $encabezado="Listado de cargos";
        $datos=Jefe::join('organigrama','organigrama.clave_area','=','jefes.clave_area')
            ->join('personal','personal.id','=','jefes.id_jefe')
            ->select('jefes.*','organigrama.descripcion_area','personal.nombre_empleado',
                'personal.apellidos_empleado')
            ->orderBy('jefes.clave_area','ASC')
            ->get();
        return view('rechumanos.listado_jefaturas',compact('encabezado','datos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $encabezado="Alta a puesto";
        $cargos=Organigrama::orderBy('descripcion_area','ASC')->get();
        $personal=Personal::select(['id','apellidos_empleado','nombre_empleado'])
            ->where('status_empleado',2)
            ->orderBy('apellidos_empleado','ASC')
            ->orderBy('nombre_empleado','ASC')
            ->get();
        return view('rechumanos.alta_jefatura')
            ->with(compact('encabezado','personal','cargos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'puesto'=>'required',
            'correo'=>'email'
        ],[
            'puesto.required'=>'Debe especificar el nombre completo del puesto',
            'correo.email'=>'No es un formato válido de correo electrónico'
        ]);
        $nuevo_jefe=new Jefe();
        $nuevo_jefe->clave_area=$request->get('area');
        $nuevo_jefe->descripcion_area=$request->get('puesto');
        $nuevo_jefe->id_jefe=$request->get('persona');
        $nuevo_jefe->correo=$request->get('correo');
        $nuevo_jefe->save();
        return redirect()->route('inicio_rechumanos');
    }

    /**
     * Display the specified resource.
     */
    public function show(Jefe $listado)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Jefe $listado)
    {
        $encabezado="Editar cargo";
        $puesto=Organigrama::where('clave_area',$listado->clave_area)->first();
        $personal=Personal::select(['id','apellidos_empleado','nombre_empleado'])
            ->where('status_empleado',2)
            ->orderBy('apellidos_empleado','ASC')
            ->orderBy('nombre_empleado','ASC')
            ->get();
        return view('rechumanos.jefatura_editar')
            ->with(compact('encabezado','puesto','personal','listado'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Jefe $listado)
    {
        $request->validate([
            'puesto'=>'required',
            'correo'=>'email'
        ],[
            'puesto.required'=>'Debe especificar el nombre completo del puesto',
            'correo.email'=>'No es un formato válido de correo electrónico'
        ]);

        Jefe::where('id',$listado->id)->update([
            'descripcion_area'=>$request->get('puesto'),
            'id_jefe'=>$request->get('persona'),
            'correo'=>$request->get('correo')
        ]);
        return redirect()->route('inicio_rechumanos');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Jefe $jefe)
    {
        //
    }
}
