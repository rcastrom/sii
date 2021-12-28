<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Escolares\EscolaresController;
use App\Http\Controllers\PDF\ConstanciaPDFController;

Route::group(['prefix'=>'escolares','middleware'=>['auth','role:escolares']],function () {
    Route::get('/', [EscolaresController::class, 'index'])->name('inicio_escolares');
    Route::group(['prefix'=>'alumnos'],function (){
        Route::get('/consulta',[EscolaresController::class,'buscar']);
        Route::post('/buscar',[EscolaresController::class,'busqueda'])
            ->name('escolares.buscar');
        Route::post('/acciones',[EscolaresController::class, 'accion'])
            ->name('escolares.accion');
        Route::post('/accionesk',[EscolaresController::class, 'accionk'])
            ->name('escolares.accion_kardex');
        Route::post('/accionesk_alta',[EscolaresController::class, 'accionkalta'])
            ->name('escolares.accion_kardex_alta');
        Route::post('/periodo_k',[EscolaresController::class, 'accionkperiodo'])
            ->name('escolares.accion_kardex_modificar1');
        Route::get('/modificar/{periodo}/{control}/{materia}',[EscolaresController::class, 'modificarkardex']);
        Route::get('/eliminar/{periodo}/{control}/{materia}',[EscolaresController::class, 'eliminarkardex']);
        Route::post('/actualizar/kardex',[EscolaresController::class, 'kardexupdate'])
            ->name('escolares.accion_actualiza_kardex');
        Route::post('/constancia',[ConstanciaPDFController::class,'crearPDF'])
            ->name('escolares.constancia');
        Route::post('/imprimir_boleta',[EscolaresController::class, 'imprimirboleta'])
            ->name('escolares.imprimir boleta');
        Route::post('/actualizar/estatus',[EscolaresController::class, 'estatusupdate'])
            ->name('escolares.accion_actualiza_estatus');
        Route::post('/actualizar/especialidad',[EscolaresController::class, 'especialidadupdate'])
            ->name('escolares.accion_actualiza_especialidad');
        Route::post('/actualizar/carrera',[EscolaresController::class, 'carreraupdate'])
            ->name('escolares.accion_actualiza_carrera');
        Route::post('/eliminar/',[EscolaresController::class, 'alumnodelete'])
            ->name('escolares.accion_borrar');
        Route::post('/baja/',[EscolaresController::class, 'alumnobajatemp'])
            ->name('escolares.accion_bajatemp');
        Route::post('/nss/',[EscolaresController::class, 'alumnonss'])
            ->name('escolares.nss');
    });
});
