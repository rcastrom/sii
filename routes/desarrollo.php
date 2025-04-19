<?php

use App\Http\Controllers\Desarrollo\AulasController;
use App\Http\Controllers\Desarrollo\DesarrolloController;
use App\Http\Controllers\Desarrollo\FechasEvaluacionController;
use App\Http\Controllers\Desarrollo\AspirantesController;
use App\Http\Controllers\PDF\EvalDocCarreraPDFController;
use App\Http\Controllers\PDF\EvalDocDeptoPDFController;
use App\Http\Controllers\PDF\EvalDocDocentePDFController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'desarrollo', 'middleware' => ['auth', 'role:desacad']], function () {
    Route::get('/', [DesarrolloController::class, 'index'])->name('inicio_desarrollo');
    Route::controller(DesarrolloController::class)->prefix('fichas')->group(function () {
        Route::get('/inicio', 'fichas_inicio');
        Route::post('/periodos', 'fichas_inicio_parametros')
            ->name('desarrollo.fichas_inicio');
        Route::get('/carreras', 'carreras_x_ofertar');
        Route::post('/carreras', 'actualizar_carreras_x_ofertar')
            ->name('desarrollo.actualizar_carreras');
        Route::get('/aulas', 'aulas_para_examen');
        Route::post('/aulas', 'alta_aula_examen')->name('desarrollo.alta_salon');
        Route::resource('/admin/aulas', AulasController::class);
    });
    Route::controller(AspirantesController::class)->prefix('asp')->group(function () {
        Route::get('/listado', 'listado');
        Route::post('/listado', 'mostrar')->name('desarrollo.mostrar');
        Route::get('/informacion/{periodo}/{aspirante}','datos_aspirante')
            ->name('desarrollo.datos_aspirante');
        Route::post('/carrera','actualizar_datos_aspirante')
            ->name('desarrollo.actualizar_datos_aspirante');
        Route::post('/contra','contra_aspirante')
            ->name('desarrollo.contra_aspirante');
        Route::post('/pago','pago_aspirante')
            ->name('desarrollo.pago_aspirante');
    });
    Route::controller(DesarrolloController::class)->prefix('eval')->group(function () {
        Route::get('/inicio', 'evaluacion_inicio');
        Route::post('/inicio', 'evaluacion_periodo')
            ->name('desarrollo.periodo_evaluacion');
        Route::resource('/periodos', FechasEvaluacionController::class);
        Route::get('/consulta', 'resultados_evaluacion1');
        Route::post('/consulta', 'resultados_evaluacion2')
            ->name('desarrollo.resultados_evaluacion');
        Route::post('/resultadosxcarrera', EvalDocCarreraPDFController::class)
            ->name('desarrollo.resultados_carrera');
        Route::post('/consultaxdepto', EvalDocDeptoPDFController::class)
            ->name('desarrollo.resultados_departamento');
        Route::post('/consultaxdocente', EvalDocDocentePDFController::class)
            ->name('desarrollo.resultados_docente');
        Route::post('/alumnosfaltaneval', 'listado_alumnos_sin_evaluar')
            ->name('desarrollo.listado_alumnos_sin_evaluar');
    });
    Route::controller(DesarrolloController::class)->prefix('mantenimiento')->group(function () {
        Route::get('/contrasena', 'contrasenia');
        Route::post('/ccontrasena', 'ccontrasenia')->name('desarrollo.contra');
    });
});
