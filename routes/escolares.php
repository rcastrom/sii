<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Escolares\EscolaresController;
use App\Http\Controllers\Escolares\EscolaresAlumnosController;
use App\Http\Controllers\Escolares\KardexController;
use App\Http\Controllers\PDF\ConstanciaPDFController;
use App\Http\Controllers\PDF\IdiomasPDFController;
use App\Http\Controllers\PDF\CertificadoPDFController;
use App\Http\Controllers\PDF\KardexPDFController;

Route::group(['prefix'=>'escolares','middleware'=>['auth','role:escolares']],function () {
    Route::get('/', [EscolaresController::class, 'index'])->name('inicio_escolares');
    Route::group(['prefix'=>'alumnos'],function (){
        Route::get('/consulta',[EscolaresAlumnosController::class,'buscar']);
        Route::post('/buscar',[EscolaresAlumnosController::class,'busqueda'])->name('escolares.buscar');
        Route::post('/consulta_alumno',[EscolaresAlumnosController::class, 'accion'])
            ->name('escolares.acciones');
        Route::resource('/kardex',KardexController::class);
        Route::get('/impresion/kardex',[KardexPDFController::class,'crearPDF'])
            ->name('escolares.imprimirkardex');
        Route::post('/constancia',[ConstanciaPDFController::class,'crearPDF'])
            ->name('escolares.constancia');
        Route::post('/imprimir_boleta',[EscolaresController::class, 'imprimirboleta'])
            ->name('escolares.imprimir boleta');
        Route::post('/actualizar/estatus',[EscolaresController::class, 'estatusupdate'])
            ->name('escolares.accion_actualiza_estatus');
        Route::post('/actualizar/especialidad',[EscolaresController::class, 'especialidadupdate'])
            ->name('escolares.accion_actualiza_especialidad');
        Route::post('/actualizar/carrera',[EscolaresController::class, 'carreraupdate'])
            ->name('escolares.accion_actualiza_carrera');
        Route::post('/eliminar/',[EscolaresController::class, 'alumnodelete'])
            ->name('escolares.accion_borrar');
        Route::post('/baja/',[EscolaresController::class, 'alumnobajatemp'])
            ->name('escolares.accion_bajatemp');
        Route::post('/nss/',[EscolaresController::class, 'alumnonss'])
            ->name('escolares.nss');
        Route::post('/idiomas',[IdiomasPDFController::class,'crearPDF'])
            ->name('escolares.idiomas');
        Route::post('/certificado',[EscolaresController::class, 'certificado'])
            ->name('escolares.certificado');
        Route::post('/imprimir_certificado',[CertificadoPDFController::class,'crearPDF'])
            ->name('escolares.certificado_pdf');
        Route::post('/actualizar', [EscolaresController::class,'modificar_datos'])
            ->name('escolares.actualizar_alumno');
        Route::get('/alta', [EscolaresController::class, 'nuevo']);
        Route::post('/nuevo',[EscolaresController::class, 'altanuevo'])
            ->name('escolares.nuevo_alumno');
        Route::post('/acciones',[EscolaresController::class, 'accion_re'])
            ->name('escolares.accion-reinscripcion');
    });
    Route::controller(EscolaresController::class)->prefix('periodos')->group(function (){
        Route::get('/alta','periodos');
        Route::post('/nuevo', 'periodoalta')->name('escolares.periodo_nuevo');
        Route::get('/modifica', 'periodomodifica');
        Route::post('/modificar', 'periodomodificar')->name('escolares.periodo_mod1');
        Route::post('/modificado', 'periodoupdate')->name('escolares.periodo_upd');
        Route::get('/reinscripcion', 'reinscripcion')->name('escolares.reinscripcion');
        Route::post('/alta_fechas', 'altaf_re')->name('escolares.fechas-reinscripcion');
        Route::get('/cierre', 'cierre');
    });
    Route::controller(EscolaresController::class)->prefix('actas')->group(function (){
        Route::get('/inicio','periodoactas_m1');
        Route::post('/por_docente','periodoactas_m2')->name('escolares.registro2');
        Route::post('/por_gpodoc','periodoactas_m3')->name('escolares.registro3');
        Route::get('/registro','periodoactas1');
        Route::post('/actas2','periodoactas2')->name('escolares.actas2');
        Route::post('/actas3','periodoactas3')->name('escolares.actas3');
        Route::post('/entrega','periodoactas_m4')->name('escolares.registro4');
        Route::get('/modificar/{per}/{rfc}/{mat}/{gpo}','modificaracta');
        Route::get('/imprimir/{periodo}/{rfc}/{materia}/{grupo}','imprimiracta');
        Route::post('/actualizar/calificacion','actasupdate')->name('escolares.actas_upd');
        Route::get('/mantenimiento','actas_mantenimiento');
        Route::post('/consulta_estatus','actas_estatus')->name('escolares.actas_estatus');
    });
    Route::controller(EscolaresController::class)->prefix('carreras')->group(function (){
        Route::get('/alta','carrerasalta');
        Route::post('/alta_procesa', 'carreranueva')->name('escolares.carrera_alta');
        Route::get('/especialidades', 'especialidadesalta');
        Route::post('/especialidad_alta', 'especialidadnueva')->name('escolares.especialidad_alta');
        Route::get('/materias', 'materianueva');
        Route::post('/materia_accion', 'materiasacciones')->name('escolares.materias_acciones');
        Route::post('/materias_nueva', 'materiaalta')->name('escolares.materia_nueva');
        Route::post('/reticula_vista', 'vistareticula')->name('escolares.vista_reticula');
    });
    Route::controller(EscolaresController::class)->prefix('idiomas')->group(function (){
        Route::get('/liberacion','idiomas_lib1');
        Route::post('/liberar', 'idiomas_lib2')->name('escolares.liberar_idioma');
        Route::post('/liberar2', 'idiomas_lib3')->name('escolares.liberar_idioma2');
        Route::get('/imprimir', 'idiomas_impre');
        Route::post('/imprimir2', 'idiomas_impre2')->name('escolares.imprimir_idioma');
        Route::get('/consulta', 'idiomas_consulta');
        Route::post('/consultar', 'idiomas_consulta2')->name('escolares.cursos_idiomas');
    });
    Route::controller(EscolaresController::class)->prefix('estadistica')->group(function (){
        Route::get('/consulta','prepoblacion');
        Route::post('/poblacion','poblacion')->name('escolares.poblacion');
        Route::get('/detalle/{periodo}/{carrera}/{reticula}','pobxcarrera')->name('escolares.est_carrera');
    });
    Route::controller(EscolaresController::class)->prefix('mantenimiento')->group(function (){
        Route::get('/base','mantenimiento_inicial');
        Route::post('/mantenimiento_accion','mantenimiento_acciones')->name('escolares.mantenimiento');
        Route::get('/contrasena','contrasenia');
        Route::post('/ccontrasena','ccontrasenia')->name('escolares.contra');
    });
});
