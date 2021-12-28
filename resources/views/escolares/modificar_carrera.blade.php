@extends('adminlte::page')

@section('title', 'Cambio carrera')

@section('content_header')
    <h1>Servicios Escolares</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <h4 class="card-title">{{ $alumno->apellido_paterno.' '.$alumno->apellido_materno.' '.$alumno->nombre_alumno }}</h4>
        <br>
        <p class="card-text">Número de control: {{ $alumno->no_de_control }}</p>
        <form action="{{route('escolares.accion_actualiza_carrera')}}" method="post" role="form">
            @csrf
            <div class="form-group">
                <label for="carrera_n">Cambiar a la carrera (convalidación) de alumno</label>
                <select name="carrera_n" id="carrera_n" class="form-control" required>
                    <option value="" selected>--Seleccione--</option>
                    @foreach($carreras as $carrera)
                        <option value="{{trim($carrera->carrera)."_".$carrera->reticula}}">{{$carrera->nombre_carrera}} (RET {{$carrera->reticula}})</option>
                    @endforeach
                </select>
            </div>
            <input type="hidden" name="control" value="{{$alumno->no_de_control}}">
            <button type="submit" class="btn btn-primary">Continuar</button>
        </form>
    </x-information>
@stop

