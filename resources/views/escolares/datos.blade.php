@extends('adminlte::page')

@section('title', 'Inicio')

@section('content_header')
    <h1>Servicios Escolares</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <h4 class="card-title">{{ $alumno->apellido_paterno.' '.$alumno->apellido_materno.' '.$alumno->nombre_alumno }}</h4>
        <p class="card-text">Número de control: {{ $control }}</p>
        <p class="card-text">{{ $ncarrera->nombre_carrera }} Ret {{ $alumno->reticula }}</p>
        <p class="card-text">Período de ingreso {{ $ingreso->identificacion_corta }}</p>
        <p class="card-text">Semestre {{ $alumno->semestre }}</p>
        <p class="card-text">Especialidad: {{ $especialidad}}</p>
        <p class="card-text">Estatus actual: {{ $estatus[0]->descripcion }}</p>
        <p class="card-text">NIP: {{ $alumno->nip }}</p>
    </x-information>
    <x-aditional>
        @slot('header','Datos Generales')
        <div class="row">
            <div class="col-sm-12 col-md-6">Domicilio</div>
            <div class="col-sm-12 col-md-6">Calle {{ $bandera==1?$datos->domicilio_calle:''}} Colonia
                {{ $bandera==1?$datos->domicilio_colonia:''}} C.P. {{ $bandera==1?$datos->codigo_postal:'' }}
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-6">Telefono</div>
            <div class="col-sm-12 col-md-6">{{ $bandera==1?$datos->telefono:'' }} </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-6">Correo</div>
            <div class="col-sm-12 col-md-6">{{ $alumno->correo_electronico }} </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-6">NSS</div>
            <div class="col-sm-12 col-md-6">{{ $alumno->nss }} </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-6">CURP</div>
            <div class="col-sm-12 col-md-6">{{ $alumno->curp_alumno }} </div>
        </div>
    </x-aditional>
    <x-aditional>
        @slot('header','Datos Adicionales')
        <form method="post" action="" class="form-inline" role="form">
            @csrf
            <legend>Seleccione una opción</legend>
            <div class="row">
                <div class="form-group col-sm-12 col-md-6">
                    <label for="accion" class="sr-only">Acción</label>
                    <select name="accion" id="accion" required class="form-control">
                        <option value="" selected>--Seleccione--</option>
                        <option value="1">Kárdex</option>
                        <option value="2">Retícula</option>
                        <option value="3">Constancia de Estudios</option>
                        <option value="4">Boleta</option>
                        <option value="5">Horario</option>
                        <option value="6">Cambiar estatus</option>
                        <option value="7">Validar reinscripción</option>
                        <option value="8">Asignar especialidad</option>
                        <option value="9">Cambio carrera</option>
                        <option value="10">Eliminar número de control</option>
                        <option value="11">Baja temporal o definitiva</option>
                        <option value="12">Asignación de NSS</option>
                        <option value="13">Acreditar complementaria</option>
                        <option value="14">Liberación idioma extranjero</option>
                        <option value="15">Certificado</option>
                        <option value="16">Modificar datos estudiante</option>
                    </select>
                </div>
                <div class="form-group col-sm-12 col-md-6">
                    <label for="periodo" class="sr-only">Periodo</label>
                    <select name="periodo" id="periodo" required class="form-control">
                        @foreach($periodos as $pers)
                            @if($periodo[0]->periodo==$pers->periodo)
                                <option value="{{$pers->periodo}}" selected>{{$pers->identificacion_corta}}</option>
                            @else
                                <option value="{{$pers->periodo}}">{{$pers->identificacion_corta}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Continuar</button>
            <div class="row">
                <div class="col-sm-12 col-md-12">
                    El período puede no ser necesario dependiendo de la acción a realizar
                </div>
            </div>
            <input type="hidden" name="control" id="control" value="{{ $control}}">
        </form>
    </x-aditional>
@stop
