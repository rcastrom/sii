<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Events\Dispatcher;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

class MenuAdminController extends Controller
{
    public function __construct(Dispatcher $events){
        $events->listen(BuildingMenu::class, function (BuildingMenu $event) {
           $event->menu->add([
              'text' => 'Inicio',
              'icon' => 'fa fa-home',
              'url' => 'admin',
           ]);
            $event->menu->add([
                'text' => 'Usuarios',
                'icon' =>'fas fa-user-plus',
                'submenu' => [
                    [
                        'text' => 'Alta',
                        'url' => 'admin/usuarios/alta',
                        'icon' => 'far fa-circle',
                    ]
                ]
            ]);
        });
    }
}
