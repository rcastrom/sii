@extends('adminlte::page')

@section('title', 'Asignación de Especialidad')

@section('content_header')
    <h1>Servicios Escolares</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <h4 class="card-title">{{ $alumno->apellido_paterno.' '.$alumno->apellido_materno.' '.$alumno->nombre_alumno }}</h4>
        <br>
        <p class="card-text">Número de control: {{ $alumno->no_de_control }}</p>
        <form action="{{route('escolares.accion_actualiza_especialidad')}}" method="post" role="form">
            @csrf
            <div class="form-group">
                <label for="espe">Cambio de especialidad de alumno</label>
                <select name="espe" id="espe" class="form-control" required>
                    <option value="" selected>--Seleccione--</option>
                    @foreach($especialidades as $espe)
                        <option value="{{$espe->especialidad}}">{{$espe->nombre_especialidad}}</option>
                    @endforeach
                </select>
            </div>
            <input type="hidden" name="control" value="{{$alumno->no_de_control}}">
            <button type="submit" class="btn btn-primary">Continuar</button>
        </form>
    </x-information>
@stop
