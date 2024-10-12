<?php

namespace App\Http\Controllers\Academicos;

use App\Http\Controllers\Acciones\AccionesController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MenuAcademicosController;
use App\Models\Carrera;
use App\Models\MateriaCarrera;
use App\Models\PeriodoEscolar;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Http\Request;

class AcademicosController extends Controller
{
    public function __construct(Dispatcher $events)
    {
        new MenuAcademicosController($events);
    }
    public function index(){
        return view('academicos.index');
    }

    public function existentes(){
        $encabezado="Grupos del período";
        $carreras = Carrera::select('carrera', 'reticula', 'nombre_reducido')
            ->orderBy('carrera')
            ->orderBy('reticula')->get();
        $periodo_semestre = (new AccionesController)->periodo();
        $periodo_actual=$periodo_semestre[0]->periodo;
        $periodos = PeriodoEscolar::orderBy('periodo','DESC')->get();
        return view('academicos.listado')
            ->with(compact('encabezado','carreras','periodo_actual','periodos'));
    }
    public function listado(Request $request){
        $periodo=$request->get('periodo');
        $carr=$request->get('carrera');
        $data=explode('_',$carr);
        $carrera=$data[0]; $ret=$data[1];
        $ncarrera=(new AccionesController)->ncarrera($carrera, $ret);
        $listado=(new AccionesController)->listado_materias($carrera, $ret, $request->get('periodo'));
        $encabezado="Grupos del período";
        return view('academicos.listado2')
            ->with(compact('listado','ncarrera','periodo','encabezado'));
    }
}
