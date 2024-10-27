<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Events\Dispatcher;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

class MenuAlumnosController extends Controller
{
    public function __construct(Dispatcher $events){
        $events->listen(BuildingMenu::class,function(BuildingMenu $event){
            $event->menu->add([
                'text' => 'Inicio',
                'icon' => 'fa fa-home',
                'url' => 'estudiante',
            ]);
            $event->menu->add([
                'text' => 'Historial',
                'icon' => 'fa fa-calendar-alt',
                'submenu' => [
                    [
                        'text' => 'Kárdex',
                        'url' => 'estudiante/historial/kardex/1',
                        'icon' => 'far fa-circle',
                    ],
                    [
                        'text' => 'Boleta',
                        'url' => 'estudiante/historial/boleta',
                        'icon' => 'far fa-circle',
                    ],
                    [
                        'text' => 'Retícula',
                        'url' => 'estudiante/historial/reticula',
                        'icon' => 'far fa-circle',
                    ],
                ]
            ]);
            $event->menu->add([
                'text'=>'Semestre en curso',
                'icon'=>'fa fa-star',
                'submenu'=>[
                    [
                        'text'=>'Horario',
                        'url'=>'estudiante/periodo/horario',
                        'icon'=>'far fa-circle',
                    ],
                    [
                        'text'=>'Evaluación al docente',
                        'url'=>'estudiante/periodo/eval',
                        'icon'=>'far fa-circle',
                    ]
                ]
            ]);
            $event->menu->add([
                'text'=>'Reinscripción',
                'icon'=>'fa fa-university',
                'submenu'=>[
                    [
                        'text'=>'Reinscripción',
                        'url'=>'estudiante/reinscripcion/',
                        'icon'=>'far fa-circle',
                    ],
                ]
            ]);
        });
    }
}
