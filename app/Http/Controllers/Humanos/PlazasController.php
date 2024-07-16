<?php

namespace App\Http\Controllers\Humanos;

use App\Http\Controllers\Controller;
use App\Models\Personal;
use App\Models\PersonalPlaza;
use App\Models\Categoria;
use App\Models\Motivo;
use Illuminate\Http\Request;

class PlazasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $trabajadores=Personal::select('id','apellidos_empleado','nombre_empleado')
            ->orderBy('apellidos_empleado','ASC')
            ->orderBy('nombre_empleado','ASC')
            ->get();
        $categorias=Categoria::select(['descripcion','id','categoria','horas'])
            ->distinct('categoria')
            ->orderBy('categoria','ASC')
            ->get();
        $motivos=Motivo::select('id','descripcion','motivo')->orderBy('motivo','ASC')->get();
        $encabezado="Alta de plaza para personal";
        return view('rechumanos.plaza_alta')
            ->with(compact('trabajadores','categorias','motivos','encabezado'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        request()->validate([
            'unidad' => 'required',
            'subunidad'=>'required',
            'horas'=>'required',
            'diagonal'=>'required',
            'efectos_iniciales'=>'required',
            'efectos_finales'=>'required',
        ], [
            'unidad.required' => 'Debe indicar la unidad de la plaza',
            'subunidad.required' => 'Debe indicar la sub unidad de la plaza',
            'horas.required'=>'Debe de indicar el número de horas de la plaza',
            'diagonal.required'=>'Debe indicar la diagonal de la plaza',
            'efectos_iniciales.required'=>'Debe indicar el efecto inicial de la plaza',
            'efectos_finales.required'=>'Debe indicar el efecto final de la plaza',
        ]);
        $personalPlaza=new PersonalPlaza();
        $personalPlaza->id_personal=$request->get('personal');
        $personalPlaza->unidad=$request->get('unidad');
        $personalPlaza->subunidad=$request->get('subunidad');
        $personalPlaza->id_categoria=$request->get('categoria');
        $personalPlaza->horas=$request->get('horas');
        $personalPlaza->diagonal=$request->get('diagonal');
        $personalPlaza->estatus_plaza=$request->get('estatus_plaza');
        $personalPlaza->id_motivo=$request->get('id_motivo');
        $personalPlaza->efectos_iniciales=$request->get('efectos_iniciales');
        $personalPlaza->efectos_finales=$request->get('efectos_finales');
        $personalPlaza->save();
        return redirect()->route('inicio_rechumanos');
    }

    /**
     * Display the specified resource.
     */
    public function show(PersonalPlaza $personalPlaza)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PersonalPlaza $personalPlaza)
    {
        $categorias=Categoria::select(['descripcion','id','categoria','horas'])
            ->distinct('categoria')
            ->orderBy('categoria','ASC')
            ->get();
        $motivos=Motivo::select('id','descripcion','motivo')->orderBy('motivo','ASC')->get();
        $encabezado="Actualización de plaza";
        return view('rechumanos.plaza_edicion')
            ->with(compact('personalPlaza','categorias','motivos','encabezado'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PersonalPlaza $personalPlaza)
    {
        request()->validate([
            'unidad' => 'required',
            'subunidad'=>'required',
            'diagonal'=>'required',
            'efectos_iniciales'=>'required',
            'efectos_finales'=>'required',
        ], [
            'unidad.required' => 'Debe indicar la unidad de la plaza',
            'subunidad.required' => 'Debe indicar la sub unidad de la plaza',
            'diagonal.required'=>'Debe indicar la diagonal de la plaza',
            'efectos_iniciales.required'=>'Debe indicar el efecto inicial de la plaza',
            'efectos_finales.required'=>'Debe indicar el efecto final de la plaza',
        ]);
        $personalPlaza->update([
            'unidad'=>$request->get('unidad'),
            'subunidad'=>$request->get('subunidad'),
            'id_categoria'=>$request->get('categoria'),
            'diagonal'=>$request->get('diagonal'),
            'horas'=>$request->get('horas'),
            'estatus_plaza'=>$request->get('estatus_plaza'),
            'id_motivo'=>$request->get('id_motivo'),
            'efectos_iniciales'=>$request->get('efectos_iniciales'),
            'efectos_finales'=>$request->get('efectos_finales'),
        ]);
        return redirect()->route('inicio_rechumanos');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PersonalPlaza $personalPlaza)
    {
        //
    }
}
