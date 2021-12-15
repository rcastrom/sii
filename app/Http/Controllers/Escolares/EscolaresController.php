<?php

namespace App\Http\Controllers\Escolares;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EscolaresController extends Controller
{
    public function index(){
        return view('escolares.index');
    }
}
