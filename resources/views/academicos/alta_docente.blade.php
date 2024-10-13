@extends('adminlte::page')

@section('title', 'Inicio')

@section('content_header')
    <h1>Jefaturas académicas</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <form action="{{route('academicos.altadocente')}}" method="post" role="form">
            @csrf
            <div class="form-group">
                <label for="control">Seleccione al docente asignado para la materia</label>
                <select name="docente" id="docente" required class="form-control">
                    <option value="" selected>--Seleccione--</option>
                    @foreach($personal as $listado)
                        <option value="{{$listado->id}}">
                            {{$listado->apellidos_empleado}} {{$listado->nombre_empleado}}
                        </option>
                    @endforeach
                </select>
            </div>
            <input type="hidden" name="materia" value="{{$materia}}">
            <input type="hidden" name="grupo" value="{{$grupo}}">
            <input type="hidden" name="periodo" value="{{$periodo}}">
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Continuar</button>
            </div>
        </form>
    </x-information>
@stop
