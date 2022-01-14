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
        <form action="{{route('escolares.idiomas')}}" method="post" role="form" target="_blank">
            @csrf
            <div class="form-group">
                <label for="fexpedicion">Fecha de expedición de la constancia</label>
                <input type="date" value="{{date('Y-m-d')}}" name="fexpedicion" class="form-control">
            </div>
            <input type="hidden" name="control" value="{{$alumno->no_de_control}}">
            <button type="submit" class="btn btn-primary">Continuar</button>
        </form>
    </x-information>
@stop
