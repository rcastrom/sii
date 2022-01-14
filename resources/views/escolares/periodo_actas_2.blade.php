@extends('adminlte::page')

@section('title', 'Actas')

@section('content_header')
    <h1>Servicios Escolares</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <h4 class="card-title">Actas del período {{$nperiodo->identificacion_corta}}</h4>
        <br>
        <form action="{{route('escolares.registro3')}}" method="post" class="form-inline" role="form">
            @csrf
            <div class="form-group">
                <label for="docente" class="col-form-label">Indique al docente</label>
                <select name="docente" id="docente" class="form-control" required>
                    <option value="" selected>--Seleccione--</option>
                    @foreach($docentes as $docente)
                        <option value="{{$docente->rfc}}">{{$docente->apellidos_empleado}} {{$docente->nombre_empleado}}</option>
                    @endforeach
                </select>
            </div>
            <input type="hidden" name="periodo" value="{{$periodo}}">
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Continuar</button>
            </div>
        </form>
    </x-information>
@stop
