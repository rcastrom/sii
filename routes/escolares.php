<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Escolares\EscolaresController;
use App\Http\Controllers\Escolares\EscolaresAlumnosController;
use App\Http\Controllers\Escolares\ActualizarAlumnoController;
use App\Http\Controllers\Escolares\AspiranteController;
use App\Http\Controllers\Escolares\KardexController;
use App\Http\Controllers\Escolares\PeriodoEscolarController;
use App\Http\Controllers\Escolares\ReinscripcionController;
use App\Http\Controllers\Escolares\CierrePeriodoController;
use App\Http\Controllers\Escolares\ActasController;
use App\Http\Controllers\Escolares\IdiomasController;
use App\Http\Controllers\Acciones\AspirantesNuevoIngresoController;
use App\Http\Controllers\PDF\ConstanciaPDFController;
use App\Http\Controllers\PDF\IdiomasPDFController;
use App\Http\Controllers\PDF\CertificadoPDFController;
use App\Http\Controllers\PDF\KardexPDFController;
use App\Http\Controllers\PDF\ImpresionFichaPDFController;

Route::group(['prefix'=>'escolares','middleware'=>['auth','role:escolares']],function () {
    Route::get('/', [EscolaresController::class, 'index'])->name('inicio_escolares');
    Route::group(['prefix'=>'alumnos'],function (){
        Route::get('/consulta',[EscolaresAlumnosController::class,'buscar']);
        Route::post('/buscar',[EscolaresAlumnosController::class,'busqueda'])->name('escolares.buscar');
        Route::post('/consulta_alumno',[EscolaresAlumnosController::class, 'accion'])
            ->name('escolares.acciones');
        Route::resource('/kardex',KardexController::class);
        Route::post('/imprimir_boleta',[EscolaresAlumnosController::class, 'imprimirboleta'])
            ->name('escolares.imprimir boleta');
        Route::post('/actualizar', [EscolaresAlumnosController::class,'modificar_datos'])
            ->name('escolares.actualizar_alumno');
        Route::post('/actualizar/estatus',[ActualizarAlumnoController::class, 'estatusupdate'])
            ->name('escolares.accion_actualiza_estatus');
        Route::post('/actualizar/especialidad',[ActualizarAlumnoController::class, 'especialidadupdate'])
            ->name('escolares.accion_actualiza_especialidad');
        Route::post('/actualizar/carrera',[ActualizarAlumnoController::class, 'carreraupdate'])
            ->name('escolares.accion_actualiza_carrera');
        Route::post('/eliminar/',[ActualizarAlumnoController::class, 'alumnodelete'])
            ->name('escolares.accion_borrar');
        Route::post('/baja/',[ActualizarAlumnoController::class, 'alumnobajatemp'])
            ->name('escolares.accion_bajatemp');
        Route::post('/nss/',[ActualizarAlumnoController::class, 'alumnonss'])
            ->name('escolares.nss');
        Route::post('/certificado',[EscolaresAlumnosController::class, 'certificado'])
            ->name('escolares.certificado');
        Route::get('/alta', [EscolaresAlumnosController::class, 'alta']);
        Route::post('/nuevo',[EscolaresAlumnosController::class, 'alta_nuevo'])
            ->name('escolares.nuevo_alumno');
        Route::get('/impresion/kardex',[KardexPDFController::class,'crearPDF'])
            ->name('escolares.imprimirkardex');
        Route::post('/constancia',[ConstanciaPDFController::class,'crearPDF'])
            ->name('escolares.constancia');
        Route::post('/idiomas',[IdiomasPDFController::class,'crearPDF'])
            ->name('escolares.idiomas');
        Route::post('/imprimir_certificado',[CertificadoPDFController::class,'crearPDF'])
            ->name('escolares.certificado_pdf');
    });
    Route::controller(EscolaresController::class)->prefix('periodos')->group(function (){
        Route::resource('/periodo_escolar',PeriodoEscolarController::class);
        Route::get('/modificar', [EscolaresController::class,'modificar_periodo']);
        Route::post('/modificar',[EscolaresController::class,'mostrar_periodo'])
            ->name('escolares.modificar_periodo');
        Route::get('/reinscripcion', [ReinscripcionController::class,'reinscripcion'])
            ->name('escolares.reinscripcion');
        Route::post('/acciones',[ReinscripcionController::class, 'accion_re'])
            ->name('escolares.accion_reinscripcion');
        Route::post('/alta_fechas', [ReinscripcionController::class,'altaf_re'])
            ->name('escolares.fechas-reinscripcion');
        Route::get('/cierre', [CierrePeriodoController::class,'cierre']);
    });
    Route::controller(EscolaresController::class)->prefix('aspirantes')->group(function (){
        Route::resource('/ficha',AspiranteController::class);
        Route::post('/listado',[AspiranteController::class,'listado'])
            ->name('escolares_aspirantes.listado');
        Route::get('/imprimir_ficha/{identificador}',[ImpresionFichaPDFController::class,'crearPDF'])
            ->name('escolares.imprimir_ficha');
        Route::post('/documentos/{ficha}',[AspiranteController::class,'actualizar_documentos'])
            ->name('escolares.actualizar_documentos');
        Route::get('/aceptados',[AspiranteController::class,'periodo_aceptados']);
        Route::post('/aceptados',[AspiranteController::class,'listado_aceptados'])
            ->name('escolares.listado_aceptados');
        Route::post('/seleccionados',[AspiranteController::class,'seleccionados'])
            ->name('escolares.aspirantes_seleccionados');
        Route::get('/estadistica',[AspiranteController::class,'estadistica']);
        Route::post('/estadistica_fichas',[EscolaresController::class,'estadistica_fichas'])
            ->name('escolares.estadistica_fichas');
        Route::get('/excel/{periodo}',[AspirantesNuevoIngresoController::class,'fichas_concentrado_excel'])
            ->name('escolares.fichas_concentrado_excel');
    });
    Route::controller(ActasController::class)->prefix('actas')->group(function (){
        Route::get('/inicio',[ActasController::class,'periodoactas_m1']);
        Route::post('/por_docente',[ActasController::class,'periodoactas_m2'])
            ->name('escolares.registro2');
        Route::post('/por_gpodoc',[ActasController::class,'periodoactas_m3'])
            ->name('escolares.registro3');
        Route::post('/entrega',[ActasController::class,'periodoactas_m4'])
            ->name('escolares.registro4');
        Route::get('/registro',[ActasController::class,'periodoactas1']);
        Route::post('/actas2',[ActasController::class,'periodoactas2'])
            ->name('escolares.actas2');
        Route::post('/actas3',[ActasController::class,'periodoactas3'])
            ->name('escolares.actas3');
        Route::get('/modificar/{per}/{docente}/{mat}/{gpo}',[ActasController::class,'modificar_acta']);
        Route::get('/imprimir/{periodo}/{docente}/{materia}/{grupo}',[ActasController::class,'imprimir_acta']);
        Route::post('/actualizar/calificacion',[ActasController::class,'actualizar_acta'])
            ->name('escolares.actas_upd');
        Route::get('/mantenimiento',[ActasController::class,'actas_mantenimiento']);
        Route::post('/consulta_estatus',[ActasController::class,'actas_estatus'])
            ->name('escolares.actas_estatus');
    });
    Route::controller(EscolaresController::class)->prefix('carreras')->group(function (){
        Route::get('/alta',[EscolaresController::class,'carreraAlta']);
        Route::post('/alta_procesa', [EscolaresController::class,'carreraNueva'])
            ->name('escolares.carrera_alta');
        Route::get('/especialidades', [EscolaresController::class,'especialidadAlta']);
        Route::post('/especialidad_alta', [EscolaresController::class,'especialidadNueva'])
            ->name('escolares.especialidad_alta');
        Route::get('/materias', [EscolaresController::class,'materiaNueva']);
        Route::post('/materia_accion', [EscolaresController::class,'materiasAcciones'])
            ->name('escolares.materias_acciones');
        Route::post('/materias_nueva', [EscolaresController::class,'materiaAlta'])
            ->name('escolares.materia_nueva');
        Route::post('/materias_editar',[EscolaresController::class,'materiaEditar'])
            ->name('escolares.materia_editar');
        Route::post('/materias_actualizar',[EscolaresController::class,'materiaActualizar'])
            ->name('escolares.materia_actualizar');
        Route::post('/reticula_vista', [EscolaresController::class,'vistaReticula'])
            ->name('escolares.vista_reticula');
    });
    Route::controller(IdiomasController::class)->prefix('idiomas')->group(function (){
        Route::get('/alta',[IdiomasController::class,'idioma_alta_formulario']);
        Route::post('/alta', [IdiomasController::class,'idioma_alta'])
            ->name('escolares.idioma_alta');
        Route::get('/editar/{idioma}',[IdiomasController::class,'idioma_modifica'])
            ->name('escolares.idioma_modifica');
        Route::post('/editar/{idioma}', [IdiomasController::class,'idioma_editar'])
            ->name('escolares.idioma_editar');
        Route::get('/liberacion',[IdiomasController::class,'idiomas_lib1']);
        Route::post('/liberar', [IdiomasController::class,'idiomas_lib2'])
            ->name('escolares.liberar_idioma');
        Route::post('/liberar2', [IdiomasController::class,'idiomas_lib3'])
            ->name('escolares.liberar_idioma2');
        Route::get('/imprimir', [IdiomasController::class,'idiomas_impre']);
        Route::post('/imprimir2', [IdiomasController::class,'idiomas_impre2'])
            ->name('escolares.imprimir_idioma');
        Route::get('/consulta', [IdiomasController::class,'idiomas_consulta']);
        Route::post('/consultar', [IdiomasController::class,'idiomas_consulta2'])
            ->name('escolares.cursos_idiomas');
    });
    Route::controller(EscolaresController::class)->prefix('estadistica')->group(function (){
        Route::get('/consulta',[EscolaresController::class,'prepoblacion']);
        Route::post('/poblacion',[EscolaresController::class,'poblacion'])
            ->name('escolares.poblacion');
        Route::get('/detalle/{periodo}/{carrera}/{reticula}',[EscolaresController::class,'pobxcarrera'])
            ->name('escolares.est_carrera');
    });
    Route::controller(EscolaresController::class)->prefix('mantenimiento')->group(function (){
        Route::get('/base',[EscolaresController::class,'mantenimiento_inicial']);
        Route::post('/mantenimiento_accion',[EscolaresController::class,'mantenimiento_acciones'])
            ->name('escolares.mantenimiento');
        Route::get('/contrasena',[EscolaresController::class,'contrasenia']);
        Route::post('/ccontrasena',[EscolaresController::class,'ccontrasenia'])
            ->name('escolares.contra');
    });
});
