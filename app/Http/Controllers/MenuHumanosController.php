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
                ]
            ]);
        });
    }
}
