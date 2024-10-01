<?php

namespace App\Http\Controllers\Escolares;

use App\Http\Controllers\Acciones\AccionesController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MenuEscolaresController;
use App\Models\Alumno;
use App\Models\Idioma;
use App\Models\IdiomasGrupo;
use App\Models\IdiomasLiberacion;
use App\Models\PeriodoEscolar;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Http\Request;

class IdiomasController extends Controller
{
    public function __construct(Dispatcher $events)
    {
        new MenuEscolaresController($events);
    }
    public function idiomas_lib1(){
        $idiomas=Idioma::all();
        $encabezado="Idiomas";
        return view('escolares.liberacion1_idiomas')->with(compact('idiomas','encabezado'));
    }
    public function idiomas_lib2(Request $request){
        request()->validate([
            'control' => 'required'
        ], [
            'control.required' => 'Debe indicar el número de control'
        ]);
        $control=$request->get('control');
        $alumno = Alumno::findOrfail($control);
        $idioma=$request->get('idioma');
        if(IdiomasLiberacion::where('control',$control)->count()>0){
            $encabezado="Error de liberación en idioma extranjero";
            $mensaje="No es posible continuar porque el estudiante ya cuenta con la liberación del idioma";
            return view('escolares.no')->with(compact('mensaje','encabezado'));
        }else{
            $encabezado="Liberación de Idioma Extranjero";
            $lengua_extranjera=Idioma::where('id',$idioma)->first();
            return view('escolares.liberar_idioma')->with(compact('control',
                'idioma','alumno','lengua_extranjera','encabezado'));
        }
    }
    public function idiomas_lib3(Request $request){
        $alumno = Alumno::findOrfail($request->get('control'));
        $lib=new IdiomasLiberacion();
        $lib->periodo=null;
        $lib->control=$request->get('control');
        $lib->calif=null;
        $lib->liberacion=null;
        $lib->idioma=$request->get('idioma');
        $lib->opcion=$request->get('opcion');
        $lib->save();
        $encabezado="Liberación de Idioma Extranjero";
        return view('escolares.prelibidiomas')->with(compact('alumno','encabezado'));
    }
    public function idiomas_impre(){
        $encabezado="Impresión de liberación de idioma extranjero";
        return view('escolares.idiomas_imprimir')->with(compact('encabezado'));
    }
    public function idiomas_impre2(Request $request){
        request()->validate([
            'control' => 'required'
        ], [
            'control.required' => 'Debe indicar el número de control'
        ]);
        $control=$request->get('control');
        if(IdiomasLiberacion::where('control',$control)->count()==0){
            $encabezado="Error para imprimir liberación de IE";
            $mensaje="No es posible continuar porque el estudiante no cuenta con la liberación del idioma";
            return view('escolares.no')->with(compact('mensaje','encabezado'));
        }else{
            $alumno = Alumno::findOrfail($control);
            $encabezado="Impresión de liberación de idioma extranjero";
            return view('escolares.prelibidiomas')->with(compact('alumno','encabezado'));
        }
    }
    public function idiomas_consulta(){
        $periodo_actual = (new AccionesController)->periodo();
        $periodos = PeriodoEscolar::orderBy('periodo','desc')->get();
        $idiomas=Idioma::all();
        $encabezado="Idioma Extranjero";
        return view('escolares.idiomas_consulta1')->with(compact('periodo_actual',
            'periodos','idiomas','encabezado'));
    }
    public function idiomas_consulta2(Request $request){
        $periodo=$request->get('periodo');
        $idioma=$request->get('idioma');
        if(IdiomasGrupo::where('periodo',$periodo)->where('idioma',$idioma)->count()>0){
            $nperiodo=PeriodoEscolar::where('periodo',$periodo)->first();
            $nidioma=Idioma::where('id',$idioma)->first();
            $info=(new AccionesController)->consulta_idiomas($periodo,$idioma);
            $encabezado="Idioma Extranjero";
            return view('escolares.idiomas_consulta2')->with(compact('nperiodo',
                'nidioma','info','encabezado'));
        }else{
            $encabezado="Error en consulta de grupos de idioma extranjero";
            $mensaje="No hay grupos registrados para el periodo solicitado";
            return view('escolares.no')->with(compact('mensaje','encabezado'));
        }
    }
}
