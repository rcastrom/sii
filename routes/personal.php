<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Docentes\DocenteController;

Route::group(['prefix'=>'personal','middleware'=>['auth','role:personal']],function (){
    Route::get('/',[DocenteController::class,'index'])->name('inicio_personal');
    Route::controller(DocenteController::class)->prefix('periodo')->group(function (){
        Route::get('/grupos','encurso');
        Route::get('/listas/{periodo}/{materia}/{gpo}','lista');
        Route::get('/excel/{periodo}/{materia}/{gpo}','excel');
        Route::get('/evaluar/{periodo}/{materia}/{gpo}','evaluar');
        Route::get('/acta/{periodo}/{materia}/{gpo}','acta');
        Route::post('/semestre/calificaciones','calificar')
            ->name('personal_calificar');
        Route::get('/residencias','residencias1');
        Route::post('/eval/residencias','residencias2')
            ->name('personal_residencias1');
    });
});
