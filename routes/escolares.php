<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Escolares\EscolaresController;
use App\Http\Controllers\PDF\ConstanciaPDFController;
use App\Http\Controllers\PDF\IdiomasPDFController;
use App\Http\Controllers\PDF\CertificadoPDFController;

Route::group(['prefix'=>'escolares','middleware'=>['auth','role:escolares']],function () {
    Route::get('/', [EscolaresController::class, 'index'])->name('inicio_escolares');
    Route::group(['prefix'=>'alumnos'],function (){
        Route::get('/consulta',[EscolaresController::class,'buscar']);
        Route::post('/buscar',[EscolaresController::class,'busqueda'])->name('escolares.buscar');
        Route::post('/consulta_alumno',[EscolaresController::class, 'accion'])
            ->name('escolares.acciones');
        Route::post('/accionesk',[EscolaresController::class, 'accionk'])
            ->name('escolares.accion_kardex');
        Route::post('/accionesk_alta',[EscolaresController::class, 'accionkalta'])
            ->name('escolares.accion_kardex_alta');
        Route::post('/periodo_k',[EscolaresController::class, 'accionkperiodo'])
            ->name('escolares.accion_kardex_modificar1');
        Route::get('/modificar/{periodo}/{control}/{materia}',[EscolaresController::class, 'modificarkardex']);
        Route::get('/eliminar/{periodo}/{control}/{materia}',[EscolaresController::class, 'eliminarkardex']);
        Route::post('/actualizar/kardex',[EscolaresController::class, 'kardexupdate'])
            ->name('escolares.accion_actualiza_kardex');
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
    Route::group(['prefix'=>'periodos'],function (){
        Route::get('/alta',[EscolaresController::class,'periodos']);
        Route::post('/nuevo',[EscolaresController::class, 'periodoalta'])
            ->name('escolares.periodo_nuevo');
        Route::get('/modifica',[EscolaresController::class, 'periodomodifica']);
        Route::post('/modificar',[EscolaresController::class, 'periodomodificar'])
            ->name('escolares.periodo_mod1');
        Route::post('/modificado',[EscolaresController::class, 'periodoupdate'])
            ->name('escolares.periodo_upd');
        Route::get('/reinscripcion',[EscolaresController::class, 'reinscripcion']);
        Route::post('/alta_fechas',[EscolaresController::class, 'altaf_re'])
            ->name('escolares.fechas-reinscripcion');
        Route::get('/cierre',[EscolaresController::class, 'cierre']);
    });
    Route::group(['prefix'=>'actas'],function (){
        Route::get('/inicio',[EscolaresController::class, 'periodoactas_m1']);
        Route::post('/por_docente',[EscolaresController::class, 'periodoactas_m2'])
            ->name('escolares.registro2');
        Route::post('/por_gpodoc',[EscolaresController::class, 'periodoactas_m3'])
            ->name('escolares.registro3');
        Route::get('/registro',[EscolaresController::class,'periodoactas1']);
        Route::post('/actas2',[EscolaresController::class, 'periodoactas2'])
            ->name('escolares.actas2');
        Route::post('/actas3',[EscolaresController::class, 'periodoactas3'])
            ->name('escolares.actas3');
        Route::post('/entrega',[EscolaresController::class, 'periodoactas_m4'])
            ->name('escolares.registro4');
        Route::get('/modificar/{per}/{rfc}/{mat}/{gpo}',[EscolaresController::class, 'modificaracta']);
        Route::get('/imprimir/{periodo}/{rfc}/{materia}/{grupo}',[EscolaresController::class, 'imprimiracta']);
        Route::post('/actualizar/calificacion',[EscolaresController::class, 'actasupdate'])
            ->name('escolares.actas_upd');
        Route::get('/mantenimiento',[EscolaresController::class, 'actas_mantenimiento']);
        Route::post('/consulta_estatus',[EscolaresController::class, 'actas_estatus'])
            ->name('escolares.actas_estatus');
    });
    Route::group(['prefix'=>'carreras'],function (){
        Route::get('/alta',[EscolaresController::class,'carrerasalta']);
        Route::post('/alta_procesa',[EscolaresController::class, 'carreranueva'])
            ->name('escolares.carrera_alta');
        Route::get('/especialidades',[EscolaresController::class, 'especialidadesalta']);
        Route::post('/especialidad_alta',[EscolaresController::class, 'especialidadnueva'])
            ->name('escolares.especialidad_alta');
        Route::get('/materias',[EscolaresController::class, 'materianueva']);
        Route::post('/materia_accion',[EscolaresController::class, 'materiasacciones'])
            ->name('escolares.materias_acciones');
        Route::post('/materias_nueva',[EscolaresController::class, 'materiaalta'])
            ->name('escolares.materia_nueva');
        Route::post('/reticula_vista',[EscolaresController::class, 'vistareticula'])
            ->name('escolares.vista_reticula');
    });
    Route::group(['prefix'=>'idiomas'],function (){
        Route::get('/liberacion',[EscolaresController::class,'idiomas_lib1']);
        Route::post('/liberar',[EscolaresController::class, 'idiomas_lib2'])
            ->name('escolares.liberar_idioma');
        Route::post('/liberar2',[EscolaresController::class, 'idiomas_lib3'])
            ->name('escolares.liberar_idioma2');
        Route::get('/imprimir',[EscolaresController::class, 'idiomas_impre']);
        Route::post('/imprimir2',[EscolaresController::class, 'idiomas_impre2'])
            ->name('escolares.imprimir_idioma');
        Route::get('/consulta',[EscolaresController::class, 'idiomas_consulta']);
        Route::post('/consultar',[EscolaresController::class, 'idiomas_consulta2'])
            ->name('escolares.cursos_idiomas');
    });
    Route::group(['prefix'=>'estadistica'],function (){
        Route::get('/consulta',[EscolaresController::class,'prepoblacion']);
        Route::post('/poblacion',[EscolaresController::class, 'poblacion'])
            ->name('escolares.poblacion');
        Route::get('/detalle/{periodo}/{carrera}/{reticula}',[EscolaresController::class, 'pobxcarrera'])
            ->name('escolares.est_carrera');
    });
    Route::group(['prefix'=>'mantenimiento'],function (){
        Route::get('/base',[EscolaresController::class,'mantenimiento_inicial']);
        Route::post('/mantenimiento_accion',[EscolaresController::class,'mantenimiento_acciones'])
            ->name('escolares.mantenimiento');
        Route::get('/contrasena',[EscolaresController::class, 'contrasenia']);
        Route::post('/ccontrasena',[EscolaresController::class, 'ccontrasenia'])
            ->name('escolares.contra');
    });
});
