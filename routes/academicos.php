<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Academicos\AcademicosController;


Route::group(['prefix' => 'academicos','middleware' => ['auth','role:academico']], function () {
    Route::get('/',[AcademicosController::class,'index'])->name('academicos.index');
    Route::group(['prefix'=>'periodos'],function (){
        Route::get('/existentes',[AcademicosController::class,'existentes']);
        Route::post('/existentes',[AcademicosController::class,'listado'])
        ->name('academicos.existentes');
    });
});
