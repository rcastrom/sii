<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 *
 *
 * @property int $periodo
 * @property string $no_de_control
 * @property string|null $estatus_periodo_alumno
 * @property string|null $creditos_cursados
 * @property string|null $creditos_aprobados
 * @property string|null $promedio_ponderado
 * @property string|null $promedio_ponderado_acumulado
 * @property string|null $promedio_aritmetico
 * @property string|null $promedio_aritmetico_acumulado
 * @property string|null $promedio_certificado
 * @property int|null $materias_cursadas
 * @property int|null $materias_reprobadas
 * @property int|null $materias_a_examen_especial
 * @property int|null $materias_especial_reprobadas
 * @property string|null $indice_reprobacion_semestre
 * @property string|null $creditos_autorizados
 * @property string|null $indice_reprobacion_acumulado
 * @method static \Illuminate\Database\Eloquent\Builder|AcumuladoHistorico newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AcumuladoHistorico newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AcumuladoHistorico query()
 * @method static \Illuminate\Database\Eloquent\Builder|AcumuladoHistorico whereCreditosAprobados($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcumuladoHistorico whereCreditosAutorizados($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcumuladoHistorico whereCreditosCursados($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcumuladoHistorico whereEstatusPeriodoAlumno($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcumuladoHistorico whereIndiceReprobacionAcumulado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcumuladoHistorico whereIndiceReprobacionSemestre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcumuladoHistorico whereMateriasAExamenEspecial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcumuladoHistorico whereMateriasCursadas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcumuladoHistorico whereMateriasEspecialReprobadas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcumuladoHistorico whereMateriasReprobadas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcumuladoHistorico whereNoDeControl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcumuladoHistorico wherePeriodo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcumuladoHistorico wherePromedioAritmetico($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcumuladoHistorico wherePromedioAritmeticoAcumulado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcumuladoHistorico wherePromedioCertificado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcumuladoHistorico wherePromedioPonderado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcumuladoHistorico wherePromedioPonderadoAcumulado($value)
 */
	class AcumuladoHistorico extends \Eloquent {}
}

namespace App\Models{
/**
 *
 *
 * @property string $no_de_control
 * @property string|null $carrera
 * @property string|null $reticula
 * @property string|null $especialidad
 * @property string|null $nivel_escolar
 * @property int|null $semestre
 * @property string|null $estatus_alumno
 * @property string $plan_de_estudios
 * @property string|null $apellido_paterno
 * @property string|null $apellido_materno
 * @property string $nombre_alumno
 * @property string|null $curp_alumno
 * @property string|null $fecha_nacimiento
 * @property string|null $sexo
 * @property string|null $estado_civil
 * @property string $tipo_ingreso
 * @property string $periodo_ingreso_it
 * @property string|null $ultimo_periodo_inscrito
 * @property string|null $promedio_periodo_anterior
 * @property string|null $promedio_aritmetico_acumulado
 * @property string|null $creditos_aprobados
 * @property string|null $creditos_cursados
 * @property string|null $promedio_final_alcanzado
 * @property string|null $escuela_procedencia
 * @property string|null $entidad_procedencia
 * @property string|null $ciudad_procedencia
 * @property string|null $correo_electronico
 * @property int|null $periodos_revalidacion
 * @property string|null $becado_por
 * @property int|null $nip
 * @property string|null $fecha_titulacion
 * @property string|null $opcion_titulacion
 * @property string|null $periodo_titulacion
 * @property string|null $nss
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Alumno newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Alumno newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Alumno query()
 * @method static \Illuminate\Database\Eloquent\Builder|Alumno whereApellidoMaterno($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Alumno whereApellidoPaterno($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Alumno whereBecadoPor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Alumno whereCarrera($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Alumno whereCiudadProcedencia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Alumno whereCorreoElectronico($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Alumno whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Alumno whereCreditosAprobados($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Alumno whereCreditosCursados($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Alumno whereCurpAlumno($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Alumno whereEntidadProcedencia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Alumno whereEscuelaProcedencia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Alumno whereEspecialidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Alumno whereEstadoCivil($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Alumno whereEstatusAlumno($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Alumno whereFechaNacimiento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Alumno whereFechaTitulacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Alumno whereNip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Alumno whereNivelEscolar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Alumno whereNoDeControl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Alumno whereNombreAlumno($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Alumno whereNss($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Alumno whereOpcionTitulacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Alumno wherePeriodoIngresoIt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Alumno wherePeriodoTitulacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Alumno wherePeriodosRevalidacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Alumno wherePlanDeEstudios($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Alumno wherePromedioAritmeticoAcumulado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Alumno wherePromedioFinalAlcanzado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Alumno wherePromedioPeriodoAnterior($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Alumno whereReticula($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Alumno whereSemestre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Alumno whereSexo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Alumno whereTipoIngreso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Alumno whereUltimoPeriodoInscrito($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Alumno whereUpdatedAt($value)
 */
	class Alumno extends \Eloquent {}
}

namespace App\Models{
/**
 *
 *
 * @property string $no_de_control
 * @property string|null $domicilio_calle
 * @property string|null $domicilio_colonia
 * @property string|null $codigo_postal
 * @property string|null $telefono
 * @property string|null $facebook
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AlumnosGeneral newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AlumnosGeneral newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AlumnosGeneral query()
 * @method static \Illuminate\Database\Eloquent\Builder|AlumnosGeneral whereCodigoPostal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AlumnosGeneral whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AlumnosGeneral whereDomicilioCalle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AlumnosGeneral whereDomicilioColonia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AlumnosGeneral whereFacebook($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AlumnosGeneral whereNoDeControl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AlumnosGeneral whereTelefono($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AlumnosGeneral whereUpdatedAt($value)
 */
	class AlumnosGeneral extends \Eloquent {}
}

namespace App\Models{
/**
 *
 *
 * @property int $id
 * @property string $aula
 * @property string $ubicacion
 * @property int|null $capacidad
 * @property bool|null $estatus
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Aula newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Aula newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Aula query()
 * @method static \Illuminate\Database\Eloquent\Builder|Aula whereAula($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aula whereCapacidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aula whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aula whereEstatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aula whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aula whereUbicacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aula whereUpdatedAt($value)
 */
	class Aula extends \Eloquent {}
}

namespace App\Models{
/**
 *
 *
 * @property int $id
 * @property string $periodo
 * @property int $aula
 * @property int $capacidad
 * @property int $disponibles
 * @property string $carrera
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AulaAspirante newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AulaAspirante newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AulaAspirante query()
 * @method static \Illuminate\Database\Eloquent\Builder|AulaAspirante whereAula($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AulaAspirante whereCapacidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AulaAspirante whereCarrera($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AulaAspirante whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AulaAspirante whereDisponibles($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AulaAspirante whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AulaAspirante wherePeriodo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AulaAspirante whereUpdatedAt($value)
 */
	class AulaAspirante extends \Eloquent {}
}

namespace App\Models{
/**
 *
 *
 * @property string $periodo
 * @property string $no_de_control
 * @property string|null $autoriza_escolar
 * @property string|null $recibo_pago
 * @property string|null $fecha_recibo
 * @property string|null $cuenta_pago
 * @property string|null $fecha_hora_seleccion
 * @property string|null $lugar_seleccion
 * @property string|null $fecha_hora_pago
 * @property string|null $lugar_pago
 * @property string|null $adeuda_escolar
 * @property string|null $adeuda_biblioteca
 * @property string|null $adeuda_financieros
 * @property string|null $otro_mensaje
 * @property string|null $baja
 * @property string|null $motivo_aviso_baja
 * @property string|null $egresa
 * @property string|null $encuesto
 * @property string|null $vobo_adelanta_sel
 * @property string|null $regular
 * @property float|null $indice_reprobacion
 * @property int|null $creditos_autorizados
 * @property string|null $estatus_reinscripcion
 * @property int|null $semestre
 * @property int|null $promedio
 * @property string|null $adeudo_especial
 * @property string|null $promedio_acumulado
 * @property string|null $proareas
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AvisoReinscripcion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AvisoReinscripcion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AvisoReinscripcion query()
 * @method static \Illuminate\Database\Eloquent\Builder|AvisoReinscripcion whereAdeudaBiblioteca($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvisoReinscripcion whereAdeudaEscolar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvisoReinscripcion whereAdeudaFinancieros($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvisoReinscripcion whereAdeudoEspecial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvisoReinscripcion whereAutorizaEscolar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvisoReinscripcion whereBaja($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvisoReinscripcion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvisoReinscripcion whereCreditosAutorizados($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvisoReinscripcion whereCuentaPago($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvisoReinscripcion whereEgresa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvisoReinscripcion whereEncuesto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvisoReinscripcion whereEstatusReinscripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvisoReinscripcion whereFechaHoraPago($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvisoReinscripcion whereFechaHoraSeleccion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvisoReinscripcion whereFechaRecibo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvisoReinscripcion whereIndiceReprobacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvisoReinscripcion whereLugarPago($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvisoReinscripcion whereLugarSeleccion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvisoReinscripcion whereMotivoAvisoBaja($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvisoReinscripcion whereNoDeControl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvisoReinscripcion whereOtroMensaje($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvisoReinscripcion wherePeriodo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvisoReinscripcion whereProareas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvisoReinscripcion wherePromedio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvisoReinscripcion wherePromedioAcumulado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvisoReinscripcion whereReciboPago($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvisoReinscripcion whereRegular($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvisoReinscripcion whereSemestre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvisoReinscripcion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvisoReinscripcion whereVoboAdelantaSel($value)
 */
	class AvisoReinscripcion extends \Eloquent {}
}

namespace App\Models{
/**
 *
 *
 * @property string $carrera
 * @property string $reticula
 * @property string $nivel_escolar
 * @property string $clave_oficial
 * @property string $nombre_carrera
 * @property string $nombre_reducido
 * @property string $siglas
 * @property int $carga_maxima
 * @property int $carga_minima
 * @property int|null $creditos_totales
 * @property string|null $modalidad
 * @property string|null $nreal
 * @property bool|null $ofertar
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Carrera newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Carrera newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Carrera query()
 * @method static \Illuminate\Database\Eloquent\Builder|Carrera whereCargaMaxima($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carrera whereCargaMinima($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carrera whereCarrera($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carrera whereClaveOficial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carrera whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carrera whereCreditosTotales($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carrera whereModalidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carrera whereNivelEscolar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carrera whereNombreCarrera($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carrera whereNombreReducido($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carrera whereNreal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carrera whereOfertar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carrera whereReticula($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carrera whereSiglas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carrera whereUpdatedAt($value)
 */
	class Carrera extends \Eloquent {}
}

namespace App\Models{
/**
 *
 *
 * @property int $id
 * @property string $categoria
 * @property string $descripcion
 * @property int|null $horas
 * @property int|null $horas_grupo
 * @property int|null $nivel
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Categoria newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Categoria newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Categoria query()
 * @method static \Illuminate\Database\Eloquent\Builder|Categoria whereCategoria($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Categoria whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Categoria whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Categoria whereHoras($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Categoria whereHorasGrupo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Categoria whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Categoria whereNivel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Categoria whereUpdatedAt($value)
 */
	class Categoria extends \Eloquent {}
}

namespace App\Models{
/**
 *
 *
 * @property int|null $entidad_federativa
 * @property string|null $nombre_entidad
 * @property string|null $clave_entidad
 * @method static \Illuminate\Database\Eloquent\Builder|EntidadesFederativa newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EntidadesFederativa newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EntidadesFederativa query()
 * @method static \Illuminate\Database\Eloquent\Builder|EntidadesFederativa whereClaveEntidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EntidadesFederativa whereEntidadFederativa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EntidadesFederativa whereNombreEntidad($value)
 */
	class EntidadesFederativa extends \Eloquent {}
}

namespace App\Models{
/**
 *
 *
 * @property string $especialidad
 * @property int $carrera
 * @property string $reticula
 * @property string $nombre_especialidad
 * @property int|null $creditos_optativos
 * @property int $creditos_especialidad
 * @property bool|null $activa
 * @property string $nombre_corto
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Especialidad newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Especialidad newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Especialidad query()
 * @method static \Illuminate\Database\Eloquent\Builder|Especialidad whereActiva($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Especialidad whereCarrera($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Especialidad whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Especialidad whereCreditosEspecialidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Especialidad whereCreditosOptativos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Especialidad whereEspecialidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Especialidad whereNombreCorto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Especialidad whereNombreEspecialidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Especialidad whereReticula($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Especialidad whereUpdatedAt($value)
 */
	class Especialidad extends \Eloquent {}
}

namespace App\Models{
/**
 *
 *
 * @property string $estatus
 * @property string $descripcion
 * @method static \Illuminate\Database\Eloquent\Builder|EstatusAlumno newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EstatusAlumno newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EstatusAlumno query()
 * @method static \Illuminate\Database\Eloquent\Builder|EstatusAlumno whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstatusAlumno whereEstatus($value)
 */
	class EstatusAlumno extends \Eloquent {}
}

namespace App\Models{
/**
 *
 *
 * @property string $periodo
 * @property string $no_de_control
 * @property string $materia
 * @property string|null $grupo
 * @property string|null $rfc
 * @property string|null $clave_area
 * @property string|null $encuesta
 * @property string|null $respuestas
 * @property string|null $resp_abierta
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|EvaluacionAlumno newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EvaluacionAlumno newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EvaluacionAlumno query()
 * @method static \Illuminate\Database\Eloquent\Builder|EvaluacionAlumno whereClaveArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EvaluacionAlumno whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EvaluacionAlumno whereEncuesta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EvaluacionAlumno whereGrupo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EvaluacionAlumno whereMateria($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EvaluacionAlumno whereNoDeControl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EvaluacionAlumno wherePeriodo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EvaluacionAlumno whereRespAbierta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EvaluacionAlumno whereRespuestas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EvaluacionAlumno whereRfc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EvaluacionAlumno whereUpdatedAt($value)
 */
	class EvaluacionAlumno extends \Eloquent {}
}

namespace App\Models{
/**
 *
 *
 * @property int $id
 * @property string $periodo
 * @property string $encuesta
 * @property string $fecha_inicio
 * @property string $fecha_final
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|FechaEvaluacion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FechaEvaluacion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FechaEvaluacion query()
 * @method static \Illuminate\Database\Eloquent\Builder|FechaEvaluacion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FechaEvaluacion whereEncuesta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FechaEvaluacion whereFechaFinal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FechaEvaluacion whereFechaInicio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FechaEvaluacion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FechaEvaluacion wherePeriodo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FechaEvaluacion whereUpdatedAt($value)
 */
	class FechaEvaluacion extends \Eloquent {}
}

namespace App\Models{
/**
 *
 *
 * @property int|null $carrera
 * @property string|null $fecha_inscripcion
 * @property string|null $fecha_inicio
 * @property string|null $fecha_fin
 * @property int|null $intervalo
 * @property int|null $personas
 * @property string|null $periodo
 * @property int|null $puntero
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|FechasCarrera newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FechasCarrera newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FechasCarrera query()
 * @method static \Illuminate\Database\Eloquent\Builder|FechasCarrera whereCarrera($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FechasCarrera whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FechasCarrera whereFechaFin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FechasCarrera whereFechaInicio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FechasCarrera whereFechaInscripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FechasCarrera whereIntervalo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FechasCarrera wherePeriodo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FechasCarrera wherePersonas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FechasCarrera wherePuntero($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FechasCarrera whereUpdatedAt($value)
 */
	class FechasCarrera extends \Eloquent {}
}

namespace App\Models{
/**
 *
 *
 * @property int $id
 * @property int|null $folio
 * @property string|null $periodo
 * @property string|null $control
 * @property string|null $tipo
 * @property string|null $fecha
 * @property string|null $anio
 * @property string|null $quien
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|FolioConstancia newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FolioConstancia newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FolioConstancia query()
 * @method static \Illuminate\Database\Eloquent\Builder|FolioConstancia whereAnio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FolioConstancia whereControl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FolioConstancia whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FolioConstancia whereFecha($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FolioConstancia whereFolio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FolioConstancia whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FolioConstancia wherePeriodo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FolioConstancia whereQuien($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FolioConstancia whereTipo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FolioConstancia whereUpdatedAt($value)
 */
	class FolioConstancia extends \Eloquent {}
}

namespace App\Models{
/**
 *
 *
 * @property string $no_de_control
 * @property string|null $apellido_paterno
 * @property string|null $apellido_materno
 * @property string|null $nombre_alumno
 * @property int|null $semestre
 * @property string|null $fecha_hora_seleccion
 * @property string|null $promedio_ponderado
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|GenerarListasTemporal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GenerarListasTemporal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GenerarListasTemporal query()
 * @method static \Illuminate\Database\Eloquent\Builder|GenerarListasTemporal whereApellidoMaterno($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GenerarListasTemporal whereApellidoPaterno($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GenerarListasTemporal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GenerarListasTemporal whereFechaHoraSeleccion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GenerarListasTemporal whereNoDeControl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GenerarListasTemporal whereNombreAlumno($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GenerarListasTemporal wherePromedioPonderado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GenerarListasTemporal whereSemestre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GenerarListasTemporal whereUpdatedAt($value)
 */
	class GenerarListasTemporal extends \Eloquent {}
}

namespace App\Models{
/**
 *
 *
 * @property int $periodo
 * @property string $materia
 * @property string $grupo
 * @property string|null $estatus_grupo
 * @property int $capacidad_grupo
 * @property int|null $alumnos_inscritos
 * @property string|null $folio_acta
 * @property string|null $paralelo_de
 * @property string|null $exclusivo_carrera
 * @property string|null $exclusivo_reticula
 * @property string|null $rfc
 * @property string|null $tipo_personal
 * @property string|null $exclusivo
 * @property bool|null $entrego
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Grupo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Grupo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Grupo query()
 * @method static \Illuminate\Database\Eloquent\Builder|Grupo whereAlumnosInscritos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Grupo whereCapacidadGrupo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Grupo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Grupo whereEntrego($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Grupo whereEstatusGrupo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Grupo whereExclusivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Grupo whereExclusivoCarrera($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Grupo whereExclusivoReticula($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Grupo whereFolioActa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Grupo whereGrupo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Grupo whereMateria($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Grupo whereParaleloDe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Grupo wherePeriodo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Grupo whereRfc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Grupo whereTipoPersonal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Grupo whereUpdatedAt($value)
 */
	class Grupo extends \Eloquent {}
}

namespace App\Models{
/**
 *
 *
 * @property string $periodo
 * @property string $no_de_control
 * @property string $materia
 * @property string|null $grupo
 * @property string|null $calificacion
 * @property string $tipo_evaluacion
 * @property string|null $fecha_calificacion
 * @property string|null $plan_de_estudios
 * @property string|null $estatus_materia
 * @property string|null $nopresento
 * @property string|null $usuario
 * @property string|null $periodo_acredita_materia
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $fecha_actualizacion
 * @method static \Illuminate\Database\Eloquent\Builder|HistoriaAlumno newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HistoriaAlumno newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HistoriaAlumno query()
 * @method static \Illuminate\Database\Eloquent\Builder|HistoriaAlumno whereCalificacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HistoriaAlumno whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HistoriaAlumno whereEstatusMateria($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HistoriaAlumno whereFechaActualizacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HistoriaAlumno whereFechaCalificacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HistoriaAlumno whereGrupo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HistoriaAlumno whereMateria($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HistoriaAlumno whereNoDeControl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HistoriaAlumno whereNopresento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HistoriaAlumno wherePeriodo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HistoriaAlumno wherePeriodoAcreditaMateria($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HistoriaAlumno wherePlanDeEstudios($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HistoriaAlumno whereTipoEvaluacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HistoriaAlumno whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HistoriaAlumno whereUsuario($value)
 */
	class HistoriaAlumno extends \Eloquent {}
}

namespace App\Models{
/**
 *
 *
 * @property int $periodo
 * @property string|null $docente
 * @property string $tipo_horario
 * @property int $dia_semana
 * @property string $hora_inicial
 * @property string|null $hora_final
 * @property string|null $materia
 * @property string|null $grupo
 * @property string|null $aula
 * @property string|null $actividad
 * @property int|null $consecutivo
 * @property string|null $vigencia_inicio
 * @property string|null $vigencia_fin
 * @property int|null $consecutivo_admvo
 * @property string|null $tipo_personal
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Horario newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Horario newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Horario query()
 * @method static \Illuminate\Database\Eloquent\Builder|Horario whereActividad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Horario whereAula($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Horario whereConsecutivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Horario whereConsecutivoAdmvo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Horario whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Horario whereDiaSemana($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Horario whereGrupo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Horario whereHoraFinal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Horario whereHoraInicial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Horario whereMateria($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Horario wherePeriodo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Horario whereRfc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Horario whereTipoHorario($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Horario whereTipoPersonal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Horario whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Horario whereVigenciaFin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Horario whereVigenciaInicio($value)
 */
	class Horario extends \Eloquent {}
}

namespace App\Models{
/**
 *
 *
 * @property int $id
 * @property string|null $idiomas
 * @property string|null $abrev
 * @method static \Illuminate\Database\Eloquent\Builder|Idioma newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Idioma newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Idioma query()
 * @method static \Illuminate\Database\Eloquent\Builder|Idioma whereAbrev($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Idioma whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Idioma whereIdiomas($value)
 */
	class Idioma extends \Eloquent {}
}

namespace App\Models{
/**
 *
 *
 * @property int $id
 * @property int $periodo
 * @property int $idioma
 * @property string $clave
 * @property string $nombre_completo
 * @property string $nombre_abrev
 * @property bool|null $ya_eval
 * @method static \Illuminate\Database\Eloquent\Builder|IdiomasGrupo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IdiomasGrupo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IdiomasGrupo query()
 * @method static \Illuminate\Database\Eloquent\Builder|IdiomasGrupo whereClave($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IdiomasGrupo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IdiomasGrupo whereIdioma($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IdiomasGrupo whereNombreAbrev($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IdiomasGrupo whereNombreCompleto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IdiomasGrupo wherePeriodo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IdiomasGrupo whereYaEval($value)
 */
	class IdiomasGrupo extends \Eloquent {}
}

namespace App\Models{
/**
 *
 *
 * @property string|null $periodo
 * @property string $control
 * @property string|null $calif
 * @property string|null $liberacion
 * @property int $idioma
 * @property string $opcion
 * @method static \Illuminate\Database\Eloquent\Builder|IdiomasLiberacion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IdiomasLiberacion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IdiomasLiberacion query()
 * @method static \Illuminate\Database\Eloquent\Builder|IdiomasLiberacion whereCalif($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IdiomasLiberacion whereControl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IdiomasLiberacion whereIdioma($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IdiomasLiberacion whereLiberacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IdiomasLiberacion whereOpcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IdiomasLiberacion wherePeriodo($value)
 */
	class IdiomasLiberacion extends \Eloquent {}
}

namespace App\Models{
/**
 *
 *
 * @property int $id
 * @property string $clave_area
 * @property string $descripcion_area
 * @property int $id_jefe
 * @property string|null $correo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Jefe newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Jefe newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Jefe query()
 * @method static \Illuminate\Database\Eloquent\Builder|Jefe whereClaveArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jefe whereCorreo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jefe whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jefe whereDescripcionArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jefe whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jefe whereIdJefe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jefe whereUpdatedAt($value)
 */
	class Jefe extends \Eloquent {}
}

namespace App\Models{
/**
 *
 *
 * @property int $materia
 * @property string|null $nivel_escolar
 * @property int|null $tipo_materia
 * @property string|null $clave_area
 * @property string $nombre_completo_materia
 * @property string $nombre_abreviado_materia
 * @property string|null $caracterizacion
 * @property string|null $generales
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Materia newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Materia newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Materia query()
 * @method static \Illuminate\Database\Eloquent\Builder|Materia whereCaracterizacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Materia whereClaveArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Materia whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Materia whereGenerales($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Materia whereMateria($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Materia whereNivelEscolar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Materia whereNombreAbreviadoMateria($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Materia whereNombreCompletoMateria($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Materia whereTipoMateria($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Materia whereUpdatedAt($value)
 */
	class Materia extends \Eloquent {}
}

namespace App\Models{
/**
 *
 *
 * @property string $carrera
 * @property string $reticula
 * @property int $materia
 * @property int|null $creditos_materia
 * @property int $horas_teoricas
 * @property int $horas_practicas
 * @property int|null $orden_certificado
 * @property int $semestre_reticula
 * @property int|null $creditos_prerrequisito
 * @property string|null $especialidad
 * @property string|null $clave_oficial_materia
 * @property int|null $renglon
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|MateriaCarrera newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MateriaCarrera newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MateriaCarrera query()
 * @method static \Illuminate\Database\Eloquent\Builder|MateriaCarrera whereCarrera($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MateriaCarrera whereClaveOficialMateria($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MateriaCarrera whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MateriaCarrera whereCreditosMateria($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MateriaCarrera whereCreditosPrerrequisito($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MateriaCarrera whereEspecialidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MateriaCarrera whereHorasPracticas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MateriaCarrera whereHorasTeoricas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MateriaCarrera whereMateria($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MateriaCarrera whereOrdenCertificado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MateriaCarrera whereRenglon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MateriaCarrera whereReticula($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MateriaCarrera whereSemestreReticula($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MateriaCarrera whereUpdatedAt($value)
 */
	class MateriaCarrera extends \Eloquent {}
}

namespace App\Models{
/**
 *
 *
 * @property int $id
 * @property string $motivo
 * @property string $descripcion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Motivo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Motivo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Motivo query()
 * @method static \Illuminate\Database\Eloquent\Builder|Motivo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Motivo whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Motivo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Motivo whereMotivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Motivo whereUpdatedAt($value)
 */
	class Motivo extends \Eloquent {}
}

namespace App\Models{
/**
 *
 *
 * @property int $id
 * @property int $id_estado
 * @property int $id_municipio
 * @property string $municipio
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Municipio newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Municipio newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Municipio query()
 * @method static \Illuminate\Database\Eloquent\Builder|Municipio whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Municipio whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Municipio whereIdEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Municipio whereIdMunicipio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Municipio whereMunicipio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Municipio whereUpdatedAt($value)
 */
	class Municipio extends \Eloquent {}
}

namespace App\Models{
/**
 *
 *
 * @property string $clave_area
 * @property string|null $descripcion_area
 * @property string|null $area_depende
 * @property string|null $siglas
 * @method static \Illuminate\Database\Eloquent\Builder|Organigrama newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Organigrama newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Organigrama query()
 * @method static \Illuminate\Database\Eloquent\Builder|Organigrama whereAreaDepende($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organigrama whereClaveArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organigrama whereDescripcionArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organigrama whereSiglas($value)
 */
	class Organigrama extends \Eloquent {}
}

namespace App\Models{
/**
 *
 *
 * @property int $id
 * @property string $ciudad
 * @property string $tec
 * @method static \Illuminate\Database\Eloquent\Builder|Parametro newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Parametro newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Parametro query()
 * @method static \Illuminate\Database\Eloquent\Builder|Parametro whereCiudad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Parametro whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Parametro whereTec($value)
 */
	class Parametro extends \Eloquent {}
}

namespace App\Models{
/**
 *
 *
 * @property int $periodo
 * @property string $identificacion_larga
 * @property string|null $identificacion_corta
 * @property string|null $fecha_inicio
 * @property string|null $fecha_termino
 * @property string|null $inicio_vacacional_ss
 * @property string|null $fin_vacacional_ss
 * @property string|null $inicio_especial
 * @property string|null $fin_especial
 * @property string|null $cierre_horarios
 * @property string|null $cierre_seleccion
 * @property string|null $inicio_sele_alumnos
 * @property string|null $fin_sele_alumnos
 * @property string|null $inicio_vacacional
 * @property string|null $termino_vacacional
 * @property string|null $inicio_cal_docentes
 * @property string|null $fin_cal_docentes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $ccarrera
 * @method static \Illuminate\Database\Eloquent\Builder|PeriodoEscolar newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PeriodoEscolar newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PeriodoEscolar query()
 * @method static \Illuminate\Database\Eloquent\Builder|PeriodoEscolar whereCcarrera($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriodoEscolar whereCierreHorarios($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriodoEscolar whereCierreSeleccion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriodoEscolar whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriodoEscolar whereFechaInicio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriodoEscolar whereFechaTermino($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriodoEscolar whereFinCalDocentes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriodoEscolar whereFinEspecial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriodoEscolar whereFinSeleAlumnos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriodoEscolar whereFinVacacionalSs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriodoEscolar whereIdentificacionCorta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriodoEscolar whereIdentificacionLarga($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriodoEscolar whereInicioCalDocentes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriodoEscolar whereInicioEspecial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriodoEscolar whereInicioSeleAlumnos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriodoEscolar whereInicioVacacional($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriodoEscolar whereInicioVacacionalSs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriodoEscolar wherePeriodo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriodoEscolar whereTerminoVacacional($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriodoEscolar whereUpdatedAt($value)
 */
	class PeriodoEscolar extends \Eloquent {}
}

namespace App\Models{
/**
 *
 *
 * @property int $id
 * @property int $fichas
 * @property bool $activo
 * @property string|null $inicio_prope
 * @property string|null $fin_prope
 * @property string|null $entrega
 * @property string|null $termina
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PeriodoFicha newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PeriodoFicha newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PeriodoFicha query()
 * @method static \Illuminate\Database\Eloquent\Builder|PeriodoFicha whereActivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriodoFicha whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriodoFicha whereEntrega($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriodoFicha whereFichas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriodoFicha whereFinPrope($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriodoFicha whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriodoFicha whereInicioPrope($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriodoFicha whereTermina($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriodoFicha whereUpdatedAt($value)
 */
	class PeriodoFicha extends \Eloquent {}
}

namespace App\Models{
/**
 *
 *
 * @property string|null $carrera
 * @property string|null $reticula
 * @property string|null $nombre_carrera
 * @property string|null $nombre_reducido
 * @property int|null $email
 * @method static \Illuminate\Database\Eloquent\Builder|PermisosCarrera newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PermisosCarrera newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PermisosCarrera query()
 * @method static \Illuminate\Database\Eloquent\Builder|PermisosCarrera whereCarrera($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermisosCarrera whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermisosCarrera whereNombreCarrera($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermisosCarrera whereNombreReducido($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermisosCarrera whereReticula($value)
 */
	class PermisosCarrera extends \Eloquent {}
}

namespace App\Models{
/**
 *
 *
 * @property int $id
 * @property string $rfc
 * @property string|null $clave_area
 * @property string|null $curp_empleado
 * @property int|null $no_tarjeta
 * @property string|null $apellidos_empleado
 * @property string|null $nombre_empleado
 * @property string $nombramiento
 * @property string|null $ingreso_rama
 * @property string|null $inicio_gobierno
 * @property string|null $inicio_sep
 * @property string|null $inicio_plantel
 * @property string|null $domicilio_empleado
 * @property string|null $colonia_empleado
 * @property int|null $codigo_postal_empleado
 * @property string|null $telefono_empleado
 * @property string|null $sexo_empleado
 * @property string|null $estado_civil
 * @property string|null $status_empleado
 * @property string|null $correo_electronico
 * @property string|null $correo_institucion
 * @property string|null $apellido_paterno
 * @property string|null $apellido_materno
 * @property string|null $siglas
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Personal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Personal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Personal query()
 * @method static \Illuminate\Database\Eloquent\Builder|Personal whereApellidoMaterno($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Personal whereApellidoPaterno($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Personal whereApellidosEmpleado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Personal whereClaveArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Personal whereCodigoPostalEmpleado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Personal whereColoniaEmpleado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Personal whereCorreoElectronico($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Personal whereCorreoInstitucion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Personal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Personal whereCurpEmpleado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Personal whereDomicilioEmpleado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Personal whereEstadoCivil($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Personal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Personal whereIngresoRama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Personal whereInicioGobierno($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Personal whereInicioPlantel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Personal whereInicioSep($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Personal whereNoTarjeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Personal whereNombramiento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Personal whereNombreEmpleado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Personal whereRfc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Personal whereSexoEmpleado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Personal whereSiglas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Personal whereStatusEmpleado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Personal whereTelefonoEmpleado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Personal whereUpdatedAt($value)
 */
	class Personal extends \Eloquent {}
}

namespace App\Models{
/**
 *
 *
 * @property int $id
 * @property string $carrera
 * @property string $nombre_corto
 * @property string $siglas
 * @property string|null $nivel
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalCarrera newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalCarrera newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalCarrera query()
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalCarrera whereCarrera($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalCarrera whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalCarrera whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalCarrera whereNivel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalCarrera whereNombreCorto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalCarrera whereSiglas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalCarrera whereUpdatedAt($value)
 */
	class PersonalCarrera extends \Eloquent {}
}

namespace App\Models{
/**
 *
 *
 * @property int $id
 * @property string $campo
 * @property string $lectura
 * @property string $tabla
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalDato newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalDato newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalDato query()
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalDato whereCampo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalDato whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalDato whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalDato whereLectura($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalDato whereTabla($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalDato whereUpdatedAt($value)
 */
	class PersonalDato extends \Eloquent {}
}

namespace App\Models{
/**
 *
 *
 * @property string $estatus
 * @property string|null $descripcion
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalEstatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalEstatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalEstatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalEstatus whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalEstatus whereEstatus($value)
 */
	class PersonalEstatus extends \Eloquent {}
}

namespace App\Models{
/**
 *
 *
 * @property int $id
 * @property int $id_docente
 * @property string|null $fecha_inicio
 * @property string|null $fecha_final
 * @property int|null $id_carrera
 * @property int|null $id_escuela
 * @property string|null $cedula
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalEstudio newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalEstudio newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalEstudio query()
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalEstudio whereCedula($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalEstudio whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalEstudio whereFechaFinal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalEstudio whereFechaInicio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalEstudio whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalEstudio whereIdCarrera($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalEstudio whereIdDocente($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalEstudio whereIdEscuela($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalEstudio whereUpdatedAt($value)
 */
	class PersonalEstudio extends \Eloquent {}
}

namespace App\Models{
/**
 *
 *
 * @property int $id
 * @property int $id_escuela
 * @property int $id_estado
 * @property int|null $id_municipio
 * @property string|null $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalInstitEstudio newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalInstitEstudio newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalInstitEstudio query()
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalInstitEstudio whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalInstitEstudio whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalInstitEstudio whereIdEscuela($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalInstitEstudio whereIdEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalInstitEstudio whereIdMunicipio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalInstitEstudio whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalInstitEstudio whereUpdatedAt($value)
 */
	class PersonalInstitEstudio extends \Eloquent {}
}

namespace App\Models{
/**
 *
 *
 * @property int $id
 * @property string $caracter
 * @property string $descripcion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalNivelEstudio newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalNivelEstudio newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalNivelEstudio query()
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalNivelEstudio whereCaracter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalNivelEstudio whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalNivelEstudio whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalNivelEstudio whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalNivelEstudio whereUpdatedAt($value)
 */
	class PersonalNivelEstudio extends \Eloquent {}
}

namespace App\Models{
/**
 *
 *
 * @property int $id
 * @property string $letra
 * @property string $descripcion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalNombramiento newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalNombramiento newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalNombramiento query()
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalNombramiento whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalNombramiento whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalNombramiento whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalNombramiento whereLetra($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalNombramiento whereUpdatedAt($value)
 */
	class PersonalNombramiento extends \Eloquent {}
}

namespace App\Models{
/**
 *
 *
 * @property int $id
 * @property int $id_personal
 * @property string $unidad
 * @property string $subunidad
 * @property int $id_categoria
 * @property int $horas
 * @property string $diagonal
 * @property string $estatus_plaza
 * @property int $id_motivo
 * @property string $efectos_iniciales
 * @property string $efectos_finales
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalPlaza newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalPlaza newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalPlaza query()
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalPlaza whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalPlaza whereDiagonal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalPlaza whereEfectosFinales($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalPlaza whereEfectosIniciales($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalPlaza whereEstatusPlaza($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalPlaza whereHoras($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalPlaza whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalPlaza whereIdCategoria($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalPlaza whereIdMotivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalPlaza whereIdPersonal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalPlaza whereSubunidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalPlaza whereUnidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalPlaza whereUpdatedAt($value)
 */
	class PersonalPlaza extends \Eloquent {}
}

namespace App\Models{
/**
 *
 *
 * @property int $plan_de_estudio
 * @property string $descripcion
 * @method static \Illuminate\Database\Eloquent\Builder|PlanDeEstudio newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PlanDeEstudio newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PlanDeEstudio query()
 * @method static \Illuminate\Database\Eloquent\Builder|PlanDeEstudio whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanDeEstudio wherePlanDeEstudio($value)
 */
	class PlanDeEstudio extends \Eloquent {}
}

namespace App\Models{
/**
 *
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereUpdatedAt($value)
 */
	class Role extends \Eloquent {}
}

namespace App\Models{
/**
 *
 *
 * @property string $periodo
 * @property string $no_de_control
 * @property string $materia
 * @property string $grupo
 * @property string|null $calificacion
 * @property string|null $tipo_evaluacion
 * @property string|null $repeticion
 * @property string|null $nopresento
 * @property string|null $status_seleccion
 * @property string|null $fecha_hora_seleccion
 * @property string|null $global
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|SeleccionMateria newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SeleccionMateria newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SeleccionMateria query()
 * @method static \Illuminate\Database\Eloquent\Builder|SeleccionMateria whereCalificacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeleccionMateria whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeleccionMateria whereFechaHoraSeleccion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeleccionMateria whereGlobal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeleccionMateria whereGrupo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeleccionMateria whereMateria($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeleccionMateria whereNoDeControl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeleccionMateria whereNopresento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeleccionMateria wherePeriodo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeleccionMateria whereRepeticion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeleccionMateria whereStatusSeleccion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeleccionMateria whereTipoEvaluacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeleccionMateria whereUpdatedAt($value)
 */
	class SeleccionMateria extends \Eloquent {}
}

namespace App\Models{
/**
 *
 *
 * @property int $plan_de_estudios
 * @property string $tipo_evaluacion
 * @property string|null $descripcion_evaluacion
 * @property string|null $descripcion_corta_evaluacion
 * @property int|null $calif_minima_aprobatoria
 * @property string|null $usocurso
 * @property string|null $nosegundas
 * @property int|null $prioridad
 * @method static \Illuminate\Database\Eloquent\Builder|TipoEvaluacion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoEvaluacion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoEvaluacion query()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoEvaluacion whereCalifMinimaAprobatoria($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoEvaluacion whereDescripcionCortaEvaluacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoEvaluacion whereDescripcionEvaluacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoEvaluacion whereNosegundas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoEvaluacion wherePlanDeEstudios($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoEvaluacion wherePrioridad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoEvaluacion whereTipoEvaluacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoEvaluacion whereUsocurso($value)
 */
	class TipoEvaluacion extends \Eloquent {}
}

namespace App\Models{
/**
 *
 *
 * @property int $id
 * @property string $descripcion
 * @method static \Illuminate\Database\Eloquent\Builder|TiposIngreso newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TiposIngreso newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TiposIngreso query()
 * @method static \Illuminate\Database\Eloquent\Builder|TiposIngreso whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TiposIngreso whereId($value)
 */
	class TiposIngreso extends \Eloquent {}
}

namespace App\Models{
/**
 *
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property mixed $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $two_factor_secret
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read string $profile_photo_url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

