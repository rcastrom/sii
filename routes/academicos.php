<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Academicos\AcademicosController;


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
       Route::get('/predocentes',[AcademicosController::class,'predocentes']);
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
    });
    Route::controller(AcademicosController::class)->prefix('mantenimiento')->group(function (){
        Route::get('/contrasena','contrasenia');
        Route::post('/ccontrasena','ccontrasenia')
            ->name('academicos.contra');
    });
});
