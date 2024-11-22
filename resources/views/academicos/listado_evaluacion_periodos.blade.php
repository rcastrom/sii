@extends('adminlte::page')

@section('title', 'Inicio')

@section('content_header')
    <h1>Jefaturas académicas</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <p>En base al listado de carreras - reticulas, seleccione obtener el concentrado de
            la evaluación al docente correspondiente</p>
        <form action="{{route('academicos.evaluacion_docente')}}" method="post" role="form" class="form">
            @csrf
            <div class="form-group">
                <label for="datos_carrera">Seleccione la carrera por buscar</label>
                <select name="datos_carrera" id="datos_carrera" required class="form-control">
                    <option value="" selected>--Seleccione--</option>
                    @foreach($carreras as $carrera)
                        <option value="{{$carrera->carrera.'_'.$carrera->reticula}}">{{$carrera->nombre_reducido.' RET('.$carrera->reticula.')'}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="periodo">Periodo de búsqueda</label>
                <select name="periodo" id="periodo" required class="form-control">
                    @foreach($periodos as $periodo)
                        <option value="{{$periodo->periodo}}"{{$periodo->periodo==$periodo_actual?' selected':''}}>{{$periodo->identificacion_corta}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Continuar</button>
            </div>
        </form>
    </x-information>
@stop
