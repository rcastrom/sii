<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Events\Dispatcher;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;


class MenuEscolaresController extends Controller
{
    public function __construct(Dispatcher $events)
    {
        $events->listen(BuildingMenu::class, function (BuildingMenu $event) {
            $event->menu->add([
                'text'=>'Inicio',
                'icon'=>'fa fa-home',
                'url'=>'escolares'
            ]);
            $event->menu->add([
                'text' => 'Alumnos',
                'icon' => 'fas fa-user-friends',
                'submenu' => [
                    [
                        'text' => 'Consulta',
                        'url'  => 'escolares/alumnos/consulta',
                        'icon' => 'far fa-circle',
                    ],
                    [
                        'text' => 'Alta',
                        'url'  => 'escolares/alumnos/alta',
                        'icon' => 'far fa-circle',
                    ]
                ]
            ]);
            $event->menu->add([
                'text' => 'Aspirantes',
                'icon' => 'fa fa-upload',
                'submenu' => [
                    [
                        'text' => 'Generar ficha',
                        'url'  => 'escolares/aspirantes/ficha',
                        'icon' => 'far fa-circle',
                    ],
                    [
                        'text' => 'Estadística',
                        'url'  => 'escolares/aspirantes/estadistica',
                        'icon' => 'far fa-circle',
                    ],
                ]
            ]);
            $event->menu->add([
                'text' => 'Períodos',
                'icon' => 'far fa-calendar-alt',
                'submenu' => [
                    [
                        'text' => 'Creación',
                        'url'  => 'escolares/periodos/periodo_escolar/create',
                        'icon' => 'far fa-circle',
                    ],
                    [
                        'text' => 'Modificación',
                        'url'  => 'escolares/periodos/modificar',
                        'icon' => 'far fa-circle',
                    ],
                    [
                        'text' => 'Reinscripción',
                        'url'  => 'escolares/periodos/reinscripcion',
                        'icon' => 'far fa-circle',
                    ],
                    [
                        'text' => 'Cierre de semestre',
                        'url'  => 'escolares/periodos/cierre',
                        'icon' => 'far fa-circle',
                    ]
                ]
            ]);
            $event->menu->add([
                'text' => 'Actas',
                'icon' => 'fas fa-square-root-alt',
                'submenu' => [
                    [
                        'text' => 'Actas',
                        'url'  => 'escolares/actas/inicio',
                        'icon' => 'far fa-circle',
                    ],
                    [
                        'text' => 'Registro',
                        'url'  => 'escolares/actas/registro',
                        'icon' => 'far fa-circle',
                    ],
                    [
                        'text' => 'Mantenimiento',
                        'url'  => 'escolares/actas/mantenimiento',
                        'icon' => 'far fa-circle',
                    ],
                    [
                        'text' => 'Foliado',
                        'url'  => 'escolares/actas/foliado',
                        'icon' => 'far fa-circle',
                    ]
                ]
            ]);
            $event->menu->add([
                'text' => 'Carreras',
                'icon' => 'fas fa-chalkboard-teacher',
                'submenu' => [
                    [
                        'text' => 'Alta',
                        'url'  => 'escolares/carreras/alta',
                        'icon' => 'far fa-circle',
                    ],
                    [
                        'text' => 'Especialidades',
                        'url'  => 'escolares/carreras/especialidades',
                        'icon' => 'far fa-circle',
                    ],
                    [
                        'text' => 'Materias',
                        'url'  => 'escolares/carreras/materias',
                        'icon' => 'far fa-circle',
                    ]
                ]
            ]);
            $event->menu->add([
                'text' => 'Idiomas',
                'icon' => 'fas fa-language',
                'submenu' => [
                    [
                        'text' => 'Liberación',
                        'url'  => 'escolares/idiomas/liberacion',
                        'icon' => 'far fa-circle',
                    ],
                    [
                        'text' => 'Imprimir',
                        'url'  => 'escolares/idiomas/imprimir',
                        'icon' => 'far fa-circle',
                    ],
                    [
                        'text' => 'Consulta',
                        'url'  => 'escolares/idiomas/consulta',
                        'icon' => 'far fa-circle',
                    ]
                ]
            ]);
            $event->menu->add([
                'text' => 'Estadística',
                'icon' => 'fas fa-chart-pie',
                'submenu' => [
                    [
                        'text' => 'Población',
                        'url'  => 'escolares/estadistica/consulta',
                        'icon' => 'far fa-circle',
                    ]
                ]
            ]);
            $event->menu->add([
                'text' => 'Mantenimiento',
                'icon' => 'fa fa-wrench',
                'submenu' => [
                    [
                        'text' => 'BD',
                        'url'  => 'escolares/mantenimiento/base',
                        'icon' => 'far fa-circle',
                    ],
                    [
                        'text' => 'Contraseña',
                        'url'  => 'escolares/mantenimiento/contrasena',
                        'icon' => 'far fa-circle',
                    ]
                ]
            ]);
        });
    }
}
