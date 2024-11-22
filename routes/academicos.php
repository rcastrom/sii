<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Academicos\AcademicosController;
use App\Http\Controllers\Academicos\HorarioNoDocenteController;
use App\Http\Controllers\Academicos\EvaluacionDocenteCarreraController;
use App\Http\Controllers\PDF\HorarioPDFController;


Route::group(['prefix' => 'academicos','middleware' => ['auth','role:academico']], function () {
    Route::get('/',[AcademicosController::class,'index'])->name('academicos.index');
    Route::group(['prefix'=>'periodos'],function (){
        Route::get('/existentes',[AcademicosController::class,'existentes']);
        Route::post('/existentes',[AcademicosController::class,'listado'])
        ->name('academicos.existentes');
        Route::get('/grupos/info/{periodo}/{materia}/{gpo}',[AcademicosController::class, 'info'])
            ->name('academicos.info');
        Route::post('/acciones/',[AcademicosController::class, 'acciones'])
            ->name('academicos.acciones');
        Route::post('/altad/',[AcademicosController::class, 'altadocente'])
            ->name('academicos.altadocente');
    });
    Route::group(['prefix'=>'estadistica','middleware' => ['auth','role:academico']],function (){
       Route::get('/prepoblacion',[AcademicosController::class,'prepoblacion']);
       Route::post('/poblacion',[AcademicosController::class, 'poblacion'])
           ->name('academicos.poblacion');
       Route::get('/desglose/{periodo}/{carrera}/{reticula}',[AcademicosController::class, 'pobxcarrera'])
           ->name('academicos.pob_x_carrera');
       Route::get('/aulas',[AcademicosController::class, 'pobxaulas']);
       Route::post('/aula2',[AcademicosController::class, 'pobxaulas2'])
            ->name('academicos.aula');
       Route::get('/evaluacion',[AcademicosController::class,'seleccion_evaluacion']);
       Route::post('/evaluacion',[EvaluacionDocenteCarreraController::class, 'resultados_evaluacion'])
           ->name('academicos.evaluacion_docente');
        Route::get('/grafica/{periodo}/{carrera}/{reticula}/{promedio}',
            [EvaluacionDocenteCarreraController::class,'grafica_evaluacion_carrera'])
            ->name('academicos.grafica_evaluacion_docente_carrera');
    });
    Route::group(['prefix'=>'docentes','middleware' => ['auth','role:academico']],function(){
        Route::get('/index',[AcademicosController::class,'predocentes']);
        Route::post('/personal',[AcademicosController::class, 'docente'])
            ->name('academicos.personal');
        Route::post('/horarios/accion',[AcademicosController::class, 'otroshorariosaccion'])
            ->name('academicos.otros_horarios');
        Route::post('/horarios/alta_admin',[AcademicosController::class, 'procesaadmvoalta'])
            ->name('academicos.altaadmin');
        Route::get('/modificar/admvo/{periodo}/{docente}/{numero}',[AcademicosController::class, 'modificaadmvo'])
            ->name('academicos.modhadmin');
        Route::get('/eliminar/admvo/{periodo}/{docente}/{numero}',[AcademicosController::class, 'eliminaadmvo'])
            ->name('academicos.delhadmin');
        Route::post('/actualizar/hadmvo',[AcademicosController::class, 'procesoadmvoupdate'])
            ->name('academicos.modadmin');
        Route::post('/horarios/alta_apoyo',[AcademicosController::class, 'procesaapoyoalta'])
            ->name('academicos.altaapoyo');
        Route::get('/modificar/apoyo/{periodo}/{docente}/{consecutivo}',[AcademicosController::class, 'modificaapoyo'])
            ->name('academicos.modhapoyo');
        Route::post('/actualizar/hapoyo',[AcademicosController::class, 'procesoapoyoupdate'])
            ->name('academicos.modapoyo');
        Route::get('/eliminar/apoyo/{periodo}/{docente}/{consecutivo}',[AcademicosController::class, 'eliminaapoyo'])
            ->name('academicos.delhapoyo');
        Route::post('/horarios/alta_obs',[AcademicosController::class, 'altaobservacion'])
            ->name('academicos.altaobs');
        Route::get('/modificar/obs/{periodo}/{docente}/{id}',[AcademicosController::class, 'modificaobservaciones'])
            ->name('academicos.modobs');
        Route::post('/actualizar/observaciones',[AcademicosController::class, 'observacionesupdate'])
            ->name('academicos.modobservaciones');
        Route::get('/eliminar/obs/{id}',[AcademicosController::class, 'eliminaobservaciones'])
            ->name('academicos.delobs');
        Route::post('/impresion',[HorarioPDFController::class,'crearPDF'])
            ->name('academicos.imprimir_horario');
    });
    Route::group(['prefix' => 'administrativos','middleware' => ['auth','role:academico']],function(){
        Route::resource('/nodocente',HorarioNoDocenteController::class);
        Route::post('/nodocente/alta',[HorarioNoDocenteController::class, 'alta'])
            ->name('nodocente.alta');
    });
    Route::controller(AcademicosController::class)->prefix('mantenimiento')->group(function (){
        Route::get('/contrasena','contrasenia');
        Route::post('/ccontrasena','ccontrasenia')
            ->name('academicos.contra');
    });
});
