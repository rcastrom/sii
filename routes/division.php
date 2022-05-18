<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Division\DivisionController;

Route::group(['prefix'=>'division','middleware'=>['auth','role:division']],function (){
    Route::get('/',[DivisionController::class,'index'])->name('inicio_division');
    Route::controller(DivisionController::class)->prefix('grupos')->group(function (){
        Route::get('/alta','altagrupo');
        Route::post('/alta/materias', 'listado2')
            ->name('dep_lista2');
        Route::get('/creacion/{periodo}/{materia}/{carrera}/{reticula}', 'creargrupo1');
        Route::post('/alta/grupo', 'creargrupo2')
            ->name('dep_grupo_alta');
        Route::get('/paralelo', 'paralelo1');
        Route::post('/paralela2', 'paralelo2')
            ->name('dep_paralelo2');
        Route::post('/paralela3', 'paralelo3')
            ->name('dep_paralelo3');
        Route::get('/existentes', 'existentes');
        Route::post('/listado/', 'listado')
            ->name('dep_lista');
        Route::get('/info/{periodo}/{materia}/{gpo}', 'info')->name('dep_info');
        Route::post('/acciones/', 'acciones')
            ->name('dep_acciones');
        Route::post('/altagrupo/', 'altacontrol')
            ->name('dep_altaa');
        Route::delete('/bajaa/', 'bajacontrol')
            ->name('dep_bajaa');
        Route::post('/modificar_horario', 'updatehorario')
            ->name('dep_grupo_modifica');
        Route::post('/capacidad', 'capgrupo')
            ->name('dep_cap_grupo');
    });
    Route::controller(DivisionController::class)->prefix('alumnos')->group(function (){
        Route::get('/consulta','buscar');
        Route::post('/buscar','busqueda')->name('dep.buscar');
        Route::post('/datos','accion2')->name('dep.accion2');
    });
    Route::controller(DivisionController::class)->prefix('estadistica')->group(function (){
        Route::get('/prepoblacion','prepoblacion');
        Route::post('/poblacion', 'poblacion')->name('dep_poblacion');
        Route::get('/desglose/{periodo}/{carrera}/{reticula}', 'pobxcarrera');
        Route::get('/aulas','pobxaulas');
        Route::post('/aula2','pobxaulas2')->name('dep_aula');
    });
    Route::controller(DivisionController::class)->prefix('mantenimiento')->group(function (){
        Route::get('/contrasena','contrasenia');
        Route::post('/ccontrasena','ccontrasenia')->name('division_contra');
    });
});
