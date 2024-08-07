<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Desarrollo\DesarrolloController;
use App\Http\Controllers\Desarrollo\AulasController;
use App\Http\Controllers\Desarrollo\FechasEvaluacionController;

Route::group(['prefix' => 'desarrollo', 'middleware' => ['auth','role:desacad']], function (){
    Route::get('/',[DesarrolloController::class,'index'])->name('inicio_desarrollo');
    Route::controller(DesarrolloController::class)->prefix('fichas')->group(function (){
        Route::get('/inicio','fichas_inicio');
        Route::post('/periodos','fichas_inicio_parametros')->name('desarrollo.fichas_inicio');
        Route::get('/carreras','carreras_x_ofertar');
        Route::post('/carreras','actualizar_carreras_x_ofertar')->name('desarrollo.actualizar_carreras');
        Route::get('/aulas','aulas_para_examen');
        Route::post('/aulas','alta_aula_examen')->name('desarrollo.alta_salon');
        Route::resource('/admin/aulas',AulasController::class);
    });
    Route::controller(DesarrolloController::class)->prefix('eval')->group(function (){
        Route::get('/inicio','evaluacion_inicio');
        Route::post('/inicio','evaluacion_periodo')->name('desarrollo.periodo_evaluacion');
        Route::resource('/periodos',FechasEvaluacionController::class);
        Route::get('/consulta','resultados_evaluacion1');
        Route::post('/consulta','resultados_evaluacion2')->name('desarrollo.resultados_evaluacion');
    });
});
