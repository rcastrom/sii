<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Docentes\DocenteController;

Route::group(['prefix'=>'personal','middleware'=>['auth','role:docente']],function (){
    Route::get('/',[DocenteController::class,'index'])->name('inicio_personal');
    Route::group(['prefix'=>'periodo'],function (){
        Route::get('/grupos',[DocenteController::class,'encurso']);
        Route::get('/listas/{periodo}/{materia}/{gpo}',[DocenteController::class,'lista']);
        Route::get('/excel/{periodo}/{materia}/{gpo}',[DocenteController::class,'excel']);
        Route::get('/evaluar/{periodo}/{materia}/{gpo}',[DocenteController::class,'evaluar']);
        Route::get('/acta/{periodo}/{materia}/{gpo}',[DocenteController::class,'acta']);
        Route::post('/semestre/calificaciones',[DocenteController::class, 'calificar'])
            ->name('personal_calificar');
        Route::get('/residencias',[DocenteController::class,'residencias1']);
        Route::post('/eval/residencias',[DocenteController::class, 'residencias2'])
            ->name('personal_residencias1');
    });
});
