<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Events\Dispatcher;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

class MenuHumanosController extends Controller
{
    public function __construct(Dispatcher $events){
        $events->listen(BuildingMenu::class, function (BuildingMenu $event){
            $event->menu->add([
                'text'=>'Inicio',
                'icon'=>'fa fa-home',
                'url'=>'rechumanos'
            ]);
            $event->menu->add([
                'text'=>'Personal',
                'icon'=>'fa fa-users',
                'submenu'=>[
                    [
                        'text'=>'Alta',
                        'url'=>'rechumanos/personal/alta',
                        'icon' => 'far fa-circle',
                    ],
                    [
                        'text'=>'Listado',
                        'url'=>'rechumanos/personal/listado',
                        'icon' => 'far fa-circle',
                    ],
                    [
                        'text'=>'Exportar CSV',
                        'url'=>'rechumanos/personal/exportar',
                        'icon' => 'far fa-circle',
                    ],
                ]
            ]);
            $event->menu->add([
                'text'=>'Plazas',
                'icon'=>'fa fa-cash-register',
                'submenu'=>[
                    [
                        'text'=>'Listado',
                        'url'=>'rechumanos/plazas/listado',
                        'icon'=>'far fa-circle',
                    ]
                ]
            ]);
            $event->menu->add([
                'text'=>'Nombramientos',
                'icon'=>'fa fa-star',
                'submenu'=>[
                    [
                        'text'=>'Listado',
                        'url'=>'rechumanos/jefaturas/listado',
                        'icon'=>'far fa-circle',
                    ]
                ]
            ]);
        });
    }
}
