<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Events\Dispatcher;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

class MenuDivisionController extends Controller
{
    public function __construct(Dispatcher $events)
    {
        $events->listen(BuildingMenu::class, function (BuildingMenu $event) {
            $event->menu->add([
                'text'=>'Inicio',
                'icon'=>'fa fa-home',
                'url'=>'division'
            ]);
            $event->menu->add([
                'text'=>'Grupos',
                'icon'=>'fa fa-university',
                'submenu'=>[
                    [
                        'text' => 'Creación',
                        'url'  => 'division/grupos/alta',
                        'icon' => 'far fa-circle',
                    ],
                    [
                        'text' => 'Creación de Paralelos',
                        'url'  => 'division/grupos/paralelo',
                        'icon' => 'far fa-circle',
                    ],
                    [
                        'text' => 'Existentes',
                        'url'  => 'division/grupos/existentes',
                        'icon' => 'far fa-circle',
                    ]
                ]
            ]);
            $event->menu->add([
                'text'=>'Alumnos',
                'icon'=>'fas fa-user-friends',
                'submenu'=>[
                    [
                        'text' => 'Consulta',
                        'url'  => 'division/alumnos/consulta',
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
                        'url'  => 'division/estadistica/prepoblacion',
                        'icon' => 'far fa-circle',
                    ],
                    [
                        'text' => 'Aulas',
                        'url'  => 'division/estadistica/aulas',
                        'icon' => 'far fa-circle',
                    ]
                ]
            ]);
            $event->menu->add([
                'text' => 'Mantenimiento',
                'icon' => 'fa fa-wrench',
                'submenu' => [
                    [
                        'text' => 'Contraseña',
                        'url'  => 'division/mantenimiento/contrasena',
                        'icon' => 'far fa-circle',
                    ]
                ]
            ]);
        });
    }
}
