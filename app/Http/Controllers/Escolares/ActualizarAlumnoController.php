<?php

namespace App\Http\Controllers\Escolares;

use App\Http\Controllers\Acciones\AccionesController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MenuEscolaresController;
use App\Models\Alumno;
use App\Models\AlumnosGeneral;
use App\Models\HistoriaAlumno;
use App\Models\MateriaCarrera;
use App\Models\PlanDeEstudio;
use App\Models\SeleccionMateria;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Http\Request;

class ActualizarAlumnoController extends Controller
{
    public function __construct(Dispatcher $events)
    {
        new MenuEscolaresController($events);
    }
    public function estatusupdate(Request $request)
    {
        $control = $request->get('control');
        $estatus = $request->get('situacion');
        Alumno::find($control)->update([
            'estatus_alumno' => $estatus
        ]);
        if($estatus=="EGR"){
            //Modificar información en la tabla de alumnos
            (new AccionesController)->actualizar_egresado($control);
            $inicio = Alumno::find($control)->periodo_ingreso_it;
            $fin = Alumno::find($control)->ultimo_periodo_inscrito;
            $semestres = (new AccionesController)->semreal($inicio,$fin);
            Alumno::where('no_de_control',$control)->update([
                'semestre' => $semestres,
            ]);
        }
        $encabezado="Estatus de alumno modificado";
        $mensaje="Se cambió el estatus del alumno";
        return view('escolares.si')->with(compact('encabezado','mensaje'));
    }
    public function especialidadupdate(Request $request)
    {
        $control = $request->get('control');
        $especialidad = $request->get('espe');
        Alumno::where('no_de_control', $control)->update([
            'especialidad' => $especialidad
        ]);
        $encabezado="Asignación de Especialidad";
        $mensaje="Se llevó a cabo la asignación de una especialidad al estudiante";
        return view('escolares.si')->with(compact('encabezado','mensaje'));
    }
    public function carreraupdate(Request $request)
    {
        $control = $request->get('control');
        $alumno = Alumno::where('no_de_control',$control)->first();
        $carrera_n0 = $request->get('carrera_n');
        $data = explode("_", $carrera_n0);
        $carrera_n = $data[0];
        $ret_n = $data[1];
        $materias = HistoriaAlumno::where('no_de_control', $control)
            ->select('periodo', 'materia', 'tipo_evaluacion')->get();
        $i = 0;
        $plan = PlanDeEstudio::max('plan_de_estudio');
        foreach ($materias as $historia) {
            $cve_of = MateriaCarrera::where('carrera', $alumno->carrera)
                ->where('reticula', $alumno->reticula)->where('materia', $historia->materia)->first();
            if (!empty($cve_of)) {
                if (MateriaCarrera::where([
                        'carrera'=>$carrera_n,
                        'reticula'=>$ret_n,
                        'clave_oficial_materia'=>trim($cve_of->clave_oficial_materia)
                    ])->count() > 0) {
                    $nmat = MateriaCarrera::where([
                        'carrera'=>$carrera_n,
                        'reticula'=>$ret_n,
                        'clave_oficial_materia'=>trim($cve_of->clave_oficial_materia)
                    ])->select('materia')->first();
                    HistoriaAlumno::where('no_de_control', $control)->where('periodo', $historia->periodo)
                        ->where('materia', $historia->materia)->update([
                            'materia' => $nmat->materia,
                            'tipo_evaluacion' => 'RC',
                            'plan_de_estudios' => $plan
                        ]);
                    $i++;
                }else{
                    HistoriaAlumno::where([
                        'no_de_control'=>$control,
                        'periodo'=>$historia->periodo,
                        'materia'=>$historia->materia
                    ])->delete();
                }
            }
        }
        Alumno::where('no_de_control', $control)->update([
            'carrera' => $carrera_n,
            'reticula' => $ret_n,
            'plan_de_estudios' => $plan
        ]);
        $encabezado="Cambio de carrera realizado";
        $mensaje="Se modificaron ".$i." en el historial del alumno";
        return view('escolares.si')->with(compact('encabezado','mensaje'));
    }
    public function alumnodelete(Request $request)
    {
        $control = $request->get('control');
        //Primero, checar si tiene materias activas
        SeleccionMateria::where('no_de_control', $control)->delete();
        //Ahora, se borra su historial
        HistoriaAlumno::where('no_de_control', $control)->delete();
        //Borrar datos generales
        AlumnosGeneral::where('no_de_control', $control)->delete();
        //Eliminar alumno
        Alumno::where('no_de_control', $control)->delete();
        $encabezado="Baja de número de control";
        $mensaje="Se eliminó todo el historial académico del estudiante, así como sus datos generales";
        return view('escolares.si')->with(compact('encabezado','mensaje'));
    }
    public function alumnobajatemp(Request $request)
    {
        $control = $request->get('control');
        $periodo = $request->get('periodo');
        $tbaja = $request->get('tbaja');
        //Primero, checar si tiene materias activas
        if(SeleccionMateria::where([
                'no_de_control'=>$control,
                'periodo'=>$periodo
            ])->count()>0){
            SeleccionMateria::where('no_de_control', $control)
                ->where('periodo', $periodo)->delete();
        }
        Alumno::where('no_de_control', $control)->update([
            'estatus_alumno' => $tbaja
        ]);
        $encabezado="Baja de número de control";
        $mensaje="Se actualizó el estatus del estudiante";
        return view('escolares.si')->with(compact('encabezado','mensaje'));
    }
    public function alumnonss(Request $request)
    {
        request()->validate([
            'nss' => 'required',
        ], [
            'nss.required' => 'Debe indicar el NSS ha ser registrado'
        ]);
        $control = $request->get('control');
        $nss = trim($request->get('nss'));
        Alumno::where('no_de_control', $control)->update([
            'nss' => $nss
        ]);
        $encabezado="Asignación de NSS";
        $mensaje="Se actualizó la información del estudiante";
        return view('escolares.si')->with(compact('encabezado','mensaje'));
    }
}
