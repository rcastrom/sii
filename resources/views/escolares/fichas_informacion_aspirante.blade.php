@extends('adminlte::page')

@section('title', 'Aspirantes')

@section('content_header')
    <h1>Servicios Escolares</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">
                    <div class="card card-primary card outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <img src="{{$documentos->imagen}}" alt="Aspirante" class="profile-user-img
                                img-fluid img-circle">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header p-2">
                            <ul class="nav nav-pills">
                                <li class="nav-item"><a href="#aspirante" class="nav-link active"
                                                        data-toggle="tab">Datos aspirante</a></li>
                                <li class="nav-item"><a href="#generales" class="nav-link"
                                    data-toggle="tab">Datos generales</a></li>
                                <li class="nav-item"><a href="#captura" class="nav-link"
                                                        data-toggle="tab">Información capturada</a></li>
                                <li class="nav-item"><a href="#documentos" class="nav-link"
                                                        data-toggle="tab">Documentos subidos</a></li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="active tab-pane" id="aspirante">
                                    <div class="card card-primary">
                                        <div class="card-header">
                                            <h3 class="card-title">Datos del aspirante</h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                                        title="Expandir">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            En caso de requerir modificar algún dato del aspirante, lo puede llevar a
                                            cabo en el formulario presentado.
                                            No podrá actualizar o modificar información sobre un período de fichas
                                            diferente al actual
                                            @if ($errors->any())
                                                <div class="alert alert-danger">
                                                    <ul>
                                                        @foreach($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                            <form action="{{route('ficha.update',['ficha'=>$aspirante->ficha])}}" method="post">
                                                @csrf
                                                @method('PUT')
                                                <div class="form-group">
                                                    <label for="ficha">Ficha</label>
                                                    <input type="text" name="ficha" id="ficha" readonly
                                                           class="form-control" value="{{$aspirante->ficha}}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="apellido_paterno">Primer apellido del aspirante</label>
                                                    <input type="text" name="apellido_paterno" id="apellido_paterno"
                                                           required onchange="this.value=this.value.toUpperCase();"
                                                           class="form-control" value="{{$aspirante->apellido_paterno_aspirante}}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="apellido_materno">Segundo apellido del aspirante</label>
                                                    <input type="text" name="apellido_materno" id="apellido_materno"
                                                           onchange="this.value=this.value.toUpperCase();"
                                                           class="form-control" value="{{$aspirante->apellido_materno_aspirante}}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="nombre_aspirante">Nombre del aspirante</label>
                                                    <input type="text" name="nombre_aspirante" id="nombre_aspirante"
                                                           required onchange="this.value=this.value.toUpperCase();"
                                                           class="form-control" value="{{$aspirante->nombre_aspirante}}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="carrera">Carrera solicitada</label>
                                                    <select name="carrera" id="carrera" class="form-control" required>
                                                        @foreach($carreras as $carrera)
                                                            <option value="{{$carrera->carrera}}"
                                                                {{$carrera->carrera==$aspirante->carrera?' selected':''}}>
                                                                {{$carrera->nombre_carrera}}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="curp">CURP</label>
                                                    <input type="text" name="curp" id="curp" required
                                                           onchange="this.value=this.value.toUpperCase();"
                                                           class="form-control" value="{{$aspirante->curp}}">
                                                </div>
                                                <button type="submit" class="btn btn-primary">Actualizar</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="generales">
                                    <div class="card card-primary">
                                        <div class="card-header">
                                            <h3 class="card-title">Información particular</h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                                        title="Expandir">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="calle_numero">Domicilio</label>
                                                <input type="text" name="calle_numero" id="calle_numero" readonly
                                                       class="form-control" value="{{$aspirante->calle_numero}}">
                                            </div>
                                            <div class="form-group">
                                                <label for="colonia">Colonia</label>
                                                <input type="text" name="colonia" id="colonia" readonly
                                                       class="form-control" value="{{$aspirante->colonia}}">
                                            </div>
                                            <div class="form-group">
                                                <label for="municipio">Municipio</label>
                                                <input type="text" name="municipio" id="municipio" readonly
                                                       class="form-control" value="{{$aspirante->municipio_domicilio}}">
                                            </div>
                                            <div class="form-group">
                                                <label for="telefono">Teléfono</label>
                                                <input type="text" name="telefono" id="telefono" readonly
                                                       class="form-control" value="{{$aspirante->telefono}}">
                                            </div>
                                            <div class="form-group">
                                                <label for="correo">Correo electrónico</label>
                                                <input type="text" name="correo" id="correo" readonly
                                                       class="form-control" value="{{$aspirante->correo_electronico}}">
                                            </div>
                                            <div class="form-group">
                                                <label for="prepa">Preparatoria de procedencia</label>
                                                <input type="text" name="prepa" id="prepa" readonly
                                                       class="form-control" value="{{$aspirante->preparatoria}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="captura">
                                    <div class="card card-primary">
                                        <div class="card-header">
                                            <h3 class="card-title">Información capturada hasta el momento</h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                                        title="Expandir">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="datos_personales">Datos personales</label>
                                                {{$aspirante->bandera1==1?"Si":"No"}}
                                            </div>
                                            <div class="form-group">
                                                <label for="datos_familiares">Datos familiares</label>
                                                {{$aspirante->bandera2==1?"Si":"No"}}
                                            </div>
                                            <div class="form-group">
                                                <label for="datos_socioeconomicos">Datos socioeconómicos</label>
                                                {{$aspirante->bandera3==1?"Si":"No"}}
                                            </div>
                                            <div class="form-group">
                                                <label for="datos_prepa">Datos preparatoria</label>
                                                {{$aspirante->bandera4==1?"Si":"No"}}
                                            </div>
                                            <div class="form-group">
                                                <label for="datos_emergencia">Datos emergencia</label>
                                                {{$aspirante->bandera5==1?"Si":"No"}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="documentos">
                                    <div class="card card-primary">
                                        <div class="card-header">
                                            <h3 class="card-title">Documentos subidos</h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                                        title="Expandir">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <strong>IMPORTANTE</strong><br>
                                            La información que se suba al servidor, es eliminada cuando se lleve a cabo
                                            el cambio de período de ficha; RESPALDE la información
                                            <div class="form-group">
                                                @if(is_null($documentos->prepa))
                                                    No ha subido su certificado de preparatoria
                                                @else
                                                    <i class="fa fa-file-pdf"></i>
                                                    <a href="{{$documentos->prepa}}" target="_blank">Certificado de
                                                        preparatoria</a>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                @if(is_null($documentos->constancia))
                                                    No tiene o no cuenta o no ha entregado su constancia de estudios
                                                    donde indique que esté en último semestre
                                                @else
                                                    <i class="fa fa-file-pdf"></i>
                                                    <a href="{{$documentos->constancia}}" target="_blank">Constancia de
                                                        preparatoria</a>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                @if(is_null($documentos->acta))
                                                    No ha subido su acta de nacimiento
                                                @else
                                                    <i class="fa fa-file-pdf"></i>
                                                    <a href="{{$documentos->acta}}" target="_blank">Acta de nacimiento</a>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                @if(is_null($documentos->clave_curp))
                                                    No ha subido su CURP
                                                @else
                                                    <i class="fa fa-file-pdf"></i>
                                                    <a href="{{$documentos->clave_curp}}" target="_blank">CURP</a>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                @if(is_null($documentos->imss))
                                                    No ha subido su NSS
                                                @else
                                                    <i class="fa fa-file-pdf"></i>
                                                    <a href="{{$documentos->imss}}" target="_blank">NSS</a>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                @if(is_null($documentos->migracion))
                                                    No requiere forma migratoria; o bien, no la ha entregado
                                                @else
                                                    <i class="fa fa-file-pdf"></i>
                                                    <a href="{{$documentos->migracion}}" target="_blank">Forma migratoria</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3>Validación de documentos</h3>
                        </div>
                        <div class="card-body">
                            Indique si alguno de los documentos que el aspirante subió a la
                            plataforma deba ser eliminado
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                                <form action="" method="post" role="form">
                                    <div class="form-check">
                                        @if(!is_null($documentos->prepa))
                                            <input type="checkbox" name="documentos" id="doc_prepa"
                                                   class="form-check-input" value="prepa">
                                            <label for="doc_prepa" class="form-check-label">
                                                Certificado de preparatoria
                                            </label>
                                        @endif
                                    </div>
                                    <div class="form-check">
                                        @if(!is_null($documentos->constancia))
                                            <input type="checkbox" name="documentos" id="doc_constancia"
                                                   class="form-check-input" value="constancia">
                                            <label for="doc_constancia" class="form-check-label">
                                                Constancia de preparatoria
                                            </label>
                                        @endif
                                    </div>
                                    <div class="form-check">
                                        @if(!is_null($documentos->acta))
                                            <input type="checkbox" name="documentos" id="doc_acta"
                                                   class="form-check-input" value="acta_nacimiento">
                                            <label for="doc_acta" class="form-check-label">
                                                Acta de nacimiento
                                            </label>
                                        @endif
                                    </div>
                                    <div class="form-check">
                                        @if(!is_null($documentos->clave_curp))
                                            <input type="checkbox" name="documentos" id="doc_curp"
                                                   class="form-check-input" value="curp">
                                            <label for="doc_curp" class="form-check-label">
                                                CURP
                                            </label>
                                        @endif
                                    </div>
                                    <div class="form-check">
                                        @if(!is_null($documentos->imss))
                                            <input type="checkbox" name="documentos" id="doc_imss"
                                                   class="form-check-input" value="imss">
                                            <label for="doc_imss" class="form-check-label">
                                                NSS
                                            </label>
                                        @endif
                                    </div>
                                    <div class="form-check">
                                        @if(!is_null($documentos->migracion))
                                            <input type="checkbox" name="documentos" id="doc_migratorio"
                                                   class="form-check-input" value="migratorio">
                                            <label for="doc_migratorio" class="form-check-label">
                                                Forma migratoria
                                            </label>
                                        @endif
                                    </div>
                                    <button type="submit" class="btn btn-primary">Continuar</button>
                                </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-information>

@stop
