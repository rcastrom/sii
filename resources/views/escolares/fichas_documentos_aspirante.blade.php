@extends('adminlte::page')

@section('title', 'Aspirantes')

@section('content_header')
    <h1>Servicios Escolares</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-info">
                        <div class="card-header">
                            Datos del aspirante
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Información general</h5>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                Nombre:
                                {{$aspirante->apellido_paterno_aspirante.' '.$aspirante->apellido_materno_aspirante.' '.$aspirante->nombre_aspirante}}
                            </li>
                            <li class="list-group-item">
                                Carrera: {{$carrera_aspirante->nombre_carrera}}
                            </li>
                            <li class="list-group-item">
                                Ficha: {{$aspirante->ficha}}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            Documentos subidos al sistema
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">IMPORTANTE</h5>
                            <p class="card-text">Los datos son eliminados del sistema cada
                                cambio de período de entrega de fichas; es importante que respalde
                                la información
                            <ul class="list-group list-group-flush">
                                @if(is_null($documentos->prepa))
                                    <li class="list-group-item">No ha subido su certificado de preparatoria
                                    </li>
                                @else
                                    <li class="list-group-item">
                                        <i class="fa fa-file-pdf"></i>
                                        <a href="{{$documentos->prepa}}" target="_blank">Certificado de
                                            preparatoria</a>
                                    </li>
                                @endif
                                @if(is_null($documentos->constancia))
                                    <li class="list-group-item">
                                        No require o no ha entregado su constancia de estudios
                                        donde indique que esté en último semestre
                                    </li>
                                @else
                                    <li class="list-group-item">
                                        <i class="fa fa-file-pdf"></i>
                                        <a href="{{$documentos->constancia}}" target="_blank">Constancia de
                                            preparatoria</a>
                                    </li>
                                @endif
                                @if(is_null($documentos->acta))
                                    <li class="list-group-item">No ha subido su acta de nacimiento
                                    </li>
                                @else
                                    <li class="list-group-item">
                                        <i class="fa fa-file-pdf"></i>
                                        <a href="{{$documentos->acta}}" target="_blank">Acta de nacimiento</a>
                                    </li>
                                @endif
                                @if(is_null($documentos->clave_curp))
                                    <li class="list-group-item">
                                        No ha subido su CURP
                                    </li>
                                @else
                                    <li class="list-group-item">
                                        <i class="fa fa-file-pdf"></i>
                                        <a href="{{$documentos->clave_curp}}" target="_blank">CURP</a>
                                    </li>
                                @endif
                                @if(is_null($documentos->imss))
                                    <li class="list-group-item">
                                        No ha subido su NSS
                                    </li>
                                @else
                                    <li class="list-group-item">
                                        <i class="fa fa-file-pdf"></i>
                                        <a href="{{$documentos->imss}}" target="_blank">NSS</a>
                                    </li>
                                @endif
                                @if(is_null($documentos->migracion))
                                    <li class="list-group-item">
                                        No requiere forma migratoria; o bien, no la ha entregado
                                    </li>
                                @else
                                    <li class="list-group-item">
                                        <i class="fa fa-file-pdf"></i>
                                        <a href="{{$documentos->migracion}}" target="_blank">Forma migratoria</a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            Documentos marcados como entregados
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Formulario de entrega</h5>
                            <p class="card-text">Emplee el siguiente formulario para modificar la
                            información registrada en el sistema
                            <form action="{{route('escolares.actualizar_documentos',['ficha'=>$ficha])}}" method="post">
                                @csrf
                                <div class="form-group">
                                    <div class="form-check">
                                        <input type="checkbox" name="documentos[]" id="cert_prepa"
                                               class="form-check-input" value="cert_prepa"
                                        {{$documentos_capturados->cert_prepa?'checked':''}}>
                                        <label for="cert_prepa" class="form-check-label">
                                            Certificado de preparatoria
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-check">
                                        <input type="checkbox" name="documentos[]" id="const_terminacion"
                                               class="form-check-input" value="const_terminacion"
                                            {{$documentos_capturados->const_terminacion?'checked':''}}>
                                        <label for="const_terminacion" class="form-check-label">
                                            Constancia de estudios vigente que señale que cursa el 6to semestre
                                            de preparatoria; o bien, constancia de certificado en trámite
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-check">
                                        <input type="checkbox" name="documentos[]" id="acta_nacimiento"
                                               class="form-check-input" value="acta_nacimiento"
                                            {{$documentos_capturados->acta_nacimiento?'checked':''}}>
                                        <label for="acta_nacimiento" class="form-check-label">
                                            Acta de nacimiento
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-check">
                                        <input type="checkbox" name="documentos[]" id="curp"
                                               class="form-check-input" value="curp"
                                            {{$documentos_capturados->curp?'checked':''}}>
                                        <label for="curp" class="form-check-label">
                                            CURP
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-check">
                                        <input type="checkbox" name="documentos[]" id="nss"
                                               class="form-check-input" value="nss"
                                            {{$documentos_capturados->nss?'checked':''}}>
                                        <label for="nss" class="form-check-label">
                                            NSS
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-check form-check-inline">
                                        <input type="radio" name="migratorio" id="extranjero1"
                                               class="form-check-input" value="1"
                                            {{$documentos_capturados->migratorio==1?'checked':''}}>
                                        <label for="extranjero1" class="form-check-label">
                                            Entregó forma migratoria
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" name="migratorio" id="extranjero2"
                                               class="form-check-input" value="2"
                                            {{$documentos_capturados->migratorio==2?'checked':''}}>
                                        <label for="extranjero2" class="form-check-label">
                                            Adeuda forma migratoria
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" name="migratorio" id="nacional"
                                               class="form-check-input" value="3"
                                            {{$documentos_capturados->migratorio==3?'checked':''}}>
                                        <label for="nacional" class="form-check-label">
                                            No requiere forma migratoria
                                        </label>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Actualizar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-information>

@stop
