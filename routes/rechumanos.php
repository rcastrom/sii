<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Humanos\HumanosController;
use App\Http\Controllers\Humanos\PlazasController;
use App\Http\Controllers\Humanos\JefesController;


Route::group(['prefix'=>'rechumanos','middleware'=>['auth','role:rechumanos']],function (){
    Route::get('/',[HumanosController::class,'index'])->name('inicio_rechumanos');
    Route::controller(HumanosController::class)->prefix('personal')->group(function (){
        Route::get('/alta',[HumanosController::class,'alta1']);
        Route::post('/alta1',[HumanosController::class,'alta_personal1'])->name('rechumanos.alta1');
        Route::post('/alta2',[HumanosController::class,'alta_personal2'])->name('rechumanos.alta2');
        Route::get('/listado',[HumanosController::class,'listado1']);
        Route::post('/listado',[HumanosController::class,'listado'])->name('rechumanos.listado');
        Route::get('/editar/{personal}',[HumanosController::class,'listado2']);
        Route::get('/edicion/{campo}/{personal}',[HumanosController::class,'edicion']);
        Route::put('/actualizar',[HumanosController::class,'actualizar'])->name('rechumanos.datos_personal');
        Route::get('/estatus/{personal}',[HumanosController::class,'estatus_personal_editar']);
        Route::post('/estatus',[HumanosController::class,'estatus_personal_editar2'])->name('rechumanos.estatus_personal');
        Route::get('/estudios/{personal}',[HumanosController::class,'estudios_personal']);
        Route::get('/estudios_editar/{estudio}',[HumanosController::class,'estudios_editar']);
        Route::put('/estudios/actualizar',[HumanosController::class,'estudios_actualizar'])->name('rechumanos.actualizar_estudios');
        Route::get('/alta_carrera/{estudio}/{bandera}',[HumanosController::class,'alta_carrera']);
        Route::post('/alta_carrera/nueva',[HumanosController::class,'alta_carrera2'])->name('rechumanos.alta_carrera');
        Route::get('/alta_escuela/{estudio}/{bandera}',[HumanosController::class,'alta_escuela']);
        Route::get('/municipios/{id}',[HumanosController::class,'municipios']);
        Route::post('/alta_escuela',[HumanosController::class,'alta_escuela2'])->name('rechumanos.alta_escuela');
        Route::get('/alta_municipio/{estudio}',[HumanosController::class,'alta_municipio']);
        Route::post('/alta_municipio',[HumanosController::class,'alta_municipio2'])->name('rechumanos.alta_municipio');
        Route::get('/nuevo_estudio/{personal}',[HumanosController::class,'nuevo_estudio']);
        Route::post('/alta_estudio',[HumanosController::class,'nuevo_estudio2'])->name('rechumanos.alta_estudio');
        Route::get('/estudios_borrar/{estudio}',[HumanosController::class,'eliminar_estudio']);
        Route::delete('/estudios_eliminar',[HumanosController::class,'eliminar_estudio2'])->name('rechumanos.borrar_estudio');
        Route::get('/plazas/{personal}/{tipo}',[HumanosController::class,'listado_plazas_personal']);
        Route::resource('/admin/personalPlaza',PlazasController::class);
        Route::get('/exportar',[HumanosController::class,'exportar']);
    });
    Route::controller(HumanosController::class)->prefix('plazas')->group(function (){
        Route::get('/listado',[HumanosController::class,'listado_plazas_uno']);
        Route::post('listado',[HumanosController::class,'listado_plazas'])->name('rechumanos.busqueda_plazas');
        Route::post('/listado2',[HumanosController::class,'listado_plazas_dos'])->name('rechumanos.busqueda_plaza_categoria');
    });
    Route::controller(HumanosController::class)->prefix('jefaturas')->group(function (){
        Route::resource('/listado',JefesController::class);
    });
});
