@extends('adminlte::page')

@section('title', 'Alumnos')

@section('content_header')
    <h1>División de Estudios Profesionales</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <h4 class="card-title">{{ $alumno->apellido_paterno.' '.$alumno->apellido_materno.' '.$alumno->nombre_alumno }}</h4>
        <p class="card-text">Número de control: {{ $alumno->no_de_control }}</p>
        <p class="card-text">{{ $ncarrera->nombre_carrera }} Ret {{ $alumno->reticula }}</p>
        <p class="card-text">Semestre {{ $alumno->semestre }}</p>
        <p class="card-text">Estatus actual: {{ $estatus->descripcion }}</p>
        <p class="card-text">Especialidad: {{ $especialidad }}</p>
        <form method="post" action="{{route('dep.accion2')}}" class="form-inline" role="form">
            @csrf
            <legend>Seleccione una opción</legend>
            <div class="row">
                <div class="form-group col-sm-12 col-md-6">
                    <label for="accion" class="sr-only">Acción</label>
                    <select name="accion" id="accion" required class="form-control">
                        <option value="" selected>--Seleccione--</option>
                        <option value="1">Kárdex</option>
                        <option value="2">Retícula</option>
                        <option value="3">Horario</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Continuar</button>
            <input type="hidden" name="control" id="control" value="{{ $alumno->no_de_control }}">
        </form>
    </x-information>
@stop
