<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Escolares\EscolaresController;


Route::get('/escolares',[EscolaresController::class,'index']);
