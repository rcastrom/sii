<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;

Route::group(['prefix'=>'admin','middleware'=>['auth','role:admin']],function (){
    Route::get('/',[AdminController::class,'index'])->name('inicio_admin');
    Route::group(['prefix'=>'usuarios'],function (){
        Route::get('/alta',[AdminController::class,'inicio_alta_usuarios']);
        Route::post('/x_rol',[AdminController::class,'alta_usuario_rol'])
            ->name('x_rol');
        Route::post('/crear_usuario',[AdminController::class,'crear_usuario'])
            ->name('crear_usuario');
    });
});
