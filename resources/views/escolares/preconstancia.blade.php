@extends('adminlte::page')

@section('title', 'Constancia de estudios')

@section('content_header')
    <h1>Servicios Escolares</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <h4 class="card-title">{{ $alumno->apellido_paterno.' '.$alumno->apellido_materno.' '.$alumno->nombre_alumno }}</h4>
        <br>
        <p class="card-text">Número de control: {{ $alumno->no_de_control }}</p>
        <form action="{{route('escolares.constancia')}}" method="post" role="form" target="_blank">
            @csrf
            <div class="form-group">
                <label for="tconstancia">Tipo de constancia</label>
                <select name="tconstancia" id="tconstancia" required class="form-control">
                    <option value="" selected>--Seleccione--</option>
                    <option value="P"> Por semestre </option>
                    <option value="S"> Egresado simple </option>
                    <option value="M"> Materias cursando </option>
                    <option value="A"> Constancia de avance </option>
                    <option value="E"> De estudios simple </option>
                    <option value="K"> Kardex </option>
                    <option value="D"> Tira de materias </option>
                    <option value="T"> Título en trámite </option>
                </select>
            </div>
            <div class="form-group">
                <label for="fexpedicion">Fecha de expedición</label>
                <input type="date" value="{{date('Y-m-d')}}" name="fexpedicion" class="form-control">
            </div>

            <input type="hidden" name="control" value="{{$alumno->no_de_control}}">
            <input type="hidden" name="periodo" value="{{$periodo}}">
            <button type="submit" class="btn btn-primary">Continuar</button>
        </form>
    </x-information>
@stop
