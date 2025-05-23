<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Events\Dispatcher;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

class MenuAcademicosController extends Controller
{
    public function __construct(Dispatcher $events){
        $events->listen(BuildingMenu::class,function(BuildingMenu $event){
            $event->menu->add([
                'text' => 'Inicio',
                'icon' => 'fa fa-home',
                'url' => 'academicos',
            ]);
            $event->menu->add([
                'text' => 'Período',
                'icon' => 'fa fa-calendar-alt',
                'submenu' => [
                    [
                        'text' => 'Grupos existentes',
                        'url' => 'academicos/periodos/existentes',
                        'icon' => 'far fa-circle',
                    ]
                ]
            ]);
            $event->menu->add([
                'text'=>'Estadística',
                'icon'=>'fas fa-chart-pie',
                'submenu'=>[
                    [
                        'text' => 'Población',
                        'url'  => 'academicos/estadistica/prepoblacion',
                        'icon' => 'far fa-circle',
                    ],
                    [
                        'text' => 'Aulas',
                        'url'  => 'academicos/estadistica/aulas',
                        'icon' => 'far fa-circle',
                    ],
                    [
                        'text'=>'Evaluación docente',
                        'url'  => 'academicos/estadistica/evaluacion',
                        'icon' => 'far fa-circle',
                    ],
                ]
            ]);
            $event->menu->add([
                'text'=>'Horarios',
                'icon'=>'fas fa-users',
                'submenu'=>[
                    [
                        'text'=>'Docentes',
                        'url'  => 'academicos/docentes/index',
                        'icon' => 'far fa-circle',
                    ],
                    [
                        'text'=>'No docentes',
                        'url'  => 'academicos/administrativos/nodocente',
                        'icon' => 'far fa-circle',
                    ],
                ],
            ]);
            $event->menu->add([
                'text' => 'Mantenimiento',
                'icon' => 'fa fa-wrench',
                'submenu' => [
                    [
                        'text' => 'Contraseña',
                        'url'  => 'academicos/mantenimiento/contrasena',
                        'icon' => 'far fa-circle',
                    ]
                ]
            ]);
        });
    }
}
