<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Docentes\DocenteController;
use App\Http\Controllers\Docentes\ParcialesController;

Route::group(['prefix'=>'personal','middleware'=>['auth','role:personal']],function (){
    Route::get('/',[DocenteController::class,'index'])->name('inicio_personal');
    Route::controller(DocenteController::class)->prefix('periodo')->group(function (){
        Route::get('/grupos',[DocenteController::class,'encurso']);
        Route::get('/listas/{periodo}/{materia}/{gpo}',[DocenteController::class,'lista']);
        Route::get('/excel/{periodo}/{materia}/{gpo}',[DocenteController::class,'excel']);
        Route::get('/evaluar/{periodo}/{materia}/{gpo}',[DocenteController::class,'evaluar']);
        Route::get('/acta/{periodo}/{materia}/{gpo}',[DocenteController::class,'acta']);
        Route::post('/semestre/calificaciones',[DocenteController::class,'calificar'])
            ->name('personal_calificar');
        Route::get('/residencias',[DocenteController::class,'residencias1']);
        Route::post('/eval/residencias',[DocenteController::class,'residencias2'])
            ->name('personal_residencias1');
    });
    Route::controller(DocenteController::class)->prefix('calif')->group(function (){
        Route::get('/parciales',[DocenteController::class,'grupos_semestre']);
        Route::get('/consulta',[DocenteController::class,'consulta_parciales']);
        Route::post('/consulta',[DocenteController::class,'consulta_calificaciones'])
            ->name('personal.consulta_calificaciones');
        Route::post('/parciales',[DocenteController::class,'preparciales'])
            ->name('personal.parciales');
        Route::resource('parcial',ParcialesController::class);

    });
});
