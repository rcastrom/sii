<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Events\Dispatcher;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

class MenuDocenteController extends Controller
{
    public function __construct(Dispatcher $events)
    {
        $events->listen(BuildingMenu::class, function (BuildingMenu $event) {
            $event->menu->add([
                'text'=>'Inicio',
                'icon'=>'fa fa-home',
                'url'=>'personal'
            ]);
            $event->menu->add([
                'text'=>'Período',
                'icon'=>'fas fa-user-friends',
                'submenu'=>[
                    [
                        'text'=>'Grupos',
                        'url'=>'personal/periodo/grupos',
                        'icon' => 'far fa-circle',
                    ],
                    [
                        'text'=>'Residencias',
                        'url'=>'personal/periodo/residencias',
                        'icon'=>'far fa-circle',
                    ],
                ]
            ]);
            $event->menu->add([
                'text'=>'Calificaciones',
                'icon'=>'fa fa-th',
                'submenu'=>[
                    [
                        'text'=>'Parciales',
                        'url'=>'personal/calif/parciales',
                        'icon'=>'far fa-circle',
                    ],
                    [
                        'text'=>'Consulta',
                        'url'=>'personal/calif/consulta',
                        'icon'=>'far fa-circle',
                    ]
                ]
            ]);
            $event->menu->add([
                'text'=>'Evaluación al Docente',
                'icon'=>'fa fa-list-alt',
                'submenu'=>[
                    [
                        'text'=>'Consulta',
                        'url'=>'personal/eval/consulta',
                        'icon'=>'far fa-circle',
                    ]
                ]
            ]);
            $event->menu->add([
                'text'=>'Utilería',
                'icon'=>'fas fa-chalkboard-teacher',
                'submenu'=>[
                    [
                        'text'=>'Cambio de contraseña',
                        'url'=>'personal/utileria/contra',
                        'icon'=>'far fa-circle'
                    ]
                ]
            ]);
        });
    }
}
