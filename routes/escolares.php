<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Escolares\EscolaresController;

Route::group(['prefix'=>'escolares','middleware'=>['auth','role:escolares']],function () {
    Route::get('/', [EscolaresController::class, 'index'])->name('inicio_escolares');
    Route::group(['prefix'=>'alumnos'],function (){
        Route::get('/consulta',[EscolaresController::class,'buscar']);
        Route::post('/buscar',[EscolaresController::class,'busqueda'])
            ->name('escolares.buscar');
    });
});
