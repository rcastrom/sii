@extends('adminlte::page')

@section('title', 'Baja temporal')

@section('content_header')
    <h1>Servicios Escolares</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <h4 class="card-title">{{ $alumno->apellido_paterno.' '.$alumno->apellido_materno.' '.$alumno->nombre_alumno }}</h4>
        <br>
        <p class="card-text">Número de control: {{ $alumno->no_de_control }}</p>
        <form action="{{route('escolares.accion_bajatemp')}}" method="post">
            @csrf
            <div class="form-group">
                <label for="tbaja">Señale el tipo de baja a realizar</label>
                <select name="tbaja" id="tbaja" required class="form-control">
                    <option value="" selected>--Seleccione--</option>
                    <option value="BT1">Baja temporal</option>
                    <option value="BDG">Baja definitiva</option>
                    <option value="BDE">Baja definitiva por emisión de certificado parcial</option>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">¿Continuar?</button>
                <input type="hidden" name="control" id="control" value="{{$alumno->no_de_control}}">
                <input type="hidden" name="periodo" id="periodo" value="{{$periodo}}">
            </div>
        </form>
    </x-information>
@stop
