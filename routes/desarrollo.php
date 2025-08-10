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
        Route::get('/inicio', [DesarrolloController::class,'fichas_inicio']);
        Route::post('/periodos', [DesarrolloController::class,'fichas_inicio_parametros'])
            ->name('desarrollo.fichas_inicio');
        Route::get('/carreras', [DesarrolloController::class,'carreras_x_ofertar']);
        Route::post('/carreras', [DesarrolloController::class,'actualizar_carreras_x_ofertar'])
            ->name('desarrollo.actualizar_carreras');
        Route::get('/aulas', [DesarrolloController::class,'aulas_para_examen']);
        Route::post('/aulas', [DesarrolloController::class,'alta_aula_examen'])->name('desarrollo.alta_salon');
        Route::resource('/admin/aulas', AulasController::class);
        Route::resource('/fechas',FechasExamenController::class);
    });
    Route::controller(PropedeuticoController::class)->prefix('prop')->group(function () {
        Route::get('/grupos', [PropedeuticoController::class,'grupos']);
        Route::post('/grupos', [PropedeuticoController::class,'alta_grupo'])
            ->name('desarrollo.alta_grupo');
        Route::get('/grupos/informe/{id}/{periodo}', [PropedeuticoController::class,'informe_grupo'])
            ->name('desarrollo.informe_grupo');
        Route::get('/grupos/eliminar/{id}/{periodo}', [PropedeuticoController::class,'grupo_eliminar'])
            ->name('desarrollo.grupo_eliminar');
        Route::post('/grupo/eliminar',[PropedeuticoController::class,'eliminar_grupo'])
            ->name('desarrollo.eliminar_grupo');
        Route::get('/grupos/aulas/{id}/{periodo}', [PropedeuticoController::class,'aula_grupo'])
            ->name('desarrollo.aula_grupo');
        Route::post('/grupos/aulas', [PropedeuticoController::class,'asignar_aula_propedeutico'])
            ->name('desarrollo.asignar_aula_propedeutico');
        Route::get('/grupos/editar/{id}/{periodo}', [PropedeuticoController::class,'grupos_editar']);
        Route::get('/grupos/maestro/{id}/{periodo}', [PropedeuticoController::class,'docente_grupo'])
            ->name('desarrollo.docente_grupo');
        Route::post('/grupos/maestro',[PropedeuticoController::class,'asignar_maestro_propedeutico'])
            ->name('desarrollo.asignar_maestro_propedeutico');
        Route::post('/grupos/editar', [PropedeuticoController::class,'grupos_editar'])
            ->name('desarrollo.grupos_editar');
    });
    Route::controller(AspirantesController::class)->prefix('asp')->group(function () {
        Route::get('/estadistica', [AspirantesController::class,'estadistica']);
        Route::post('/estadistica', [AspirantesController::class,'fichas_concentrado_estadistico'])
            ->name('desarrollo.fichas_concentrado_estadistico');
        Route::get('/listado', [AspirantesController::class,'listado']);
        Route::post('/listado', [AspirantesController::class,'mostrar'])->name('desarrollo.mostrar');
        Route::get('/informacion/{periodo}/{aspirante}',[AspirantesController::class,'datos_aspirante'])
            ->name('desarrollo.datos_aspirante');
        Route::get('/excel/{periodo}',[AspirantesNuevoIngresoController::class,'fichas_concentrado_excel'])
            ->name('desarrollo.fichas_concentrado_excel');
        Route::post('/carrera',[AspirantesController::class,'actualizar_datos_aspirante'])
            ->name('desarrollo.actualizar_datos_aspirante');
        Route::post('/contra',[AspirantesController::class,'contra_aspirante'])
            ->name('desarrollo.contra_aspirante');
        Route::post('/pago',[AspirantesController::class,'pago_aspirante'])
            ->name('desarrollo.pago_aspirante');
        Route::get('/seleccionar',[AspirantesController::class,'seleccionar']);
        Route::post('/seleccionar',[AspirantesController::class,'seleccionar_listado'])
            ->name('desarrollo.seleccionar_listado');
        Route::post('/grupo',[AspirantesController::class,'grupo_aspirante'])
            ->name('desarrollo.grupo_aspirante');
    });
    Route::controller(DesarrolloController::class)->prefix('eval')->group(function () {
        Route::get('/inicio', [DesarrolloController::class,'evaluacion_inicio']);
        Route::post('/inicio', [DesarrolloController::class,'evaluacion_periodo'])
            ->name('desarrollo.periodo_evaluacion');
        Route::resource('/periodos', FechasEvaluacionController::class);
        Route::get('/consulta', [DesarrolloController::class,'resultados_evaluacion1']);
        Route::post('/consulta', [DesarrolloController::class,'resultados_evaluacion2'])
            ->name('desarrollo.resultados_evaluacion');
        Route::post('/resultadosxcarrera', EvalDocCarreraPDFController::class)
            ->name('desarrollo.resultados_carrera');
        Route::post('/consultaxdepto', EvalDocDeptoPDFController::class)
            ->name('desarrollo.resultados_departamento');
        Route::post('/consultaxdocente', EvalDocDocentePDFController::class)
            ->name('desarrollo.resultados_docente');
        Route::post('/alumnosfaltaneval', [DesarrolloController::class,'listado_alumnos_sin_evaluar'])
            ->name('desarrollo.listado_alumnos_sin_evaluar');
    });
    Route::controller(DesarrolloController::class)->prefix('mantenimiento')->group(function () {
        Route::get('/contrasena', [DesarrolloController::class,'contrasenia']);
        Route::post('/ccontrasena', [DesarrolloController::class,'ccontrasenia'])->name('desarrollo.contra');
    });
});
