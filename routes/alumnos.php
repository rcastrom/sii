<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Alumnos\AlumnosController;
use App\Http\Controllers\Alumnos\ReinscripcionController;

Route::group(['prefix'=>'estudiante','middleware'=>['auth','role:alumno']],function (){
    Route::get('/',[AlumnosController::class,'index'])->name('inicio_alumnos');
    Route::group(['prefix'=>'historial'],function (){
        Route::get('/kardex/{opcion}',[AlumnosController::class,'ver_kardex']);
        Route::get('/boleta',[AlumnosController::class,'boleta']);
        Route::post('/boleta',[AlumnosController::class,'verboleta'])
            ->name('alumnos.boleta');
        Route::get('/reticula',[AlumnosController::class,'reticula']);
    });
    Route::group(['prefix'=>'periodo'],function (){
        Route::get('/horario',[AlumnosController::class,'horario']);
        Route::get('/eval',[AlumnosController::class,'evaluacion']);
        Route::post('/evaluacion',[AlumnosController::class, 'evaluar'])
            ->name('alumnos.eval_doc');
        Route::post('/eval_doc',[AlumnosController::class, 'evaluaciondoc'])
            ->name('alumnos.eval_docente');
        Route::get('/calificaciones',[AlumnosController::class,'consulta_calificaciones']);
    });
    Route::group(['prefix'=>'reinscripcion'],function (){
        Route::get('/',[ReinscripcionController::class,'index'])
            ->name('reinscripciones');
        Route::get('/{materia}/{tipocur}',[ReinscripcionController::class, 'seleccion_materia']);
        Route::post('/seleccion/',[ReinscripcionController::class, 'reinscribir'])
            ->name('alumnos.seleccion');
    });
    Route::controller(AlumnosController::class)->prefix('mantenimiento')->group(function (){
        Route::get('/contrasena',[AlumnosController::class,'contrasenia']);
        Route::post('/ccontrasena',[AlumnosController::class,'ccontrasenia'])
            ->name('alumnos.contra');
    });
});
