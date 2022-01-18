@extends('adminlte::page')

@section('title', 'Grupos')

@section('content_header')
    <h1>División de Estudios Profesionales</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <h4 class="card-title">Materia: {{$nmateria->nombre_completo_materia}} Grupo: {{$grupo}}</h4><br>
        <h5>Clave: {{$materia}}</h5><br>
        <form action="{{route('dep_bajaa')}}" method="post" role="form">
            @csrf
            @method('DELETE')
            <div class="form-group">
                <label for="control">Indique por favor el número de control del estudiante</label>
                <select name="control" id="control" required class="form-control">
                    <option value="" selected>--Seleccione--</option>
                    @foreach($alumnos as $listado)
                        <option value="{{$listado->no_de_control}}">
                            {{$listado->no_de_control}} {{$listado->apellido_paterno}} {{$listado->apellido_materno}} {{$listado->nombre_alumno}}
                        </option>
                    @endforeach
                </select>
            </div>
            <input type="hidden" name="materia" id="materia" value="{{$materia}}">
            <input type="hidden" name="grupo" id="grupo" value="{{$grupo}}">
            <input type="hidden" name="periodo" id="periodo" value="{{$periodo}}">
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Continuar</button>
            </div>
        </form>
    </x-information>
@stop
