<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Docentes\DocenteController;

Route::group(['prefix'=>'personal','middleware'=>['auth','role:docente']],function (){
    Route::get('/',[DocenteController::class,'index'])->name('inicio_personal');
});
