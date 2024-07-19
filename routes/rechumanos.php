<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Humanos\HumanosController;
use App\Http\Controllers\Humanos\PlazasController;
use App\Http\Controllers\Humanos\JefesController;


Route::group(['prefix'=>'rechumanos','middleware'=>['auth','role:rechumanos']],function (){
    Route::get('/',[HumanosController::class,'index'])->name('inicio_rechumanos');
    Route::controller(HumanosController::class)->prefix('personal')->group(function (){
        Route::get('/alta','alta1');
        Route::post('/alta1','alta_personal1')->name('rechumanos.alta1');
        Route::post('/alta2','alta_personal2')->name('rechumanos.alta2');
        Route::get('/listado','listado1');
        Route::post('/listado','listado')->name('rechumanos.listado');
        Route::get('/editar/{personal}','listado2');
        Route::get('/edicion/{campo}/{personal}','edicion');
        Route::put('/actualizar','actualizar')->name('rechumanos.datos_personal');
        Route::get('/estatus/{personal}','estatus_personal_editar');
        Route::post('/estatus','estatus_personal_editar2')->name('rechumanos.estatus_personal');
        Route::get('/estudios/{personal}','estudios_personal');
        Route::get('/estudios_editar/{estudio}','estudios_editar');
        Route::put('/estudios/actualizar','estudios_actualizar')->name('rechumanos.actualizar_estudios');
        Route::get('/alta_carrera/{estudio}/{bandera}','alta_carrera');
        Route::post('/alta_carrera/nueva','alta_carrera2')->name('rechumanos.alta_carrera');
        Route::get('/alta_escuela/{estudio}/{bandera}','alta_escuela');
        Route::get('/municipios/{id}','municipios');
        Route::post('/alta_escuela','alta_escuela2')->name('rechumanos.alta_escuela');
        Route::get('/alta_municipio/{estudio}','alta_municipio');
        Route::post('/alta_municipio','alta_municipio2')->name('rechumanos.alta_municipio');
        Route::get('/nuevo_estudio/{personal}','nuevo_estudio');
        Route::post('/alta_estudio','nuevo_estudio2')->name('rechumanos.alta_estudio');
        Route::get('/estudios_borrar/{estudio}','eliminar_estudio');
        Route::delete('/estudios_eliminar','eliminar_estudio2')->name('rechumanos.borrar_estudio');
        Route::get('/plazas/{personal}/{tipo}','listado_plazas_personal');
        Route::resource('/admin/personalPlaza',PlazasController::class);
    });
    Route::controller(HumanosController::class)->prefix('plazas')->group(function (){
        Route::get('/listado','listado_plazas_uno');
        Route::post('listado','listado_plazas')->name('rechumanos.busqueda_plazas');
        Route::post('/listado2','listado_plazas_dos')->name('rechumanos.busqueda_plaza_categoria');
    });
    Route::controller(HumanosController::class)->prefix('jefaturas')->group(function (){
        Route::resource('/listado',JefesController::class);
    });
});
