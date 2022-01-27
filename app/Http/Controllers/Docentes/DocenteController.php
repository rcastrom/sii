<?php

namespace App\Http\Controllers\Docentes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\Events\Dispatcher;
use App\Http\Controllers\MenuDocenteController;


class DocenteController extends Controller
{
    public function __construct(Dispatcher $events)
    {
        new MenuDocenteController($events);
    }
    public function index(){
        return view('personal.index');
    }
}
