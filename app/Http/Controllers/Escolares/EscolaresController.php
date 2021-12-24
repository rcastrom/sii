<?php

namespace App\Http\Controllers\Escolares;

use App\Models\Alumno;
use App\Models\AlumnosGeneral;
use App\Models\PeriodoEscolar;
use App\Models\EstatusAlumno;
use App\Models\Especialidad;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\MenuEscolaresController;
use Illuminate\Contracts\Events\Dispatcher;
use App\Http\Controllers\Acciones\AccionesController;

class EscolaresController extends Controller
{
    public function __construct(Dispatcher $events)
    {
        new MenuEscolaresController($events);
    }
    public function index(){
        return view('escolares.index');
    }
    public function buscar(){
        $encabezado="Búsqueda de Estudiante";
        return view('escolares.busqueda')->with(compact('encabezado'));
    }
    public function busqueda(Request $request)
    {
        request()->validate([
            'control' => 'required',
        ], [
            'control.required' => 'Debe indicar un dato para ser buscado'
        ]);
        $encabezado="Búsqueda de Estudiante";
        $control = $request->control;
        $tbusqueda = $request->tbusqueda;
        if ($tbusqueda == "1") {
            $alumno = Alumno::findOrfail($control);
            $datos = AlumnosGeneral::where('no_de_control',$control)->first();
            if(empty($datos)){
                $info=collect(['domicilio_calle','domicilio_colonia','codigo_postal','telefono']);
                $datos=$info->combine(['','','','']);
                $bandera=0;
            }else{
                $bandera=1;
            }
            $ncarrera = (new AccionesController)->ncarrera($alumno->carrera,$alumno->reticula);
            $periodo = (new AccionesController)->periodo();
            $periodos = PeriodoEscolar::orderBy('periodo', 'desc')
                ->get();
            $ingreso = PeriodoEscolar::where('periodo', $alumno->periodo_ingreso_it)
                ->select('identificacion_corta')->first();
            $estatus = EstatusAlumno::where('estatus', $alumno->estatus_alumno)->get();
            $espe = Especialidad::where('especialidad', $alumno->especialidad)
                ->where('carrera', $alumno->carrera)->where('reticula', $alumno->reticula)->first();
            if (empty($espe)) {
                $especialidad = "POR ASIGNAR";
            } else {
                $especialidad = $espe->nombre_especialidad;
            }
            return view('escolares.datos')->
            with(compact('alumno', 'ncarrera', 'datos', 'control', 'periodo',
                'periodos', 'estatus', 'especialidad', 'ingreso','bandera','encabezado'));
        } elseif ($tbusqueda == '2') {
            $arroja = Alumno::where('apellido_paterno', strtoupper($control))
                ->orWhere('apellido_materno', strtoupper($control))
                ->orWhere('nombre_alumno', strtoupper($control))
                ->orderBY('apellido_paterno')
                ->orderBy('apellido_materno')
                ->orderBy('nombre_alumno')
                ->get();
            $periodo = $this->periodo();
            $periodos = PeriodoEscolar::all()
                ->orderBy('periodo', 'desc')
                ->get();
            return view('escolares.datos2')->with(compact('arroja', 'periodo', 'periodos'));
        }
    }
}
