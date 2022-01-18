<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Division\DivisionController;

Route::group(['prefix'=>'division','middleware'=>['auth','role:division']],function (){
    Route::get('/',[DivisionController::class,'index'])->name('inicio_division');
    Route::group(['prefix'=>'grupos'],function (){
        Route::get('/alta',[DivisionController::class,'altagrupo']);
        Route::post('/alta/materias',[DivisionController::class, 'listado2'])
            ->name('dep_lista2');
        Route::get('/creacion/{periodo}/{materia}/{carrera}/{reticula}',[DivisionController::class, 'creargrupo1']);
        Route::post('/alta/grupo',[DivisionController::class, 'creargrupo2'])
            ->name('dep_grupo_alta');
        Route::get('/paralelo',[DivisionController::class, 'paralelo1']);
        Route::post('/paralela2',[DivisionController::class, 'paralelo2'])
            ->name('dep_paralelo2');
        Route::post('/paralela3',[DivisionController::class, 'paralelo3'])
            ->name('dep_paralelo3');
        Route::get('/existentes',[DivisionController::class, 'existentes']);
        Route::post('/listado/',[DivisionController::class, 'listado'])
            ->name('dep_lista');
        Route::get('/info/{periodo}/{materia}/{gpo}',[DivisionController::class, 'info'])->name('dep_info');
        Route::post('/acciones/',[DivisionController::class, 'acciones'])
            ->name('dep_acciones');
        Route::post('/altagrupo/',[DivisionController::class, 'altacontrol'])
            ->name('dep_altaa');
        Route::delete('/bajaa/',[DivisionController::class, 'bajacontrol'])
            ->name('dep_bajaa');

    });
});
