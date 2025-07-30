<?php

namespace App\Http\Controllers\Acciones;

use App\Exports\FichasExport;
use App\Http\Controllers\Controller;
use App\Models\Carrera;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AspirantesNuevoIngresoController extends Controller
{
    /**
     * @return string[]
     */
    public function carreras_por_ofertar(): array
    {
        $carreras = Carrera::select(['carrera', 'nombre_carrera'])
            ->where('nivel_escolar', '=', 'L')
            ->where('ofertar', '=', 1)
            ->orderBy('carrera')
            ->get();
        $carreras_por_ofertar = array();
        $nombre_de_carreras = array();
        foreach ($carreras as $carrera) {
            $carreras_por_ofertar[] = '"' . trim($carrera->carrera) . '"';
            $nombre_de_carreras[] = '"' . trim($carrera->nombre_carrera) . '"';
        }
        $carreras_ofertar = "{" . implode(",", $carreras_por_ofertar) . "}";
        $nombre_carreras = "{" . implode(",", $nombre_de_carreras) . "}";
        return array($carreras_ofertar, $nombre_carreras);
    }

    public function fichas_concentrado_excel($periodo)
    {
        list($carreras_ofertar, $nombre_carreras) = (new AspirantesNuevoIngresoController)->carreras_por_ofertar();
        $concentrado_total=(new AccionesController)->concentrado_fichas_excel($periodo,
            $carreras_ofertar,$nombre_carreras);
        $datos=collect($concentrado_total);
        return Excel::download(new FichasExport($datos), 'fichas_concentrados_'.$periodo.'.xlsx');
    }
}
