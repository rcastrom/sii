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
 * @property string|null $actividad
 * @property string|null $descripcion_actividad
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActividadesApoyo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActividadesApoyo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActividadesApoyo query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActividadesApoyo whereActividad($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActividadesApoyo whereDescripcionActividad($value)
 */
	class ActividadesApoyo extends \Eloquent {}
}

namespace App\Models{
/**
 * Class AcumuladoHistorico
 *
 * @package App
 * @mixin Builder
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcumuladoHistorico newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcumuladoHistorico newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcumuladoHistorico query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcumuladoHistorico whereCreditosAprobados($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcumuladoHistorico whereCreditosAutorizados($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcumuladoHistorico whereCreditosCursados($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcumuladoHistorico whereEstatusPeriodoAlumno($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcumuladoHistorico whereIndiceReprobacionAcumulado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcumuladoHistorico whereIndiceReprobacionSemestre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcumuladoHistorico whereMateriasAExamenEspecial($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcumuladoHistorico whereMateriasCursadas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcumuladoHistorico whereMateriasEspecialReprobadas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcumuladoHistorico whereMateriasReprobadas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcumuladoHistorico whereNoDeControl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcumuladoHistorico wherePeriodo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcumuladoHistorico wherePromedioAritmetico($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcumuladoHistorico wherePromedioAritmeticoAcumulado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcumuladoHistorico wherePromedioCertificado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcumuladoHistorico wherePromedioPonderado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcumuladoHistorico wherePromedioPonderadoAcumulado($value)
 */
	class AcumuladoHistorico extends \Eloquent {}
}

namespace App\Models{
/**
 * Class Alumno
 *
 * @package App
 * @mixin Builder
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumno newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumno newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumno query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumno whereApellidoMaterno($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumno whereApellidoPaterno($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumno whereBecadoPor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumno whereCarrera($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumno whereCiudadProcedencia($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumno whereCorreoElectronico($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumno whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumno whereCreditosAprobados($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumno whereCreditosCursados($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumno whereCurpAlumno($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumno whereEntidadProcedencia($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumno whereEscuelaProcedencia($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumno whereEspecialidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumno whereEstadoCivil($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumno whereEstatusAlumno($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumno whereFechaNacimiento($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumno whereFechaTitulacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumno whereNip($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumno whereNivelEscolar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumno whereNoDeControl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumno whereNombreAlumno($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumno whereNss($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumno whereOpcionTitulacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumno wherePeriodoIngresoIt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumno wherePeriodoTitulacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumno wherePeriodosRevalidacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumno wherePlanDeEstudios($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumno wherePromedioAritmeticoAcumulado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumno wherePromedioFinalAlcanzado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumno wherePromedioPeriodoAnterior($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumno whereReticula($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumno whereSemestre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumno whereSexo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumno whereTipoIngreso($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumno whereUltimoPeriodoInscrito($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumno whereUpdatedAt($value)
 */
	class Alumno extends \Eloquent {}
}

