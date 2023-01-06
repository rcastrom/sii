<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Humanos\HumanosController;

Route::group(['prefix'=>'rechumanos','middleware'=>['auth','role:rechumanos']],function (){
    Route::get('/',[HumanosController::class,'index'])->name('inicio_rhumanos');
    Route::controller(HumanosController::class)->prefix('personal')->group(function (){
        Route::get('/alta','alta1');
        Route::post('/alta1','alta_personal1')->name('rechumanos.alta1');
        Route::post('/alta2','alta_personal2')->name('rechumanos.alta2');
        Route::get('/listado','listado');
        Route::get('/editar/{personal}','listado2');
        Route::get('/edicion/{campo}/{personal}','edicion');
        Route::put('/actualizar','actualizar')->name('rechumanos.datos_personal');
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
    });
});
