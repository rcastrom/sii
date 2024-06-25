<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Events\Dispatcher;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

class MenuDesarrolloController extends Controller
{
    public function __construct(Dispatcher $events)
    {
        $events->listen(BuildingMenu::class, function (BuildingMenu $event){
            $event->menu->add([
                'text'=>'Inicio',
                'icon'=>'fa fa-home',
                'url'=>'desarrollo'
            ]);
            $event->menu->add([
                'text'=>'Fichas',
                'icon'=>'fa fa-calendar',
                'submenu'=>[
                    [
                        'text' => 'Apertura',
                        'url'  => 'desarrollo/fichas/inicio',
                        'icon' => 'far fa-circle',
                    ],
                    [
                        'text' => 'Carreras',
                        'url'  => 'desarrollo/fichas/carreras',
                        'icon' => 'far fa-circle',
                    ],
                    [
                        'text' => 'Aulas para examen',
                        'url'  => 'desarrollo/fichas/aulas',
                        'icon' => 'far fa-circle',
                    ],
                ]
            ]);
        });
    }
}
