@extends('adminlte::page')

@section('title', 'Cancelación num control')

@section('content_header')
    <h1>Servicios Escolares</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <h4 class="card-title">{{ $alumno->apellido_paterno.' '.$alumno->apellido_materno.' '.$alumno->nombre_alumno }}</h4>
        <br>
        <p class="card-text">Número de control: {{ $alumno->no_de_control }}</p>
        <x-alert>
            @php
                $mensaje="Éste módulo se encarga de eliminar el número de control para ".$alumno->no_de_control.", por lo que
               se borrará su historial académico y demás información.";
            @endphp
            @slot('mensaje',$mensaje)
        </x-alert>
        <form action="{{route('escolares.accion_borrar')}}" method="post">
            @csrf
            <div class="form-group">
                <input type="checkbox" value="1" name="confirmar" required class="form-check-inline">Favor de confirmar
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-danger">¿Continuar?</button>
                <input type="hidden" name="control" id="control" value="{{$alumno->no_de_control}}">
            </div>
        </form>
    </x-information>
@stop
