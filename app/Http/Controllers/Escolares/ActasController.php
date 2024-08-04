<?php

namespace App\Http\Controllers\Escolares;

use App\Http\Controllers\Acciones\AccionesController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MenuEscolaresController;
use App\Models\Grupo;
use App\Models\PeriodoEscolar;
use App\Models\Personal;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Http\Request;

class ActasController extends Controller
{
    public function __construct(Dispatcher $events)
    {
        new MenuEscolaresController($events);
    }
    public function periodoactas_m1()
    {
        $periodo_actual = (new AccionesController)->periodo();
        $periodos = PeriodoEscolar::orderBy('periodo','DESC')->get();
        $encabezado="Actas del período";
        return view('escolares.periodo_actas_1')
            ->with(compact('periodo_actual', 'periodos','encabezado'));
    }
    public function periodoactas_m2(Request $request)
    {
        $periodo = $request->get('periodo');
        $docentes = Grupo::where('periodo', $periodo)
            ->join('personal', 'personal.rfc', '=', 'grupos.rfc')
            ->select('grupos.rfc', 'apellidos_empleado', 'nombre_empleado')
            ->distinct()
            ->orderBy('apellidos_empleado', 'ASC')
            ->orderBy('nombre_empleado', 'ASC')
            ->get();
        $nperiodo = PeriodoEscolar::where('periodo', $periodo)->first();
        $encabezado="Actas del período";
        return view('escolares.periodo_actas_2')
            ->with(compact('periodo', 'docentes',
            'nperiodo','encabezado'));
    }
    public function periodoactas_m3(Request $request)
    {
        $periodo = $request->get('periodo');
        $docente = $request->get('docente');
        $grupos = Grupo::where('periodo', $periodo)
            ->where('rfc', $docente)
            ->join('materias', 'materias.materia', '=', 'grupos.materia')
            ->select('grupos.materia', 'grupo', 'nombre_abreviado_materia')
            ->orderBy('nombre_abreviado_materia', 'ASC')
            ->get();
        $ndocente = Personal::where('rfc', $docente)->first();
        $nperiodo = PeriodoEscolar::where('periodo', $periodo)->first();
        $encabezado="Actas del período";
        return view('escolares.periodo_actas_3')->with(compact('periodo',
            'docente', 'nperiodo', 'grupos', 'ndocente','encabezado'));
    }
}
