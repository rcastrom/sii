@extends('adminlte::page')

@section('title', 'Inicio')

@section('content_header')
    <h1>Desarrollo Académico</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <div class="card-body">
            <div class="card-header">
                <h4>Periodo: {{$nombre_periodo->identificacion_corta}}</h4>
            </div>
            <div class="row">
                <div class="col-12">
                    <p>En base al listado de carreras - reticulas, seleccione aquella para obtener el concentrado de
                        la evaluación al docente correspondiente</p>
                </div>
            </div>
            <form action="{{route('desarrollo.resultados_carrera')}}" method="post"
                  role="form" class="form" target="_blank">
                @csrf
                <div class="form-group">
                    <label for="carrera">Seleccione la carrera por buscar</label>
                    <select name="carrera" id="carrera" required class="form-control">
                        <option value="" selected>--Seleccione--</option>
                        @foreach($carreras as $carrera)
                            <option value="{{$carrera->carrera.'_'.$carrera->reticula}}">{{$carrera->nombre_reducido.' RET('.$carrera->reticula.')'}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Continuar</button>
                </div>
                <input type="hidden" name="periodo" value="{{$periodo}}">
            </form>
        </div>
    </x-information>
@stop
