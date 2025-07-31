<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Division\DivisionController;

Route::group(['prefix'=>'division','middleware'=>['auth','role:division']],function (){
    Route::get('/',[DivisionController::class,'index'])->name('inicio_division');
    Route::controller(DivisionController::class)->prefix('grupos')->group(function (){
        Route::get('/alta',[DivisionController::class,'altagrupo']);
        Route::post('/alta/materias', [DivisionController::class,'listado2'])
            ->name('dep_lista2');
        Route::get('/creacion/{periodo}/{materia}/{carrera}/{reticula}', [DivisionController::class,'creargrupo1']);
        Route::post('/alta/grupo', [DivisionController::class,'creargrupo2'])
            ->name('dep_grupo_alta');
        Route::get('/paralelo', [DivisionController::class,'paralelo1']);
        Route::post('/paralela2', [DivisionController::class,'paralelo2'])
            ->name('dep_paralelo2');
        Route::post('/paralela3', [DivisionController::class,'paralelo3'])
            ->name('dep_paralelo3');
        Route::get('/existentes', [DivisionController::class,'existentes']);
        Route::post('/listado/', [DivisionController::class,'listado'])
            ->name('dep_lista');
        Route::get('/info/{periodo}/{materia}/{gpo}', [DivisionController::class,'info'])
            ->name('dep_info');
        Route::post('/acciones/', [DivisionController::class,'acciones'])
            ->name('dep_acciones');
        Route::post('/altagrupo/', [DivisionController::class,'altacontrol'])
            ->name('dep_altaa');
        Route::delete('/bajaa/', [DivisionController::class,'bajacontrol'])
            ->name('dep_bajaa');
        Route::post('/modificar_horario', [DivisionController::class,'updatehorario'])
            ->name('dep_grupo_modifica');
        Route::post('/capacidad', [DivisionController::class,'capgrupo'])
            ->name('dep_cap_grupo');
    });
    Route::controller(DivisionController::class)->prefix('alumnos')->group(function (){
        Route::get('/consulta',[DivisionController::class,'buscar']);
        Route::post('/buscar',[DivisionController::class,'busqueda'])->name('dep.buscar');
        Route::post('/datos',[DivisionController::class,'accion2'])->name('dep.accion2');
    });
    Route::controller(DivisionController::class)->prefix('estadistica')->group(function (){
        Route::get('/prepoblacion',[DivisionController::class,'prepoblacion']);
        Route::post('/poblacion', [DivisionController::class,'poblacion'])->name('dep_poblacion');
        Route::get('/desglose/{periodo}/{carrera}/{reticula}', [DivisionController::class,'pobxcarrera']);
        Route::get('/aulas',[DivisionController::class,'pobxaulas']);
        Route::post('/aula2',[DivisionController::class,'pobxaulas2'])->name('dep_aula');
    });
    Route::controller(DivisionController::class)->prefix('mantenimiento')->group(function (){
        Route::get('/contrasena',[DivisionController::class,'contrasenia']);
        Route::post('/ccontrasena',[DivisionController::class,'ccontrasenia'])->name('division_contra');
    });
});
