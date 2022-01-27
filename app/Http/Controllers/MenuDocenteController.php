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
        });
    }
}
