<?php

use App\Http\Controllers\Desarrollo\AulasController;
use App\Http\Controllers\Desarrollo\DesarrolloController;
use App\Http\Controllers\Desarrollo\FechasEvaluacionController;
use App\Http\Controllers\Desarrollo\PropedeuticoController;
use App\Http\Controllers\Desarrollo\AspirantesController;
use App\Http\Controllers\Desarrollo\FechasExamenController;
use App\Http\Controllers\Acciones\AspirantesNuevoIngresoController;
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
        Route::resource('/fechas',FechasExamenController::class);
    });
    Route::controller(PropedeuticoController::class)->prefix('prop')->group(function () {
        Route::get('/grupos', 'grupos');
        Route::post('/grupos', 'alta_grupo')
            ->name('desarrollo.alta_grupo');
        Route::get('/grupos/informe/{id}/{periodo}', 'informe_grupo')
            ->name('desarrollo.informe_grupo');
        Route::get('/grupos/eliminar/{id}/{periodo}', 'grupo_eliminar')
            ->name('desarrollo.grupo_eliminar');
        Route::post('/grupo/eliminar','eliminar_grupo')
            ->name('desarrollo.eliminar_grupo');
        Route::get('/grupos/aulas/{id}/{periodo}', 'aula_grupo')
            ->name('desarrollo.aula_grupo');
        Route::post('/grupos/aulas', 'asignar_aula_propedeutico')
            ->name('desarrollo.asignar_aula_propedeutico');
        Route::get('/grupos/editar/{id}/{periodo}', 'grupos_editar');
        Route::get('/grupos/maestro/{id}/{periodo}', 'docente_grupo')
            ->name('desarrollo.docente_grupo');
        Route::post('/grupos/maestro','asignar_maestro_propedeutico')
            ->name('desarrollo.asignar_maestro_propedeutico');
        Route::post('/grupos/editar', 'grupos_editar')
            ->name('desarrollo.grupos_editar');
    });
    Route::controller(AspirantesController::class)->prefix('asp')->group(function () {
        Route::get('/estadistica', 'estadistica');
        Route::post('/estadistica', 'fichas_concentrado_estadistico')
            ->name('desarrollo.fichas_concentrado_estadistico');
        Route::get('/listado', 'listado');
        Route::post('/listado', 'mostrar')->name('desarrollo.mostrar');
        Route::get('/informacion/{periodo}/{aspirante}','datos_aspirante')
            ->name('desarrollo.datos_aspirante');
        Route::get('/excel/{periodo}',[AspirantesNuevoIngresoController::class,'fichas_concentrado_excel'])
            ->name('desarrollo.fichas_concentrado_excel');
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
