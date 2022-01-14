@extends('adminlte::page')

@section('title', 'Idioma Extranjero')

@section('content_header')
    <h1>Servicios Escolares</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <h4 class="card-title">{{ $alumno->apellido_paterno.' '.$alumno->apellido_materno.' '.$alumno->nombre_alumno }}</h4>
        <br>
        <p class="card-text">Número de control: {{ $alumno->no_de_control }}</p>
        <h5>Constancia de liberación de idioma extranjero</h5>
        <form method="post" action="{{route('escolares.liberar_idioma2')}}" role="form">
            @csrf
            <div class="form-group">
                <label for="opcion">Opción de liberación</label>
                <select name="opcion" id="opcion" class="form-control" required>
                    <option value="" selected>--Seleccione--</option>
                    <option value="X">Examen</option>
                    <option value="A">Aprobación de curso</option>
                    <option value="D">Diplomado</option>
                    <option value="E">Institución Externa</option>
                </select>
            </div>
            <input type="hidden" name="control" value="{{$alumno->no_de_control}}">
            <input type="hidden" name="idioma" value="{{$idioma}}">
            <button type="submit" class="btn btn-primary">Continuar</button>
        </form>
    </x-information>
@stop