namespace App\Models{
/**
 * Class AlumnosGeneral
 *
 * @package App
 * @mixin Builder
 * @property string $no_de_control
 * @property string|null $domicilio_calle
 * @property string|null $domicilio_colonia
 * @property string|null $codigo_postal
 * @property string|null $telefono
 * @property string|null $facebook
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AlumnosGeneral newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AlumnosGeneral newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AlumnosGeneral query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AlumnosGeneral whereCodigoPostal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AlumnosGeneral whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AlumnosGeneral whereDomicilioCalle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AlumnosGeneral whereDomicilioColonia($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AlumnosGeneral whereFacebook($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AlumnosGeneral whereNoDeControl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AlumnosGeneral whereTelefono($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AlumnosGeneral whereUpdatedAt($value)
 */
	class AlumnosGeneral extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property string $periodo
 * @property int $docente
 * @property string $actividad
 * @property int $consecutivo
 * @property string|null $especifica_actividad
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApoyoDocencia newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApoyoDocencia newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApoyoDocencia query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApoyoDocencia whereActividad($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApoyoDocencia whereConsecutivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApoyoDocencia whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApoyoDocencia whereDocente($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApoyoDocencia whereEspecificaActividad($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApoyoDocencia wherePeriodo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApoyoDocencia whereUpdatedAt($value)
 */
	class ApoyoDocencia extends \Eloquent {}
}

namespace App\Models{
/**
 * Class Aula
 *
 * @package App
 * @mixin Builder
 * @property int $id
 * @property string $aula
 * @property string $ubicacion
 * @property int|null $capacidad
 * @property bool|null $estatus
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Aula newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Aula newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Aula query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Aula whereAula($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Aula whereCapacidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Aula whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Aula whereEstatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Aula whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Aula whereUbicacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Aula whereUpdatedAt($value)
 */
	class Aula extends \Eloquent {}
}

namespace App\Models{
/**
 * Class AulaAspirante
 *
 * @package App
 * @mixin Builder
 * @property int $id
 * @property string $periodo
 * @property int $aula
 * @property int $capacidad
 * @property int $disponibles
 * @property string $carrera
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AulaAspirante newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AulaAspirante newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AulaAspirante query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AulaAspirante whereAula($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AulaAspirante whereCapacidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AulaAspirante whereCarrera($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AulaAspirante whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AulaAspirante whereDisponibles($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AulaAspirante whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AulaAspirante wherePeriodo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AulaAspirante whereUpdatedAt($value)
 */
	class AulaAspirante extends \Eloquent {}
}

namespace App\Models{
/**
 * Class AvisoReinscripcion
 *
 * @package App
 * @mixin Builder
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvisoReinscripcion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvisoReinscripcion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvisoReinscripcion query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvisoReinscripcion whereAdeudaBiblioteca($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvisoReinscripcion whereAdeudaEscolar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvisoReinscripcion whereAdeudaFinancieros($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvisoReinscripcion whereAdeudoEspecial($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvisoReinscripcion whereAutorizaEscolar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvisoReinscripcion whereBaja($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvisoReinscripcion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvisoReinscripcion whereCreditosAutorizados($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvisoReinscripcion whereCuentaPago($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvisoReinscripcion whereEgresa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvisoReinscripcion whereEncuesto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvisoReinscripcion whereEstatusReinscripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvisoReinscripcion whereFechaHoraPago($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvisoReinscripcion whereFechaHoraSeleccion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvisoReinscripcion whereFechaRecibo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvisoReinscripcion whereIndiceReprobacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvisoReinscripcion whereLugarPago($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvisoReinscripcion whereLugarSeleccion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvisoReinscripcion whereMotivoAvisoBaja($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvisoReinscripcion whereNoDeControl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvisoReinscripcion whereOtroMensaje($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvisoReinscripcion wherePeriodo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvisoReinscripcion whereProareas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvisoReinscripcion wherePromedio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvisoReinscripcion wherePromedioAcumulado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvisoReinscripcion whereReciboPago($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvisoReinscripcion whereRegular($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvisoReinscripcion whereSemestre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvisoReinscripcion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvisoReinscripcion whereVoboAdelantaSel($value)
 */
	class AvisoReinscripcion extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $parcial
 * @property string $no_de_control
 * @property int $calificacion
 * @property bool|null $desertor
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalificacionParcial newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalificacionParcial newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalificacionParcial query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalificacionParcial whereCalificacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalificacionParcial whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalificacionParcial whereDesertor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalificacionParcial whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalificacionParcial whereNoDeControl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalificacionParcial whereParcial($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalificacionParcial whereUpdatedAt($value)
 */
	class CalificacionParcial extends \Eloquent {}
}

namespace App\Models{
/**
 * Class Carrera
 *
 * @package App
 * @mixin Builder
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Carrera newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Carrera newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Carrera query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Carrera whereCargaMaxima($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Carrera whereCargaMinima($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Carrera whereCarrera($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Carrera whereClaveOficial($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Carrera whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Carrera whereCreditosTotales($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Carrera whereModalidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Carrera whereNivelEscolar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Carrera whereNombreCarrera($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Carrera whereNombreReducido($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Carrera whereNreal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Carrera whereOfertar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Carrera whereReticula($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Carrera whereSiglas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Carrera whereUpdatedAt($value)
 */
	class Carrera extends \Eloquent {}
}

namespace App\Models{
/**
 * Class Categoria
 *
 * @package App
 * @mixin Builder
 * @property int $id
 * @property string $categoria
 * @property string $descripcion
 * @property int|null $horas
 * @property int|null $horas_grupo
 * @property int|null $nivel
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Categoria newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Categoria newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Categoria query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Categoria whereCategoria($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Categoria whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Categoria whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Categoria whereHoras($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Categoria whereHorasGrupo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Categoria whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Categoria whereNivel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Categoria whereUpdatedAt($value)
 */
	class Categoria extends \Eloquent {}
}

namespace App\Models{
/**
 * Class EntidadesFederativos
 *
 * @package App
 * @mixin Builder
 * @property int|null $entidad_federativa
 * @property string|null $nombre_entidad
 * @property string|null $clave_entidad
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EntidadesFederativa newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EntidadesFederativa newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EntidadesFederativa query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EntidadesFederativa whereClaveEntidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EntidadesFederativa whereEntidadFederativa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EntidadesFederativa whereNombreEntidad($value)
 */
	class EntidadesFederativa extends \Eloquent {}
}

namespace App\Models{
/**
 * Class Especialidad
 *
 * @package App
 * @mixin Builder
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Especialidad newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Especialidad newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Especialidad query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Especialidad whereActiva($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Especialidad whereCarrera($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Especialidad whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Especialidad whereCreditosEspecialidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Especialidad whereCreditosOptativos($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Especialidad whereEspecialidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Especialidad whereNombreCorto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Especialidad whereNombreEspecialidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Especialidad whereReticula($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Especialidad whereUpdatedAt($value)
 */
	class Especialidad extends \Eloquent {}
}

namespace App\Models{
/**
 * Class EstatusAlumno
 *
 * @package App
 * @mixin Builder
 * @property string $estatus
 * @property string $descripcion
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EstatusAlumno newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EstatusAlumno newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EstatusAlumno query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EstatusAlumno whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EstatusAlumno whereEstatus($value)
 */
	class EstatusAlumno extends \Eloquent {}
}

namespace App\Models{
/**
 * Class EvaluacionAlumno
 *
 * @package App
 * @mixin Builder
 * @property string $periodo
 * @property string $no_de_control
 * @property string $materia
 * @property string $grupo
 * @property int|null $personal
 * @property string|null $clave_area
 * @property string $encuesta
 * @property string $respuestas
 * @property string|null $resp_abierta
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $consecutivo
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvaluacionAlumno newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvaluacionAlumno newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvaluacionAlumno query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvaluacionAlumno whereClaveArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvaluacionAlumno whereConsecutivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvaluacionAlumno whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvaluacionAlumno whereEncuesta($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvaluacionAlumno whereGrupo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvaluacionAlumno whereMateria($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvaluacionAlumno whereNoDeControl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvaluacionAlumno wherePeriodo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvaluacionAlumno wherePersonal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvaluacionAlumno whereRespAbierta($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvaluacionAlumno whereRespuestas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvaluacionAlumno whereUpdatedAt($value)
 */
	class EvaluacionAlumno extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $aspecto
 * @property string $encuesta
 * @property string $descripcion
 * @property int $consecutivo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvaluacionAspecto newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvaluacionAspecto newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvaluacionAspecto query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvaluacionAspecto whereAspecto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvaluacionAspecto whereConsecutivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvaluacionAspecto whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvaluacionAspecto whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvaluacionAspecto whereEncuesta($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvaluacionAspecto whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvaluacionAspecto whereUpdatedAt($value)
 */
	class EvaluacionAspecto extends \Eloquent {}
}

namespace App\Models{
/**
 * Class FechaEvaluacion
 *
 * @package App
 * @mixin Builder
 * @property int $id
 * @property string $periodo
 * @property string $encuesta
 * @property string $fecha_inicio
 * @property string $fecha_final
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FechaEvaluacion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FechaEvaluacion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FechaEvaluacion query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FechaEvaluacion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FechaEvaluacion whereEncuesta($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FechaEvaluacion whereFechaFinal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FechaEvaluacion whereFechaInicio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FechaEvaluacion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FechaEvaluacion wherePeriodo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FechaEvaluacion whereUpdatedAt($value)
 */
	class FechaEvaluacion extends \Eloquent {}
}

namespace App\Models{
/**
 * Class FechasCarrera
 *
 * @package App
 * @mixin Builder
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FechasCarrera newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FechasCarrera newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FechasCarrera query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FechasCarrera whereCarrera($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FechasCarrera whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FechasCarrera whereFechaFin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FechasCarrera whereFechaInicio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FechasCarrera whereFechaInscripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FechasCarrera whereIntervalo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FechasCarrera wherePeriodo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FechasCarrera wherePersonas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FechasCarrera wherePuntero($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FechasCarrera whereUpdatedAt($value)
 */
	class FechasCarrera extends \Eloquent {}
}

namespace App\Models{
/**
 * Class FolioConstancia
 *
 * @package App
 * @mixin Builder
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FolioConstancia newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FolioConstancia newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FolioConstancia query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FolioConstancia whereAnio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FolioConstancia whereControl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FolioConstancia whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FolioConstancia whereFecha($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FolioConstancia whereFolio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FolioConstancia whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FolioConstancia wherePeriodo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FolioConstancia whereQuien($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FolioConstancia whereTipo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FolioConstancia whereUpdatedAt($value)
 */
	class FolioConstancia extends \Eloquent {}
}

namespace App\Models{
/**
 * Class GenerarListasTemporal
 *
 * @package App
 * @mixin Builder
 * @property string $no_de_control
 * @property string|null $apellido_paterno
 * @property string|null $apellido_materno
 * @property string|null $nombre_alumno
 * @property int|null $semestre
 * @property string|null $fecha_hora_seleccion
 * @property string|null $promedio_ponderado
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GenerarListasTemporal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GenerarListasTemporal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GenerarListasTemporal query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GenerarListasTemporal whereApellidoMaterno($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GenerarListasTemporal whereApellidoPaterno($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GenerarListasTemporal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GenerarListasTemporal whereFechaHoraSeleccion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GenerarListasTemporal whereNoDeControl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GenerarListasTemporal whereNombreAlumno($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GenerarListasTemporal wherePromedioPonderado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GenerarListasTemporal whereSemestre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GenerarListasTemporal whereUpdatedAt($value)
 */
	class GenerarListasTemporal extends \Eloquent {}
}

namespace App\Models{
/**
 * Class Grupo
 *
 * @package App
 * @mixin Builder
 * @property int $periodo
 * @property string $materia
 * @property string $grupo
 * @property string|null $estatus_grupo
 * @property int|null $capacidad_grupo
 * @property int|null $alumnos_inscritos
 * @property string|null $folio_acta
 * @property string|null $paralelo_de
 * @property string|null $carrera
 * @property string|null $reticula
 * @property int|null $docente
 * @property string|null $tipo_personal
 * @property string|null $exclusivo
 * @property bool|null $entrego
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grupo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grupo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grupo query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grupo whereAlumnosInscritos($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grupo whereCapacidadGrupo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grupo whereCarrera($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grupo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grupo whereDocente($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grupo whereEntrego($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grupo whereEstatusGrupo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grupo whereExclusivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grupo whereFolioActa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grupo whereGrupo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grupo whereMateria($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grupo whereParaleloDe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grupo wherePeriodo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grupo whereReticula($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grupo whereTipoPersonal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grupo whereUpdatedAt($value)
 */
	class Grupo extends \Eloquent {}
}

namespace App\Models{
/**
 * Class HistoriaAlumno
 *
 * @package App
 * @mixin Builder
 * @property int $id
 * @property string $periodo
 * @property string $no_de_control
 * @property string $materia
 * @property string|null $grupo
 * @property int|null $calificacion
 * @property string|null $tipo_evaluacion
 * @property string|null $plan_de_estudios
 * @property string|null $estatus_materia
 * @property string|null $nopresento
 * @property string|null $usuario
 * @property string|null $periodo_acredita_materia
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistoriaAlumno newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistoriaAlumno newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistoriaAlumno query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistoriaAlumno whereCalificacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistoriaAlumno whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistoriaAlumno whereEstatusMateria($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistoriaAlumno whereGrupo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistoriaAlumno whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistoriaAlumno whereMateria($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistoriaAlumno whereNoDeControl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistoriaAlumno whereNopresento($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistoriaAlumno wherePeriodo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistoriaAlumno wherePeriodoAcreditaMateria($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistoriaAlumno wherePlanDeEstudios($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistoriaAlumno whereTipoEvaluacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistoriaAlumno whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistoriaAlumno whereUsuario($value)
 */
	class HistoriaAlumno extends \Eloquent {}
}

namespace App\Models{
/**
 * Class Horario
 *
 * @package App
 * @mixin Builder
 * @property int $periodo
 * @property int|null $docente
 * @property string $tipo_horario
 * @property int $dia_semana
 * @property \Illuminate\Support\Carbon $hora_inicial
 * @property \Illuminate\Support\Carbon|null $hora_final
 * @property string|null $materia
 * @property string|null $grupo
 * @property string|null $aula
 * @property string|null $actividad
 * @property int|null $consecutivo
 * @property \Illuminate\Support\Carbon|null $vigencia_inicio
 * @property \Illuminate\Support\Carbon|null $vigencia_fin
 * @property int|null $consecutivo_admvo
 * @property string|null $tipo_personal
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Horario newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Horario newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Horario query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Horario whereActividad($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Horario whereAula($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Horario whereConsecutivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Horario whereConsecutivoAdmvo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Horario whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Horario whereDiaSemana($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Horario whereDocente($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Horario whereGrupo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Horario whereHoraFinal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Horario whereHoraInicial($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Horario whereMateria($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Horario wherePeriodo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Horario whereTipoHorario($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Horario whereTipoPersonal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Horario whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Horario whereVigenciaFin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Horario whereVigenciaInicio($value)
 */
	class Horario extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property string $periodo
 * @property int $docente
 * @property int $consecutivo_admvo
 * @property int $descripcion_horario
 * @property string $area_adscripcion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioAdministrativo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioAdministrativo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioAdministrativo query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioAdministrativo whereAreaAdscripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioAdministrativo whereConsecutivoAdmvo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioAdministrativo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioAdministrativo whereDescripcionHorario($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioAdministrativo whereDocente($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioAdministrativo wherePeriodo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioAdministrativo whereUpdatedAt($value)
 */
	class HorarioAdministrativo extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $periodo
 * @property int $personal
 * @property int $descripcion_horario
 * @property string $area_adscripcion
 * @property string|null $observacion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioNoDocente newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioNoDocente newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioNoDocente query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioNoDocente whereAreaAdscripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioNoDocente whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioNoDocente whereDescripcionHorario($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioNoDocente whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioNoDocente whereObservacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioNoDocente wherePeriodo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioNoDocente wherePersonal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioNoDocente whereUpdatedAt($value)
 */
	class HorarioNoDocente extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $periodo
 * @property int $docente
 * @property string $observaciones
 * @property string|null $depto
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioObservacion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioObservacion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioObservacion query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioObservacion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioObservacion whereDepto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioObservacion whereDocente($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioObservacion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioObservacion whereObservaciones($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioObservacion wherePeriodo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioObservacion whereUpdatedAt($value)
 */
	class HorarioObservacion extends \Eloquent {}
}

namespace App\Models{
/**
 * Class Idioma
 *
 * @package App
 * @mixin Builder
 * @property int $id
 * @property string|null $idiomas
 * @property string|null $abrev
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Idioma newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Idioma newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Idioma query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Idioma whereAbrev($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Idioma whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Idioma whereIdiomas($value)
 */
	class Idioma extends \Eloquent {}
}

namespace App\Models{
/**
 * Class IdiomasGrupo
 *
 * @package App
 * @mixin Builder
 * @property int $id
 * @property int $periodo
 * @property int $idioma
 * @property string $clave
 * @property string $nombre_completo
 * @property string $nombre_abrev
 * @property bool|null $ya_eval
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IdiomasGrupo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IdiomasGrupo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IdiomasGrupo query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IdiomasGrupo whereClave($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IdiomasGrupo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IdiomasGrupo whereIdioma($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IdiomasGrupo whereNombreAbrev($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IdiomasGrupo whereNombreCompleto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IdiomasGrupo wherePeriodo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IdiomasGrupo whereYaEval($value)
 */
	class IdiomasGrupo extends \Eloquent {}
}

namespace App\Models{
/**
 * Class IdiomasLiberacion
 *
 * @package App
 * @mixin Builder
 * @property string|null $periodo
 * @property string $control
 * @property string|null $calif
 * @property string|null $liberacion
 * @property int $idioma
 * @property string $opcion
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IdiomasLiberacion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IdiomasLiberacion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IdiomasLiberacion query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IdiomasLiberacion whereCalif($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IdiomasLiberacion whereControl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IdiomasLiberacion whereIdioma($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IdiomasLiberacion whereLiberacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IdiomasLiberacion whereOpcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IdiomasLiberacion wherePeriodo($value)
 */
	class IdiomasLiberacion extends \Eloquent {}
}

namespace App\Models{
/**
 * Class Jefe
 *
 * @package App
 * @mixin Builder
 * @property int $id
 * @property string $clave_area
 * @property string $descripcion_area
 * @property int $id_jefe
 * @property string|null $correo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jefe newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jefe newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jefe query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jefe whereClaveArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jefe whereCorreo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jefe whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jefe whereDescripcionArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jefe whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jefe whereIdJefe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jefe whereUpdatedAt($value)
 */
	class Jefe extends \Eloquent {}
}

namespace App\Models{
/**
 * Class Materia
 *
 * @package App
 * @mixin Builder
 * @property string $materia
 * @property string|null $nivel_escolar
 * @property int|null $tipo_materia
 * @property string|null $clave_area
 * @property string $nombre_completo_materia
 * @property string $nombre_abreviado_materia
 * @property string|null $caracterizacion
 * @property string|null $generales
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Materia newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Materia newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Materia query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Materia whereCaracterizacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Materia whereClaveArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Materia whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Materia whereGenerales($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Materia whereMateria($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Materia whereNivelEscolar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Materia whereNombreAbreviadoMateria($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Materia whereNombreCompletoMateria($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Materia whereTipoMateria($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Materia whereUpdatedAt($value)
 */
	class Materia extends \Eloquent {}
}

namespace App\Models{
/**
 * Class MateriaCarrera
 *
 * @package App
 * @mixin Builder
 * @property string $carrera
 * @property string $reticula
 * @property string $materia
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MateriaCarrera newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MateriaCarrera newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MateriaCarrera query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MateriaCarrera whereCarrera($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MateriaCarrera whereClaveOficialMateria($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MateriaCarrera whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MateriaCarrera whereCreditosMateria($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MateriaCarrera whereCreditosPrerrequisito($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MateriaCarrera whereEspecialidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MateriaCarrera whereHorasPracticas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MateriaCarrera whereHorasTeoricas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MateriaCarrera whereMateria($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MateriaCarrera whereOrdenCertificado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MateriaCarrera whereRenglon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MateriaCarrera whereReticula($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MateriaCarrera whereSemestreReticula($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MateriaCarrera whereUpdatedAt($value)
 */
	class MateriaCarrera extends \Eloquent {}
}

namespace App\Models{
/**
 * Class Motivo
 *
 * @package App
 * @mixin Builder
 * @property int $id
 * @property string $motivo
 * @property string $descripcion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Motivo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Motivo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Motivo query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Motivo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Motivo whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Motivo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Motivo whereMotivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Motivo whereUpdatedAt($value)
 */
	class Motivo extends \Eloquent {}
}

namespace App\Models{
/**
 * Class Municipio
 *
 * @package App
 * @mixin Builder
 * @property int $id
 * @property int $id_estado
 * @property int $id_municipio
 * @property string $municipio
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Municipio newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Municipio newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Municipio query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Municipio whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Municipio whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Municipio whereIdEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Municipio whereIdMunicipio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Municipio whereMunicipio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Municipio whereUpdatedAt($value)
 */
	class Municipio extends \Eloquent {}
}

namespace App\Models{
/**
 * Class Organigrama
 *
 * @package App
 * @mixin Builder
 * @property string $clave_area
 * @property string|null $descripcion_area
 * @property string|null $area_depende
 * @property string|null $siglas
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organigrama newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organigrama newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organigrama query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organigrama whereAreaDepende($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organigrama whereClaveArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organigrama whereDescripcionArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organigrama whereSiglas($value)
 */
	class Organigrama extends \Eloquent {}
}

namespace App\Models{
/**
 * Class Parametro
 *
 * @package App
 * @mixin Builder
 * @property int $id
 * @property string $ciudad
 * @property string $tec
 * @property string|null $cct
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parametro newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parametro newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parametro query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parametro whereCct($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parametro whereCiudad($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parametro whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parametro whereTec($value)
 */
	class Parametro extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $periodo
 * @property string $materia
 * @property string $grupo
 * @property int $unidad
 * @property int $docente
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parcial newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parcial newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parcial query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parcial whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parcial whereDocente($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parcial whereGrupo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parcial whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parcial whereMateria($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parcial wherePeriodo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parcial whereUnidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parcial whereUpdatedAt($value)
 */
	class Parcial extends \Eloquent {}
}

namespace App\Models{
/**
 * Class PeriodoEscolar
 *
 * @package App
 * @mixin Builder
 * @property int $id
 * @property string $periodo
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
 * @property bool|null $cambio_carrera
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodoEscolar newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodoEscolar newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodoEscolar query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodoEscolar whereCambioCarrera($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodoEscolar whereCierreHorarios($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodoEscolar whereCierreSeleccion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodoEscolar whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodoEscolar whereFechaInicio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodoEscolar whereFechaTermino($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodoEscolar whereFinCalDocentes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodoEscolar whereFinEspecial($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodoEscolar whereFinSeleAlumnos($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodoEscolar whereFinVacacionalSs($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodoEscolar whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodoEscolar whereIdentificacionCorta($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodoEscolar whereIdentificacionLarga($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodoEscolar whereInicioCalDocentes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodoEscolar whereInicioEspecial($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodoEscolar whereInicioSeleAlumnos($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodoEscolar whereInicioVacacional($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodoEscolar whereInicioVacacionalSs($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodoEscolar wherePeriodo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodoEscolar whereTerminoVacacional($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodoEscolar whereUpdatedAt($value)
 */
	class PeriodoEscolar extends \Eloquent {}
}

namespace App\Models{
/**
 * Class PeriodoFicha
 *
 * @package App
 * @mixin Builder
 * @property int $id
 * @property int $fichas
 * @property bool $activo
 * @property string|null $inicio_prope
 * @property string|null $fin_prope
 * @property string|null $entrega
 * @property string|null $termina
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodoFicha newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodoFicha newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodoFicha query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodoFicha whereActivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodoFicha whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodoFicha whereEntrega($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodoFicha whereFichas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodoFicha whereFinPrope($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodoFicha whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodoFicha whereInicioPrope($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodoFicha whereTermina($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodoFicha whereUpdatedAt($value)
 */
	class PeriodoFicha extends \Eloquent {}
}

namespace App\Models{
/**
 * Class PermisosCarrera
 *
 * @package App
 * @mixin Builder
 * @property int $id
 * @property string $carrera
 * @property int $reticula
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PermisosCarrera newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PermisosCarrera newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PermisosCarrera query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PermisosCarrera whereCarrera($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PermisosCarrera whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PermisosCarrera whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PermisosCarrera whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PermisosCarrera whereReticula($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PermisosCarrera whereUpdatedAt($value)
 */
	class PermisosCarrera extends \Eloquent {}
}

namespace App\Models{
/**
 * Class Personal
 *
 * @package App
 * @mixin Builder
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personal query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personal whereApellidoMaterno($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personal whereApellidoPaterno($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personal whereApellidosEmpleado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personal whereClaveArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personal whereCodigoPostalEmpleado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personal whereColoniaEmpleado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personal whereCorreoElectronico($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personal whereCorreoInstitucion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personal whereCurpEmpleado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personal whereDomicilioEmpleado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personal whereEstadoCivil($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personal whereIngresoRama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personal whereInicioGobierno($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personal whereInicioPlantel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personal whereInicioSep($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personal whereNoTarjeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personal whereNombramiento($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personal whereNombreEmpleado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personal whereRfc($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personal whereSexoEmpleado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personal whereSiglas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personal whereStatusEmpleado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personal whereTelefonoEmpleado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personal whereUpdatedAt($value)
 */
	class Personal extends \Eloquent {}
}

namespace App\Models{
/**
 * Class PersonalCarrera
 *
 * @package App
 * @mixin Builder
 * @property int $id
 * @property string $carrera
 * @property string $nombre_corto
 * @property string $siglas
 * @property string|null $nivel
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalCarrera newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalCarrera newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalCarrera query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalCarrera whereCarrera($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalCarrera whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalCarrera whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalCarrera whereNivel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalCarrera whereNombreCorto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalCarrera whereSiglas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalCarrera whereUpdatedAt($value)
 */
	class PersonalCarrera extends \Eloquent {}
}

namespace App\Models{
/**
 * Class PersonalDato
 *
 * @package App
 * @mixin Builder
 * @property int $id
 * @property string $campo
 * @property string $lectura
 * @property string $tabla
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalDato newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalDato newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalDato query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalDato whereCampo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalDato whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalDato whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalDato whereLectura($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalDato whereTabla($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalDato whereUpdatedAt($value)
 */
	class PersonalDato extends \Eloquent {}
}

namespace App\Models{
/**
 * Class PersonalEstatus
 *
 * @package App
 * @mixin Builder
 * @property string $estatus
 * @property string|null $descripcion
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalEstatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalEstatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalEstatus query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalEstatus whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalEstatus whereEstatus($value)
 */
	class PersonalEstatus extends \Eloquent {}
}

namespace App\Models{
/**
 * Class PersonalEstudio
 *
 * @package App
 * @mixin Builder
 * @property int $id
 * @property int $id_docente
 * @property string|null $fecha_inicio
 * @property string|null $fecha_final
 * @property int|null $id_carrera
 * @property int|null $id_escuela
 * @property string|null $cedula
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalEstudio newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalEstudio newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalEstudio query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalEstudio whereCedula($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalEstudio whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalEstudio whereFechaFinal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalEstudio whereFechaInicio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalEstudio whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalEstudio whereIdCarrera($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalEstudio whereIdDocente($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalEstudio whereIdEscuela($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalEstudio whereUpdatedAt($value)
 */
	class PersonalEstudio extends \Eloquent {}
}

namespace App\Models{
/**
 * Class PersonalInstitEstudio
 *
 * @package App
 * @mixin Builder
 * @property int $id
 * @property int $id_escuela
 * @property int $id_estado
 * @property int|null $id_municipio
 * @property string|null $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalInstitEstudio newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalInstitEstudio newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalInstitEstudio query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalInstitEstudio whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalInstitEstudio whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalInstitEstudio whereIdEscuela($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalInstitEstudio whereIdEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalInstitEstudio whereIdMunicipio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalInstitEstudio whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalInstitEstudio whereUpdatedAt($value)
 */
	class PersonalInstitEstudio extends \Eloquent {}
}

namespace App\Models{
/**
 * Class PersonalNivelEstudio
 *
 * @package App
 * @mixin Builder
 * @property int $id
 * @property string $caracter
 * @property string $descripcion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalNivelEstudio newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalNivelEstudio newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalNivelEstudio query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalNivelEstudio whereCaracter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalNivelEstudio whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalNivelEstudio whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalNivelEstudio whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalNivelEstudio whereUpdatedAt($value)
 */
	class PersonalNivelEstudio extends \Eloquent {}
}

namespace App\Models{
/**
 * Class PersonalNombramiento
 *
 * @package App
 * @mixin Builder
 * @property int $id
 * @property string $letra
 * @property string $descripcion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalNombramiento newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalNombramiento newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalNombramiento query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalNombramiento whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalNombramiento whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalNombramiento whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalNombramiento whereLetra($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalNombramiento whereUpdatedAt($value)
 */
	class PersonalNombramiento extends \Eloquent {}
}

namespace App\Models{
/**
 * Class PersonalPlaza
 *
 * @package App
 * @mixin Builder
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalPlaza newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalPlaza newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalPlaza query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalPlaza whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalPlaza whereDiagonal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalPlaza whereEfectosFinales($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalPlaza whereEfectosIniciales($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalPlaza whereEstatusPlaza($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalPlaza whereHoras($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalPlaza whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalPlaza whereIdCategoria($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalPlaza whereIdMotivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalPlaza whereIdPersonal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalPlaza whereSubunidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalPlaza whereUnidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalPlaza whereUpdatedAt($value)
 */
	class PersonalPlaza extends \Eloquent {}
}

namespace App\Models{
/**
 * Class PlanDeEstudio
 *
 * @package App
 * @mixin Builder
 * @property int $plan_de_estudio
 * @property string $descripcion
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanDeEstudio newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanDeEstudio newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanDeEstudio query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanDeEstudio whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanDeEstudio wherePlanDeEstudio($value)
 */
	class PlanDeEstudio extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property string $encuesta
 * @property string $aspecto
 * @property int $no_pregunta
 * @property string|null $pregunta
 * @property string|null $respuestas
 * @property int|null $resp_val
 * @property int $consecutivo
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pregunta newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pregunta newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pregunta query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pregunta whereAspecto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pregunta whereConsecutivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pregunta whereEncuesta($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pregunta whereNoPregunta($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pregunta wherePregunta($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pregunta whereRespVal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pregunta whereRespuestas($value)
 */
	class Pregunta extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int|null $clave_puesto
 * @property string|null $descripcion_puesto
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Puesto newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Puesto newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Puesto query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Puesto whereClavePuesto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Puesto whereDescripcionPuesto($value)
 */
	class Puesto extends \Eloquent {}
}

namespace App\Models{
/**
 * Class Role
 *
 * @package App
 * @mixin Builder
 * @property int $id
 * @property string $name
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereUpdatedAt($value)
 */
	class Role extends \Eloquent {}
}

namespace App\Models{
/**
 * Class SeleccionMateria
 *
 * @package App
 * @mixin Builder
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeleccionMateria newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeleccionMateria newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeleccionMateria query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeleccionMateria whereCalificacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeleccionMateria whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeleccionMateria whereFechaHoraSeleccion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeleccionMateria whereGlobal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeleccionMateria whereGrupo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeleccionMateria whereMateria($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeleccionMateria whereNoDeControl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeleccionMateria whereNopresento($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeleccionMateria wherePeriodo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeleccionMateria whereRepeticion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeleccionMateria whereStatusSeleccion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeleccionMateria whereTipoEvaluacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeleccionMateria whereUpdatedAt($value)
 */
	class SeleccionMateria extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $periodo
 * @property string $no_de_control
 * @property string $materia
 * @property string $grupo
 * @property string $movimiento
 * @property string $responsable
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeleccionMateriaLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeleccionMateriaLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeleccionMateriaLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeleccionMateriaLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeleccionMateriaLog whereGrupo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeleccionMateriaLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeleccionMateriaLog whereMateria($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeleccionMateriaLog whereMovimiento($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeleccionMateriaLog whereNoDeControl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeleccionMateriaLog wherePeriodo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeleccionMateriaLog whereResponsable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeleccionMateriaLog whereUpdatedAt($value)
 */
	class SeleccionMateriaLog extends \Eloquent {}
}

namespace App\Models{
/**
 * Class TipoEvaluacion
 *
 * @package App
 * @mixin Builder
 * @property int $plan_de_estudios
 * @property string $tipo_evaluacion
 * @property string|null $descripcion_evaluacion
 * @property string|null $descripcion_corta_evaluacion
 * @property int|null $calif_minima_aprobatoria
 * @property string|null $usocurso
 * @property string|null $nosegundas
 * @property int|null $prioridad
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TipoEvaluacion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TipoEvaluacion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TipoEvaluacion query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TipoEvaluacion whereCalifMinimaAprobatoria($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TipoEvaluacion whereDescripcionCortaEvaluacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TipoEvaluacion whereDescripcionEvaluacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TipoEvaluacion whereNosegundas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TipoEvaluacion wherePlanDeEstudios($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TipoEvaluacion wherePrioridad($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TipoEvaluacion whereTipoEvaluacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TipoEvaluacion whereUsocurso($value)
 */
	class TipoEvaluacion extends \Eloquent {}
}

namespace App\Models{
/**
 * Class TiposIngreso
 *
 * @package App
 * @mixin Builder
 * @property int $id
 * @property string $descripcion
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TiposIngreso newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TiposIngreso newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TiposIngreso query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TiposIngreso whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TiposIngreso whereId($value)
 */
	class TiposIngreso extends \Eloquent {}
}

namespace App\Models{
/**
 * Class User
 *
 * @package App
 * @mixin Builder
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTwoFactorSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

