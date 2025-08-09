@extends('adminlte::page')

@section('title', 'Aspirantes')

@section('content_header')
    <h1>Desarrollo Académico</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <div class="card-header">
            <div class="card-title">Período {{$nombre_periodo->identificacion_corta}}<br>
                 Datos de la ficha {{$datos_aspirante->ficha}} </div>
        </div>
        <div class="card-body">
            <div class="accordion" id="datos_aspirante">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Datos personales
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                         data-bs-parent="#datos_aspirante">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col-3">
                                    Apellido Paterno
                                </div>
                                <div class="col">
                                    {{$datos_aspirante->apellido_paterno_aspirante}}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-3">
                                    Apellido Materno
                                </div>
                                <div class="col">
                                    {{$datos_aspirante->apellido_materno_aspirante}}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-3">
                                    Nombre
                                </div>
                                <div class="col">
                                    {{$datos_aspirante->nombre_aspirante}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Carrera solicitada
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                         data-bs-parent="#datos_aspirante">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col">
                                    @php
                                        $nombre_carrera=\Illuminate\Support\Facades\DB::table('carreras')
                                    ->select('nombre_reducido')
                                    ->where(['carrera'=>$datos_aspirante->carrera,'ofertar'=>1])
                                    ->first();
                                    @endphp
                                    {{$nombre_carrera->nombre_reducido}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Genero
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
                         data-bs-parent="#datos_aspirante">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col">{{$datos_aspirante->sexo}}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingFour">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            Fecha de nacimiento
                        </button>
                    </h2>
                    <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour"
                         data-bs-parent="#datos_aspirante">
                        <div class="accordion-body">
                            <div class="row">
                                @php
                                    $origin=date_create($datos_aspirante->fecha_nacimiento);
                                    $target=date_create(now());
                                    $interval=date_diff($origin,$target);
                                @endphp
                                <div class="col">{{$datos_aspirante->fecha_nacimiento}}. Edad: {{$interval->format('%Y')}} años</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingFive">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                            País de nacimiento
                        </button>
                    </h2>
                    <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive"
                         data-bs-parent="#datos_aspirante">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col">{{$datos_aspirante->pais}}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingSix">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                            Lugar de nacimiento
                        </button>
                    </h2>
                    <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix"
                         data-bs-parent="#datos_aspirante">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col">{{$datos_aspirante->edo_nacimiento}}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingSeven">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                            Domicilio
                        </button>
                    </h2>
                    <div id="collapseSeven" class="accordion-collapse collapse" aria-labelledby="headingSeven"
                         data-bs-parent="#datos_aspirante">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col">
                                    <div class="row">
                                        <div class="col">
                                            Calle y número
                                        </div>
                                        <div class="col">
                                            {{$datos_aspirante->calle_numero}}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            Colonia
                                        </div>
                                        <div class="col">
                                            {{$datos_aspirante->colonia}}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            Estado
                                        </div>
                                        <div class="col">
                                            {{$datos_aspirante->edo_domicilio}}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            Municipio
                                        </div>
                                        <div class="col">
                                            {{$datos_aspirante->municipio_domicilio}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingEight">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                            Teléfono
                        </button>
                    </h2>
                    <div id="collapseEight" class="accordion-collapse collapse" aria-labelledby="headingEight"
                         data-bs-parent="#datos_aspirante">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col">
                                    {{$datos_aspirante->telefono}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingNine">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
                            Correo electrónico
                        </button>
                    </h2>
                    <div id="collapseNine" class="accordion-collapse collapse" aria-labelledby="headingNine"
                         data-bs-parent="#datos_aspirante">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col">
                                    {{$datos_aspirante->correo_electronico}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingTen">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseTen" aria-expanded="false" aria-controls="collapseTen">
                            Redes sociales
                        </button>
                    </h2>
                    <div id="collapseTen" class="accordion-collapse collapse" aria-labelledby="headingTen"
                         data-bs-parent="#datos_aspirante">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col">
                                    Facebook
                                </div>
                                <div class="col">
                                    {{$datos_aspirante->facebook}}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    Instagram
                                </div>
                                <div class="col">
                                    {{$datos_aspirante->instagram}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingEleven">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseEleven" aria-expanded="false" aria-controls="collapseEleven">
                            Datos de la preparatoria de procedencia
                        </button>
                    </h2>
                    <div id="collapseEleven" class="accordion-collapse collapse" aria-labelledby="headingEleven"
                         data-bs-parent="#datos_aspirante">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col">
                                    <div class="row">
                                        <div class="col">
                                            Preparatoria
                                        </div>
                                        <div class="col">
                                            {{$datos_aspirante->preparatoria}}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            Estado
                                        </div>
                                        <div class="col">
                                            {{$datos_aspirante->edo_preparatoria}}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            Municipio
                                        </div>
                                        <div class="col">
                                            {{$datos_aspirante->mun_preparatoria}}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            Año de egreso
                                        </div>
                                        <div class="col">
                                            {{$datos_aspirante->egreso}}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            Promedio
                                        </div>
                                        <div class="col">
                                            {{$datos_aspirante->promedio}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <h5>Información capturada</h5>
                </div>
                <div class="col-6">
                    <h5>Pagos registrados</h5>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="row">
                        <div class="col">
                            Datos personales
                        </div>
                        <div class="col">
                            {{$datos_aspirante->bandera1==1?"Listo":"Sin captura"}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            Datos familiares
                        </div>
                        <div class="col">
                            {{$datos_aspirante->bandera2==1?"Listo":"Sin captura"}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            Datos preparatoria
                        </div>
                        <div class="col">
                            {{$datos_aspirante->bandera3==1?"Listo":"Sin captura"}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            Datos socioeconómicos
                        </div>
                        <div class="col">
                            {{$datos_aspirante->bandera4==1?"Listo":"Sin captura"}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            Contacto caso emergencia
                        </div>
                        <div class="col">
                            {{$datos_aspirante->bandera5==1?"Listo":"Sin captura"}}
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="row">
                        <div class="col">
                            Pago de ficha
                        </div>
                        <div class="col">
                            {{$datos_aspirante->pago1==1?"Listo":"Sin registro de pago"}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            Pago de curso propedéutico
                        </div>
                        <div class="col">
                            {{$datos_aspirante->pago2==1?"Listo":"Sin registro de pago"}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            Pago de inscripción
                        </div>
                        <div class="col">
                            {{$datos_aspirante->pago3==1?"Listo":"Sin registro de pago"}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#carreraModal">
                        Modificar carrera
                    </button>
                </div>
                <div class="col-md-4">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#passwdModal">
                        Modificar contraseña
                    </button>
                </div>
                <div class="col-md-4">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#pagosModal">
                        Modificar pagos
                    </button>
                </div>
            </div>

            <!-- Modal cambio de carrera -->
            <div class="modal fade" id="carreraModal" tabindex="-1" aria-labelledby="carreraModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="carreraModalLabel">Cambio de carrera</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{route('desarrollo.actualizar_datos_aspirante')}}" method="post" role="form">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="carrera" class="col-form-label">Carrera:</label>
                                    <select class="form-select" id="carrera" name="carrera" required>
                                        <option selected>Seleccione una carrera</option>
                                        @foreach($carreras as $carrera)
                                            <option value="{{$carrera->carrera}}">{{$carrera->nombre_reducido}}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="aspirante" value="{{$aspirante}}">
                                    <input type="hidden" name="periodo" value="{{$periodo}}">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary">Continuar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal cambio de contraseña -->
            <div class="modal fade" id="passwdModal" tabindex="-1" aria-labelledby="passwdModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="passwdModalLabel">Cambio de contraseña</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{route('desarrollo.contra_aspirante')}}" method="post" role="form">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="contra" class="col-form-label">Nueva contraseña:</label>
                                    <input type="password" name="contra" id="contra" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="verifica" class="col-form-label">Verifique:</label>
                                    <input type="password" name="verifica" id="verifica" class="form-control" required>
                                </div>
                                <input type="hidden" name="aspirante" value="{{$aspirante}}">
                                <input type="hidden" name="periodo" value="{{$periodo}}">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary">Continuar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal cambio de pagos -->
            <div class="modal fade" id="pagosModal" tabindex="-1" aria-labelledby="pagosModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="pagosModalLabel">Modificar pagos</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{route('desarrollo.pago_aspirante')}}" method="post" role="form">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="pago" class="col-form-label">Pago a registrar:</label>
                                    <select class="form-select" id="pago" name="pago" required>
                                        <option selected>Seleccione</option>
                                        <option value="2">Registrar pago de propedéutico</option>
                                        <option value="3">Registrar pago de inscripción</option>
                                    </select>
                                    <input type="hidden" name="aspirante" value="{{$aspirante}}">
                                    <input type="hidden" name="periodo" value="{{$periodo}}">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary">Continuar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    La opción de modificar carrera, es posible SIEMPRE Y CUANDO el estudiante aún no cuente con
                    carga académica asignada; ya que, de contar con horario y número de control, entonces es estudiante
                    y el cambio lo debe de realizar División de Estudios
                </div>
            </div>
        </div>
    </x-information>
@endsection
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7"
          crossorigin="anonymous">
@stop
@section('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq"
            crossorigin="anonymous"></script>
@stop
