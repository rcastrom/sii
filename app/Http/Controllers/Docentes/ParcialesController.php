<?php

namespace App\Http\Controllers\Docentes;

use App\Http\Controllers\Controller;
use App\Models\CalificacionParcial;
use App\Models\Parcial;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ParcialesController extends Controller
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $periodo=base64_decode($request->periodo);
        $grupo=base64_decode($request->grupo);
        $materia=base64_decode($request->materia);
        $unidad=base64_decode($request->unidad);
        $docente=base64_decode($request->docente);
        $parcial=new Parcial();
        $parcial->periodo=$periodo;
        $parcial->grupo=$grupo;
        $parcial->materia=$materia;
        $parcial->unidad=$unidad;
        $parcial->docente=$docente;
        $parcial->save();
        $id=$parcial->id;
        $calificacion=$request->all();
        foreach ($calificacion as $key=>$value) {
            if(str_contains($key,'al')){
                $datos=explode("al",$key);
                $alumno=$datos[1];
                $desertor= isset($key["d".$alumno]);
                CalificacionParcial::insert([
                    'parcial'=>$id,
                    'no_de_control'=>$alumno,
                    'calificacion'=>$value,
                    'desertor'=>$desertor,
                    'created_at'=>Carbon::now(),
                ]);
            }
        }
        return redirect()->route('inicio_personal');
    }

    /**
     * Display the specified resource.
     */
    public function show(Parcial $parcial)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Parcial $parcial)
    {
       //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Parcial $parcial)
    {
        $id=$parcial->id;
        $calificacion=$request->all();
        foreach ($calificacion as $key=>$value) {
            if(str_contains($key,'al')){
                $datos=explode("al",$key);
                $alumno=$datos[1];
                $desertor= isset($key["d".$alumno]);
                CalificacionParcial::where([
                    'parcial'=>$id,
                    'id'=>$alumno])
                    ->update([
                        'calificacion'=>$value,
                        'desertor'=>$desertor,
                        'updated_at'=>Carbon::now(),
                    ]);
            }
        }
        return redirect()->route('inicio_personal');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Parcial $parcial)
    {
        //
    }
}
